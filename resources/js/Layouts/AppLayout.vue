<template>
    <div class="h-screen flex flex-col text-slate-800 bg-slate-50">
        <!-- Toast -->
        <div :class="['fixed top-5 right-5 z-50 transition-all duration-300 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3',
                      toast.show ? 'translate-y-0 opacity-100' : '-translate-y-24 opacity-0']">
            <span v-if="toast.type === 'success'" class="text-emerald-400 text-lg"><i class="fa-solid fa-circle-check"></i></span>
            <span v-else-if="toast.type === 'warning'" class="text-amber-400 text-lg"><i class="fa-solid fa-circle-exclamation"></i></span>
            <span v-else class="text-rose-400 text-lg"><i class="fa-solid fa-triangle-exclamation"></i></span>
            <span class="text-sm font-medium">{{ toast.message }}</span>
        </div>

        <!-- Header -->
        <header class="sticky top-0 z-30 shrink-0 shadow-lg" style="background: linear-gradient(to right, #2563eb, #4f46e5, #7c3aed);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 backdrop-blur-sm text-white p-2.5 rounded-xl flex items-center justify-center border border-white/20">
                            <i class="fa-solid fa-laptop-medical text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-white leading-tight">ระบบยืม-คืนอุปกรณ์</h1>
                            <p class="text-xs text-indigo-200 font-semibold tracking-wide">กลุ่มงาน IT และ สื่อโสตทัศน์</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <template v-if="$page.props.auth.user">
                            <div class="hidden sm:flex items-center gap-2.5 pl-4 border-l border-white/20">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-white">{{ $page.props.auth.user.name }}</p>
                                    <p class="text-xs text-indigo-200">{{ $page.props.auth.user.role === 'admin' ? 'เจ้าหน้าที่ IT/AV' : 'ผู้ขอใช้บริการ' }}</p>
                                </div>
                                <img v-if="$page.props.auth.user.avatar"
                                     :src="$page.props.auth.user.avatar"
                                     class="w-10 h-10 rounded-xl object-cover border-2 border-white/30"
                                     :alt="$page.props.auth.user.name" />
                                <div v-else class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center text-white font-bold text-sm">
                                    {{ initials }}
                                </div>
                            </div>
                            <button @click="doLogout" class="text-xs text-white/70 hover:text-white transition-colors flex items-center gap-1.5 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span class="hidden sm:inline">ออกจากระบบ</span>
                            </button>
                        </template>
                        <template v-else>
                            <Link href="/login" class="bg-white text-indigo-700 hover:bg-indigo-50 text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-2 transition-colors shadow-sm">
                                <i class="fa-brands fa-google text-red-500"></i> เข้าสู่ระบบ
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </header>

        <!-- Body -->
        <div class="flex-1 flex flex-col md:flex-row max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6 overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 shrink-0 flex flex-col gap-2">
                <nav class="space-y-1 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
                    <!-- Dashboard - public -->
                    <Link href="/" :class="navClass('/')">
                        <span class="flex items-center gap-3"><i class="fa-solid fa-chart-pie text-lg"></i> แดชบอร์ดรวม</span>
                        <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                    </Link>

                    <!-- Auth-required menus -->
                    <template v-if="$page.props.auth.user">
                        <Link href="/borrow" :class="navClass('/borrow')">
                            <span class="flex items-center gap-3 relative">
                                <i class="fa-solid fa-basket-shopping text-lg"></i> ขอยืมอุปกรณ์
                                <span v-if="cartCount > 0" class="absolute -top-1.5 -right-2 bg-rose-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ cartCount }}</span>
                            </span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>

                        <Link href="/inventory" :class="navClass('/inventory')">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-boxes-stacked text-lg"></i> คลังวัสดุอุปกรณ์</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>

                        <Link href="/tracking" :class="navClass('/tracking')">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-map-location-dot text-lg"></i> ติดตามสถานะครุภัณฑ์</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>

                        <Link href="/statistics" :class="navClass('/statistics')">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-chart-column text-lg"></i> สถิติเชิงลึก</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>

                        <Link href="/history" :class="navClass('/history')">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-history text-lg"></i> ประวัติรายการยืม-คืน</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>

                        <Link v-if="$page.props.auth.user.role === 'admin'" href="/admin/return" :class="navClass('/admin/return')">
                            <span class="flex items-center gap-3 font-semibold text-indigo-600"><i class="fa-solid fa-screwdriver-wrench text-lg"></i> คืนอุปกรณ์</span>
                            <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                        </Link>
                    </template>

                    <!-- Guest lock notice -->
                    <template v-else>
                        <div class="px-4 py-3 text-xs text-slate-400 flex items-center gap-2 border-t border-slate-100 mt-1 pt-3">
                            <i class="fa-solid fa-lock text-slate-300"></i>
                            เมนูอื่นต้องเข้าสู่ระบบก่อน
                        </div>
                    </template>
                </nav>

                <div class="text-white p-5 rounded-2xl shadow-md hidden md:flex flex-col gap-3 mt-auto" style="background: linear-gradient(to bottom right, #1e1b4b, #0f172a);">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-indigo-300"></i>
                        <h3 class="font-bold text-sm">ติดต่อเจ้าหน้าที่ IT</h3>
                    </div>
                    <p class="text-xs text-indigo-200 leading-relaxed">มีปัญหาในการยืมอุปกรณ์? กรุณาติดต่อ กลุ่มงานระบบฯ ห้อง A-414 หรือโทร 2404</p>
                    <div class="h-px bg-indigo-800/60 my-1"></div>
                    <div class="flex justify-between items-center text-xs text-indigo-300">
                        <span><i class="fa-solid fa-clock mr-1"></i> จันทร์-ศุกร์</span>
                        <span>08:30 - 16:30 น.</span>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 bg-white border border-slate-200 shadow-sm rounded-2xl flex flex-col overflow-y-auto scrollbar-thin p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { computed, provide, reactive } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useCart } from '@/composables/useCart'

const page = usePage()
const { cartCount } = useCart()

const initials = computed(() => {
    const name = page.props.auth.user?.name || ''
    return name.split(' ').map(n => n.charAt(0)).join('').substring(0, 2)
})

function navClass(path) {
    const base = 'w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 '
    const active = 'bg-indigo-50 text-indigo-700'
    const inactive = 'text-slate-600 hover:bg-slate-50 hover:text-slate-950'
    const isActive = path === '/' ? page.url === '/' : page.url.startsWith(path)
    return base + (isActive ? active : inactive)
}

// Toast system
const toast = reactive({ show: false, message: '', type: 'success' })
let toastTimer = null

function showToast(message, type = 'success') {
    toast.message = message
    toast.type = type
    toast.show = true
    clearTimeout(toastTimer)
    toastTimer = setTimeout(() => { toast.show = false }, 3000)
}

provide('showToast', showToast)

function doLogout() {
    router.post('/logout')
}
</script>

<style>
body { font-family: 'Prompt', 'Sarabun', sans-serif; }
.scrollbar-thin::-webkit-scrollbar { width: 6px; height: 6px; }
.scrollbar-thin::-webkit-scrollbar-track { background: #f1f5f9; }
.scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
</style>
