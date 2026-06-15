<template>
    <AppLayout>
        <Head title="สถิติเชิงลึก" />

        <div class="border-b border-slate-200 pb-4 mb-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-indigo-600"></i>
                รายงานสถิติเชิงลึกระบบและการจัดสรรครุภัณฑ์
            </h2>
            <p class="text-xs text-slate-500 mt-1">ประมวลผลสรุปประสิทธิภาพการหมุนเวียนครุภัณฑ์คอมพิวเตอร์และสื่อโสตทัศน์</p>
        </div>

        <!-- Stats cards row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Category breakdown -->
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider flex items-center justify-between">
                    สัดส่วนคลังอุปกรณ์พัสดุ <i class="fa-solid fa-boxes-stacked text-indigo-500"></i>
                </h3>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-extrabold text-slate-900">{{ stats.totalEquipments }}</span>
                    <span class="text-xs text-slate-500 font-semibold">รายการในคลังรวม</span>
                </div>
                <div class="space-y-3.5 mt-3 pt-3 border-t border-slate-100 text-xs">
                    <div>
                        <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-laptop text-blue-500"></i>กลุ่มอุปกรณ์ IT</span>
                            <span>{{ stats.itCount }} ชิ้น ({{ itPct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full transition-all duration-700" :style="{ width: itPct + '%' }"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="fa-solid fa-camera text-violet-500"></i>กลุ่มสื่อโสตทัศน์ (AV)</span>
                            <span>{{ stats.avCount }} ชิ้น ({{ avPct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-violet-500 h-full rounded-full transition-all duration-700" :style="{ width: avPct + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status distribution -->
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-3">
                <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider flex items-center justify-between">
                    การจัดสรรสถานะปัจจุบัน <i class="fa-solid fa-chart-pie text-indigo-500"></i>
                </h3>
                <div class="space-y-2 text-xs mt-2">
                    <div v-for="s in enrichedStatus" :key="s.key" class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                        <span class="flex items-center gap-2 font-medium text-slate-700">
                            <span :class="['w-2.5 h-2.5 rounded-full', s.dot]"></span>{{ s.label }}
                        </span>
                        <span :class="['font-bold text-slate-900 px-2 py-0.5 rounded text-xs', s.badge]">{{ s.count }}</span>
                    </div>
                </div>
            </div>

            <!-- Performance card -->
            <div class="text-white p-5 rounded-2xl shadow-sm flex flex-col justify-between" style="background: linear-gradient(to bottom right, #1e1b4b, #0f172a);">
                <div>
                    <h3 class="font-bold text-xs text-indigo-200 uppercase tracking-wider flex items-center justify-between">
                        ประสิทธิภาพการยืม-คืนรวม <i class="fa-solid fa-wave-square text-indigo-400"></i>
                    </h3>
                    <div class="flex items-baseline gap-2 mt-3">
                        <span class="text-4xl font-extrabold text-white">{{ stats.totalRequests }}</span>
                        <span class="text-xs text-indigo-200 font-semibold">ใบขอยืมรวมทั้งหมด</span>
                    </div>
                    <p class="text-[11px] text-indigo-300 mt-2 leading-relaxed">ข้อมูลอ้างอิงจากการอนุมัติของเจ้าหน้าที่รวมถึงบันทึกยืมด่วน</p>
                </div>
                <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs mt-4">
                    <span class="text-indigo-200 font-medium">อัตราการคืนตามกำหนด:</span>
                    <span class="font-bold text-emerald-400">{{ returnRate }}%</span>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-indigo-600"></i> สัดส่วนสถานะอุปกรณ์ทั้งหมด
                </h3>
                <div class="flex items-center justify-center h-56">
                    <canvas ref="doughnutRef"></canvas>
                </div>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-indigo-600"></i> ปริมาณการขอยืมแยกตามแผนก
                </h3>
                <div class="h-56">
                    <canvas ref="barRef"></canvas>
                </div>
            </div>
        </div>

        <!-- Bottom row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top borrowed -->
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-trophy text-amber-500"></i>
                    อันดับอุปกรณ์ที่ถูกยืมบ่อยที่สุด (Top 3)
                </h3>
                <div v-if="topBorrowed.length === 0" class="text-center py-8 text-slate-400">
                    <i class="fa-solid fa-trophy text-2xl mb-1 text-slate-300 block"></i>
                    <p class="text-xs">ยังไม่มีประวัติการยืม</p>
                </div>
                <div v-else class="flex flex-col gap-3">
                    <div v-for="(item, idx) in topBorrowed" :key="item.id"
                         class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <span :class="['w-7 h-7 rounded-full flex items-center justify-center font-extrabold text-xs border', medalClass(idx)]">{{ idx + 1 }}</span>
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ item.name }}</h4>
                                <p class="text-[10px] text-slate-400 font-mono">{{ item.id }}</p>
                            </div>
                        </div>
                        <span class="bg-indigo-50 border border-indigo-200 text-indigo-700 text-[11px] font-bold px-2.5 py-1 rounded-lg shrink-0">
                            ยืม {{ item.count }} ครั้ง
                        </span>
                    </div>
                </div>
            </div>

            <!-- Department bars -->
            <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-indigo-600"></i>
                    สัดส่วนการขอยืมแยกตามแผนกงาน
                </h3>
                <div class="space-y-4 text-xs">
                    <div v-for="(dept, idx) in deptStats" :key="dept.name">
                        <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                            <span class="truncate pr-2">{{ dept.name }}</span>
                            <span class="font-bold text-slate-950 shrink-0">{{ dept.count }} ใบ ({{ dept.pct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div :class="['h-full rounded-full', barColors[idx % barColors.length]]" :style="{ width: dept.pct + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Chart, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend, DoughnutController, BarController } from 'chart.js'

Chart.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend, DoughnutController, BarController)

const props = defineProps({
    stats:           { type: Object, required: true },
    statusBreakdown: { type: Array,  default: () => [] },
    topBorrowed:     { type: Array,  default: () => [] },
    deptStats:       { type: Array,  default: () => [] },
})

const doughnutRef = ref(null)
const barRef      = ref(null)

const itPct = computed(() => props.stats.totalEquipments ? Math.round(props.stats.itCount / props.stats.totalEquipments * 100) : 0)
const avPct = computed(() => props.stats.totalEquipments ? Math.round(props.stats.avCount / props.stats.totalEquipments * 100) : 0)
const returnRate = computed(() => {
    if (!props.stats.totalRequests) return 0
    return Math.round(props.stats.returnedCount / props.stats.totalRequests * 100)
})

const statusStyle = {
    Available:   { dot: 'bg-emerald-500', badge: 'bg-emerald-50 text-emerald-800' },
    Borrowed:    { dot: 'bg-blue-500',    badge: 'bg-blue-50 text-blue-800' },
    Pending:     { dot: 'bg-amber-500',   badge: 'bg-amber-50 text-amber-800' },
    Maintenance: { dot: 'bg-rose-500',    badge: 'bg-rose-50 text-rose-800' },
}
const enrichedStatus = computed(() => props.statusBreakdown.map(s => ({
    ...s,
    dot:   statusStyle[s.key]?.dot   || 'bg-slate-500',
    badge: statusStyle[s.key]?.badge || 'bg-slate-50 text-slate-800',
})))

const barColors = ['bg-indigo-600', 'bg-blue-500', 'bg-violet-500', 'bg-emerald-500', 'bg-rose-500']

function medalClass(idx) {
    return (['bg-amber-100 text-amber-800 border-amber-300', 'bg-slate-200 text-slate-800 border-slate-300', 'bg-orange-50 text-orange-900 border-orange-200'][idx]) || 'bg-slate-50 text-slate-500 border-slate-200'
}

onMounted(() => {
    new Chart(doughnutRef.value, {
        type: 'doughnut',
        data: {
            labels: props.statusBreakdown.map(s => s.label),
            datasets: [{ data: props.statusBreakdown.map(s => s.count), backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'], borderWidth: 2, borderColor: '#fff' }],
        },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom', labels: { font: { family: 'Prompt', size: 11 }, padding: 12 } } } },
    })

    new Chart(barRef.value, {
        type: 'bar',
        data: {
            labels: props.deptStats.map(d => d.name.length > 14 ? d.name.substring(0, 14) + '…' : d.name),
            datasets: [{ label: 'ใบขอยืม', data: props.deptStats.map(d => d.count), backgroundColor: '#4f46e5', borderRadius: 6 }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { family: 'Prompt', size: 10 } } },
                x: { ticks: { font: { family: 'Prompt', size: 9 } } },
            },
        },
    })
})
</script>
