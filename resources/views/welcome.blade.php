<!DOCTYPE html>
<html lang="th" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบยืม-คืนอุปกรณ์ IT & สื่อโสตทัศน์</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Prompt & Sarabun -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Anuphan', 'Sarabun', sans-serif;
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800">

    <!-- Toast Notification (Custom alert) -->
    <div id="toast" class="fixed top-5 right-5 z-50 transform translate-y-[-100px] opacity-0 transition-all duration-300 ease-out bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3">
        <span id="toast-icon" class="text-emerald-400 text-lg"><i class="fa-solid fa-circle-check"></i></span>
        <span id="toast-message" class="text-sm font-medium">ทำรายการสำเร็จ</span>
    </div>

    <!-- Header Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-tr from-blue-600 to-indigo-600 text-white p-2.5 rounded-xl shadow-md shadow-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-laptop-medical text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 leading-tight">ระบบยืม-คืนอุปกรณ์</h1>
                        <p class="text-xs text-indigo-600 font-semibold tracking-wide">กลุ่มงาน IT และ สื่อโสตทัศน์</p>
                    </div>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-4">
                    <!-- Role Selector (Demo purpose) -->
                    <div class="bg-slate-100 p-1 rounded-xl flex items-center gap-1 border border-slate-200">
                        <button onclick="switchRole('user')" id="role-user-btn" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 bg-white text-blue-600 shadow-sm">
                            <i class="fa-solid fa-user text-xxs"></i> ผู้ขอใช้บริการ
                        </button>
                        <button onclick="switchRole('admin')" id="role-admin-btn" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 text-slate-600 hover:text-slate-900">
                            <i class="fa-solid fa-user-shield text-xxs"></i> เจ้าหน้าที่ IT/AV
                        </button>
                    </div>

                    <!-- Current User Badge -->
                    <div class="hidden sm:flex items-center gap-2.5 pl-4 border-l border-slate-200">
                        <div class="text-right">
                            <p id="user-display-name" class="text-sm font-semibold text-slate-800">สมชาย ยอดรัก</p>
                            <p id="user-display-role" class="text-xs text-slate-500">อาจารย์ประจำสาขาคอมพิวเตอร์</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold" id="user-avatar">
                            สย
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Layout with Sidebar -->
    <div class="flex-1 flex flex-col md:flex-row max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6 overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 flex-shrink-0 flex flex-col gap-2">
            <nav class="space-y-1 bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
                <button onclick="changeTab('dashboard')" id="nav-dashboard" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 bg-indigo-50 text-indigo-700">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-pie text-lg"></i> แดชบอร์ดภาพรวม
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <button onclick="changeTab('inventory')" id="nav-inventory" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i> คลังวัสดุอุปกรณ์
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <button onclick="changeTab('cart')" id="nav-cart" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950">
                    <span class="flex items-center gap-3 relative">
                        <i class="fa-solid fa-basket-shopping text-lg"></i> ขอส่งยืมอุปกรณ์
                        <span id="cart-badge" class="absolute -top-1.5 -right-2 bg-rose-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">0</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <button onclick="changeTab('tracking')" id="nav-tracking" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-map-location-dot text-lg"></i> ติดตามสถานะครุภัณฑ์
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <button onclick="changeTab('statistics')" id="nav-statistics" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-column text-lg"></i> สถิติเชิงลึก
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <button onclick="changeTab('history')" id="nav-history" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950">
                    <span class="flex items-center gap-3">
                        <i class="fa-solid fa-history text-lg"></i> ประวัติรายการยืม-คืน
                    </span>
                    <i class="fa-solid fa-chevron-right text-xs opacity-70"></i>
                </button>

                <!-- Admin specific navigation link (visible only in Admin role) -->
                <button onclick="changeTab('admin')" id="nav-admin" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-400 hover:bg-slate-50 hover:text-slate-950 hidden">
                    <span class="flex items-center gap-3 font-semibold text-indigo-600">
                        <i class="fa-solid fa-screwdriver-wrench text-lg"></i> จัดการคำขอ (Admin)
                    </span>
                    <span id="admin-pending-badge" class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">3</span>
                </button>
            </nav>

            <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-md flex flex-col gap-3 mt-auto hidden md:flex">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-indigo-300"></i>
                    <h3 class="font-bold text-sm tracking-wide">ติดต่อเจ้าหน้าที่ IT</h3>
                </div>
                <p class="text-xs text-indigo-200 leading-relaxed">มีปัญหาในการยืมอุปกรณ์ หรือไม่พบอุปกรณ์ที่ต้องการ? กรุณาติดต่ออาคาร 4 ชั้น 2 หรือโทร 4001</p>
                <div class="h-px bg-indigo-800/60 my-1"></div>
                <div class="flex justify-between items-center text-xs text-indigo-300">
                    <span><i class="fa-solid fa-clock mr-1"></i> จันทร์-ศุกร์</span>
                    <span>08:30 - 16:30 น.</span>
                </div>
            </div>
        </aside>

        <!-- Main Workspace Pane -->
        <main class="flex-1 bg-white border border-slate-200 shadow-sm rounded-2xl flex flex-col overflow-y-auto max-h-[calc(100vh-10rem)] scrollbar-thin p-6">
            
            <!-- SECTION 1: DASHBOARD TAB -->
            <section id="tab-dashboard" class="flex flex-col gap-6">
                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 text-white p-6 rounded-2xl relative overflow-hidden shadow-lg">
                    <div class="absolute -right-10 -bottom-10 text-white/10 text-9xl font-bold"><i class="fa-solid fa-laptop"></i></div>
                    <div class="relative z-10 flex flex-col gap-2">
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full font-semibold w-max backdrop-blur-sm">ยินดีต้อนรับสู่ระบบยืม-คืน</span>
                        <h2 class="text-2xl font-bold">ค้นหาและยืมอุปกรณ์ที่คุณต้องการสำหรับการเรียนและการทำงานได้ง่ายขึ้น</h2>
                        <p class="text-indigo-100 text-xs sm:text-sm max-w-2xl">เรามีเครื่องโปรเจคเตอร์, โน้ตบุ๊กประสิทธิภาพสูง, อุปกรณ์ส่งสัญญาณ และไมค์ไร้สายคุณภาพสูงพร้อมให้คุณยืมใช้งาน</p>
                    </div>
                </div>

                <!-- Stats Overview Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 font-semibold">อุปกรณ์พร้อมใช้งาน</span>
                            <span id="stat-available" class="text-2xl font-extrabold text-slate-900 mt-1">12</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg"><i class="fa-solid fa-circle-check"></i></div>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 font-semibold">กำลังถูกยืมใช้งาน</span>
                            <span id="stat-borrowed" class="text-2xl font-extrabold text-slate-900 mt-1">4</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg"><i class="fa-solid fa-handshake"></i></div>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 font-semibold">ค้างคืน เกินกำหนดส่ง</span>
                            <span id="stat-overdue" class="text-2xl font-extrabold text-rose-600 mt-1">1</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl flex items-center justify-between shadow-sm">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 font-semibold">รอเจ้าหน้าที่ตรวจสอบ</span>
                            <span id="stat-pending" class="text-2xl font-extrabold text-amber-600 mt-1">2</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    </div>
                </div>

                <!-- 2-Column Grid: Left Column (Borrowed/Overdue) & Right Column (Returned) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left: Borrowed (Orange) & Overdue (Red) -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                            รายการค้างส่งมอบ & เกินกำหนดส่งคืน
                        </h3>
                        <div id="dashboard-borrowed-list" class="flex flex-col gap-3 max-h-[380px] overflow-y-auto scrollbar-thin pr-1">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>

                    <!-- Right: Returned (Green) -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i>
                            รายการส่งคืนแล้วเสร็จ
                        </h3>
                        <div id="dashboard-returned-list" class="flex flex-col gap-3 max-h-[380px] overflow-y-auto scrollbar-thin pr-1">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>
                </div>

                <!-- Wide Asset Tracking Status Table with Pagination -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col gap-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i>
                                ประวัติติดตามสถานะและการปรับพิกัดครุภัณฑ์ย้อนหลัง
                            </h3>
                            <p class="text-xxs text-slate-400 mt-0.5">บันทึกวงจรชีวิตครุภัณฑ์คอมพิวเตอร์และโสตฯ (ส่งซ่อม, ย้ายชั้น, จำหน่ายพ้นโครงการ)</p>
                        </div>
                        <span class="bg-indigo-50 text-indigo-700 text-xxs font-bold px-2.5 py-1 rounded-lg"><i class="fa-solid fa-history mr-1"></i>เรียงตามเหตุการณ์ล่าสุด</span>
                    </div>

                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="min-w-full divide-y divide-slate-200 text-xs">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">วันที่ทำรายการ</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">รหัสครุภัณฑ์</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">ชื่อวัสดุอุปกรณ์</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">เหตุการณ์ / สถานะ</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">รายละเอียดเหตุการณ์</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">พิกัดจัดวางใหม่</th>
                                    <th scope="col" class="px-4 py-3 text-left font-bold text-slate-500 uppercase tracking-wider">ผู้ทำบันทึก</th>
                                </tr>
                            </thead>
                            <tbody id="dashboard-tracking-table-body" class="bg-white divide-y divide-slate-100">
                                <!-- Populated dynamically by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Navigation Controls -->
                    <div class="flex items-center justify-between pt-1">
                        <span id="dashboard-table-info" class="text-xxs text-slate-500">แสดงรายการ 1-5 จากทั้งหมด 0 รายการ</span>
                        <div class="flex gap-1.5">
                            <button onclick="changeDashboardPage(-1)" id="dash-btn-prev" class="px-3 py-1.5 text-xxs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg transition-colors flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i> ย้อนกลับ
                            </button>
                            <button onclick="changeDashboardPage(1)" id="dash-btn-next" class="px-3 py-1.5 text-xxs font-semibold bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg transition-colors flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                ถัดไป <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 2: INVENTORY TAB -->
            <section id="tab-inventory" class="flex flex-col gap-6 hidden">
                <!-- Filters & Search Toolbar -->
                <div class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 transform -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" id="search-input" onkeyup="filterEquipment()" placeholder="ค้นหาชื่ออุปกรณ์ รหัส หรือซีเรียล..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="category-filter" onchange="filterEquipment()" class="px-3 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="all">ทุกหมวดหมู่</option>
                            <option value="it">กลุ่มอุปกรณ์ IT</option>
                            <option value="av">กลุ่มสื่อโสตทัศน์ (AV)</option>
                        </select>
                        <select id="status-filter" onchange="filterEquipment()" class="px-3 py-2 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            <option value="all">ทุกสถานะ</option>
                            <option value="Available">ว่าง / พร้อมยืม</option>
                            <option value="Borrowed">ถูกยืม</option>
                            <option value="Pending">รอตรวจสอบ</option>
                            <option value="Maintenance">ส่งซ่อม / บำรุง</option>
                        </select>
                    </div>
                </div>

                <!-- Equipment Grid Cards -->
                <div id="equipment-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Cards will be generated dynamically -->
                </div>
            </section>

            <!-- SECTION 3: CART/REQUEST FORM TAB -->
            <section id="tab-cart" class="flex flex-col gap-6 hidden">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-basket-shopping text-indigo-600"></i> ทำรายการขอส่งยืมวัสดุอุปกรณ์</h2>
                    <p class="text-xs text-slate-500 mt-1">กรุณาตรวจสอบรายการอุปกรณ์ที่เลือกและระบุรายละเอียดการยืมใช้งานให้ถูกต้องครบถ้วนเพื่อส่งอนุมัติ</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                    <!-- Left: Selected Items (Cart list) -->
                    <div class="lg:col-span-3 flex flex-col gap-4">
                        <h3 class="font-bold text-sm text-slate-800 flex items-center gap-2"><i class="fa-solid fa-list-check text-indigo-500"></i> อุปกรณ์ที่เลือกยืม (<span id="cart-item-count">0</span>)</h3>
                        <div id="cart-items-container" class="flex flex-col gap-3">
                            <!-- Dynamic Cart Items list -->
                        </div>
                    </div>

                    <!-- Right: Booking Form -->
                    <div class="lg:col-span-2 bg-slate-50 p-5 rounded-2xl border border-slate-100 flex flex-col gap-4">
                        <h3 class="font-bold text-sm text-slate-950 border-b border-slate-200 pb-2"><i class="fa-regular fa-file-lines text-indigo-600 mr-1.5"></i> รายละเอียดข้อมูลการยืม</h3>
                        
                        <form id="borrow-form" onsubmit="submitBorrowRequest(event)" class="space-y-4">
                            <!-- Borrower Info -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อผู้ขอใช้บริการ / แผนก</label>
                                <input type="text" id="form-borrower" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="เช่น ดร.สมชาย ยอดรัก (สาขาคอมพิวเตอร์)">
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                                <input type="text" id="form-phone" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="เช่น 089-XXXXXXX">
                            </div>

                            <!-- Purpose -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">วัตถุประสงค์ในการยืมใช้งาน</label>
                                <textarea id="form-purpose" rows="2" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none" placeholder="ระบุการเรียนการสอน ห้องประชุม หรือจัดกิจกรรม..."></textarea>
                            </div>

                            <!-- Borrow Date / Return Date -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">วันที่ต้องการยืม</label>
                                    <input type="date" id="form-borrow-date" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">วันที่ต้องการส่งคืน</label>
                                    <input type="date" id="form-return-date" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                                </div>
                            </div>

                            <!-- Terms Agreement -->
                            <div class="flex items-start gap-2 pt-2">
                                <input type="checkbox" id="form-agree" required class="mt-0.5 border-slate-300 rounded text-indigo-600 focus:ring-indigo-500">
                                <label for="form-agree" class="text-xxs text-slate-500 leading-normal">ข้าพเจ้ายินดีดูแลรักษาและจะคืนวัสดุอุปกรณ์ทั้งหมดตรงเวลา หากเกิดการชำรุดเสียหายจากความประมาท ยินดีชดใช้ตามระเบียบ</label>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-md flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i> ส่งคำขอยืมอุปกรณ์
                            </button>
                        </form>
                    </div>

                    <!-- Divider and Quick Borrow Section -->
                    <div class="col-span-1 lg:col-span-5 pt-4">
                        <hr class="border-slate-200 mb-6">
                        
                        <div class="bg-gradient-to-r from-slate-50 to-indigo-50/40 p-5 rounded-2xl border border-indigo-100 shadow-sm">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2"><i class="fa-solid fa-bolt text-amber-500"></i> บันทึกยืมอุปกรณ์อย่างรวดเร็ว (สำหรับเจ้าหน้าที่ / ด่วน)</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">ข้ามขั้นตอนการจัดลงตะกร้าแบบปกติ ทำรายการยึดสิทธิ์ผู้ยืมและเปลี่ยนสถานะพัสดุในคลังเป็น "ถูกยืมทันที"</p>
                                </div>
                                <span class="bg-indigo-100 text-indigo-800 text-[10px] font-bold px-2 py-0.5 rounded-full"><i class="fa-solid fa-bolt mr-1"></i> Quick Action</span>
                            </div>

                            <form id="quick-borrow-form" onsubmit="submitQuickBorrow(event)" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">รหัสพัสดุอุปกรณ์ (Asset ID)</label>
                                    <input type="text" id="qb-asset-id" required placeholder="เช่น IT-001 หรือ AV-001" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white uppercase">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">ชื่อผู้ขอใช้บริการ / แผนก</label>
                                    <input type="text" id="qb-borrower" required placeholder="ระบุผู้ยืม เช่น ศศิวิมล (ประชาสัมพันธ์)" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">วันที่ต้องการคืนอุปกรณ์</label>
                                    <input type="date" id="qb-return-date" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-indigo-500 focus:outline-none bg-white">
                                </div>
                                <div>
                                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-2">
                                        <i class="fa-solid fa-clipboard-check text-emerald-400"></i> บันทึกยืมด่วนทันที
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </section>

            <!-- SECTION 4: LIFE CYCLE TRACKING TAB -->
            <section id="tab-tracking" class="flex flex-col gap-6 hidden">
                <div class="border-b border-slate-200 pb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-map-location-dot text-indigo-600"></i> กำกับติดตามสถานะประวัติครุภัณฑ์</h2>
                        <p class="text-xs text-slate-500 mt-1">สืบค้นประวัติย้อนหลัง บันทึกวงจรชีวิตพัสดุ (ส่งซ่อม, ย้ายชั้น/พิกัด, พ้นโครงการ/จำหน่ายออก)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left column: Assets list with status & current location -->
                    <div class="lg:col-span-5 flex flex-col gap-3">
                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 flex justify-between items-center">
                            <span class="text-xs font-bold text-slate-700">เลือกครุภัณฑ์เพื่อดูประวัติ</span>
                            <div class="flex gap-1">
                                <button onclick="filterTrackingList('all')" id="track-btn-all" class="px-2 py-1 text-[10px] font-semibold rounded bg-slate-200 text-slate-800">ทั้งหมด</button>
                                <button onclick="filterTrackingList('it')" id="track-btn-it" class="px-2 py-1 text-[10px] font-semibold rounded bg-white text-slate-600 hover:bg-slate-100">คอม/IT</button>
                                <button onclick="filterTrackingList('av')" id="track-btn-av" class="px-2 py-1 text-[10px] font-semibold rounded bg-white text-slate-600 hover:bg-slate-100">สื่อ AV</button>
                            </div>
                        </div>

                        <!-- Mini Asset cards list -->
                        <div id="tracking-assets-list" class="flex flex-col gap-2 max-h-[500px] overflow-y-auto scrollbar-thin">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>

                    <!-- Right column: Selected Asset History & Log Editor -->
                    <div class="lg:col-span-7 flex flex-col gap-5 bg-slate-50/50 p-5 rounded-2xl border border-slate-200">
                        <div id="selected-asset-panel">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION 5: STATISTICS TAB (NEW) -->
            <section id="tab-statistics" class="flex flex-col gap-6 hidden">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                        <i class="fa-solid fa-chart-column text-indigo-600"></i>
                        รายงานสถิติเชิงลึกระบบและการจัดสรรครุภัณฑ์
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">ประมวลผลสรุปประสิทธิภาพการหมุนเวียนครุภัณฑ์คอมพิวเตอร์และสื่อโสตทัศน์เพื่อประกอบการวางแผนจัดซื้อจัดจ้าง</p>
                </div>

                <!-- Statistics Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Box 1: Equipment Breakdown By Category -->
                    <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                        <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider flex items-center justify-between">
                            สัดส่วนคลังอุปกรณ์พัสดุ
                            <i class="fa-solid fa-boxes-stacked text-indigo-500"></i>
                        </h3>
                        <div class="flex items-baseline gap-2 mt-1">
                            <span id="stats-total-eq" class="text-4xl font-extrabold text-slate-900">0</span>
                            <span class="text-xs text-slate-500 font-semibold">รายการในคลังรวม</span>
                        </div>
                        
                        <div class="space-y-3.5 mt-3 pt-3 border-t border-slate-100 text-xs">
                            <!-- IT bar -->
                            <div>
                                <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-laptop text-blue-500 text-[11px]"></i>กลุ่มอุปกรณ์ IT</span>
                                    <span id="stats-it-count-text">0 ชิ้น (0%)</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div id="stats-it-bar" class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                            <!-- AV bar -->
                            <div>
                                <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-camera text-violet-500 text-[11px]"></i>กลุ่มสื่อโสตทัศน์ (AV)</span>
                                    <span id="stats-av-count-text">0 ชิ้น (0%)</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div id="stats-av-bar" class="bg-violet-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Box 2: Current Status Distribution -->
                    <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-3">
                        <h3 class="font-bold text-xs text-slate-400 uppercase tracking-wider flex items-center justify-between">
                            การจัดสรรสถานะปัจจุบัน
                            <i class="fa-solid fa-chart-pie text-indigo-500"></i>
                        </h3>
                        
                        <div class="space-y-2 text-xs mt-2">
                            <!-- Available -->
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                                <span class="flex items-center gap-2 font-medium text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    ว่าง / พร้อมใช้งานยืม
                                </span>
                                <span id="stats-status-available" class="font-bold text-slate-900 bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded">0</span>
                            </div>
                            <!-- Borrowed -->
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                                <span class="flex items-center gap-2 font-medium text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                    กำลังถูกยืมใช้งานจริง
                                </span>
                                <span id="stats-status-borrowed" class="font-bold text-slate-900 bg-blue-50 text-blue-800 px-2 py-0.5 rounded">0</span>
                            </div>
                            <!-- Pending -->
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                                <span class="flex items-center gap-2 font-medium text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    อยู่ระหว่างรอนุมัติยืม
                                </span>
                                <span id="stats-status-pending" class="font-bold text-slate-900 bg-amber-50 text-amber-800 px-2 py-0.5 rounded">0</span>
                            </div>
                            <!-- Maintenance -->
                            <div class="flex items-center justify-between p-2 rounded-lg hover:bg-slate-50">
                                <span class="flex items-center gap-2 font-medium text-slate-700">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    ส่งซ่อม / ชำรุดชั่วคราว
                                </span>
                                <span id="stats-status-maintenance" class="font-bold text-slate-900 bg-rose-50 text-rose-800 px-2 py-0.5 rounded">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Box 3: General System Health & Borrowing Counts -->
                    <div class="bg-gradient-to-br from-indigo-900 to-slate-900 text-white p-5 rounded-2xl shadow-sm flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-xs text-indigo-200 uppercase tracking-wider flex items-center justify-between">
                                ประสิทธิภาพการยืม-คืนรวม
                                <i class="fa-solid fa-wave-square text-indigo-400"></i>
                            </h3>
                            <div class="flex items-baseline gap-2 mt-3">
                                <span id="stats-total-borrow-requests" class="text-4xl font-extrabold text-white">0</span>
                                <span class="text-xs text-indigo-200 font-semibold">ใบขอยื่นยืมรวมทั้งหมด</span>
                            </div>
                            <p class="text-[11px] text-indigo-300 mt-2 leading-relaxed">ข้อมูลอ้างอิงจากการอนุมัติของเจ้าหน้าที่รวมถึงบันทึกยืมด่วน (Quick Borrow)</p>
                        </div>
                        <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs mt-4">
                            <span class="text-indigo-200 font-medium">อัตราการคืนตามกำหนด:</span>
                            <span class="font-bold text-emerald-400">95.4%</span>
                        </div>
                    </div>

                </div>

                <!-- Deep Dive Analytical Breakdown -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2">
                    
                    <!-- Left: Top 3 Most Borrowed Items (Dynamic Count) -->
                    <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-trophy text-amber-500"></i>
                                อันดับอุปกรณ์ครุภัณฑ์ที่ถูกยืมบ่อยที่สุด (Top Borrowed)
                            </h3>
                            <p class="text-xxs text-slate-400 mt-0.5">วิเคราะห์จากความถี่ของการจัดสรรอุปกรณ์ในใบขอยืมทั้งหมด</p>
                        </div>

                        <div id="stats-top-borrowed-container" class="flex flex-col gap-3 mt-1.5">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>

                    <!-- Right: Borrowing Count By Department -->
                    <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex flex-col gap-4">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-users-gear text-indigo-600"></i>
                                สัดส่วนจำนวนการขอยืนยืมแยกตามแผนกงาน
                            </h3>
                            <p class="text-xxs text-slate-400 mt-0.5">สถิติปริมาณคำขอใช้งานเพื่อจำแนกสิทธิ์และความสำคัญของพัสดุ</p>
                        </div>

                        <div id="stats-departments-container" class="space-y-4 mt-1.5 text-xs">
                            <!-- Populated dynamically by JS -->
                        </div>
                    </div>

                </div>
            </section>

            <!-- SECTION 6: HISTORY TAB -->
            <section id="tab-history" class="flex flex-col gap-6 hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-history text-indigo-600"></i> ประวัติและสถานะการยืมทั้งหมด</h2>
                        <p class="text-xs text-slate-500 mt-1">รายการคำขอย้อนหลังและสถานะปัจจุบันของคุุณในระบบ</p>
                    </div>
                </div>

                <!-- Table of history -->
                <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">รหัสคำขอ / วันที่ยืม</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">ผู้ยืม / แผนก</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">อุปกรณ์ที่ยืม</th>
                                <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">กำหนดส่งคืน</th>
                                <th scope="col" class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">สถานะการทำรายการ</th>
                            </tr>
                        </thead>
                        <tbody id="history-table-body" class="bg-white divide-y divide-slate-200">
                            <!-- Dynamic Content -->
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION 7: ADMIN MANAGEMENT TAB -->
            <section id="tab-admin" class="flex flex-col gap-6 hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2"><i class="fa-solid fa-toolbox text-indigo-600"></i> ระบบควบคุมจัดการสำหรับเจ้าหน้าที่ (Admin)</h2>
                        <p class="text-xs text-slate-500 mt-1">อนุมัติคำขอยืม ดำเนินการยืม-คืน ตรวจเช็คอุปกรณ์และจัดการคลังเครื่องมือ</p>
                    </div>
                    <!-- Create New Asset Trigger -->
                    <button onclick="openAddAssetModal()" class="bg-indigo-600 text-white font-semibold px-4 py-2 rounded-xl text-xs hover:bg-indigo-700 flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> เพิ่มทะเบียนอุปกรณ์ใหม่
                    </button>
                </div>

                <!-- Admin Action Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <!-- Left Column: Pending Approvals list -->
                    <div class="xl:col-span-2 flex flex-col gap-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2"><i class="fa-regular fa-clock text-amber-500 text-base"></i> รายการที่รอการอนุมัติ (<span id="admin-pending-count">0</span>)</h3>
                        </div>

                        <div id="admin-pending-list" class="flex flex-col gap-4">
                            <!-- Dynamic Cards -->
                        </div>
                    </div>

                    <!-- Right Column: Quick Return and Tools actions -->
                    <div class="flex flex-col gap-6">
                        <!-- Quick Return Action Box -->
                        <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl flex flex-col gap-3">
                            <h4 class="font-bold text-slate-900 text-xs tracking-wider uppercase flex items-center gap-2"><i class="fa-solid fa-arrow-rotate-left text-blue-500"></i> บันทึกคืนอุปกรณ์อย่างรวดเร็ว</h4>
                            <p class="text-xxs text-slate-500">พิมพ์รหัส หรือ ซีเรียลอุปกรณ์ด้านล่างเพื่อบันทึกคืนเมื่อได้รับเครื่องมือคืนมาจริง</p>
                            
                            <div class="flex gap-2">
                                <input type="text" id="quick-return-input" placeholder="เช่น IT-002, AV-001..." class="flex-1 border border-slate-200 rounded-xl px-3 py-1.5 text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none bg-white">
                                <button onclick="processQuickReturn()" class="bg-blue-600 text-white font-bold px-3 py-1.5 rounded-xl text-xs hover:bg-blue-700">ยืนยันคืน</button>
                            </div>
                        </div>

                        <!-- Statistics box for Admin -->
                        <div class="bg-indigo-950 text-white p-5 rounded-2xl flex flex-col gap-3 shadow-md">
                            <h4 class="font-bold text-indigo-300 text-xs tracking-wider uppercase">สถิติระบบประมวลผลประจำเดือน</h4>
                            <div class="grid grid-cols-2 gap-4 mt-1">
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <p class="text-xxs text-indigo-200">สถิติยืมเดือนนี้</p>
                                    <p class="text-lg font-bold text-white mt-1">45 ครั้ง</p>
                                </div>
                                <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                                    <p class="text-xxs text-indigo-200">อัตราความตรงเวลา</p>
                                    <p class="text-lg font-bold text-emerald-400 mt-1">94.2 %</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- Modal: Add New Equipment Asset -->
    <div id="add-asset-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-all">
            <div class="bg-slate-900 text-white p-5 flex justify-between items-center">
                <h3 class="font-bold text-base flex items-center gap-2"><i class="fa-solid fa-plus-circle text-emerald-400"></i> เพิ่มทะเบียนวัสดุอุปกรณ์</h3>
                <button onclick="closeAddAssetModal()" class="text-slate-400 hover:text-white transition-colors"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="add-asset-form" onsubmit="submitAddAsset(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">หมวดหมู่และกลุ่มงาน</label>
                    <select id="modal-category" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="it">กลุ่มอุปกรณ์ IT (คอมพิวเตอร์, แท็บเล็ต, อะแดปเตอร์)</option>
                        <option value="av">กลุ่มสื่อโสตทัศน์ (กล้อง, เครื่องเสียง, ไมค์, จอโปรเจคเตอร์)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อวัสดุอุปกรณ์ (Brand & Model)</label>
                    <input type="text" id="modal-name" required placeholder="เช่น iPad Pro 11นิ้ว M2 พร้อมปากกา" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสพัสดุ (Asset ID)</label>
                        <input type="text" id="modal-id" required placeholder="IT-015" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ซีเรียลนัมเบอร์ (S/N)</label>
                        <input type="text" id="modal-sn" required placeholder="SN8493012" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">คำอธิบายย่อ / สเปก</label>
                    <input type="text" id="modal-desc" placeholder="เช่น ความจุ 256GB, สตรีมเสียง, กล่องชาร์จ" class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div class="pt-4 border-t border-slate-100 flex gap-2">
                    <button type="button" onclick="closeAddAssetModal()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-xs transition-colors">ยกเลิก</button>
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors">บันทึกลงคลัง</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript logic -->
    <script>
        // Database State
        const state = {
            currentTab: 'dashboard',
            dashboardPage: 1, // Track page for dashboard asset lifecycle logs
            currentUser: {
                role: 'user',
                name: 'ดร.สมชาย ยอดรัก',
                department: 'สาขาคอมพิวเตอร์',
                id: 'USER-102'
            },
            equipments: [
                {
                    id: 'IT-001',
                    name: 'MacBook Air M2 13"',
                    category: 'it',
                    serial: 'SN-MBA2026-001',
                    description: 'RAM 16GB, SSD 512GB สำหรับเขียนโปรแกรมหรืองานวิชาการ',
                    status: 'Available', // Available, Borrowed, Pending, Maintenance
                    icon: 'fa-laptop',
                    currentLocation: 'อาคาร 4 ชั้น 2 ห้องคอมพิวเตอร์หลัก',
                    lifecycleState: 'Active' // Active, Under Repair, Relocated, Decommissioned
                },
                {
                    id: 'IT-002',
                    name: 'iPad Pro 11" M2 Wifi',
                    category: 'it',
                    serial: 'SN-IPD2026-002',
                    description: 'พร้อม Apple Pencil Gen 2 สำหรับการสอนและการจดบันทึก',
                    status: 'Borrowed',
                    icon: 'fa-tablet-screen-button',
                    currentLocation: 'ชั้น 1 ห้องงานลงทะเบียนนักศึกษา',
                    lifecycleState: 'Relocated'
                },
                {
                    id: 'IT-003',
                    name: 'USB-C Multiport Adapter 7-in-1',
                    category: 'it',
                    serial: 'SN-ADP7IN1-003',
                    description: 'HDMI 4K, USB-A, SD Card Reader สำหรับเชื่อมต่อหน้าจอ',
                    status: 'Available',
                    icon: 'fa-network-wired',
                    currentLocation: 'อาคาร 4 ชั้น 2 ห้องเก็บอุปกรณ์สำรอง',
                    lifecycleState: 'Active'
                },
                {
                    id: 'AV-001',
                    name: 'Epson Projector Full HD 4000lm',
                    category: 'av',
                    serial: 'SN-EPSPROJ-001',
                    description: 'โปรเจคเตอร์ความสว่างสูง มีพอร์ต HDMI และระบบเชื่อมต่อไร้สาย',
                    status: 'Available',
                    icon: 'fa-circle-play',
                    currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3',
                    lifecycleState: 'Active'
                },
                {
                    id: 'AV-002',
                    name: 'Shure Wireless Mic SLXD24 (ไมค์เดี่ยว)',
                    category: 'av',
                    serial: 'SN-SHUREMIC-002',
                    description: 'ไมโครโฟนไร้สายระดับพรีเมียม สัญญาณเสถียร เหมาะสำหรับสัมมนา',
                    status: 'Pending',
                    icon: 'fa-microphone',
                    currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3',
                    lifecycleState: 'Active'
                },
                {
                    id: 'AV-003',
                    name: 'Sony Alpha 7 IV (Body + 28-70mm)',
                    category: 'av',
                    serial: 'SN-SONYA74-003',
                    description: 'กล้องถ่ายภาพความละเอียดสูง 33MP สำหรับถ่ายวิดีโอสัมมนากิจกรรม',
                    status: 'Maintenance',
                    icon: 'fa-camera',
                    currentLocation: 'แผนกเคลมสินค้า ศูนย์บริการ Sony (พระราม 9)',
                    lifecycleState: 'Under Repair'
                },
                {
                    id: 'IT-004',
                    name: 'Asus ExpertBook B1 14"',
                    category: 'it',
                    serial: 'SN-ASUSB1-004',
                    description: 'Windows 11 Pro, RAM 16GB สำหรับทำงานทั่วไป',
                    status: 'Available',
                    icon: 'fa-laptop',
                    currentLocation: 'อาคาร 4 ชั้น 2 ห้องทำงานเจ้าหน้าที่ฝ่าย IT',
                    lifecycleState: 'Active'
                },
                {
                    id: 'AV-004',
                    name: 'Rode Wireless GO II Dual Mic',
                    category: 'av',
                    serial: 'SN-RODEWGO-004',
                    description: 'ไมโครโฟนหนีบปกเสื้อแบบคู่ ไร้สายตัวเล็ก น้ำหนักเบา',
                    status: 'Available',
                    icon: 'fa-microphone-lines',
                    currentLocation: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3',
                    lifecycleState: 'Active'
                }
            ],
            borrowRequests: [
                {
                    id: 'REQ-2026-001',
                    borrowerName: 'อาจารย์สุดา เทพพรดี',
                    department: 'แผนกวิชาศิลปศาสตร์',
                    phone: '081-234-5678',
                    items: ['IT-002'], // iPad Pro
                    purpose: 'ใช้งานเปิดพรีเซนต์เทชันวิชาประวัติศาสตร์สากล ณ ห้อง 431',
                    borrowDate: '2026-06-08',
                    returnDate: '2026-06-12',
                    status: 'Approved' // Pending, Approved, Returned, Overdue
                },
                {
                    id: 'REQ-2026-002',
                    borrowerName: 'ดร.สมชาย ยอดรัก',
                    department: 'สาขาคอมพิวเตอร์',
                    phone: '089-999-8888',
                    items: ['AV-002'], // Shure Wireless Mic
                    purpose: 'สัมมนาการเรียนรู้ Machine Learning ยุคใหม่ อาคาร 5 ชั้น 1',
                    borrowDate: '2026-06-09',
                    returnDate: '2026-06-11',
                    status: 'Pending'
                },
                {
                    id: 'REQ-2026-003',
                    borrowerName: 'นายพงษ์ศักดิ์ รัตนวิจิตร',
                    department: 'กลุ่มงานประชาสัมพันธ์',
                    phone: '085-321-7654',
                    items: ['AV-003'], // Sony Camera
                    purpose: 'ทำรายงานกิจกรรมปฐมนิเทศพนักงานใหม่',
                    borrowDate: '2026-06-05',
                    returnDate: '2026-06-07',
                    status: 'Overdue'
                },
                {
                    id: 'REQ-2026-004',
                    borrowerName: 'คุณศศิวิมล มิ่งขวัญ',
                    department: 'กลุ่มประชาสัมพันธ์',
                    phone: '084-555-4433',
                    items: ['IT-003'],
                    purpose: 'ขอยืมสายแปลงสัญญาณจัดนิทรรศการวิชาการ',
                    borrowDate: '2026-06-01',
                    returnDate: '2026-06-03',
                    status: 'Returned'
                },
                {
                    id: 'REQ-2026-005',
                    borrowerName: 'ดร.ปรีชา มีทอง',
                    department: 'สาขาวิศวกรรมศาสตร์',
                    phone: '082-111-2222',
                    items: ['IT-001'],
                    purpose: 'ใช้ประมวลผลโมเดลคอมพิวเตอร์ในห้องปฏิบัติการวิจัย',
                    borrowDate: '2026-05-25',
                    returnDate: '2026-05-28',
                    status: 'Returned'
                },
                {
                    id: 'REQ-2026-006',
                    borrowerName: 'นางสาวจารุณี แสนสุข',
                    department: 'งานบริการการศึกษา',
                    phone: '087-444-9999',
                    items: ['AV-004'],
                    purpose: 'บันทึกภาพถ่ายทอดสดพิธีมอบประกาศนียบัตร',
                    borrowDate: '2026-06-02',
                    returnDate: '2026-06-04',
                    status: 'Returned'
                }
            ],
            assetLogs: [
                { id: 'L-01', assetId: 'IT-001', date: '2026-06-08', action: 'Inspect', detail: 'ตรวจเช็คสภาพฮาร์ดแวร์ประจำปี ทำความสะอาดฝุ่นภายในเครื่อง ผลการตรวจผ่านระดับดีเยี่ยม', location: 'อาคาร 4 ชั้น 2 ห้องคอมพิวเตอร์หลัก', operator: 'สิริพร สุวรรณ' },
                { id: 'L-02', assetId: 'IT-002', date: '2026-06-05', action: 'Relocate', detail: 'ย้ายจุดติดตั้งจากฝ่ายวิชาการชั้น 3 ลงมาอำนวยการรับลงทะเบียนที่ ชั้น 1 ห้องงานลงทะเบียนนักศึกษา', location: 'ชั้น 1 ห้องงานลงทะเบียนนักศึกษา', operator: 'สมยศ ดีจิตร' },
                { id: 'L-03', assetId: 'AV-003', date: '2026-06-04', action: 'Maintenance', detail: 'เซนเซอร์มีจุดฝุ่นรบกวน ส่งเคลมศูนย์บริการด่วนสำหรับตรวจเซ็คระดับความละเอียดลึก', location: 'แผนกเคลมสินค้า ศูนย์บริการ Sony (พระราม 9)', operator: 'กิตติศักดิ์ พูลเพิ่ม' },
                { id: 'L-04', assetId: 'IT-004', date: '2026-06-02', action: 'Inspect', detail: 'อัปเกรดระบบปฏิบัติการ Windows 11 และลงโปรแกรมรักษาความปลอดภัยความเสถียรล่าสุด', location: 'อาคาร 4 ชั้น 2 ห้องทำงานเจ้าหน้าที่ฝ่าย IT', operator: 'วันชัย มั่นคง' },
                { id: 'L-05', assetId: 'IT-001', date: '2026-05-28', action: 'Relocate', detail: 'ย้ายจุดให้บริการคอมพิวเตอร์สืบค้นข้อมูลมายังอาคารหอสมุด ชั้น 1', location: 'อาคารหอสมุด ชั้น 1', operator: 'สมยศ ดีจิตร' },
                { id: 'L-06', assetId: 'AV-001', date: '2026-05-20', action: 'Inspect', detail: 'ทำความสะอาดฟิลเตอร์เครื่องฉายภาพ และตรวจวัดความเข้มแสงหลอดภาพ', location: 'ห้องศูนย์สื่อโสตทัศน์ ชั้น 3', operator: 'กิตติศักดิ์ พูลเพิ่ม' },
                { id: 'L-07', assetId: 'IT-002', date: '2026-05-15', action: 'Decommission', detail: 'หมดอายุการใช้งานโครงการจัดสรรคอมพิวเตอร์ปี 2021 ดำเนินเรื่องคัดจำหน่ายพัสดุพ้นทะเบียน', location: 'ห้องพัสดุกลาง ชั้น 1', operator: 'สิริพร สุวรรณ' },
                { id: 'L-08', assetId: 'IT-003', date: '2026-05-10', action: 'Maintenance', detail: 'พบอาการพอร์ต HDMI สัญญาณติดๆ ดับๆ นำส่งบอร์ดรับประกันและทำการเปลี่ยนชิปแปลงสัญญาณใหม่', location: 'ห้องซ่อมบำรุงวิชาการ ชั้น 2', operator: 'วันชัย มั่นคง' }
            ],
            cart: [],
            selectedTrackingAssetId: 'IT-001',
            trackingFilter: 'all'
        };

        // Initialize application
        window.onload = function() {
            renderDashboard();
            renderInventory();
            renderCart();
            renderHistory();
            renderAdminView();
            renderTracking();
            renderStatistics();
            updateGlobalCounters();
            
            // Set default dates on checkout forms
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            const returnDay = nextWeek.toISOString().split('T')[0];
            
            document.getElementById('form-borrow-date').value = today;
            document.getElementById('form-return-date').value = returnDay;
            document.getElementById('qb-return-date').value = returnDay;
        }

        // Helper: Custom Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMsg = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            
            toastMsg.textContent = message;
            if (type === 'success') {
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-check text-emerald-400"></i>';
            } else if (type === 'warning') {
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-exclamation text-amber-400"></i>';
            } else {
                toastIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation text-rose-400"></i>';
            }
            
            toast.className = toast.className.replace('translate-y-[-100px] opacity-0', 'translate-y-0 opacity-100');
            setTimeout(() => {
                toast.className = toast.className.replace('translate-y-0 opacity-100', 'translate-y-[-100px] opacity-0');
            }, 3000);
        }

        // Action: Tab Control Menu
        function changeTab(tabId) {
            state.currentTab = tabId;
            
            // Hide all tab screens
            const tabs = ['dashboard', 'inventory', 'cart', 'history', 'admin', 'tracking', 'statistics'];
            tabs.forEach(t => {
                const element = document.getElementById(`tab-${t}`);
                if (element) {
                    element.classList.add('hidden');
                }
                
                // Reset tab button highlight
                const btn = document.getElementById(`nav-${t}`);
                if (btn) {
                    btn.className = "w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 text-slate-600 hover:bg-slate-50 hover:text-slate-950";
                }
            });

            // Show active tab
            document.getElementById(`tab-${tabId}`).classList.remove('hidden');
            
            // Highlight active button
            const activeBtn = document.getElementById(`nav-${tabId}`);
            if (activeBtn) {
                activeBtn.className = "w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-150 bg-indigo-50 text-indigo-700";
            }

            // Perform specific updates
            if (tabId === 'dashboard') {
                renderDashboard();
            } else if (tabId === 'inventory') {
                renderInventory();
            } else if (tabId === 'cart') {
                renderCart();
            } else if (tabId === 'history') {
                renderHistory();
            } else if (tabId === 'admin') {
                renderAdminView();
            } else if (tabId === 'tracking') {
                renderTracking();
            } else if (tabId === 'statistics') {
                renderStatistics();
            }
        }

        // Action: Change Dashboard tracking table pagination page
        function changeDashboardPage(direction) {
            const maxPage = Math.ceil(state.assetLogs.length / 5);
            state.dashboardPage += direction;
            if (state.dashboardPage < 1) state.dashboardPage = 1;
            if (state.dashboardPage > maxPage) state.dashboardPage = maxPage;
            renderDashboard();
        }

        // Render Component: Dashboard Content (With New 2-Column status layout and paginated logs table)
        function renderDashboard() {
            const borrowedList = document.getElementById('dashboard-borrowed-list');
            const returnedList = document.getElementById('dashboard-returned-list');
            
            borrowedList.innerHTML = '';
            returnedList.innerHTML = '';

            // Left Column: Filter requests with status 'Approved' (กำลังยืม) and 'Overdue' (เกินกำหนด)
            const activeBorrowings = state.borrowRequests.filter(r => r.status === 'Approved' || r.status === 'Overdue');
            // Right Column: Filter requests with status 'Returned'
            const returnedBorrowings = state.borrowRequests.filter(r => r.status === 'Returned');

            // Render Left Column
            if (activeBorrowings.length === 0) {
                borrowedList.innerHTML = `
                    <div class="text-center py-12 text-slate-450">
                        <i class="fa-solid fa-box-open text-3xl mb-2 block text-slate-300"></i>
                        <p class="text-xs font-semibold">ไม่มีคอมพิวเตอร์/ครุภัณฑ์ค้างส่งมอบ</p>
                    </div>
                `;
            } else {
                // High Priority "Overdue" stays on top
                activeBorrowings.sort((a, b) => (b.status === 'Overdue' ? 1 : 0) - (a.status === 'Overdue' ? 1 : 0));

                activeBorrowings.forEach(req => {
                    req.items.forEach(itemId => {
                        const eq = state.equipments.find(e => e.id === itemId);
                        if (!eq) return;

                        const isOverdue = req.status === 'Overdue';
                        const cardTheme = isOverdue 
                            ? 'bg-rose-50/70 border-rose-200 hover:border-rose-300 text-rose-950' 
                            : 'bg-amber-50/70 border-amber-200 hover:border-amber-300 text-amber-950';
                        
                        const statusBadge = isOverdue
                            ? '<span class="bg-rose-100 text-rose-800 border border-rose-300 text-[10px] px-2 py-0.5 rounded-full font-bold"><i class="fa-solid fa-triangle-exclamation mr-1"></i>เกินกำหนดส่งคืน</span>'
                            : '<span class="bg-amber-100 text-amber-800 border border-amber-300 text-[10px] px-2 py-0.5 rounded-full font-bold"><i class="fa-solid fa-handshake mr-1"></i>กำลังยืมใช้งาน</span>';

                        const dateTextColor = isOverdue ? 'text-rose-600 font-bold' : 'text-slate-600 font-medium';

                        borrowedList.innerHTML += `
                            <div class="p-3.5 border rounded-xl shadow-xs transition-all flex flex-col gap-2.5 ${cardTheme}">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-9 h-9 rounded-lg bg-white border border-slate-100 text-indigo-600 flex items-center justify-center flex-shrink-0 text-base">
                                            <i class="fa-solid ${eq.icon}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-xs text-slate-900 truncate">${eq.name}</h4>
                                            <p class="text-[10px] text-slate-500 font-mono">ID: ${eq.id} | S/N: ${eq.serial}</p>
                                        </div>
                                    </div>
                                    ${statusBadge}
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-[11px] pt-1.5 border-t border-slate-200/50">
                                    <div>
                                        <p class="text-slate-400 font-semibold text-[9px] uppercase">ผู้ขอยืมใช้บริการ</p>
                                        <p class="font-bold text-slate-800 truncate">${req.borrowerName}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-slate-400 font-semibold text-[9px] uppercase">กำหนดกำหนดส่งมอบคืน</p>
                                        <p class="${dateTextColor}">${formatThaiDate(req.returnDate)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                });
            }

            // Render Right Column (Returned)
            if (returnedBorrowings.length === 0) {
                returnedList.innerHTML = `
                    <div class="text-center py-12 text-slate-450">
                        <i class="fa-solid fa-circle-check text-3xl mb-2 block text-slate-300"></i>
                        <p class="text-xs font-semibold">ไม่มีข้อมูลการส่งคืนพัสดุ</p>
                    </div>
                `;
            } else {
                returnedBorrowings.forEach(req => {
                    req.items.forEach(itemId => {
                        const eq = state.equipments.find(e => e.id === itemId);
                        if (!eq) return;

                        returnedList.innerHTML += `
                            <div class="bg-emerald-50/40 border border-emerald-100 hover:border-emerald-200 p-3.5 rounded-xl shadow-xs transition-all flex flex-col gap-2.5">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="w-9 h-9 rounded-lg bg-white border border-slate-100 text-emerald-600 flex items-center justify-center flex-shrink-0 text-base">
                                            <i class="fa-solid ${eq.icon}"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-xs text-slate-900 truncate">${eq.name}</h4>
                                            <p class="text-[10px] text-slate-500 font-mono">ID: ${eq.id}</p>
                                        </div>
                                    </div>
                                    <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 text-[10px] px-2 py-0.5 rounded-full font-bold">
                                        <i class="fa-solid fa-square-check mr-1"></i>คืนวัสดุเสร็จสิ้น
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-[11px] pt-1.5 border-t border-emerald-100">
                                    <div>
                                        <p class="text-emerald-700/60 font-semibold text-[9px] uppercase">ผู้รับมอบรับบริการ</p>
                                        <p class="font-bold text-slate-800 truncate">${req.borrowerName}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-emerald-700/60 font-semibold text-[9px] uppercase">วันที่ประทับคืนจริง</p>
                                        <p class="text-slate-600 font-semibold">${formatThaiDate(req.returnDate)}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                });
            }

            // Render Wide Tracking Table Paginated
            const tableBody = document.getElementById('dashboard-tracking-table-body');
            tableBody.innerHTML = '';

            const logsPerPage = 5;
            const totalLogs = state.assetLogs.length;
            const maxPage = Math.ceil(totalLogs / logsPerPage) || 1;

            if (state.dashboardPage < 1) state.dashboardPage = 1;
            if (state.dashboardPage > maxPage) state.dashboardPage = maxPage;

            const startIndex = (state.dashboardPage - 1) * logsPerPage;
            const endIndex = Math.min(startIndex + logsPerPage, totalLogs);
            const currentPageLogs = state.assetLogs.slice(startIndex, endIndex);

            currentPageLogs.forEach(log => {
                const asset = state.equipments.find(e => e.id === log.assetId);
                const assetName = asset ? asset.name : 'พัสดุไม่มีรายชื่อในทะเบียน';

                let actionBadge = '';
                if (log.action === 'Inspect') {
                    actionBadge = '<span class="inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-emerald-50 text-emerald-700 border border-emerald-200">ตรวจสอบเรียบร้อย</span>';
                } else if (log.action === 'Relocate') {
                    actionBadge = '<span class="inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-blue-50 text-blue-700 border border-blue-200">ย้ายสถานที่</span>';
                } else if (log.action === 'Maintenance') {
                    actionBadge = '<span class="inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-rose-50 text-rose-700 border border-rose-200">ส่งซ่อมพัสดุ</span>';
                } else if (log.action === 'Decommission') {
                    actionBadge = '<span class="inline-flex px-2 py-0.5 rounded-full font-bold text-[10px] bg-slate-150 text-slate-700 border border-slate-300">ออกโครงการ</span>';
                }

                tableBody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap text-slate-500 font-medium">${formatThaiDate(log.date)}</td>
                        <td class="px-4 py-3 whitespace-nowrap font-mono text-indigo-600 font-bold">${log.assetId}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800">${assetName}</td>
                        <td class="px-4 py-3 whitespace-nowrap">${actionBadge}</td>
                        <td class="px-4 py-3 text-slate-600 max-w-xs truncate" title="${log.detail}">${log.detail}</td>
                        <td class="px-4 py-3 font-medium text-slate-700"><i class="fa-solid fa-map-pin text-rose-500 mr-1.5"></i>${log.location}</td>
                        <td class="px-4 py-3 text-slate-500 whitespace-nowrap font-medium">${log.operator}</td>
                    </tr>
                `;
            });

            // Update Pagination Text Info & Button States
            const infoText = document.getElementById('dashboard-table-info');
            infoText.innerHTML = `แสดงประวัติรายการที่ <strong class="text-slate-800 font-bold">${startIndex + 1}-${endIndex}</strong> จากทั้งหมด <strong class="text-slate-800 font-bold">${totalLogs}</strong> บันทึกประวัติ (หน้า ${state.dashboardPage}/${maxPage})`;

            const prevBtn = document.getElementById('dash-btn-prev');
            const nextBtn = document.getElementById('dash-btn-next');

            prevBtn.disabled = state.dashboardPage === 1;
            nextBtn.disabled = state.dashboardPage === maxPage;
        }

        // Render Component: Analytical In-depth Statistics (NEW TAB)
        function renderStatistics() {
            // Calculate basic counts
            const totalEq = state.equipments.length;
            const itCount = state.equipments.filter(e => e.category === 'it').length;
            const avCount = state.equipments.filter(e => e.category === 'av').length;

            const itPercentage = totalEq > 0 ? Math.round((itCount / totalEq) * 100) : 0;
            const avPercentage = totalEq > 0 ? Math.round((avCount / totalEq) * 100) : 0;

            // Render basic counts
            document.getElementById('stats-total-eq').textContent = totalEq;
            document.getElementById('stats-it-count-text').textContent = `${itCount} ชิ้น (${itPercentage}%)`;
            document.getElementById('stats-av-count-text').textContent = `${avCount} ชิ้น (${avPercentage}%)`;

            // Adjust progress bars
            document.getElementById('stats-it-bar').style.width = `${itPercentage}%`;
            document.getElementById('stats-av-bar').style.width = `${avPercentage}%`;

            // Calculate status breakdown
            const statusAvailable = state.equipments.filter(e => e.status === 'Available').length;
            const statusBorrowed = state.equipments.filter(e => e.status === 'Borrowed').length;
            const statusPending = state.equipments.filter(e => e.status === 'Pending').length;
            const statusMaintenance = state.equipments.filter(e => e.status === 'Maintenance').length;

            document.getElementById('stats-status-available').textContent = statusAvailable;
            document.getElementById('stats-status-borrowed').textContent = statusBorrowed;
            document.getElementById('stats-status-pending').textContent = statusPending;
            document.getElementById('stats-status-maintenance').textContent = statusMaintenance;

            document.getElementById('stats-total-borrow-requests').textContent = state.borrowRequests.length;

            // Analytical Calculation: Top Borrowed Items (Dynamic counts from borrow history)
            const itemBorrowCounts = {};
            state.borrowRequests.forEach(req => {
                req.items.forEach(itemId => {
                    itemBorrowCounts[itemId] = (itemBorrowCounts[itemId] || 0) + 1;
                });
            });

            // Convert to array and sort
            const sortedBorrowedItems = Object.keys(itemBorrowCounts).map(itemId => {
                return {
                    id: itemId,
                    count: itemBorrowCounts[itemId],
                    asset: state.equipments.find(e => e.id === itemId)
                };
            }).filter(item => item.asset !== undefined) // filter out decommissioned items
              .sort((a, b) => b.count - a.count)
              .slice(0, 3); // top 3

            // Render Top Borrowed Items
            const topBorrowedContainer = document.getElementById('stats-top-borrowed-container');
            topBorrowedContainer.innerHTML = '';

            if (sortedBorrowedItems.length === 0) {
                topBorrowedContainer.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <i class="fa-solid fa-trophy text-2xl mb-1 text-slate-300"></i>
                        <p class="text-xxs">ยังไม่มีประวัติการทำรายการยืมพัสดุในฐานข้อมูล</p>
                    </div>
                `;
            } else {
                sortedBorrowedItems.forEach((item, index) => {
                    const badgeColors = [
                        'bg-amber-100 text-amber-800 border-amber-300', // Gold for 1st
                        'bg-slate-200 text-slate-800 border-slate-300', // Silver for 2nd
                        'bg-amber-50 text-amber-900 border-amber-200'   // Bronze for 3rd
                    ];
                    const medalClass = badgeColors[index] || 'bg-slate-50 text-slate-500';

                    topBorrowedContainer.innerHTML += `
                        <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-150 rounded-xl">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center font-extrabold text-xs border ${medalClass}">
                                    ${index + 1}
                                </span>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-xs text-slate-900 truncate">${item.asset.name}</h4>
                                    <p class="text-[10px] text-slate-400 font-mono">รหัสพัสดุ: ${item.id} | S/N: ${item.asset.serial}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="bg-indigo-50 border border-indigo-200 text-indigo-700 text-xxs font-bold px-2.5 py-1 rounded-lg">
                                    ยืมไปแล้ว ${item.count} ครั้ง
                                </span>
                            </div>
                        </div>
                    `;
                });
            }

            // Analytical Calculation: Borrowing Count By Department
            const deptCounts = {};
            state.borrowRequests.forEach(req => {
                const dept = req.department || 'ไม่ระบุแผนก';
                deptCounts[dept] = (deptCounts[dept] || 0) + 1;
            });

            const totalRequests = state.borrowRequests.length || 1;
            const departmentsContainer = document.getElementById('stats-departments-container');
            departmentsContainer.innerHTML = '';

            const sortedDepts = Object.keys(deptCounts).map(deptName => {
                return {
                    name: deptName,
                    count: deptCounts[deptName]
                };
            }).sort((a, b) => b.count - a.count);

            if (sortedDepts.length === 0) {
                departmentsContainer.innerHTML = `
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-xxs">ไม่มีประวัติความเคลื่อนไหวรายแผนก</p>
                    </div>
                `;
            } else {
                sortedDepts.forEach((dept, index) => {
                    const pct = Math.round((dept.count / totalRequests) * 100);
                    const colorThemes = [
                        'bg-indigo-600',
                        'bg-blue-500',
                        'bg-violet-500',
                        'bg-emerald-500',
                        'bg-rose-500'
                    ];
                    const barColor = colorThemes[index % colorThemes.length];

                    departmentsContainer.innerHTML += `
                        <div>
                            <div class="flex justify-between font-semibold text-slate-700 mb-1.5">
                                <span class="truncate pr-2">${dept.name}</span>
                                <span class="font-bold text-slate-950 flex-shrink-0">${dept.count} ใบขอใช้ (${pct}%)</span>
                            </div>
                            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                <div class="${barColor} h-full rounded-full" style="width: ${pct}%"></div>
                            </div>
                        </div>
                    `;
                });
            }
        }

        // Action: Switch Role (User / Admin)
        function switchRole(role) {
            state.currentUser.role = role;
            
            const userBtn = document.getElementById('role-user-btn');
            const adminBtn = document.getElementById('role-admin-btn');
            const navAdmin = document.getElementById('nav-admin');
            
            if (role === 'admin') {
                state.currentUser.name = 'คุณกิตติศักดิ์ พูลเพิ่ม';
                state.currentUser.department = 'หัวหน้าฝ่าย IT & โสตทัศนูปกรณ์';
                
                adminBtn.className = "px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 bg-white text-indigo-600 shadow-sm";
                userBtn.className = "px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
                
                navAdmin.classList.remove('hidden');
                showToast('เข้าสู่สิทธิ์เจ้าหน้าที่ IT/AV แล้ว (สิทธิ์ผู้ดูแลระบบ)', 'success');
            } else {
                state.currentUser.name = 'ดร.สมชาย ยอดรัก';
                state.currentUser.department = 'อาจารย์ประจำสาขาคอมพิวเตอร์';
                
                userBtn.className = "px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 bg-white text-blue-600 shadow-sm";
                adminBtn.className = "px-3 py-1.5 text-xs font-semibold rounded-lg transition-all duration-200 flex items-center gap-1.5 text-slate-600 hover:text-slate-900";
                
                navAdmin.classList.add('hidden');
                if (state.currentTab === 'admin') {
                    changeTab('dashboard');
                }
                showToast('เข้าสู่สิทธิ์ผู้ขอใช้บริการทั่วไปแล้ว', 'success');
            }
            
            document.getElementById('user-display-name').textContent = state.currentUser.name;
            document.getElementById('user-display-role').textContent = state.currentUser.department;
            
            const initials = state.currentUser.name.split(' ').map(n => n.charAt(0)).join('').substring(0, 2);
            document.getElementById('user-avatar').textContent = initials;
            
            updateGlobalCounters();
            renderDashboard();
            renderInventory();
            renderHistory();
            renderTracking();
            renderStatistics();
        }

        // Global Counter Updates
        function updateGlobalCounters() {
            const available = state.equipments.filter(e => e.status === 'Available').length;
            const borrowed = state.equipments.filter(e => e.status === 'Borrowed').length;
            const overdue = state.borrowRequests.filter(r => r.status === 'Overdue').length;
            const pending = state.borrowRequests.filter(r => r.status === 'Pending').length;

            document.getElementById('stat-available').textContent = available;
            document.getElementById('stat-borrowed').textContent = borrowed;
            document.getElementById('stat-overdue').textContent = overdue;
            document.getElementById('stat-pending').textContent = pending;

            const adminBadge = document.getElementById('admin-pending-badge');
            adminBadge.textContent = pending;
            if (pending > 0) {
                adminBadge.classList.remove('hidden');
            } else {
                adminBadge.classList.add('hidden');
            }

            const cartBadge = document.getElementById('cart-badge');
            cartBadge.textContent = state.cart.length;
            if (state.cart.length > 0) {
                cartBadge.classList.remove('hidden');
            } else {
                cartBadge.classList.add('hidden');
            }
        }

        // Render Component: Inventory Cards
        function renderInventory() {
            const grid = document.getElementById('equipment-grid');
            grid.innerHTML = '';
            
            state.equipments.forEach(item => {
                const statusColor = getStatusColor(item.status);
                const statusThai = getStatusThai(item.status);
                const inCart = state.cart.includes(item.id);
                
                let actionBtn = '';
                if (item.status === 'Available') {
                    if (inCart) {
                        actionBtn = `<button onclick="removeFromCart('${item.id}')" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1"><i class="fa-solid fa-minus-circle"></i> คืนจากตะกร้า</button>`;
                    } else {
                        actionBtn = `<button onclick="addToCart('${item.id}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1"><i class="fa-solid fa-plus-circle"></i> เลือกยืม</button>`;
                    }
                } else {
                    actionBtn = `<button disabled class="bg-slate-100 text-slate-400 cursor-not-allowed font-semibold py-1.5 px-3 rounded-lg text-xs">ไม่ว่างให้ยืม</button>`;
                }

                const catBadge = item.category === 'it' 
                    ? '<span class="bg-blue-50 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-laptop text-[9px] mr-1"></i> อุปกรณ์ IT</span>'
                    : '<span class="bg-violet-50 text-violet-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-camera text-[9px] mr-1"></i> สื่อโสตทัศน์ (AV)</span>';

                grid.innerHTML += `
                    <div class="bg-white border border-slate-200 hover:border-slate-300 rounded-2xl overflow-hidden p-5 flex flex-col gap-4 shadow-sm transition-all duration-200 group">
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 bg-slate-50 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 rounded-xl flex items-center justify-center text-lg transition-colors">
                                <i class="fa-solid ${item.icon}"></i>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold ${statusColor}">${statusThai}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                ${catBadge}
                                <span class="text-[10px] text-slate-400 font-mono">ID: ${item.id}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors">${item.name}</h3>
                            <p class="text-xxs text-slate-500 mt-1 leading-relaxed line-clamp-2">${item.description}</p>
                        </div>
                        <div class="h-px bg-slate-100 my-1"></div>
                        <div class="flex items-center justify-between text-xxs text-slate-400">
                            <span>S/N: ${item.serial}</span>
                            ${actionBtn}
                        </div>
                    </div>
                `;
            });
        }

        // Action: Cart Actions (Add, Remove)
        function addToCart(itemId) {
            const item = state.equipments.find(e => e.id === itemId);
            if (!item || item.status !== 'Available') {
                showToast('อุปกรณ์ชิ้นนี้ไม่ว่างให้ยืม ณ ขณะนี้', 'warning');
                return;
            }
            if (state.cart.includes(itemId)) {
                showToast('มีอุปกรณ์ชิ้นนี้ในรายการแล้ว', 'warning');
                return;
            }
            
            state.cart.push(itemId);
            updateGlobalCounters();
            renderInventory();
            renderDashboard();
            renderStatistics();
            showToast(`เพิ่ม ${item.name} ลงในรายการเรียบร้อย`, 'success');
        }

        function removeFromCart(itemId) {
            state.cart = state.cart.filter(id => id !== itemId);
            updateGlobalCounters();
            renderInventory();
            renderCart();
            renderDashboard();
            renderStatistics();
            showToast('ลบออกจากรายการแล้ว', 'warning');
        }

        // Render Component: Cart Form List
        function renderCart() {
            const container = document.getElementById('cart-items-container');
            const cartCount = document.getElementById('cart-item-count');
            
            cartCount.textContent = state.cart.length;
            container.innerHTML = '';

            if (state.cart.length === 0) {
                container.innerHTML = `
                    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-basket-shopping text-4xl text-slate-300"></i>
                        <p class="text-xs font-semibold">ตะกร้าของคุณยังว่างอยู่</p>
                        <p class="text-xxs text-slate-400">กรุณาไปที่คลังวัสดุอุปกรณ์ และเลือกอุปกรณ์อย่างน้อย 1 ชิ้นเพื่อยืม</p>
                        <button onclick="changeTab('inventory')" class="mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-xl text-xs transition-colors">ไปดูคลังอุปกรณ์</button>
                    </div>
                `;
                return;
            }

            state.cart.forEach(itemId => {
                const item = state.equipments.find(e => e.id === itemId);
                if (!item) return;

                const catBadge = item.category === 'it' 
                    ? '<span class="bg-blue-50 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-laptop text-[9px] mr-1"></i> อุปกรณ์ IT</span>'
                    : '<span class="bg-violet-50 text-violet-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-camera text-[9px] mr-1"></i> สื่อโสตทัศน์ (AV)</span>';

                container.innerHTML += `
                    <div class="bg-white border border-slate-200 p-4 rounded-xl flex items-center justify-between gap-4 shadow-sm">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-slate-50 text-indigo-600 rounded-lg flex items-center justify-center text-base flex-shrink-0">
                                <i class="fa-solid ${item.icon}"></i>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-xs text-slate-900 truncate">${item.name}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    ${catBadge}
                                    <span class="text-[10px] font-mono text-slate-400">S/N: ${item.serial}</span>
                                </div>
                            </div>
                        </div>
                        <button onclick="removeFromCart('${item.id}')" class="text-slate-400 hover:text-rose-600 p-2 text-sm transition-colors" title="ลบรายการนี้">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                `;
            });
        }

        // Action: Submit New Borrowing Request (Normal Flow)
        function submitBorrowRequest(e) {
            e.preventDefault();
            
            if (state.cart.length === 0) {
                showToast('กรุณาเลือกอุปกรณ์ลงตะกร้าก่อนส่งขอข้อมูล', 'danger');
                return;
            }

            const borrower = document.getElementById('form-borrower').value;
            const phone = document.getElementById('form-phone').value;
            const purpose = document.getElementById('form-purpose').value;
            const borrowDate = document.getElementById('form-borrow-date').value;
            const returnDate = document.getElementById('form-return-date').value;

            if (new Date(returnDate) < new Date(borrowDate)) {
                showToast('วันส่งคืนจะต้องอยู่หลังจากวันที่ยืม', 'danger');
                return;
            }

            const newReqId = `REQ-2026-${String(state.borrowRequests.length + 1).padStart(3, '0')}`;
            
            const newRequest = {
                id: newReqId,
                borrowerName: borrower,
                department: state.currentUser.department,
                phone: phone,
                items: [...state.cart],
                purpose: purpose,
                borrowDate: borrowDate,
                returnDate: returnDate,
                status: 'Pending'
            };

            state.borrowRequests.unshift(newRequest);

            state.cart.forEach(itemId => {
                const item = state.equipments.find(e => e.id === itemId);
                if (item) item.status = 'Pending';
            });

            state.cart = [];
            document.getElementById('borrow-form').reset();
            
            const today = new Date().toISOString().split('T')[0];
            const nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            document.getElementById('form-borrow-date').value = today;
            document.getElementById('form-return-date').value = nextWeek.toISOString().split('T')[0];

            updateGlobalCounters();
            changeTab('history');
            showToast(`ส่งยื่นคำขอเลขที่ ${newReqId} สำเร็จแล้ว รอเจ้าหน้าที่ตรวจสอบ`, 'success');
        }

        // Action: Quick Borrow Request (Fast High-Priority Admin Flow)
        function submitQuickBorrow(e) {
            e.preventDefault();
            
            const assetId = document.getElementById('qb-asset-id').value.trim().toUpperCase();
            const borrower = document.getElementById('qb-borrower').value.trim();
            const returnDate = document.getElementById('qb-return-date').value;

            const asset = state.equipments.find(e => e.id === assetId || e.serial.toUpperCase() === assetId);

            if (!asset) {
                showToast(`ไม่พบรหัสอุปกรณ์หรือซีเรียลนัมเบอร์ "${assetId}" ในระบบ`, 'danger');
                return;
            }

            if (asset.status !== 'Available') {
                showToast(`อุปกรณ์ "${asset.name}" ไม่พร้อมใช้งานในขณะนี้ (สถานะ: ${getStatusThai(asset.status)})`, 'warning');
                return;
            }

            const newReqId = `REQ-2026-${String(state.borrowRequests.length + 1).padStart(3, '0')}`;
            const todayStr = new Date().toISOString().split('T')[0];

            const quickRequest = {
                id: newReqId,
                borrowerName: borrower,
                department: 'แผนกเร่งด่วน / งานสารสนเทศ',
                phone: '-',
                items: [asset.id],
                purpose: 'ทำรายการยืมด่วนแบบข้ามตะกร้า (บันทึกพิเศษโดยเจ้าหน้าที่)',
                borrowDate: todayStr,
                returnDate: returnDate,
                status: 'Approved'
            };

            asset.status = 'Borrowed';
            state.borrowRequests.unshift(quickRequest);

            const logId = `L-${String(state.assetLogs.length + 1).padStart(2, '0')}`;
            state.assetLogs.unshift({
                id: logId,
                assetId: asset.id,
                date: todayStr,
                action: 'Relocate',
                detail: `ทำรายการยืมด่วนโดยคุณ ${borrower} กำหนดคืนพัสดุในวันที่ ${formatThaiDate(returnDate)}`,
                location: `ใช้งานโดย ${borrower}`,
                operator: state.currentUser.name
            });

            document.getElementById('qb-asset-id').value = '';
            document.getElementById('qb-borrower').value = '';
            
            const nextWeek = new Date();
            nextWeek.setDate(nextWeek.getDate() + 7);
            document.getElementById('qb-return-date').value = nextWeek.toISOString().split('T')[0];

            updateGlobalCounters();
            renderDashboard();
            renderInventory();
            renderHistory();
            renderTracking();
            renderStatistics();
            
            showToast(`ลงทะเบียนยืมด่วนรหัส ${newReqId} สำหรับ ${asset.name} เสร็จสมบูรณ์แล้ว`, 'success');
        }

        // Render Component: History Table
        function renderHistory() {
            const tbody = document.getElementById('history-table-body');
            tbody.innerHTML = '';

            state.borrowRequests.forEach(req => {
                const statusThai = getRequestStatusThai(req.status);
                const statusColor = getRequestStatusColor(req.status);
                
                let itemsListHtml = '<div class="flex flex-col gap-1">';
                req.items.forEach(itemId => {
                    const item = state.equipments.find(e => e.id === itemId);
                    if (item) {
                        itemsListHtml += `
                            <span class="inline-flex items-center gap-1 text-slate-700 bg-slate-50 border border-slate-200 text-xxs px-1.5 py-0.5 rounded">
                                <i class="fa-solid ${item.icon} text-slate-400"></i> ${item.name} (${item.id})
                            </span>
                        `;
                    } else {
                        itemsListHtml += `<span class="text-slate-400 text-xxs">ไม่พบข้อมูลอุปกรณ์ (${itemId})</span>`;
                    }
                });
                itemsListHtml += '</div>';

                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-900 text-xs">${req.id}</span>
                                <span class="text-xxs text-slate-400 mt-0.5">วันที่ขอยืม: ${formatThaiDate(req.borrowDate)}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-800 text-xs">${req.borrowerName}</span>
                                <span class="text-xxs text-slate-500 mt-0.5">${req.department}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            ${itemsListHtml}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">
                            ${formatThaiDate(req.returnDate)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-3 py-1 text-xxs font-bold rounded-full ${statusColor}">${statusThai}</span>
                        </td>
                    </tr>
                `;
            });
        }

        // Render Component: Asset Lifecycle and Location History Tracking
        function renderTracking() {
            const listContainer = document.getElementById('tracking-assets-list');
            listContainer.innerHTML = '';

            const filtered = state.equipments.filter(item => {
                if (state.trackingFilter === 'all') return true;
                return item.category === state.trackingFilter;
            });

            filtered.forEach(item => {
                const isSelected = item.id === state.selectedTrackingAssetId;
                const selectedClass = isSelected 
                    ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' 
                    : 'border-slate-200 bg-white hover:border-slate-300';
                
                let stateBadge = '';
                if (item.lifecycleState === 'Active') {
                    stateBadge = '<span class="bg-emerald-50 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full font-semibold"><i class="fa-solid fa-circle text-[8px] mr-1"></i>ใช้งานปกติ</span>';
                } else if (item.lifecycleState === 'Under Repair') {
                    stateBadge = '<span class="bg-rose-50 text-rose-700 text-[10px] px-2 py-0.5 rounded-full font-semibold"><i class="fa-solid fa-wrench text-[8px] mr-1"></i>กำลังส่งซ่อม</span>';
                } else if (item.lifecycleState === 'Relocated') {
                    stateBadge = '<span class="bg-blue-50 text-blue-700 text-[10px] px-2 py-0.5 rounded-full font-semibold"><i class="fa-solid fa-location-dot text-[8px] mr-1"></i>ย้ายตำแหน่ง</span>';
                } else if (item.lifecycleState === 'Decommissioned') {
                    stateBadge = '<span class="bg-slate-100 text-slate-700 text-[10px] px-2 py-0.5 rounded-full font-semibold"><i class="fa-solid fa-trash-can text-[8px] mr-1"></i>ออกโครงการ</span>';
                }

                listContainer.innerHTML += `
                    <div onclick="selectTrackingAsset('${item.id}')" class="p-4 border rounded-xl cursor-pointer transition-all duration-200 flex flex-col gap-2 ${selectedClass}">
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-[10px] font-mono text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded">${item.id}</span>
                            ${stateBadge}
                        </div>
                        <h4 class="font-bold text-xs text-slate-900 leading-snug">${item.name}</h4>
                        <div class="flex items-center gap-1.5 text-xxs text-slate-500 mt-1">
                            <i class="fa-solid fa-map-pin text-rose-500"></i>
                            <span class="truncate">พิกัด: ${item.currentLocation || 'ไม่ระบุสถานที่'}</span>
                        </div>
                    </div>
                `;
            });

            renderSelectedTrackingAsset();
        }

        // Action: Change Tracking Filter
        function filterTrackingList(cat) {
            state.trackingFilter = cat;
            
            const btnAll = document.getElementById('track-btn-all');
            const btnIt = document.getElementById('track-btn-it');
            const btnAv = document.getElementById('track-btn-av');

            const activeClass = "px-2 py-1 text-[10px] font-semibold rounded bg-indigo-600 text-white";
            const inactiveClass = "px-2 py-1 text-[10px] font-semibold rounded bg-white text-slate-600 hover:bg-slate-100 border border-slate-200";

            btnAll.className = cat === 'all' ? activeClass : inactiveClass;
            btnIt.className = cat === 'it' ? activeClass : inactiveClass;
            btnAv.className = cat === 'av' ? activeClass : inactiveClass;

            renderTracking();
        }

        function selectTrackingAsset(assetId) {
            state.selectedTrackingAssetId = assetId;
            renderTracking();
        }

        // Render Selected Asset History details on Right Column
        function renderSelectedTrackingAsset() {
            const panel = document.getElementById('selected-asset-panel');
            const asset = state.equipments.find(e => e.id === state.selectedTrackingAssetId);

            if (!asset) {
                panel.innerHTML = `
                    <div class="text-center py-12 text-slate-400">
                        <i class="fa-solid fa-map-location-dot text-4xl mb-3 block text-slate-300"></i>
                        <p class="text-sm">กรุณาเลือกวัสดุอุปกรณ์คอมพิวเตอร์จากฝั่งซ้าย</p>
                    </div>
                `;
                return;
            }

            let currentLifecycleHtml = '';
            if (asset.lifecycleState === 'Active') {
                currentLifecycleHtml = '<span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-bold">พร้อมใช้งานปกติ</span>';
            } else if (asset.lifecycleState === 'Under Repair') {
                currentLifecycleHtml = '<span class="bg-rose-100 text-rose-800 text-xs px-2.5 py-1 rounded-full font-bold">กำลังส่งซ่อมบำรุง</span>';
            } else if (asset.lifecycleState === 'Relocated') {
                currentLifecycleHtml = '<span class="bg-blue-100 text-blue-800 text-xs px-2.5 py-1 rounded-full font-bold">ย้ายตำแหน่งสถานที่</span>';
            } else if (asset.lifecycleState === 'Decommissioned') {
                currentLifecycleHtml = '<span class="bg-slate-200 text-slate-800 text-xs px-2.5 py-1 rounded-full font-bold">ออกนอกโครงการ/จำหน่าย</span>';
            }

            const relatedLogs = state.assetLogs.filter(log => log.assetId === asset.id);
            let timelineHtml = '';

            if (relatedLogs.length === 0) {
                timelineHtml = `
                    <div class="text-center py-6 text-slate-400 bg-white border border-slate-100 rounded-xl">
                        <i class="fa-solid fa-timeline text-2xl mb-1 text-slate-300"></i>
                        <p class="text-xxs">ไม่มีประวัติการสลับสับเปลี่ยนหรือซ่อมบำรุงที่บันทึกไว้</p>
                    </div>
                `;
            } else {
                relatedLogs.forEach((log, index) => {
                    let actionIcon = '<i class="fa-solid fa-info text-slate-500"></i>';
                    let bgIconColor = 'bg-slate-100 text-slate-600';
                    
                    if (log.action === 'Inspect') {
                        actionIcon = '<i class="fa-solid fa-check text-emerald-500"></i>';
                        bgIconColor = 'bg-emerald-50 text-emerald-600 border border-emerald-200';
                    } else if (log.action === 'Relocate') {
                        actionIcon = '<i class="fa-solid fa-map-pin text-blue-500"></i>';
                        bgIconColor = 'bg-blue-50 text-blue-600 border border-blue-200';
                    } else if (log.action === 'Maintenance') {
                        actionIcon = '<i class="fa-solid fa-wrench text-rose-500"></i>';
                        bgIconColor = 'bg-rose-50 text-rose-600 border border-rose-200';
                    } else if (log.action === 'Decommission') {
                        actionIcon = '<i class="fa-solid fa-trash-can text-slate-500"></i>';
                        bgIconColor = 'bg-slate-100 text-slate-600 border border-slate-300';
                    }

                    const isLast = index === relatedLogs.length - 1;

                    timelineHtml += `
                        <div class="relative flex gap-4">
                            ${!isLast ? '<div class="absolute left-4 top-8 -bottom-6 w-0.5 bg-slate-200"></div>' : ''}
                            
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 z-10 ${bgIconColor}">
                                ${actionIcon}
                            </div>
                            <div class="bg-white p-3.5 rounded-xl border border-slate-100 flex-1 flex flex-col gap-1 shadow-xs">
                                <div class="flex justify-between items-start gap-2">
                                    <span class="text-xs font-bold text-slate-800">${getActionThai(log.action)}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">${formatThaiDate(log.date)}</span>
                                </div>
                                <p class="text-xxs text-slate-600 mt-0.5 leading-relaxed">${log.detail}</p>
                                <div class="flex flex-wrap items-center justify-between text-[10px] text-slate-400 mt-2 border-t border-slate-50 pt-1.5">
                                    <span><i class="fa-solid fa-location-arrow mr-1"></i>พิกัด: ${log.location}</span>
                                    <span>ผู้ทำรายการ: ${log.operator}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }

            panel.innerHTML = `
                <div class="flex items-start gap-4 border-b border-slate-200 pb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-xl flex-shrink-0 shadow-sm shadow-indigo-200">
                        <i class="fa-solid ${asset.icon}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-mono text-indigo-700 bg-indigo-50 font-bold px-2 py-0.5 rounded">ID: ${asset.id}</span>
                            ${currentLifecycleHtml}
                        </div>
                        <h3 class="font-bold text-slate-900 text-base mt-1 leading-tight">${asset.name}</h3>
                        <p class="text-xxs text-slate-500 mt-1">ซีเรียล S/N: ${asset.serial} | พิกัดปัจจุบัน: ${asset.currentLocation}</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3">
                    <h4 class="text-xs font-bold text-slate-700 flex items-center gap-1.5"><i class="fa-solid fa-history text-slate-500"></i> ไทม์ไลน์บันทึกเหตุการณ์และการเปลี่ยนแปลง</h4>
                    <div class="flex flex-col gap-4 mt-1.5 pl-1 max-h-[300px] overflow-y-auto scrollbar-thin py-2">
                        ${timelineHtml}
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-200 pt-5">
                    <h4 class="text-xs font-bold text-indigo-950 mb-3 flex items-center gap-1.5"><i class="fa-solid fa-file-pen text-indigo-600"></i> บันทึกเหตุการณ์พิกัดหรือปรับสถานะครุภัณฑ์คอมพิวเตอร์</h4>
                    
                    <form id="add-log-form" onsubmit="submitAssetLog(event)" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
                        <div class="col-span-1">
                            <label class="block text-xxs font-bold text-slate-500 mb-1">เหตุการณ์ / ประเภทรายการ</label>
                            <select id="log-action" required class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50">
                                <option value="Relocate">ย้ายสถานที่ / ปรับพิกัดติดตั้ง</option>
                                <option value="Maintenance">ส่งซ่อมบำรุงภายนอก/ชำรุด</option>
                                <option value="Inspect">ตรวจสภาพประจำปี/เช็คสภาพ</option>
                                <option value="Decommission">ออกโครงการ / คัดจำหน่ายพัสดุออก</option>
                            </select>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-xxs font-bold text-slate-500 mb-1">ระบุรายละเอียดตำแหน่งพิกัดใหม่</label>
                            <input type="text" id="log-location" required placeholder="เช่น ชั้น 1 ห้องสโมสร หรือ ศูนย์ซ่อม" class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50">
                        </div>
                        <div class="col-span-1 sm:col-span-2">
                            <label class="block text-xxs font-bold text-slate-500 mb-1">บันทึกรายละเอียดเพิ่มเติม</label>
                            <input type="text" id="log-detail" required placeholder="เช่น ย้ายจากอาคาร 4 ไปชั้น 1 หรือ อาการซ่อมจอเสีย" class="w-full border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs bg-slate-50">
                        </div>
                        <div class="col-span-1 sm:col-span-2 pt-2 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs px-4 py-2 rounded-lg transition-colors flex items-center gap-1.5">
                                <i class="fa-solid fa-save"></i> บันทึกอัปเดตลงระบบ
                            </button>
                        </div>
                    </form>
                </div>
            `;
        }

        // Action: Submit Asset Log & Location Update
        function submitAssetLog(e) {
            e.preventDefault();

            const asset = state.equipments.find(e => e.id === state.selectedTrackingAssetId);
            if (!asset) return;

            const action = document.getElementById('log-action').value;
            const location = document.getElementById('log-location').value.trim();
            const detail = document.getElementById('log-detail').value.trim();
            const todayStr = new Date().toISOString().split('T')[0];

            let newLifecycleState = 'Active';
            if (action === 'Maintenance') {
                newLifecycleState = 'Under Repair';
                asset.status = 'Maintenance';
            } else if (action === 'Relocate') {
                newLifecycleState = 'Relocated';
            } else if (action === 'Decommission') {
                newLifecycleState = 'Decommissioned';
                asset.status = 'Maintenance';
            } else if (action === 'Inspect') {
                newLifecycleState = 'Active';
            }

            asset.currentLocation = location;
            asset.lifecycleState = newLifecycleState;

            const logId = `L-${String(state.assetLogs.length + 1).padStart(2, '0')}`;
            const newLog = {
                id: logId,
                assetId: asset.id,
                date: todayStr,
                action: action,
                detail: detail,
                location: location,
                operator: state.currentUser.name
            };

            state.assetLogs.unshift(newLog);

            updateGlobalCounters();
            renderInventory();
            renderTracking();
            renderDashboard();
            renderStatistics();
            
            showToast(`ปรับปรุงประวัติและพิกัดอุปกรณ์ ${asset.id} ลงระบบแล้ว`, 'success');
        }

        // Helpers for Tracking actions
        function getActionThai(action) {
            switch (action) {
                case 'Inspect': return 'ตรวจสภาพและซ่อมบำรุงเบื้องต้น';
                case 'Relocate': return 'ย้ายตำแหน่งติดตั้ง / เปลี่ยนพิกัด';
                case 'Maintenance': return 'ส่งซ่อมหน่วยงานภายนอก';
                case 'Decommission': return 'หมดอายุการใช้ / จำหน่ายพ้นโครงการ';
                default: return 'อื่น ๆ';
            }
        }

        // Filter Function for Inventory
        function filterEquipment() {
            const search = document.getElementById('search-input').value.toLowerCase();
            const cat = document.getElementById('category-filter').value;
            const status = document.getElementById('status-filter').value;

            const grid = document.getElementById('equipment-grid');
            grid.innerHTML = '';

            state.equipments.forEach(item => {
                const matchesSearch = item.name.toLowerCase().includes(search) || 
                                      item.id.toLowerCase().includes(search) || 
                                      item.serial.toLowerCase().includes(search);
                const matchesCategory = (cat === 'all') || (item.category === cat);
                const matchesStatus = (status === 'all') || (item.status === status);

                if (matchesSearch && matchesCategory && matchesStatus) {
                    const statusColor = getStatusColor(item.status);
                    const statusThai = getStatusThai(item.status);
                    const inCart = state.cart.includes(item.id);
                    
                    let actionBtn = '';
                    if (item.status === 'Available') {
                        if (inCart) {
                            actionBtn = `<button onclick="removeFromCart('${item.id}')" class="bg-amber-500 hover:bg-amber-600 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1"><i class="fa-solid fa-minus-circle"></i> คืนจากตะกร้า</button>`;
                        } else {
                            actionBtn = `<button onclick="addToCart('${item.id}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded-lg text-xs flex items-center gap-1"><i class="fa-solid fa-plus-circle"></i> เลือกยืม</button>`;
                        }
                    } else {
                        actionBtn = `<button disabled class="bg-slate-100 text-slate-400 cursor-not-allowed font-semibold py-1.5 px-3 rounded-lg text-xs">ไม่ว่างให้ยืม</button>`;
                    }

                    const catBadge = item.category === 'it' 
                        ? '<span class="bg-blue-50 text-blue-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-laptop text-[9px] mr-1"></i> อุปกรณ์ IT</span>'
                        : '<span class="bg-violet-50 text-violet-700 text-[10px] font-semibold px-2 py-0.5 rounded-full"><i class="fa-solid fa-camera text-[9px] mr-1"></i> สื่อโสตทัศน์ (AV)</span>';

                    grid.innerHTML += `
                        <div class="bg-white border border-slate-200 hover:border-slate-300 rounded-2xl overflow-hidden p-5 flex flex-col gap-4 shadow-sm transition-all duration-200 group">
                            <div class="flex items-start justify-between">
                                <div class="w-11 h-11 bg-slate-50 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 rounded-xl flex items-center justify-center text-lg transition-colors">
                                    <i class="fa-solid ${item.icon}"></i>
                                </div>
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold ${statusColor}">${statusThai}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    ${catBadge}
                                    <span class="text-[10px] text-slate-400 font-mono">ID: ${item.id}</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors">${item.name}</h3>
                                <p class="text-xxs text-slate-500 mt-1 leading-relaxed line-clamp-2">${item.description}</p>
                            </div>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <div class="flex items-center justify-between text-xxs text-slate-400">
                                <span>S/N: ${item.serial}</span>
                                ${actionBtn}
                            </div>
                        </div>
                    `;
                }
            });
        }

        // Render Component: Admin Console
        function renderAdminView() {
            const list = document.getElementById('admin-pending-list');
            const pendingRequests = state.borrowRequests.filter(r => r.status === 'Pending');
            
            document.getElementById('admin-pending-count').textContent = pendingRequests.length;
            list.innerHTML = '';

            if (pendingRequests.length === 0) {
                list.innerHTML = `
                    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-400">
                        <i class="fa-regular fa-bell-slash text-3xl mb-2"></i>
                        <p class="text-xs font-semibold">ไม่มีรายการที่คอยให้ตรวจสอบเพิ่มเติม</p>
                    </div>
                `;
                return;
            }

            pendingRequests.forEach(req => {
                let itemsListHtml = '';
                req.items.forEach(itemId => {
                    const item = state.equipments.find(e => e.id === itemId);
                    if (item) {
                        itemsListHtml += `
                            <div class="flex items-center justify-between bg-white px-3 py-2 rounded-lg border border-slate-150 text-xs">
                                <span class="font-semibold text-slate-800"><i class="fa-solid ${item.icon} text-indigo-500 mr-1.5"></i> ${item.name}</span>
                                <span class="text-[10px] font-mono text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded">ID: ${item.id} | S/N: ${item.serial}</span>
                            </div>
                        `;
                    }
                });

                list.innerHTML += `
                    <div class="bg-amber-50/50 border border-amber-100 p-5 rounded-2xl flex flex-col gap-3 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-amber-200/50 pb-2.5">
                            <div>
                                <span class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2 py-0.5 rounded-full">ใบขอยืมใหม่</span>
                                <span class="text-xs font-bold text-slate-900 ml-1.5">${req.id}</span>
                            </div>
                            <span class="text-xxs text-slate-500">ยื่นคำขอเมื่อ: ${formatThaiDate(req.borrowDate)}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5 text-xs">
                                <p><strong class="text-slate-500">ผู้ขอใช้บริการ:</strong> ${req.borrowerName} (${req.department})</p>
                                <p><strong class="text-slate-500">วัตถุประสงค์:</strong> ${req.purpose}</p>
                                <p><strong class="text-slate-500">เบอร์ติดต่อ:</strong> <a href="tel:${req.phone}" class="text-indigo-600 hover:underline">${req.phone}</a></p>
                            </div>
                            <div class="flex flex-col gap-1.5 text-xs">
                                <p><strong class="text-slate-500">ช่วงเวลาที่ใช้งาน:</strong> ${formatThaiDate(req.borrowDate)} ถึง ${formatThaiDate(req.returnDate)}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5 mt-2">
                            <h4 class="text-xxs font-bold text-slate-500 uppercase tracking-wider">อุปกรณ์ที่ยื่นคำยืม</h4>
                            ${itemsListHtml}
                        </div>

                        <div class="flex gap-2 justify-end pt-3 mt-1 border-t border-amber-200/50">
                            <button onclick="approveRequest('${req.id}', false)" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold px-4 py-2 rounded-xl text-xs transition-colors">ปฏิเสธคำขอ</button>
                            <button onclick="approveRequest('${req.id}', true)" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition-colors">อนุมัติการยืม</button>
                        </div>
                    </div>
                `;
            });
        }

        // Admin Action: Approve / Reject Request
        function approveRequest(reqId, isApproved) {
            const reqIndex = state.borrowRequests.findIndex(r => r.id === reqId);
            if (reqIndex === -1) return;

            const req = state.borrowRequests[reqIndex];
            
            if (isApproved) {
                req.status = 'Approved';
                req.items.forEach(itemId => {
                    const item = state.equipments.find(e => e.id === itemId);
                    if (item) item.status = 'Borrowed';
                });
                showToast(`อนุมัติใบขอการยืม ${reqId} สำเร็จแล้ว`, 'success');
            } else {
                req.status = 'Returned';
                req.items.forEach(itemId => {
                    const item = state.equipments.find(e => e.id === itemId);
                    if (item) item.status = 'Available';
                });
                state.borrowRequests.splice(reqIndex, 1);
                showToast(`ปฏิเสธคำขอยืมใบคำขอ ${reqId} แล้ว`, 'warning');
            }

            updateGlobalCounters();
            renderAdminView();
            renderHistory();
            renderDashboard();
            renderStatistics();
        }

        // Admin Action: Quick Return of Assets
        function processQuickReturn() {
            const input = document.getElementById('quick-return-input').value.trim().toUpperCase();
            if (!input) {
                showToast('กรุณาระบุรหัสครุภัณฑ์เพื่อคืนพัสดุ', 'danger');
                return;
            }

            const item = state.equipments.find(e => e.id === input || e.serial.toUpperCase() === input);
            
            if (!item) {
                showToast(`ไม่พบอุปกรณ์รหัส/ซีเรียล: "${input}" ในฐานข้อมูลคลัง`, 'danger');
                return;
            }

            if (item.status === 'Available') {
                showToast(`อุปกรณ์ ${item.name} มีสถานะว่างอยู่แล้วในระบบ`, 'warning');
                return;
            }

            item.status = 'Available';

            const request = state.borrowRequests.find(r => r.items.includes(item.id) && (r.status === 'Approved' || r.status === 'Overdue'));
            if (request) {
                request.status = 'Returned';
            }

            const todayStr = new Date().toISOString().split('T')[0];
            const logId = `L-${String(state.assetLogs.length + 1).padStart(2, '0')}`;
            state.assetLogs.unshift({
                id: logId,
                assetId: item.id,
                date: todayStr,
                action: 'Inspect',
                detail: 'ทำรายการส่งคืนพัสดุอุปกรณ์สำเร็จ ตรวจสอบฮาร์ดแวร์สภาพสมบูรณ์ดี',
                location: 'คลังพัสดุ IT',
                operator: state.currentUser.name
            });

            document.getElementById('quick-return-input').value = '';
            updateGlobalCounters();
            renderAdminView();
            renderHistory();
            renderTracking();
            renderDashboard();
            renderStatistics();
            showToast(`รับคืนและบันทึกข้อมูลอุปกรณ์ ${item.name} สำเร็จ`, 'success');
        }

        // Action: Modal Control
        function openAddAssetModal() {
            document.getElementById('add-asset-modal').classList.remove('hidden');
        }

        function closeAddAssetModal() {
            document.getElementById('add-asset-modal').classList.add('hidden');
            document.getElementById('add-asset-form').reset();
        }

        // Admin Action: Save Asset Form
        function submitAddAsset(e) {
            e.preventDefault();

            const category = document.getElementById('modal-category').value;
            const name = document.getElementById('modal-name').value;
            const id = document.getElementById('modal-id').value.toUpperCase().trim();
            const sn = document.getElementById('modal-sn').value.trim();
            const desc = document.getElementById('modal-desc').value;

            const duplicate = state.equipments.find(eq => eq.id === id);
            if (duplicate) {
                showToast(`รหัสครุภัณฑ์ ${id} ซ้ำในฐานข้อมูลคลังแล้ว`, 'danger');
                return;
            }

            const newAsset = {
                id: id,
                name: name,
                category: category,
                serial: sn || 'N/A',
                description: desc || 'ไม่มีรายละเอียดเพิ่มเติม',
                status: 'Available',
                icon: category === 'it' ? 'fa-laptop' : 'fa-camera',
                currentLocation: category === 'it' ? 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2' : 'ห้องโสตทัศน์ ชั้น 3',
                lifecycleState: 'Active'
            };

            state.equipments.push(newAsset);
            closeAddAssetModal();
            updateGlobalCounters();
            renderInventory();
            renderTracking();
            renderDashboard();
            renderStatistics();
            showToast(`เพิ่มอุปกรณ์ "${name}" ลงทะเบียนพัสดุเรียบร้อย`, 'success');
        }

        // Helpers for UI Color Coding
        function getStatusColor(status) {
            switch (status) {
                case 'Available': return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                case 'Borrowed': return 'bg-blue-50 text-blue-700 border border-blue-200';
                case 'Pending': return 'bg-amber-50 text-amber-700 border border-amber-200';
                case 'Maintenance': return 'bg-rose-50 text-rose-700 border border-rose-300';
                default: return 'bg-slate-50 text-slate-500';
            }
        }

        // Translation status helpers
        function getStatusThai(status) {
            switch (status) {
                case 'Available': return 'ว่าง / พร้อมยืม';
                case 'Borrowed': return 'ถูกยืมใช้งาน';
                case 'Pending': return 'รอการอนุมัติ';
                case 'Maintenance': return 'ส่งบำรุง / ซ่อม';
                default: return 'ไม่ทราบสถานะ';
            }
        }

        function getRequestStatusColor(status) {
            switch (status) {
                case 'Pending': return 'bg-amber-50 text-amber-800 border border-amber-200';
                case 'Approved': return 'bg-blue-50 text-blue-800 border border-blue-200';
                case 'Returned': return 'bg-emerald-50 text-emerald-800 border border-emerald-200';
                case 'Overdue': return 'bg-rose-50 text-rose-800 border border-rose-200';
                default: return 'bg-slate-50 text-slate-500';
            }
        }

        function getRequestStatusThai(status) {
            switch (status) {
                case 'Pending': return 'รอการตรวจสอบ';
                case 'Approved': return 'กำลังยืมใช้งาน';
                case 'Returned': return 'ส่งคืนพัสดุแล้ว';
                case 'Overdue': return 'เกินกำหนดส่งคืน';
                default: return 'ไม่พบข้อมูล';
            }
        }

        function formatThaiDate(dateStr) {
            if (!dateStr) return '';
            const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear() + 543}`;
        }
    </script>
</body>
</html>