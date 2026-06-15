<template>
    <AppLayout>
        <Head title="แดชบอร์ดรวม" />

        <!-- Welcome Banner -->
        <div class="rounded-2xl shadow-lg mb-6" style="background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 55%, #7c3aed 100%);">
            <div class="p-6 flex justify-between gap-4">
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div>
                        <span style="display:inline-block; background:rgba(255,255,255,0.25); color:#fff; font-size:11px; font-weight:700; padding:3px 12px; border-radius:999px;">
                            ยินดีต้อนรับสู่ระบบยืม-คืน
                        </span>
                    </div>
                    <div style="font-size:20px; font-weight:800; color:#fff; line-height:1.4;">
                        ค้นหาและยืมอุปกรณ์ที่ต้องการ<br>สำหรับการเรียนและการทำงาน
                    </div>
                    <div style="font-size:13px; color:#c7d2fe;">
                        โปรเจคเตอร์ &nbsp;•&nbsp; โน้ตบุ๊ก &nbsp;•&nbsp; อุปกรณ์ AV &nbsp;•&nbsp; ไมค์ไร้สาย พร้อมให้บริการ
                    </div>
                </div>
                <div class="shrink-0 hidden sm:flex items-center justify-center w-16 h-16 rounded-2xl self-center"
                     style="background:rgba(255,255,255,0.15); flex-shrink:0;">
                    <i class="fa-solid fa-laptop-medical text-white text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                <div><span class="text-xs text-slate-500 font-semibold">อุปกรณ์พร้อมใช้งาน</span><div class="text-2xl font-extrabold text-slate-900 mt-1">{{ stats.available }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                <div><span class="text-xs text-slate-500 font-semibold">กำลังถูกยืมใช้งาน</span><div class="text-2xl font-extrabold text-slate-900 mt-1">{{ stats.borrowed }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg"><i class="fa-solid fa-handshake"></i></div>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                <div><span class="text-xs text-slate-500 font-semibold">ค้างคืน เกินกำหนดส่ง</span><div class="text-2xl font-extrabold text-rose-600 mt-1">{{ stats.overdue }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
            <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                <div><span class="text-xs text-slate-500 font-semibold">รอเจ้าหน้าที่ตรวจสอบ</span><div class="text-2xl font-extrabold text-amber-600 mt-1">{{ stats.pending }}</div></div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg"><i class="fa-solid fa-clock-rotate-left"></i></div>
            </div>
        </div>

        <!-- Active / Returned columns -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                    รายการค้างส่งมอบ & เกินกำหนดส่งคืน
                </h3>
                <div v-if="activeBorrows.length === 0" class="text-center py-10 text-slate-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2 block text-slate-300"></i>
                    <p class="text-xs font-semibold">ไม่มีครุภัณฑ์ค้างส่งมอบ</p>
                </div>
                <div v-else class="flex flex-col gap-3 max-h-96 overflow-y-auto scrollbar-thin pr-1">
                    <div v-for="req in activeBorrows" :key="req.id">
                        <div v-for="eq in req.equipments" :key="eq.id"
                             :class="['p-3.5 border rounded-xl flex flex-col gap-2.5', req.status === 'Overdue' ? 'bg-rose-50/70 border-rose-200' : 'bg-amber-50/70 border-amber-200']">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-white border border-slate-100 text-indigo-600 flex items-center justify-center text-base">
                                        <i :class="'fa-solid ' + eq.icon"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">{{ eq.name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-mono">{{ eq.id }}</p>
                                    </div>
                                </div>
                                <span v-if="req.status === 'Overdue'" class="bg-rose-100 text-rose-800 border border-rose-300 text-[10px] px-2 py-0.5 rounded-full font-bold">เกินกำหนด</span>
                                <span v-else class="bg-amber-100 text-amber-800 border border-amber-300 text-[10px] px-2 py-0.5 rounded-full font-bold">กำลังยืม</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-1.5 border-t border-slate-200/50">
                                <div><p class="text-slate-400 font-semibold text-[9px] uppercase">ผู้ขอยืม</p><p class="font-bold text-slate-800 truncate">{{ req.borrower_name }}</p></div>
                                <div class="text-right"><p class="text-slate-400 font-semibold text-[9px] uppercase">กำหนดคืน</p>
                                    <p :class="req.status === 'Overdue' ? 'text-rose-600 font-bold' : 'text-slate-600 font-medium'">{{ formatThaiDate(req.return_date) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    รายการส่งคืนแล้วเสร็จ
                </h3>
                <div v-if="returnedItems.length === 0" class="text-center py-10 text-slate-400">
                    <i class="fa-solid fa-circle-check text-3xl mb-2 block text-slate-300"></i>
                    <p class="text-xs font-semibold">ไม่มีข้อมูลการส่งคืนพัสดุ</p>
                </div>
                <div v-else class="flex flex-col gap-3 max-h-96 overflow-y-auto scrollbar-thin pr-1">
                    <div v-for="req in returnedItems" :key="req.id">
                        <div v-for="eq in req.equipments" :key="eq.id"
                             class="bg-emerald-50/40 border border-emerald-100 p-3.5 rounded-xl flex flex-col gap-2.5">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-lg bg-white border border-slate-100 text-emerald-600 flex items-center justify-center text-base">
                                        <i :class="'fa-solid ' + eq.icon"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">{{ eq.name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-mono">{{ eq.id }}</p>
                                    </div>
                                </div>
                                <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 text-[10px] px-2 py-0.5 rounded-full font-bold">คืนแล้ว</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-[11px] pt-1.5 border-t border-emerald-100">
                                <div><p class="text-emerald-700/60 font-semibold text-[9px] uppercase">ผู้ยืม</p><p class="font-bold text-slate-800 truncate">{{ req.borrower_name }}</p></div>
                                <div class="text-right"><p class="text-emerald-700/60 font-semibold text-[9px] uppercase">วันที่คืน</p><p class="text-slate-600 font-semibold">{{ formatThaiDate(req.return_date) }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Tracking Table (3-item pagination via Inertia) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i>
                        ประวัติติดตามสถานะและการปรับพิกัดครุภัณฑ์ย้อนหลัง
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">บันทึกวงจรชีวิตครุภัณฑ์คอมพิวเตอร์และโสตฯ</p>
                </div>
                <span class="bg-indigo-50 text-indigo-700 text-[11px] font-bold px-2.5 py-1 rounded-lg">
                    <i class="fa-solid fa-history mr-1"></i>เรียงตามเหตุการณ์ล่าสุด
                </span>
            </div>

            <div class="overflow-x-auto border border-slate-200 rounded-xl">
                <table class="min-w-full divide-y divide-slate-200 text-xs">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">วันที่</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">รหัสครุภัณฑ์</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">ชื่ออุปกรณ์</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">เหตุการณ์</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">รายละเอียด</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">พิกัดใหม่</th>
                            <th class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">ผู้บันทึก</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <tr v-for="log in assetLogs.data" :key="log.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-medium">{{ formatThaiDate(log.date) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono text-indigo-600 font-bold">{{ log.assetId }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ log.assetName }}</td>
                            <td class="px-4 py-3 whitespace-nowrap"><span :class="actionBadgeClass(log.action)">{{ actionLabel(log.action) }}</span></td>
                            <td class="px-4 py-3 text-slate-600 max-w-xs truncate" :title="log.detail">{{ log.detail }}</td>
                            <td class="px-4 py-3 font-medium text-slate-700"><i class="fa-solid fa-map-pin text-rose-500 mr-1.5"></i>{{ log.location }}</td>
                            <td class="px-4 py-3 text-slate-500 whitespace-nowrap">{{ log.operator }}</td>
                        </tr>
                        <tr v-if="!assetLogs.data?.length">
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">ไม่มีข้อมูล</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination links -->
            <div class="flex items-center justify-between pt-1">
                <span class="text-[11px] text-slate-500">
                    แสดง <strong class="text-slate-800">{{ assetLogs.from }}-{{ assetLogs.to }}</strong>
                    จาก <strong class="text-slate-800">{{ assetLogs.total }}</strong> บันทึก
                </span>
                <div class="flex gap-1.5">
                    <Link v-if="assetLogs.prev_page_url" :href="assetLogs.prev_page_url"
                          class="px-3 py-1.5 text-[11px] font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg flex items-center gap-1">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i> ย้อนกลับ
                    </Link>
                    <Link v-if="assetLogs.next_page_url" :href="assetLogs.next_page_url"
                          class="px-3 py-1.5 text-[11px] font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg flex items-center gap-1">
                        ถัดไป <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    stats:        { type: Object, required: true },
    activeBorrows:{ type: Array,  default: () => [] },
    returnedItems:{ type: Array,  default: () => [] },
    assetLogs:    { type: Object, required: true },
})

function formatThaiDate(dateStr) {
    if (!dateStr) return '-'
    const months = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
    const d = new Date(dateStr)
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}

function actionBadgeClass(action) {
    const map = {
        Inspect:      'inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200',
        Relocate:     'inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-blue-50 text-blue-700 border border-blue-200',
        Maintenance:  'inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200',
        Decommission: 'inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-slate-100 text-slate-700 border border-slate-300',
    }
    return map[action] || 'inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-slate-50 text-slate-500'
}
function actionLabel(action) {
    const map = { Inspect: 'ตรวจสอบ', Relocate: 'ย้ายสถานที่', Maintenance: 'ส่งซ่อม', Decommission: 'ออกโครงการ' }
    return map[action] || action
}
</script>
