<template>
    <AppLayout>
        <Head title="คลังวัสดุอุปกรณ์" />

        <!-- Header row -->
        <div class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center mb-6">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input v-model="search" type="text" placeholder="ค้นหาชื่ออุปกรณ์ รหัส หรือซีเรียล..."
                           class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white" />
                </div>
                <div class="flex items-center gap-2">
                    <select v-model="categoryFilter" class="px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="all">ทุกหมวดหมู่</option>
                        <option value="it">กลุ่ม IT</option>
                        <option value="av">กลุ่ม AV</option>
                    </select>
                    <select v-model="statusFilter" class="px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                        <option value="all">ทุกสถานะ</option>
                        <option value="Available">ว่าง / พร้อมยืม</option>
                        <option value="Borrowed">ถูกยืม</option>
                        <option value="Pending">รอตรวจสอบ</option>
                        <option value="Maintenance">ส่งซ่อม</option>
                    </select>
                </div>
            </div>

            <button v-if="isAdmin" @click="showAddModal = true"
                    class="shrink-0 flex items-center gap-2 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-sm transition-colors"
                    style="background:#4f46e5;">
                <i class="fa-solid fa-plus"></i> เพิ่มทะเบียนอุปกรณ์ใหม่
            </button>
        </div>

        <!-- Equipment Grid -->
        <div v-if="equipments.data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div v-for="item in equipments.data" :key="item.id"
                 class="bg-white border border-slate-200 hover:border-slate-300 rounded-2xl overflow-hidden shadow-sm transition-all duration-200 group flex flex-col">
                <div class="relative overflow-hidden h-44 bg-slate-100">
                    <img :src="item.image" :alt="item.name"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                         loading="lazy"
                         @error="e => e.target.src = `https://placehold.co/400x220/e2e8f0/94a3b8?text=${encodeURIComponent(item.id)}`" />
                    <span :class="['absolute top-3 right-3 text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm', getStatusColor(item.status)]">
                        {{ getStatusThai(item.status) }}
                    </span>
                    <span :class="['absolute top-3 left-3 text-[10px] font-semibold px-2 py-0.5 rounded-full',
                                   item.category === 'it' ? 'bg-blue-600/90 text-white' : 'bg-violet-600/90 text-white']">
                        {{ item.category === 'it' ? 'IT' : 'AV' }}
                    </span>
                </div>
                <div class="p-5 flex flex-col gap-3 flex-1">
                    <div>
                        <span class="text-[10px] text-slate-400 font-mono">ID: {{ item.id }}</span>
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors mt-0.5">{{ item.name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2">{{ item.description }}</p>
                    </div>
                    <div class="h-px bg-slate-100"></div>
                    <div class="flex items-center justify-between text-xs text-slate-400 mt-auto">
                        <span class="font-mono truncate">S/N: {{ item.serial }}</span>
                        <button v-if="item.status === 'Available' && !inCart(item.id)"
                                @click="doAddToCart(item.id)"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1 transition-colors shrink-0">
                            <i class="fa-solid fa-plus-circle"></i> เลือกยืม
                        </button>
                        <button v-else-if="item.status === 'Available' && inCart(item.id)"
                                @click="doRemoveFromCart(item.id)"
                                class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1 transition-colors shrink-0">
                            <i class="fa-solid fa-minus-circle"></i> ยกเลิก
                        </button>
                        <button v-else disabled class="bg-slate-100 text-slate-400 cursor-not-allowed font-semibold py-1.5 px-3 rounded-lg text-xs shrink-0">ไม่ว่างให้ยืม</button>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="flex flex-col items-center justify-center py-20 text-slate-400">
            <i class="fa-solid fa-box-open text-5xl text-slate-200 mb-3"></i>
            <p class="font-semibold text-sm">ไม่พบอุปกรณ์ที่ตรงกับเงื่อนไข</p>
        </div>

        <!-- Pagination -->
        <div v-if="equipments.last_page > 1" class="flex items-center justify-between mt-2">
            <span class="text-xs text-slate-500">
                แสดง <strong class="text-slate-800">{{ equipments.from }}-{{ equipments.to }}</strong>
                จาก <strong class="text-slate-800">{{ equipments.total }}</strong> รายการ
            </span>
            <div class="flex gap-1.5">
                <Link v-if="equipments.prev_page_url" :href="equipments.prev_page_url"
                      class="px-3 py-1.5 text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg flex items-center gap-1">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i> ย้อนกลับ
                </Link>
                <span v-for="link in pageNumbers" :key="link.label">
                    <Link v-if="link.url" :href="link.url"
                          :class="['px-3 py-1.5 text-xs font-semibold border rounded-lg transition-colors',
                                   link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white border-slate-200 hover:bg-slate-50 text-slate-700']">
                        {{ link.label }}
                    </Link>
                    <span v-else :class="['px-3 py-1.5 text-xs font-semibold border rounded-lg bg-slate-50 border-slate-200 text-slate-400']">{{ link.label }}</span>
                </span>
                <Link v-if="equipments.next_page_url" :href="equipments.next_page_url"
                      class="px-3 py-1.5 text-xs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg flex items-center gap-1">
                    ถัดไป <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </Link>
            </div>
        </div>

        <!-- Add Equipment Modal -->
        <Transition name="modal">
            <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" @click.self="closeModal">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                    <div class="flex justify-between items-center p-5 text-white" style="background:#1e293b;">
                        <h3 class="font-bold text-base flex items-center gap-2">
                            <i class="fa-solid fa-plus-circle" style="color:#34d399;"></i> เพิ่มทะเบียนวัสดุอุปกรณ์ใหม่
                        </h3>
                        <button @click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                    <form @submit.prevent="doAddEquipment" class="p-6 space-y-4">
                        <!-- Image upload -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">รูปภาพอุปกรณ์</label>
                            <div class="flex gap-4 items-start">
                                <div class="w-24 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center">
                                    <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" alt="preview" />
                                    <i v-else class="fa-solid fa-image text-2xl text-slate-300"></i>
                                </div>
                                <div class="flex-1">
                                    <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 transition-colors"
                                           :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''"
                                           @dragover.prevent="isDragging = true"
                                           @dragleave="isDragging = false"
                                           @drop.prevent="onDrop">
                                        <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-xl mb-1"></i>
                                        <span class="text-xs text-slate-500 font-medium">คลิกหรือลากวางไฟล์รูปภาพ</span>
                                        <span class="text-[10px] text-slate-400">PNG, JPG, WEBP (สูงสุด 5MB)</span>
                                        <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange" />
                                    </label>
                                    <button v-if="imagePreview" type="button" @click="clearImage"
                                            class="mt-1.5 text-[11px] text-rose-500 hover:text-rose-700 flex items-center gap-1">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i> ลบรูปภาพ
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">หมวดหมู่</label>
                                <select v-model="addForm.category" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                    <option value="it">กลุ่มอุปกรณ์ IT</option>
                                    <option value="av">กลุ่มสื่อโสตทัศน์ (AV)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสพัสดุ (Asset ID)</label>
                                <input v-model="addForm.id" type="text" required placeholder="IT-015"
                                       :class="['w-full border rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none uppercase',
                                                addForm.errors.id ? 'border-rose-400' : 'border-slate-200']" />
                                <p v-if="addForm.errors.id" class="text-rose-500 text-[11px] mt-0.5">{{ addForm.errors.id }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อวัสดุอุปกรณ์</label>
                            <input v-model="addForm.name" type="text" required placeholder="เช่น iPad Pro 11นิ้ว M2"
                                   class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">ซีเรียลนัมเบอร์</label>
                                <input v-model="addForm.serial" type="text" placeholder="SN8493012"
                                       class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">คำอธิบาย / สเปก</label>
                                <input v-model="addForm.description" type="text" placeholder="เช่น 256GB, ชาร์จเจอร์"
                                       class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex gap-3">
                            <button type="button" @click="closeModal"
                                    class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-sm transition-colors">
                                ยกเลิก
                            </button>
                            <button type="submit" :disabled="addForm.processing"
                                    class="flex-1 text-white font-bold py-2.5 rounded-xl text-sm transition-colors disabled:opacity-60"
                                    style="background:#4f46e5;">
                                <i class="fa-solid fa-save mr-1.5"></i> บันทึกลงคลัง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch, inject } from 'vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { useCart } from '@/composables/useCart'
