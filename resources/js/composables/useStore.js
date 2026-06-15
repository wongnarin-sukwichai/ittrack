import { reactive, computed } from 'vue'

const state = reactive({
    equipments: [
        { id: 'IT-001', name: 'MacBook Air M2 13"', category: 'it', serial: 'SN-MBA2026-001', description: 'RAM 16GB, SSD 512GB สำหรับเขียนโปรแกรมหรืองานวิชาการ', status: 'Available', icon: 'fa-laptop', currentLocation: 'อาคาร 4 ชั้น 2 ห้องคอมพิวเตอร์หลัก', lifecycleState: 'Active', image: 'https://picsum.photos/seed/macbook/400/220' },
        { id: 'IT-002', name: 'iPad Pro 11" M2 Wifi', category: 'it', serial: 'SN-IPD2026-002', description: 'พร้อม Apple Pencil Gen 2 สำหรับการสอนและการจดบันทึก', status: 'Borrowed', icon: 'fa-tablet-screen-button', currentLocation: 'ชั้น 1 ห้องงานลงทะเบียนนักศึกษา', lifecycleState: 'Relocated', image: 'https://picsum.photos/seed/ipad/400/220' },
        { id: 'IT-003', name: 'USB-C Multiport Adapter 7-in-1', category: 'it', serial: 'SN-ADP7IN1-003', description: 'HDMI 4K, USB-A, SD Card Reader สำหรับเชื่อมต่อหน้าจอ', status: 'Available', icon: 'fa-network-wired', currentLocation: 'อาคาร 4 ชั้น 2 ห้องเก็บอุปกรณ์สำรอง', lifecycleState: 'Active', image: 'https://picsum.photos/seed/adapter/400/220' },
        { id: 'AV-001', name: 'Epson Projector Full HD 4000lm', category: 'av', serial: 'SN-EPSPROJ-001', description: 'โปรเจคเตอร์ความสว่างสูง มีพอร์ต HDMI และระบบเชื่อมต่อไร้สาย', status: 'Available', icon: 'fa-circle-play', currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3', lifecycleState: 'Active', image: 'https://picsum.photos/seed/projector/400/220' },
        { id: 'AV-002', name: 'Shure Wireless Mic SLXD24 (ไมค์เดี่ยว)', category: 'av', serial: 'SN-SHUREMIC-002', description: 'ไมโครโฟนไร้สายระดับพรีเมียม สัญญาณเสถียร เหมาะสำหรับสัมมนา', status: 'Pending', icon: 'fa-microphone', currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3', lifecycleState: 'Active', image: 'https://picsum.photos/seed/microphone/400/220' },
        { id: 'AV-003', name: 'Sony Alpha 7 IV (Body + 28-70mm)', category: 'av', serial: 'SN-SONYA74-003', description: 'กล้องถ่ายภาพความละเอียดสูง 33MP สำหรับถ่ายวิดีโอสัมมนากิจกรรม', status: 'Maintenance', icon: 'fa-camera', currentLocation: 'แผนกเคลมสินค้า ศูนย์บริการ Sony (พระราม 9)', lifecycleState: 'Under Repair', image: 'https://picsum.photos/seed/camera/400/220' },
        { id: 'IT-004', name: 'Asus ExpertBook B1 14"', category: 'it', serial: 'SN-ASUSB1-004', description: 'Windows 11 Pro, RAM 16GB สำหรับทำงานทั่วไป', status: 'Available', icon: 'fa-laptop', currentLocation: 'อาคาร 4 ชั้น 2 ห้องทำงานเจ้าหน้าที่ฝ่าย IT', lifecycleState: 'Active', image: 'https://picsum.photos/seed/asus/400/220' },
        { id: 'AV-004', name: 'Rode Wireless GO II Dual Mic', category: 'av', serial: 'SN-RODEWGO-004', description: 'ไมโครโฟนหนีบปกเสื้อแบบคู่ ไร้สายตัวเล็ก น้ำหนักเบา', status: 'Available', icon: 'fa-microphone-lines', currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3', lifecycleState: 'Active', image: 'https://picsum.photos/seed/rode/400/220' },
    ],
    borrowRequests: [
        { id: 'REQ-2026-001', borrowerName: 'อาจารย์สุดา เทพพรดี', department: 'แผนกวิชาศิลปศาสตร์', email: 'suda.t@msu.ac.th', items: ['IT-002'], purpose: 'ใช้งานเปิดพรีเซนต์เทชันวิชาประวัติศาสตร์สากล ณ ห้อง 431', borrowDate: '2026-06-08', returnDate: '2026-06-12', status: 'Approved' },
        { id: 'REQ-2026-002', borrowerName: 'ดร.สมชาย ยอดรัก', department: 'สาขาคอมพิวเตอร์', email: 'somchai.y@msu.ac.th', items: ['AV-002'], purpose: 'สัมมนาการเรียนรู้ Machine Learning ยุคใหม่ อาคาร 5 ชั้น 1', borrowDate: '2026-06-09', returnDate: '2026-06-11', status: 'Pending' },
        { id: 'REQ-2026-003', borrowerName: 'นายพงษ์ศักดิ์ รัตนวิจิตร', department: 'กลุ่มงานประชาสัมพันธ์', email: 'pongsak.r@msu.ac.th', items: ['AV-003'], purpose: 'ทำรายงานกิจกรรมปฐมนิเทศพนักงานใหม่', borrowDate: '2026-06-05', returnDate: '2026-06-07', status: 'Overdue' },
        { id: 'REQ-2026-004', borrowerName: 'คุณศศิวิมล มิ่งขวัญ', department: 'กลุ่มประชาสัมพันธ์', email: 'sasiwimol.m@msu.ac.th', items: ['IT-003'], purpose: 'ขอยืมสายแปลงสัญญาณจัดนิทรรศการวิชาการ', borrowDate: '2026-06-01', returnDate: '2026-06-03', status: 'Returned' },
        { id: 'REQ-2026-005', borrowerName: 'ดร.ปรีชา มีทอง', department: 'สาขาวิศวกรรมศาสตร์', email: 'preecha.m@msu.ac.th', items: ['IT-001'], purpose: 'ใช้ประมวลผลโมเดลคอมพิวเตอร์ในห้องปฏิบัติการวิจัย', borrowDate: '2026-05-25', returnDate: '2026-05-28', status: 'Returned' },
        { id: 'REQ-2026-006', borrowerName: 'นางสาวจารุณี แสนสุข', department: 'งานบริการการศึกษา', email: 'jarunee.s@msu.ac.th', items: ['AV-004'], purpose: 'บันทึกภาพถ่ายทอดสดพิธีมอบประกาศนียบัตร', borrowDate: '2026-06-02', returnDate: '2026-06-04', status: 'Returned' },
    ],
    assetLogs: [
        { id: 'L-01', assetId: 'IT-001', date: '2026-06-08', action: 'Inspect', detail: 'ตรวจเช็คสภาพฮาร์ดแวร์ประจำปี ทำความสะอาดฝุ่นภายในเครื่อง ผลการตรวจผ่านระดับดีเยี่ยม', location: 'อาคาร 4 ชั้น 2 ห้องคอมพิวเตอร์หลัก', operator: 'สิริพร สุวรรณ' },
        { id: 'L-02', assetId: 'IT-002', date: '2026-06-05', action: 'Relocate', detail: 'ย้ายจุดติดตั้งจากฝ่ายวิชาการชั้น 3 ลงมาอำนวยการรับลงทะเบียนที่ ชั้น 1 ห้องงานลงทะเบียนนักศึกษา', location: 'ชั้น 1 ห้องงานลงทะเบียนนักศึกษา', operator: 'สมยศ ดีจิตร' },
        { id: 'L-03', assetId: 'AV-003', date: '2026-06-04', action: 'Maintenance', detail: 'เซนเซอร์มีจุดฝุ่นรบกวน ส่งเคลมศูนย์บริการด่วนสำหรับตรวจเซ็คระดับความละเอียดลึก', location: 'แผนกเคลมสินค้า ศูนย์บริการ Sony (พระราม 9)', operator: 'กิตติศักดิ์ พูลเพิ่ม' },
        { id: 'L-04', assetId: 'IT-004', date: '2026-06-02', action: 'Inspect', detail: 'อัปเกรดระบบปฏิบัติการ Windows 11 และลงโปรแกรมรักษาความปลอดภัยความเสถียรล่าสุด', location: 'อาคาร 4 ชั้น 2 ห้องทำงานเจ้าหน้าที่ฝ่าย IT', operator: 'วันชัย มั่นคง' },
        { id: 'L-05', assetId: 'IT-001', date: '2026-05-28', action: 'Relocate', detail: 'ย้ายจุดให้บริการคอมพิวเตอร์สืบค้นข้อมูลมายังอาคารหอสมุด ชั้น 1', location: 'อาคารหอสมุด ชั้น 1', operator: 'สมยศ ดีจิตร' },
        { id: 'L-06', assetId: 'AV-001', date: '2026-05-20', action: 'Inspect', detail: 'ทำความสะอาดฟิลเตอร์เครื่องฉายภาพ และตรวจวัดความเข้มแสงหลอดภาพ', location: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3', operator: 'กิตติศักดิ์ พูลเพิ่ม' },
        { id: 'L-07', assetId: 'IT-002', date: '2026-05-15', action: 'Decommission', detail: 'หมดอายุการใช้งานโครงการจัดสรรคอมพิวเตอร์ปี 2021 ดำเนินเรื่องคัดจำหน่ายพัสดุพ้นทะเบียน', location: 'ห้องพัสดุกลาง ชั้น 1', operator: 'สิริพร สุวรรณ' },
        { id: 'L-08', assetId: 'IT-003', date: '2026-05-10', action: 'Maintenance', detail: 'พบอาการพอร์ต HDMI สัญญาณติดๆ ดับๆ นำส่งบอร์ดรับประกันและทำการเปลี่ยนชิปแปลงสัญญาณใหม่', location: 'ห้องซ่อมบำรุงวิชาการ ชั้น 2', operator: 'วันชัย มั่นคง' },
    ],
    cart: [],
    selectedTrackingAssetId: 'IT-001',
    trackingFilter: 'all',
})

// ---- Computed helpers ----
const statAvailable  = computed(() => state.equipments.filter(e => e.status === 'Available').length)
const statBorrowed   = computed(() => state.equipments.filter(e => e.status === 'Borrowed').length)
const statOverdue    = computed(() => state.borrowRequests.filter(r => r.status === 'Overdue').length)
const statPending    = computed(() => state.borrowRequests.filter(r => r.status === 'Pending').length)

// ---- Actions ----
function addToCart(itemId) {
    if (!state.cart.includes(itemId)) state.cart.push(itemId)
}
function removeFromCart(itemId) {
    state.cart = state.cart.filter(id => id !== itemId)
}
function clearCart() {
    state.cart = []
}

function submitBorrowRequest({ borrowerName, email, purpose, borrowDate, returnDate }) {
    const newId = `REQ-2026-${String(state.borrowRequests.length + 1).padStart(3, '0')}`
    state.borrowRequests.unshift({
        id: newId,
        borrowerName,
        department: 'ผู้ใช้งานระบบ',
        email,
        items: [...state.cart],
        purpose,
        borrowDate,
        returnDate,
        status: 'Pending',
    })
    state.cart.forEach(itemId => {
        const item = state.equipments.find(e => e.id === itemId)
        if (item) item.status = 'Pending'
    })
    clearCart()
    return newId
}

function submitQuickBorrow({ assetId, borrowerName, email }) {
    const asset = state.equipments.find(e => e.id === assetId.toUpperCase() || e.serial.toUpperCase() === assetId.toUpperCase())
    if (!asset) return { error: `ไม่พบรหัสอุปกรณ์ "${assetId}"` }
    if (asset.status !== 'Available') return { error: `${asset.name} ไม่ว่างให้ยืม (สถานะ: ${getStatusThai(asset.status)})` }

    const newId = `REQ-2026-${String(state.borrowRequests.length + 1).padStart(3, '0')}`
    const today = new Date().toISOString().split('T')[0]
    const d = new Date(); d.setDate(d.getDate() + 7)
    const returnDateStr = d.toISOString().split('T')[0]
    state.borrowRequests.unshift({ id: newId, borrowerName, department: 'บันทึกด่วน', email, items: [asset.id], purpose: 'บันทึกยืมด่วนโดยเจ้าหน้าที่', borrowDate: today, returnDate: returnDateStr, status: 'Approved' })
    asset.status = 'Borrowed'
    addAssetLog(asset.id, 'Relocate', `ยืมด่วนโดย ${borrowerName}`, asset.currentLocation, borrowerName)
    return { success: newId }
}

function processReturn(input) {
    const key = input.trim().toUpperCase()
    const item = state.equipments.find(e => e.id === key || e.serial.toUpperCase() === key)
    if (!item) return { error: `ไม่พบอุปกรณ์รหัส "${input}"` }
    if (item.status === 'Available') return { error: `${item.name} ว่างอยู่แล้ว` }
    item.status = 'Available'
    const req = state.borrowRequests.find(r => r.items.includes(item.id) && (r.status === 'Approved' || r.status === 'Overdue'))
    if (req) req.status = 'Returned'
    addAssetLog(item.id, 'Inspect', 'ส่งคืนพัสดุอุปกรณ์สำเร็จ ตรวจสอบสภาพสมบูรณ์ดี', 'คลังพัสดุ IT', 'เจ้าหน้าที่')
    return { success: `รับคืน ${item.name} เรียบร้อย` }
}

function addAssetLog(assetId, action, detail, location, operator) {
    const id = `L-${String(state.assetLogs.length + 1).padStart(2, '0')}`
    state.assetLogs.unshift({ id, assetId, date: new Date().toISOString().split('T')[0], action, detail, location, operator })
}

function submitAssetLog({ assetId, action, location, detail, operator }) {
    const asset = state.equipments.find(e => e.id === assetId)
    if (!asset) return
    if (action === 'Maintenance') { asset.lifecycleState = 'Under Repair'; asset.status = 'Maintenance' }
    else if (action === 'Relocate') { asset.lifecycleState = 'Relocated' }
    else if (action === 'Decommission') { asset.lifecycleState = 'Decommissioned'; asset.status = 'Maintenance' }
    else if (action === 'Inspect') { asset.lifecycleState = 'Active' }
    asset.currentLocation = location
    addAssetLog(assetId, action, detail, location, operator)
}

function addEquipment({ category, name, id, serial, description, image }) {
    state.equipments.push({
        id: id.toUpperCase(),
        name, category,
        serial: serial || 'N/A',
        description: description || '-',
        status: 'Available',
        icon: category === 'it' ? 'fa-laptop' : 'fa-camera',
        currentLocation: category === 'it' ? 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2' : 'ห้องโสตทัศน์ ชั้น 3',
        lifecycleState: 'Active',
        image: image || `https://picsum.photos/seed/${id}/400/220`,
    })
}

// ---- Helpers ----
function getStatusColor(status) {
    const map = { Available: 'bg-emerald-50 text-emerald-700 border border-emerald-200', Borrowed: 'bg-blue-50 text-blue-700 border border-blue-200', Pending: 'bg-amber-50 text-amber-700 border border-amber-200', Maintenance: 'bg-rose-50 text-rose-700 border border-rose-300' }
    return map[status] || 'bg-slate-50 text-slate-500'
}
function getStatusThai(status) {
    const map = { Available: 'ว่าง / พร้อมยืม', Borrowed: 'ถูกยืมใช้งาน', Pending: 'รอการอนุมัติ', Maintenance: 'ส่งบำรุง / ซ่อม' }
    return map[status] || 'ไม่ทราบสถานะ'
}
function getRequestStatusColor(status) {
    const map = { Pending: 'bg-amber-50 text-amber-800 border border-amber-200', Approved: 'bg-blue-50 text-blue-800 border border-blue-200', Returned: 'bg-emerald-50 text-emerald-800 border border-emerald-200', Overdue: 'bg-rose-50 text-rose-800 border border-rose-200' }
    return map[status] || 'bg-slate-50 text-slate-500'
}
function getRequestStatusThai(status) {
    const map = { Pending: 'รอการตรวจสอบ', Approved: 'กำลังยืมใช้งาน', Returned: 'ส่งคืนแล้ว', Overdue: 'เกินกำหนดส่งคืน' }
    return map[status] || '-'
}
function getActionThai(action) {
    const map = { Inspect: 'ตรวจสภาพและซ่อมบำรุงเบื้องต้น', Relocate: 'ย้ายตำแหน่งติดตั้ง / เปลี่ยนพิกัด', Maintenance: 'ส่งซ่อมหน่วยงานภายนอก', Decommission: 'หมดอายุการใช้ / จำหน่ายพ้นโครงการ' }
    return map[action] || 'อื่นๆ'
}
function formatThaiDate(dateStr) {
    if (!dateStr) return ''
    const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.']
    const d = new Date(dateStr)
    if (isNaN(d.getTime())) return dateStr
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543}`
}

export function useStore() {
    return {
        state,
        statAvailable, statBorrowed, statOverdue, statPending,
        addToCart, removeFromCart, clearCart,
        submitBorrowRequest, submitQuickBorrow, processReturn,
        addAssetLog, submitAssetLog, addEquipment,
        getStatusColor, getStatusThai,
        getRequestStatusColor, getRequestStatusThai,
        getActionThai, formatThaiDate,
    }
}
