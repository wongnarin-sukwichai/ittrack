<template>
    <AppLayout>
        <Head title="คืนอุปกรณ์" />

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-arrow-rotate-left text-indigo-600"></i> คืนอุปกรณ์
            </h2>
            <p class="text-xs text-slate-500 mt-1">บันทึกการคืนอุปกรณ์และตรวจเช็คสภาพ</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left: Active borrowings -->
            <div class="xl:col-span-2 flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-handshake text-blue-500 text-base"></i>
                    รายการที่กำลังยืมอยู่ ({{ activeBorrows.length }} รายการ)
                </h3>

                <div v-if="activeBorrows.length === 0" class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400">
                    <i class="fa-regular fa-bell-slash text-3xl mb-2 block text-slate-300"></i>
                    <p class="text-xs font-semibold">ไม่มีรายการที่กำลังยืมอยู่ในขณะนี้</p>
                </div>

                <div v-else class="flex flex-col gap-4">
                    <div v-for="req in activeBorrows" :key="req.id"
                         :class="['p-5 rounded-2xl flex flex-col gap-3 shadow-sm border',
                                  req.status === 'Overdue' ? 'bg-rose-50/50 border-rose-200' : 'bg-blue-50/30 border-blue-100']">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b pb-2.5"
                             :class="req.status === 'Overdue' ? 'border-rose-200/50' : 'border-blue-100'">
                            <div>
                                <span :class="['text-[10px] font-bold px-2 py-0.5 rounded-full', req.status === 'Overdue' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800']">
                                    {{ req.status === 'Overdue' ? 'เกินกำหนดส่งคืน' : 'กำลังยืมใช้งาน' }}
                                </span>
                                <span class="text-xs font-bold text-slate-900 ml-1.5">{{ req.id }}</span>
                            </div>
                            <span class="text-[11px] text-slate-500">ยืมเมื่อ: {{ formatThaiDate(req.borrowDate) }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                            <div class="flex flex-col gap-1.5">
                                <p><strong class="text-slate-500">ผู้ยืม:</strong> {{ req.borrowerName }}</p>
                                <p><strong class="text-slate-500">อีเมล:</strong> {{ req.email }}</p>
                                <p><strong class="text-slate-500">วัตถุประสงค์:</strong> {{ req.purpose }}</p>
                            </div>
                            <div>
                                <p><strong class="text-slate-500">กำหนดคืน:</strong>
                                    <span :class="['ml-1 font-bold', req.status === 'Overdue' ? 'text-rose-600' : 'text-slate-700']">
                                        {{ formatThaiDate(req.returnDate) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">อุปกรณ์ที่ยืม</h4>
                            <div v-for="eq in req.equipments" :key="eq.id"
                                 class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-slate-100 text-xs">
                                <span class="font-semibold text-slate-800">
                                    <i :class="'fa-solid ' + eq.icon + ' text-indigo-500 mr-1.5'"></i>
                                    {{ eq.name }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">{{ eq.id }}</span>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end pt-3 mt-1 border-t border-slate-200/50">
                            <button @click="doReturn(req)" :disabled="loading"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition-colors flex items-center gap-1.5 disabled:opacity-60">
                                <i class="fa-solid fa-arrow-rotate-left"></i> บันทึกรับคืน
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Quick return + stats -->
            <div class="flex flex-col gap-6">
                <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl flex flex-col gap-3">
                    <h4 class="font-bold text-slate-900 text-xs tracking-wider uppercase flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i> บันทึกคืนอุปกรณ์รวดเร็ว
                    </h4>
                    <p class="text-[11px] text-slate-500">พิมพ์รหัส หรือ ซีเรียลอุปกรณ์ด้านล่างเพื่อบันทึกคืนทันที</p>
                    <form @submit.prevent="doQuickReturn" class="flex gap-2">
                        <input v-model="returnInput" type="text" placeholder="เช่น IT-002, AV-001..."
                               class="flex-1 border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none bg-white" />
                        <button type="submit" :disabled="loading"
                                class="bg-blue-600 text-white font-bold px-3 py-1.5 rounded-xl text-xs hover:bg-blue-700 transition-colors disabled:opacity-60">
                            ยืนยันคืน
                        </button>
                    </form>
                </div>

                <div class="bg-indigo-950 text-white p-5 rounded-2xl flex flex-col gap-3 shadow-md">
                    <h4 class="font-bold text-indigo-300 text-xs tracking-wider uppercase">สถิติระบบประมวลผลประจำเดือน</h4>
                    <div class="grid grid-cols-2 gap-4 mt-1">
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[11px] text-indigo-200">สถิติยืมรวม</p>
                            <p class="text-lg font-bold text-white mt-1">{{ stats.total }} ครั้ง</p>
                        </div>
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[11px] text-indigo-200">อัตราคืนตรงเวลา</p>
                            <p class="text-lg font-bold text-emerald-400 mt-1">{{ stats.returnRate }}%</p>
                        </div>
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[11px] text-indigo-200">คืนแล้ว</p>
                            <p class="text-lg font-bold text-emerald-300 mt-1">{{ stats.returned }}</p>
                        </div>
                        <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                            <p class="text-[11px] text-indigo-200">เกินกำหนด</p>
                            <p class="text-lg font-bold text-rose-400 mt-1">{{ stats.overdue }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Swal from 'sweetalert2'

defineProps({
    activeBorrows: { type: Array,  default: () => [] },
    stats:         { type: Object, required: true },
})

const page        = usePage()
const returnInput = ref('')
const loading     = ref(false)

function formatThaiDate(dateStr) {
    if (!dateStr) return '-'
    const months = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
    const d = new Date(dateStr)
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}

async function doReturn(req) {
    const { isConfirmed } = await Swal.fire({
        title: 'บันทึกรับคืน?',
        text: `บันทึกการรับคืนใบขอ ${req.id}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'บันทึกคืน',
        cancelButtonText: 'ยกเลิก',
    })
    if (!isConfirmed) return

    loading.value = true
    router.patch(`/admin/return/${req.id}`, {}, {
        onSuccess: () => {
            const msg = page.props.flash?.success || 'รับคืนสำเร็จ!'
            Swal.fire({ title: msg, icon: 'success', timer: 1500, showConfirmButton: false })
        },
        onError: () => {
            Swal.fire({ title: 'เกิดข้อผิดพลาด', icon: 'error', confirmButtonColor: '#EF4444' })
        },
        onFinish: () => { loading.value = false },
    })
}

function doQuickReturn() {
    if (!returnInput.value.trim()) return
    loading.value = true
    router.post('/admin/return/quick', { asset_id: returnInput.value.trim() }, {
        onSuccess: () => {
            const flash = page.props.flash
            if (flash?.error) {
                Swal.fire({ title: 'ไม่สำเร็จ', text: flash.error, icon: 'error', confirmButtonColor: '#EF4444' })
            } else {
                returnInput.value = ''
                Swal.fire({ title: flash?.success || 'คืนอุปกรณ์สำเร็จ!', icon: 'success', timer: 1500, showConfirmButton: false })
            }
        },
        onError: (errors) => {
            const msg = Object.values(errors)[0] || 'ไม่พบรหัสอุปกรณ์'
            Swal.fire({ title: 'ไม่สำเร็จ', text: msg, icon: 'error', confirmButtonColor: '#EF4444' })
        },
        onFinish: () => { loading.value = false },
    })
}
</script>
