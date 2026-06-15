<?php

namespace App\Http\Controllers;

use App\Models\AssetLog;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AssetLogController extends Controller
{
    public function index(): Response
    {
        $equipments = Equipment::with(['assetLogs' => fn ($q) => $q->orderByDesc('date')])
            ->orderBy('id')
            ->get()
            ->map(fn ($e) => [
                'id'              => $e->id,
                'name'            => $e->name,
                'category'        => $e->category,
                'serial'          => $e->serial,
                'description'     => $e->description,
                'status'          => $e->status,
                'icon'            => $e->icon,
                'currentLocation' => $e->current_location,
                'lifecycleState'  => $e->lifecycle_state,
                'image'           => $e->image_url,
                'assetLogs'       => $e->assetLogs->map(fn ($l) => [
                    'id'       => $l->id,
                    'action'   => $l->action,
                    'detail'   => $l->detail,
                    'location' => $l->location,
                    'operator' => $l->operator,
                    'date'     => $l->date->format('Y-m-d'),
                ]),
            ]);

        return Inertia::render('Tracking', compact('equipments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'asset_id' => 'required|exists:equipments,id',
            'action'   => 'required|in:Relocate,Maintenance,Inspect,Decommission',
            'location' => 'required|string|max:255',
            'detail'   => 'required|string',
        ]);

        $equipment = Equipment::find($request->asset_id);

        AssetLog::create([
            'asset_id' => $request->asset_id,
            'action'   => $request->action,
            'detail'   => $request->detail,
            'location' => $request->location,
            'operator' => auth()->user()->name,
            'date'     => today(),
        ]);

        // Update lifecycle state and location
        $stateMap = [
            'Maintenance'  => 'Under Repair',
            'Relocate'     => 'Relocated',
            'Inspect'      => 'Active',
            'Decommission' => 'Decommissioned',
        ];
        $statusMap = [
            'Maintenance'  => 'Maintenance',
            'Decommission' => 'Maintenance',
        ];

        $equipment->update([
            'lifecycle_state'  => $stateMap[$request->action] ?? $equipment->lifecycle_state,
            'current_location' => $request->location,
            'status'           => $statusMap[$request->action] ?? $equipment->status,
        ]);

        return back()->with('success', 'บันทึกเหตุการณ์สำเร็จแล้ว');
    }
}
