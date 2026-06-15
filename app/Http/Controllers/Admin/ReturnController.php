<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReturnController extends Controller
{
    public function index(): Response
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $equipmentMap = Equipment::all()->keyBy('id');

        $activeBorrows = BorrowRequest::whereIn('status', ['Approved', 'Overdue'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($r) => [
                'id'           => $r->id,
                'borrowerName' => $r->borrower_name,
                'email'        => $r->email,
                'items'        => $r->items,
                'equipments'   => $equipmentMap->only($r->items)->values(),
                'purpose'      => $r->purpose,
                'borrowDate'   => $r->borrow_date->format('Y-m-d'),
                'returnDate'   => $r->return_date->format('Y-m-d'),
                'status'       => $r->status,
            ]);

        $stats = [
            'total'       => BorrowRequest::count(),
            'returned'    => BorrowRequest::where('status', 'Returned')->count(),
            'overdue'     => BorrowRequest::where('status', 'Overdue')->count(),
            'returnRate'  => BorrowRequest::count() > 0
                ? round(BorrowRequest::where('status', 'Returned')->count() / BorrowRequest::count() * 100)
                : 0,
        ];

        return Inertia::render('Admin/Return', compact('activeBorrows', 'stats'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $borrow = BorrowRequest::findOrFail($id);

        foreach ($borrow->items as $eqId) {
            Equipment::where('id', $eqId)->update(['status' => 'Available']);
        }

        $borrow->update(['status' => 'Returned']);

        return back()->with('success', "รับคืนใบขอ {$id} สำเร็จแล้ว");
    }

    public function quickReturn(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $request->validate(['asset_id' => 'required|string']);
        $assetId = strtoupper($request->asset_id);

        $equipment = Equipment::find($assetId);
        if (!$equipment) {
            return back()->with('error', "ไม่พบรหัสอุปกรณ์ \"{$assetId}\"");
        }

        $borrow = BorrowRequest::whereIn('status', ['Approved', 'Overdue'])
            ->whereJsonContains('items', $assetId)
            ->first();

        if (!$borrow) {
            return back()->with('error', "{$equipment->name} ไม่มีรายการยืมที่ active");
        }

        foreach ($borrow->items as $eqId) {
            Equipment::where('id', $eqId)->update(['status' => 'Available']);
        }
        $borrow->update(['status' => 'Returned']);

        return back()->with('success', "คืน {$equipment->name} ({$assetId}) สำเร็จแล้ว");
    }
}
