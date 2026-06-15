<template>
    <AppLayout>
        <Head title="ติดตามสถานะครุภัณฑ์" />

        <div class="border-b border-slate-200 pb-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-indigo-600"></i> กำกับติดตามสถานะประวัติครุภัณฑ์
                </h2>
                <p class="text-xs text-slate-500 mt-1">สืบค้นประวัติย้อนหลัง บันทึกวงจรชีวิตพัสดุ (ส่งซ่อม, ย้ายชั้น/พิกัด, พ้นโครงการ)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: Asset list -->
            <div class="lg:col-span-5 flex flex-col gap-3">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-700">เลือกครุภัณฑ์เพื่อดูประวัติ</span>
                    <div class="flex gap-1">
                        <button v-for="f in categoryFilters" :key="f.value" @click="trackingFilter = f.value"
                                :class="['px-2 py-1 text-[10px] font-semibold rounded transition-colors',
                                         trackingFilter === f.value ? 'bg-indigo-600 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200']">
                            {{ f.label }}
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-2 max-h-[500px] overflow-y-auto scrollbar-thin">
                    <div v-for="item in filteredAssets" :key="item.id"
                         @click="selectedId = item.id"
                         :class="['border rounded-xl cursor-pointer transition-all duration-200 overflow-hidden flex items-center gap-3 px-3 py-2.5',
                                  selectedId === item.id ? 'border-indigo-500 bg-indigo-50/60 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50']">
                        <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0 bg-slate-100 border border-slate-200">
                            <img :src="item.image" :alt="item.name"
                                 class="w-full h-full object-cover"
                                 @error="e => e.target.src = `https://placehold.co/48x48/e2e8f0/94a3b8?text=${item.id}`" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                <span class="text-[10px] font-mono font-bold text-indigo-700 bg-indigo-50 px-1.5 py-0.5 rounded">{{ item.id }}</span>
                                <span :class="['text-[9px] px-1.5 py-0.5 rounded-full font-semibold', lifecycleBadgeClass(item.lifecycleState)]">
                                    {{ lifecycleBadgeLabel(item.lifecycleState) }}
                                </span>
                            </div>
                            <h4 class="font-bold text-xs text-slate-900 leading-snug truncate">{{ item.name }}</h4>
                            <div class="flex items-center gap-1 text-[10px] text-slate-500 mt-0.5">
                                <i class="fa-solid fa-map-pin text-rose-400"></i>
                                <span class="truncate">{{ item.currentLocation }}</span>
                            </div>
                        </div>
                        <i v-if="selectedId === item.id" class="fa-solid fa-chevron-right text-indigo-500 text-[10px] shrink-0"></i>
                    </div>
                </div>
            </div>

            <!-- Right: Timeline detail + log form -->
            <div class="lg:col-span-7 flex flex-col gap-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-200">
                <template v-if="selectedAsset">
                    <div class="flex items-start gap-4 border-b border-slate-200 pb-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl shrink-0 shadow-sm shadow-indigo-200">
                            <i :class="'fa-solid ' + selectedAsset.icon"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[10px] font-mono text-indigo-700 bg-indigo-50 font-bold px-2 py-0.5 rounded">ID: {{ selectedAsset.id }}</span>
                                <span :class="['text-xs px-2.5 py-1 rounded-full font-bold', lifecycleBadgeClass(selectedAsset.lifecycleState)]">
                                    {{ lifecycleBadgeLabel(selectedAsset.lifecycleState) }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mt-1 leading-tight">{{ selectedAsset.name }}</h3>
                            <p class="text-[11px] text-slate-500 mt-1">S/N: {{ selectedAsset.serial }} | พิกัดปัจจุบัน: {{ selectedAsset.currentLocation }}</p>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5 mb-3">
                            <i class="fa-solid fa-history text-slate-500"></i> ไทม์ไลน์บันทึกเหตุการณ์
                        </h4>
                        <div v-if="selectedLogs.length === 0" class="text-center py-8 text-slate-400 bg-white border border-slate-100 rounded-xl">
                            <i class="fa-solid fa-timeline text-2xl mb-1 text-slate-300 block"></i>
                            <p class="text-xs">ไม่มีประวัติการเปลี่ยนแปลงที่บันทึกไว้</p>
                        </div>
                        <div v-else class="flex flex-col gap-4 max-h-72 overflow-y-auto scrollbar-thin py-1 pl-1">
                            <div v-for="(log, idx) in selectedLogs" :key="log.id" class="relative flex gap-4">
                                <div v-if="idx < selectedLogs.length - 1" class="absolute left-4 top-8 -bottom-6 w-0.5 bg-slate-200"></div>
                                <div :class="['w-8 h-8 rounded-full flex items-center justify-center shrink-0 z-10 border', logIconClass(log.action)]">
                                    <i :class="logIcon(log.action)"></i>
                                </div>
                                <div class="bg-white p-3.5 rounded-xl border border-slate-100 flex-1 shadow-sm flex flex-col gap-1">
                                    <div class="flex justify-between items-start gap-2">
                                        <span class="text-xs font-bold text-slate-800">{{ getActionThai(log.action) }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ formatThaiDate(log.date) }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-600 leading-relaxed">{{ log.detail }}</p>
                                    <div class="flex flex-wrap justify-between text-[10px] text-slate-400 mt-1.5 border-t border-slate-50 pt-1.5">
                                        <span><i class="fa-solid fa-location-arrow mr-1"></i>{{ log.location }}</span>
                                        <span>ผู้ทำรายการ: {{ log.operator }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Log form -->
                    <div class="mt-2 border-t border-slate-200 pt-5">
                        <h4 class="text-xs font-bold text-indigo-950 mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-file-pen text-indigo-600"></i> บันทึกเหตุการณ์ / ปรับสถานะครุภัณฑ์
                        </h4>
                        <form @submit.prevent="doAddLog" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 mb-1">เหตุการณ์ / ประเภทรายการ</label>
                                <select v-model="logAction" required class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50">
                                    <option value="Relocate">ย้ายสถานที่ / ปรับพิกัดติดตั้ง</option>
                                    <option value="Maintenance">ส่งซ่อมบำรุงภายนอก/ชำรุด</option>
                                    <option value="Inspect">ตรวจสภาพประจำปี/เช็คสภาพ</option>
                                    <option value="Decommission">ออกโครงการ / คัดจำหน่ายพัสดุออก</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 mb-1">พิกัดใหม่</label>
                                <input v-model="logLocation" type="text" required placeholder="เช่น ชั้น 1 ห้องสโมสร"
                                       class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-bold text-slate-500 mb-1">รายละเอียดเพิ่มเติม</label>
                                <input v-model="logDetail" type="text" required placeholder="เช่น ย้ายจากอาคาร 4 หรือ อาการซ่อมจอเสีย"
                                       class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50" />
                            </div>
                            <div class="sm:col-span-2 pt-2 border-t border-slate-100 flex justify-end">
                                <button type="submit" :disabled="loading"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition-colors flex items-center gap-1.5 disabled:opacity-60">
                                    <i class="fa-solid fa-save"></i> บันทึกอัปเดตลงระบบ
                                </button>
                            </div>
                        </form>
                    </div>
                </template>

                <div v-else class="flex flex-col items-center justify-center h-full py-20 text-slate-400">
                    <i class="fa-solid fa-map-location-dot text-5xl text-slate-200 mb-3"></i>
                    <p class="text-sm font-semibold">กรุณาเลือกครุภัณฑ์จากรายการด้านซ้าย</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Swal from 'sweetalert2'

const props = defineProps({
    equipments: { type: Array, default: () => [] },
})

const page = usePage()

const selectedId     = ref(props.equipments[0]?.id || null)
const trackingFilter = ref('all')
const logAction      = ref('Relocate')
const logLocation    = ref('')
const logDetail      = ref('')
const loading        = ref(false)

const categoryFilters = [
    { value: 'all', label: 'ทั้งหมด' },
    { value: 'it',  label: 'คอม/IT' },
    { value: 'av',  label: 'สื่อ AV' },
]

const filteredAssets  = computed(() => props.equipments.filter(e => trackingFilter.value === 'all' || e.category === trackingFilter.value))
const selectedAsset   = computed(() => props.equipments.find(e => e.id === selectedId.value))
const selectedLogs    = computed(() => selectedAsset.value?.assetLogs || [])

function doAddLog() {
    if (!selectedId.value) return
    loading.value = true
    router.post('/tracking/log', {
        asset_id: selectedId.value,
        action:   logAction.value,
        location: logLocation.value,
        detail:   logDetail.value,
    }, {
        onSuccess: () => {
            logLocation.value = ''
            logDetail.value   = ''
            const msg = page.props.flash?.success || 'บันทึกสำเร็จ!'
            Swal.fire({ title: msg, icon: 'success', timer: 1500, showConfirmButton: false })
        },
        onError: (errors) => {
            const msg = Object.values(errors)[0] || 'เกิดข้อผิดพลาด'
            Swal.fire({ title: 'ไม่สำเร็จ', text: msg, icon: 'error', confirmButtonColor: '#EF4444' })
        },
        onFinish: () => { loading.value = false },
    })
}

function formatThaiDate(dateStr) {
    if (!dateStr) return '-'
    const months = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
    const d = new Date(dateStr)
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}
function getActionThai(a) {
    return { Relocate: 'ย้ายสถานที่', Maintenance: 'ส่งซ่อม', Inspect: 'ตรวจสอบ', Decommission: 'ออกโครงการ' }[a] || a
}
function lifecycleBadgeClass(s) {
    return { Active: 'bg-emerald-100 text-emerald-800', 'Under Repair': 'bg-rose-100 text-rose-800', Relocated: 'bg-blue-100 text-blue-800', Decommissioned: 'bg-slate-200 text-slate-800' }[s] || 'bg-slate-100 text-slate-600'
}
function lifecycleBadgeLabel(s) {
    return { Active: 'ใช้งานปกติ', 'Under Repair': 'กำลังส่งซ่อม', Relocated: 'ย้ายตำแหน่ง', Decommissioned: 'ออกโครงการ' }[s] || s
}
function logIcon(a) {
    return { Inspect: 'fa-solid fa-check text-emerald-500', Relocate: 'fa-solid fa-map-pin text-blue-500', Maintenance: 'fa-solid fa-wrench text-rose-500', Decommission: 'fa-solid fa-trash-can text-slate-500' }[a] || 'fa-solid fa-info text-slate-400'
}
function logIconClass(a) {
    return { Inspect: 'bg-emerald-50 border-emerald-200', Relocate: 'bg-blue-50 border-blue-200', Maintenance: 'bg-rose-50 border-rose-200', Decommission: 'bg-slate-100 border-slate-300' }[a] || 'bg-slate-50 border-slate-200'
}
</script>
