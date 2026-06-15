<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Equipment;
use Inertia\Inertia;
use Inertia\Response;

class StatisticsController extends Controller
{
    public function index(): Response
    {
        $equipments = Equipment::all();
        $requests   = BorrowRequest::all();

        $statusBreakdown = [
            ['key' => 'Available',   'label' => 'ว่าง / พร้อมใช้',  'count' => $equipments->where('status', 'Available')->count()],
            ['key' => 'Borrowed',    'label' => 'กำลังถูกยืม',      'count' => $equipments->where('status', 'Borrowed')->count()],
            ['key' => 'Pending',     'label' => 'รอนุมัติยืม',      'count' => $equipments->where('status', 'Pending')->count()],
            ['key' => 'Maintenance', 'label' => 'ส่งซ่อม / ชำรุด', 'count' => $equipments->where('status', 'Maintenance')->count()],
        ];

        // Top 3 most borrowed equipment
        $counts = [];
        foreach ($requests as $r) {
            foreach ($r->items as $id) {
                $counts[$id] = ($counts[$id] ?? 0) + 1;
            }
        }
        arsort($counts);
        $topBorrowed = collect(array_slice($counts, 0, 3, true))
            ->map(fn ($count, $id) => [
                'id'    => $id,
                'count' => $count,
                'name'  => $equipments->firstWhere('id', $id)?->name ?? $id,
            ])->values();

        // Department stats
        $deptCounts = $requests->groupBy('department')
            ->map->count()
            ->sortDesc();
        $total = $requests->count() ?: 1;
        $deptStats = $deptCounts->map(fn ($c, $name) => [
            'name'  => $name,
            'count' => $c,
            'pct'   => round($c / $total * 100),
        ])->values();

        $stats = [
            'totalEquipments'  => $equipments->count(),
            'itCount'          => $equipments->where('category', 'it')->count(),
            'avCount'          => $equipments->where('category', 'av')->count(),
            'totalRequests'    => $requests->count(),
            'returnedCount'    => $requests->where('status', 'Returned')->count(),
        ];

        return Inertia::render('Statistics', compact('stats', 'statusBreakdown', 'topBorrowed', 'deptStats'));
    }
}
