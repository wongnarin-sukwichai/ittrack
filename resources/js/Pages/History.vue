<template>
    <AppLayout>
        <Head title="ประวัติรายการยืม-คืน" />

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-history text-indigo-600"></i> ประวัติและสถานะการยืมทั้งหมด
                </h2>
                <p class="text-xs text-slate-500 mt-1">รายการคำขอย้อนหลังและสถานะปัจจุบันในระบบ</p>
            </div>
        </div>

        <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm mb-4">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">รหัสคำขอ / วันที่ยืม</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ผู้ยืม / อีเมล</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">อุปกรณ์ที่ยืม</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">กำหนดส่งคืน</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <tr v-for="req in requests.data" :key="req.id" class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-xs">{{ req.id }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5">{{ formatThaiDate(req.borrowDate) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-800 text-xs">{{ req.borrowerName }}</span>
                                <span class="text-[11px] text-slate-500 mt-0.5">{{ req.email || req.department }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span v-for="eq in req.equipments" :key="eq.id"
                                      class="inline-flex items-center gap-1 text-slate-700 bg-slate-50 border border-slate-200 text-[11px] px-1.5 py-0.5 rounded w-max">
                                    <i :class="'fa-solid ' + eq.icon + ' text-slate-400'" class="text-[10px]"></i>
                                    {{ eq.name }} ({{ eq.id }})
                                </span>
                                <!-- Fallback: if equipment was deleted -->
                                <span v-if="req.equipments.length === 0" v-for="id in req.items" :key="id"
                                      class="inline-flex items-center gap-1 text-slate-500 bg-slate-50 border border-slate-200 text-[11px] px-1.5 py-0.5 rounded w-max font-mono">
                                    {{ id }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">{{ formatThaiDate(req.returnDate) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span :class="['inline-flex px-3 py-1 text-[11px] font-bold rounded-full', getStatusColor(req.status)]">
                                {{ getStatusThai(req.status) }}
                            </span>
                        </td>
                    </tr>
                    <tr v-if="requests.data.length === 0">
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl text-slate-200 block mb-2"></i>
                            ยังไม่มีประวัติการยืมในระบบ
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500">
                แสดง <strong class="text-slate-800">{{ requests.from }}-{{ requests.to }}</strong>
                จาก <strong class="text-slate-800">{{ requests.total }}</strong> รายการ
                (หน้า {{ requests.current_page }}/{{ requests.last_page }})
            </span>
            <div class="flex gap-1.5">
                <Link v-if="requests.prev_page_url" :href="requests.prev_page_url"
                      class="px-3 py-1.5 text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> ย้อนกลับ
                </Link>
                <span v-for="link in pageNumbers" :key="link.label">
                    <Link v-if="link.url" :href="link.url"
                          :class="['px-3 py-1.5 text-xs font-semibold border rounded-lg transition-colors',
                                   link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-700']">
                        {{ link.label }}
                    </Link>
                </span>
                <Link v-if="requests.next_page_url" :href="requests.next_page_url"
                      class="px-3 py-1.5 text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg transition-colors flex items-center gap-1">
                    ถัดไป <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    requests: { type: Object, required: true },
})

const pageNumbers = computed(() => (props.requests.links || []).filter(l => l.label !== '&laquo; Previous' && l.label !== 'Next &raquo;'))

function formatThaiDate(dateStr) {
    if (!dateStr) return '-'
    const months = ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
    const d = new Date(dateStr)
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}
function getStatusColor(s) {
    return { Pending: 'bg-amber-100 text-amber-800', Approved: 'bg-blue-100 text-blue-800', Returned: 'bg-emerald-100 text-emerald-800', Overdue: 'bg-rose-100 text-rose-800' }[s] || 'bg-slate-100 text-slate-600'
}
function getStatusThai(s) {
    return { Pending: 'รอตรวจสอบ', Approved: 'อนุมัติแล้ว', Returned: 'คืนแล้ว', Overdue: 'เกินกำหนด' }[s] || s
}
</script>
