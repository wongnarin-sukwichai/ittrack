<?php

namespace Database\Seeders;

use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class BorrowRequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            [
                'id'            => 'REQ-2026-001',
                'borrower_name' => 'วงค์นรินทร์ สุวิชัย',
                'email'         => 'wongnarin.s@msu.ac.th',
                'department'    => 'กองบริหารงานวิชาการ',
                'items'         => ['IT-002'],
                'purpose'       => 'บรรยายพิเศษนักศึกษาชั้นปีที่ 3 ห้อง 4201',
                'borrow_date'   => '2026-05-20',
                'return_date'   => '2026-05-21',
                'status'        => 'Returned',
            ],
            [
                'id'            => 'REQ-2026-002',
                'borrower_name' => 'สมศรี ใจดี',
                'email'         => 'somsri.j@msu.ac.th',
                'department'    => 'คณะวิทยาการสารสนเทศ',
                'items'         => ['AV-001'],
                'purpose'       => 'งานสัมมนาวิชาการประจำปี 2566',
                'borrow_date'   => '2026-05-28',
                'return_date'   => '2026-05-29',
                'status'        => 'Overdue',
            ],
            [
                'id'            => 'REQ-2026-003',
                'borrower_name' => 'ประวิทย์ มาลัย',
                'email'         => 'prawit.m@msu.ac.th',
                'department'    => 'สำนักงานอธิการบดี',
                'items'         => ['IT-002'],
                'purpose'       => 'ประชุมสภามหาวิทยาลัย',
                'borrow_date'   => '2026-06-01',
                'return_date'   => '2026-06-10',
                'status'        => 'Approved',
            ],
        ];

        foreach ($requests as $data) {
            BorrowRequest::create($data);
        }

        // Update equipment status to match borrow requests
        Equipment::where('id', 'IT-002')->update(['status' => 'Borrowed']);
        Equipment::where('id', 'AV-001')->update(['status' => 'Borrowed', 'lifecycle_state' => 'Active']);
    }
}
