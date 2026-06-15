<template>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(to bottom right, #0f172a, #1e1b4b, #0f172a);">
        <Head title="เข้าสู่ระบบ" />

        <div class="w-full max-w-md">
            <!-- Logo Card -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl shadow-2xl mb-4 border border-white/20"
                     style="background: linear-gradient(to top right, #2563eb, #4f46e5);">
                    <i class="fa-solid fa-laptop-medical text-4xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">ระบบยืม-คืนอุปกรณ์</h1>
                <p class="text-indigo-300 text-sm mt-1">กลุ่มงาน IT และ สื่อโสตทัศน์</p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-2">เข้าสู่ระบบ</h2>
                <p class="text-slate-500 text-sm mb-6">ใช้บัญชี Google ของมหาวิทยาลัยในการเข้าสู่ระบบ</p>

                <!-- Error banner (flash from server) -->
                <div v-if="errorMessage"
                     class="mb-4 flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl px-4 py-3">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-rose-500 shrink-0"></i>
                    <p class="text-sm font-medium leading-snug">{{ errorMessage }}</p>
                </div>

                <a href="/auth/google"
                   class="w-full flex items-center justify-center gap-3 bg-white border-2 border-slate-200 hover:border-indigo-400 hover:bg-indigo-50 text-slate-700 font-semibold py-3 px-4 rounded-xl transition-all shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    เข้าสู่ระบบด้วย Google
                </a>

                <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-500 text-center leading-relaxed">
                        <i class="fa-solid fa-shield-halved text-indigo-500 mr-1"></i>
                        ระบบใช้ Google OAuth 2.0 ในการยืนยันตัวตน<br>ไม่มีการเก็บรหัสผ่านในระบบ
                    </p>
                </div>

                <div class="mt-4 text-center">
                    <a href="/" class="text-xs text-slate-400 hover:text-indigo-600 transition-colors">
                        <i class="fa-solid fa-arrow-left mr-1"></i> กลับหน้าแดชบอร์ด (ไม่ต้องล็อกอิน)
                    </a>
                </div>
            </div>

            <p class="text-center text-indigo-400/60 text-xs mt-6">
                ITTrack &copy; {{ new Date().getFullYear() }} กลุ่มงาน IT และสื่อโสตทัศน์
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'

const page         = usePage()
const errorMessage = computed(() => page.props.flash?.error || null)
</script>
