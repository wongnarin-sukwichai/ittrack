<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\BorrowRequest;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BorrowRequestController extends Controller
{
    public function index(): Response
    {
        $equipmentMap = Equipment::all()->keyBy('id');

        $requests = BorrowRequest::orderByDesc('created_at')
            ->paginate(5)
            ->through(fn ($r) => [
                'id'           => $r->id,
                'borrowerName' => $r->borrower_name,
                'email'        => $r->email,
                'department'   => $r->department,
                'items'        => $r->items,
                'equipments'   => $equipmentMap->only($r->items)->values(),
                'purpose'      => $r->purpose,
                'borrowDate'   => $r->borrow_date->format('Y-m-d'),
                'returnDate'   => $r->return_date->format('Y-m-d'),
                'status'       => $r->status,
            ]);

        return Inertia::render('History', ['requests' => $requests]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'items'       => 'required|array|min:1',
            'items.*'     => 'exists:equipments,id',
            'purpose'     => 'required|string',
            'return_date' => 'required|date|after_or_equal:today',
        ]);

        $equipments = Equipment::whereIn('id', $request->items)
            ->where('status', 'Available')
            ->get();

        if ($equipments->count() !== count($request->items)) {
            return back()->with('error', 'อุปกรณ์บางรายการไม่ว่างให้ยืม กรุณาตรวจสอบอีกครั้ง');
        }

        $id = BorrowRequest::generateId();

        BorrowRequest::create([
            'id'           => $id,
            'borrower_name'=> auth()->user()->name,
            'email'        => auth()->user()->email,
            'department'   => 'ผู้ขอใช้บริการ',
            'items'        => $request->items,
            'purpose'      => $request->purpose,
            'borrow_date'  => today(),
            'return_date'  => $request->return_date,
            'status'       => 'Pending',
        ]);

        $equipments->each(fn ($e) => $e->update(['status' => 'Pending']));

        return back()->with('success', "ส่งคำขอ {$id} สำเร็จแล้ว รอเจ้าหน้าที่ตรวจสอบ");
    }

    public function quickStore(Request $request): RedirectResponse
    {
        $request->validate([
            'asset_id' => 'required|string|exists:equipments,id',
        ]);

        $equipment = Equipment::find(strtoupper($request->asset_id));

        if ($equipment->status !== 'Available') {
            return back()->with('error', "{$equipment->name} ไม่ว่างให้ยืม (สถานะ: {$equipment->status})");
        }

        $id = BorrowRequest::generateId();
        $returnDate = now()->addDays(7)->format('Y-m-d');

        BorrowRequest::create([
            'id'           => $id,
            'borrower_name'=> auth()->user()->name,
            'email'        => auth()->user()->email,
            'department'   => 'บันทึกด่วน',
            'items'        => [$equipment->id],
            'purpose'      => 'บันทึกยืมด่วนโดยเจ้าหน้าที่',
            'borrow_date'  => today(),
            'return_date'  => $returnDate,
            'status'       => 'Approved',
        ]);

        $equipment->update(['status' => 'Borrowed']);

        AssetLog::create([
            'asset_id' => $equipment->id,
            'action'   => 'Relocate',
            'detail'   => "ยืมด่วนโดย " . auth()->user()->name,
            'location' => $equipment->current_location,
            'operator' => auth()->user()->name,
            'date'     => today(),
        ]);

        return back()->with('success', "บันทึกยืมด่วน {$id} สำเร็จแล้ว");
    }
}