import Swal from 'sweetalert2'

const props = defineProps({
    equipments: { type: Object, required: true },
    filters:    { type: Object, default: () => ({}) },
})

const page      = usePage()
const showToast = inject('showToast')
const isAdmin   = computed(() => page.props.auth.user?.role === 'admin')
const { inCart, addToCart, removeFromCart } = useCart()

// Filters (initialize from server-side filter state)
const search         = ref(props.filters?.search    || '')
const categoryFilter = ref(props.filters?.category  || 'all')
const statusFilter   = ref(props.filters?.status    || 'all')

// Pagination links (exclude prev/next labels)
const pageNumbers = computed(() => (props.equipments.links || []).filter(l => l.label !== '&laquo; Previous' && l.label !== 'Next &raquo;'))

// Debounced search
let searchTimer = null
watch(search, () => {
    clearTimeout(searchTimer)
    searchTimer = setTimeout(applyFilters, 350)
})
watch([categoryFilter, statusFilter], applyFilters)

function applyFilters() {
    router.get('/inventory', {
        search:   search.value || undefined,
        category: categoryFilter.value !== 'all' ? categoryFilter.value : undefined,
        status:   statusFilter.value   !== 'all' ? statusFilter.value   : undefined,
    }, { preserveState: true, replace: true })
}

