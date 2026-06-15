<template>
    <AppLayout>
        <Head title="ขอยืมอุปกรณ์" />

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-indigo-600"></i> ทำรายการขอยืมวัสดุอุปกรณ์
            </h2>
            <p class="text-xs text-slate-500 mt-1">กรุณาตรวจสอบรายการและระบุรายละเอียดให้ครบถ้วนเพื่อส่งคำขอ</p>
        </div>

        <!-- QUICK BORROW -->
        <div class="p-5 rounded-2xl border border-indigo-100 shadow-sm mb-6" style="background: linear-gradient(to right, #f8fafc, #eef2ff);">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i>
                        บันทึกยืมอุปกรณ์อย่างรวดเร็ว (สำหรับเจ้าหน้าที่ / ด่วน)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">ข้ามขั้นตอนการจัดลงตะกร้า เปลี่ยนสถานะพัสดุเป็น "ถูกยืมทันที"</p>
                </div>
                <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                    <i class="fa-solid fa-bolt mr-1"></i> Quick Action
                </span>
            </div>
            <form @submit.prevent="doQuickBorrow" class="flex flex-col sm:flex-row gap-3 items-end">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-slate-600 mb-1">รหัสพัสดุอุปกรณ์ (Asset ID)</label>
                    <input v-model="qbAssetId" type="text" required placeholder="เช่น IT-001 หรือ AV-001"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white uppercase" />
                </div>
                <div class="shrink-0">
                    <button type="submit" :disabled="loading"
                            class="w-full sm:w-auto bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-5 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-2 disabled:opacity-50">
                        <i class="fa-solid fa-clipboard-check text-emerald-400"></i> บันทึกยืมด่วนทันที
                    </button>
                </div>
            </form>
        </div>

        <hr class="border-slate-200 mb-6">

        <!-- CART + BORROW FORM -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left: Cart items -->
            <div class="lg:col-span-3 flex flex-col gap-4">
                <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-indigo-500"></i>
                    อุปกรณ์ที่เลือกยืม ({{ cart.length }})
                </h3>

                <div v-if="cart.length === 0" class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400 flex flex-col items-center gap-2">
                    <i class="fa-solid fa-basket-shopping text-4xl text-slate-300"></i>
                    <p class="text-xs font-semibold">ตะกร้าของคุณยังว่างอยู่</p>
                    <p class="text-[11px] text-slate-400">ไปที่คลังวัสดุอุปกรณ์ และเลือกอุปกรณ์อย่างน้อย 1 ชิ้น</p>
                    <Link href="/inventory" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-xl text-xs transition-colors">
                        ไปดูคลังอุปกรณ์
                    </Link>
                </div>

                <div v-else class="flex flex-col gap-3">
                    <div v-for="itemId in cart" :key="itemId"
                         class="bg-white border border-slate-200 p-4 rounded-xl flex items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-slate-50 text-indigo-600 rounded-lg flex items-center justify-center text-base shrink-0">
                                <i :class="'fa-solid ' + (getEquipment(itemId)?.icon || 'fa-box')"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ getEquipment(itemId)?.name || itemId }}</h4>
                                <p class="text-[10px] font-mono text-slate-400">S/N: {{ getEquipment(itemId)?.serial || '-' }}</p>
                            </div>
                        </div>
                        <button type="button" @click="removeFromCart(itemId)" class="text-slate-400 hover:text-rose-600 p-2 text-sm transition-colors">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right: Booking form -->
            <div class="lg:col-span-2 bg-slate-50 p-5 rounded-2xl border border-slate-100 flex flex-col gap-4">
                <h3 class="font-bold text-sm text-slate-950 border-b border-slate-200 pb-2">
                    <i class="fa-regular fa-file-lines text-indigo-600 mr-1.5"></i> รายละเอียดการยืม
                </h3>

                <form @submit.prevent="doSubmitBorrow" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อผู้ขอใช้บริการ</label>
                        <input type="text" :value="$page.props.auth.user?.name" readonly
                               class="w-full border border-slate-200 bg-slate-100 rounded-xl px-3 py-2 text-sm text-slate-600 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">อีเมล</label>
                        <input type="email" :value="$page.props.auth.user?.email" readonly
                               class="w-full border border-slate-200 bg-slate-100 rounded-xl px-3 py-2 text-sm text-slate-600 cursor-not-allowed" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">วัตถุประสงค์ในการยืม</label>
                        <textarea v-model="purpose" rows="2" required
                                  class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                  placeholder="ระบุการเรียนการสอน ห้องประชุม หรือจัดกิจกรรม..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">วันที่ยืม</label>
                            <input type="text" :value="todayThai" readonly
                                   class="w-full border border-slate-200 bg-slate-100 rounded-xl px-3 py-2 text-sm text-slate-600 cursor-not-allowed" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">วันที่ต้องการคืน</label>
                            <input v-model="returnDate" type="date" required
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        </div>
                    </div>
                    <div class="flex items-start gap-2 pt-2">
                        <input v-model="agree" type="checkbox" id="agree" required class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500" />
                        <label for="agree" class="text-[11px] text-slate-500 leading-normal">ข้าพเจ้ายินดีดูแลรักษาและจะคืนวัสดุอุปกรณ์ทั้งหมดตรงเวลา หากเกิดความเสียหายจากความประมาท ยินดีชดใช้ตามระเบียบ</label>
                    </div>
                    <button type="submit" :disabled="cart.length === 0 || loading"
                            class="w-full text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed hover:opacity-90"
                            style="background: linear-gradient(to right, #4f46e5, #2563eb);">
                        <i class="fa-solid fa-paper-plane"></i> ส่งคำขอยืมอุปกรณ์
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useCart } from '@/composables/useCart'
import Swal from 'sweetalert2'

