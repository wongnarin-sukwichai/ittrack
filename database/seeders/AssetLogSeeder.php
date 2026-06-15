<?php

namespace Database\Seeders;

use App\Models\AssetLog;
use Illuminate\Database\Seeder;

class AssetLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            ['asset_id' => 'IT-001', 'action' => 'Inspect',     'detail' => 'ตรวจสภาพประจำปีงบประมาณ 2566 — สภาพดี พร้อมใช้งาน',                'location' => 'ห้องเก็บอุปกรณ์ ชั้น 2',     'operator' => 'วงค์นรินทร์ สุวิชัย', 'date' => '2026-01-15'],
            ['asset_id' => 'IT-001', 'action' => 'Relocate',    'detail' => 'ย้ายจากห้องฝึกอบรม ชั้น 3 มาเก็บที่ห้องเก็บอุปกรณ์ ชั้น 2',         'location' => 'ห้องเก็บอุปกรณ์ ชั้น 2',     'operator' => 'สมชาย รักดี',        'date' => '2026-03-10'],
            ['asset_id' => 'IT-002', 'action' => 'Inspect',     'detail' => 'ตรวจเช็คสภาพก่อนปล่อยยืม — แบตเตอรี่ 95% ปกติ',                     'location' => 'ห้องเก็บอุปกรณ์ ชั้น 2',     'operator' => 'วงค์นรินทร์ สุวิชัย', 'date' => '2026-02-20'],
            ['asset_id' => 'IT-004', 'action' => 'Maintenance', 'detail' => 'ส่งซ่อมอะแดปเตอร์ชำรุด ไฟไม่เข้า — ส่งศูนย์ซ่อม TP-Link',             'location' => 'ห้องซ่อมบำรุง ชั้น 1',       'operator' => 'ประวิทย์ มาลัย',      'date' => '2026-04-05'],
            ['asset_id' => 'AV-001', 'action' => 'Relocate',    'detail' => 'นำออกใช้งานห้อง 4201 สำหรับงานสัมมนา ยังไม่ได้คืน',                   'location' => 'ห้องเรียน 4201 อาคาร 4',   'operator' => 'สมศรี ใจดี',         'date' => '2026-05-28'],
            ['asset_id' => 'AV-002', 'action' => 'Inspect',     'detail' => 'ตรวจสอบความถี่และแบตเตอรี่ไมค์ไร้สาย — พร้อมใช้งาน',                 'location' => 'ห้องโสตทัศน์ ชั้น 3',       'operator' => 'วงค์นรินทร์ สุวิชัย', 'date' => '2026-05-30'],
            ['asset_id' => 'AV-003', 'action' => 'Inspect',     'detail' => 'ตรวจสภาพกล้องและอุปกรณ์เสริม เลนส์ใสสะอาด แบตเตอรี่ OK',            'location' => 'ห้องโสตทัศน์ ชั้น 3',       'operator' => 'วงค์นรินทร์ สุวิชัย', 'date' => '2026-06-01'],
            ['asset_id' => 'IT-003', 'action' => 'Relocate',    'detail' => 'ย้ายจากห้องผู้อำนวยการมาเก็บที่ห้องเก็บอุปกรณ์ ชั้น 2',              'location' => 'ห้องเก็บอุปกรณ์ ชั้น 2',     'operator' => 'สมชาย รักดี',        'date' => '2026-06-05'],
        ];

        foreach ($logs as $log) {
            AssetLog::create($log);
        }
    }
}