function getStatusColor(s) {
    return { Available: 'bg-emerald-100 text-emerald-700', Borrowed: 'bg-blue-100 text-blue-700', Pending: 'bg-amber-100 text-amber-700', Maintenance: 'bg-rose-100 text-rose-700' }[s] || 'bg-slate-100 text-slate-600'
}
function getStatusThai(s) {
    return { Available: 'ว่างพร้อมยืม', Borrowed: 'กำลังถูกยืม', Pending: 'รอตรวจสอบ', Maintenance: 'ส่งซ่อม' }[s] || s
}

function doAddToCart(id)      { addToCart(id);      showToast?.('เพิ่มในรายการยืมแล้ว') }
function doRemoveFromCart(id) { removeFromCart(id); showToast?.('ลบออกจากรายการแล้ว', 'warning') }

// Add Equipment Modal
const showAddModal = ref(false)
const imagePreview = ref('')
const isDragging   = ref(false)
const fileInput    = ref(null)

const addForm = useForm({
    id: '', name: '', category: 'it', serial: '', description: '', image: null,
})

function processFile(file) {
    if (!file || !file.type.startsWith('image/')) return
    if (file.size > 5 * 1024 * 1024) { showToast?.('ไฟล์รูปภาพต้องมีขนาดไม่เกิน 5MB', 'warning'); return }
    addForm.image = file
    const reader = new FileReader()
    reader.onload = e => { imagePreview.value = e.target.result }
    reader.readAsDataURL(file)
}
function onFileChange(e) { processFile(e.target.files[0]) }
function onDrop(e)       { isDragging.value = false; processFile(e.dataTransfer.files[0]) }
function clearImage()    { imagePreview.value = ''; addForm.image = null; if (fileInput.value) fileInput.value.value = '' }

function closeModal() {
    showAddModal.value = false
    addForm.reset()
    clearImage()
}

function doAddEquipment() {
    addForm.id = addForm.id.toUpperCase()
    addForm.post('/inventory', {
        onSuccess: () => {
            closeModal()
            const msg = page.props.flash?.success || 'เพิ่มอุปกรณ์สำเร็จ!'
            Swal.fire({ title: msg, icon: 'success', timer: 1800, showConfirmButton: false })
        },
    })
}
</script>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: all .2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
