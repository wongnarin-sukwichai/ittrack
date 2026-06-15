<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipments = [
            ['id' => 'IT-001', 'name' => 'โน้ตบุ๊ก Dell Latitude 5540', 'category' => 'it', 'serial' => 'DL5540-2024-001', 'description' => 'Intel Core i7-1355U, RAM 16GB, SSD 512GB, Windows 11 Pro', 'status' => 'Available', 'icon' => 'fa-laptop', 'current_location' => 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2', 'lifecycle_state' => 'Active'],
            ['id' => 'IT-002', 'name' => 'โน้ตบุ๊ก Lenovo ThinkPad X1 Carbon', 'category' => 'it', 'serial' => 'LNV-X1C-2023-088', 'description' => 'Intel Core i5-1335U, RAM 16GB, SSD 256GB, Windows 11', 'status' => 'Borrowed', 'icon' => 'fa-laptop', 'current_location' => 'ห้องประชุมสัมมนา อาคาร 4', 'lifecycle_state' => 'Active'],
            ['id' => 'IT-003', 'name' => 'แท็บเล็ต iPad Pro 12.9" M2', 'category' => 'it', 'serial' => 'IPAD-PRO-M2-2024', 'description' => 'Apple M2 chip, 256GB Wi-Fi, พร้อม Apple Pencil Gen2', 'status' => 'Available', 'icon' => 'fa-tablet-screen-button', 'current_location' => 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2', 'lifecycle_state' => 'Active'],
            ['id' => 'IT-004', 'name' => 'เราเตอร์ WiFi Portable TP-Link M7350', 'category' => 'it', 'serial' => 'TPLINK-M7350-055', 'description' => '4G LTE Mobile WiFi, รองรับ 32 อุปกรณ์พร้อมกัน', 'status' => 'Maintenance', 'icon' => 'fa-wifi', 'current_location' => 'ห้องซ่อมบำรุง ชั้น 1', 'lifecycle_state' => 'Under Repair'],
            ['id' => 'IT-005', 'name' => 'สายต่อ HDMI to USB-C 4K', 'category' => 'it', 'serial' => 'CABLE-HDMI-4K-012', 'description' => 'สาย HDMI 2.0 to Type-C 4K@60Hz ความยาว 2 เมตร', 'status' => 'Available', 'icon' => 'fa-plug', 'current_location' => 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2', 'lifecycle_state' => 'Active'],
            ['id' => 'AV-001', 'name' => 'โปรเจคเตอร์ Epson EB-X51', 'category' => 'av', 'serial' => 'EPS-EBX51-2023-007', 'description' => 'XGA 3800 Lumens, HDMI/VGA/USB, พร้อมรีโมท', 'status' => 'Borrowed', 'icon' => 'fa-video', 'current_location' => 'ห้องเรียน 4201 อาคาร 4', 'lifecycle_state' => 'Active'],
            ['id' => 'AV-002', 'name' => 'ไมโครโฟนไร้สาย Shure BLX288/PG58', 'category' => 'av', 'serial' => 'SHURE-BLX288-2022', 'description' => 'ระบบ Dual Channel, ช่วงความถี่ H10 / 542-572MHz', 'status' => 'Available', 'icon' => 'fa-microphone', 'current_location' => 'ห้องโสตทัศน์ ชั้น 3', 'lifecycle_state' => 'Active'],
            ['id' => 'AV-003', 'name' => 'กล้องบันทึกวีดิโอ Sony FX3', 'category' => 'av', 'serial' => 'SONY-FX3-2024-003', 'description' => 'Full-Frame Cinema Camera, 4K 120fps, พร้อมขาตั้งและแบตสำรอง', 'status' => 'Available', 'icon' => 'fa-camera', 'current_location' => 'ห้องโสตทัศน์ ชั้น 3', 'lifecycle_state' => 'Active'],
        ];

        foreach ($equipments as $data) {
            Equipment::create($data);
        }
    }
}
