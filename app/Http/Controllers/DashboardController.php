<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'available' => Equipment::where('status', 'Available')->count(),
            'borrowed'  => Equipment::where('status', 'Borrowed')->count(),
            'overdue'   => BorrowRequest::where('status', 'Overdue')->count(),
            'pending'   => BorrowRequest::where('status', 'Pending')->count(),
        ];

        $equipmentMap = Equipment::all()->keyBy('id');

        $activeBorrows = BorrowRequest::whereIn('status', ['Approved', 'Overdue'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($r) => array_merge($r->toArray(), [
                'equipments' => $equipmentMap->only($r->items)->values(),
            ]));

        $returnedItems = BorrowRequest::where('status', 'Returned')
            ->orderByDesc('updated_at')
            ->take(10)
            ->get()
            ->map(fn ($r) => array_merge($r->toArray(), [
                'equipments' => $equipmentMap->only($r->items)->values(),
            ]));

        $assetLogs = AssetLog::with('equipment')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(3)
            ->through(fn ($log) => [
                'id'       => $log->id,
                'assetId'  => $log->asset_id,
                'assetName'=> $log->equipment?->name ?? 'ไม่พบข้อมูล',
                'action'   => $log->action,
                'detail'   => $log->detail,
                'location' => $log->location,
                'operator' => $log->operator,
                'date'     => $log->date->format('Y-m-d'),
            ]);

        return Inertia::render('Home', compact('stats', 'activeBorrows', 'returnedItems', 'assetLogs'));
    }
}