const props = defineProps({
    equipments: { type: Array, default: () => [] },
})

const page = usePage()
const { cart, removeFromCart, clearCart } = useCart()

const today     = new Date().toISOString().split('T')[0]
const nextWeek  = new Date(); nextWeek.setDate(nextWeek.getDate() + 7)
const todayThai = formatThaiDate(today)

const purpose    = ref('')
const returnDate = ref(nextWeek.toISOString().split('T')[0])
const agree      = ref(false)
const qbAssetId  = ref('')
const loading    = ref(false)

function formatThaiDate(dateStr) {
    if (!dateStr) return '-'
    const months = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
    const d = new Date(dateStr)
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}

function getEquipment(id) { return props.equipments.find(e => e.id === id) }

async function doSubmitBorrow() {
    if (cart.length === 0) return
    const { isConfirmed } = await Swal.fire({
        title: 'ยืนยันการส่งคำขอ?',
        text: `ยืมอุปกรณ์ ${cart.length} รายการ`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4F46E5',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'ส่งคำขอ',
        cancelButtonText: 'ยกเลิก',
    })
    if (!isConfirmed) return

    loading.value = true
    router.post('/borrow', {
        items:       [...cart],
        purpose:     purpose.value,
        return_date: returnDate.value,
    }, {
        onSuccess: () => {
            const flash = page.props.flash
            if (flash?.error) {
                Swal.fire({ title: 'ไม่สำเร็จ', text: flash.error, icon: 'error', confirmButtonColor: '#EF4444' })
            } else {
                clearCart()
                purpose.value  = ''
                agree.value    = false
                Swal.fire({ title: flash?.success || 'ส่งคำขอสำเร็จ!', icon: 'success', timer: 2000, showConfirmButton: false })
            }
        },
        onError: (errors) => {
            const msg = Object.values(errors)[0] || 'เกิดข้อผิดพลาด กรุณาลองใหม่'
            Swal.fire({ title: 'ข้อมูลไม่ถูกต้อง', text: msg, icon: 'error', confirmButtonColor: '#EF4444' })
        },
        onFinish: () => { loading.value = false },
    })
}

function doQuickBorrow() {
    if (!qbAssetId.value.trim()) return
    loading.value = true
    router.post('/borrow/quick', { asset_id: qbAssetId.value.trim() }, {
        onSuccess: () => {
            const flash = page.props.flash
            if (flash?.error) {
                Swal.fire({ title: 'ไม่สำเร็จ', text: flash.error, icon: 'error', confirmButtonColor: '#EF4444' })
            } else {
                qbAssetId.value = ''
                Swal.fire({ title: flash?.success || 'บันทึกยืมด่วนสำเร็จ!', icon: 'success', timer: 2000, showConfirmButton: false })
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
