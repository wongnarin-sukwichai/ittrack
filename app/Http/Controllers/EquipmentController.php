<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn ($q2) => $q2
                ->where('name', 'like', "%$q%")
                ->orWhere('id', 'like', "%$q%")
                ->orWhere('serial', 'like', "%$q%")
            );
        }
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $equipments = $query->orderBy('id')->paginate(9)->withQueryString()
            ->through(fn ($e) => [
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
            ]);

        return Inertia::render('Inventory', [
            'equipments' => $equipments,
            'filters'    => $request->only(['search', 'category', 'status']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $request->validate([
            'id'          => 'required|string|unique:equipments,id',
            'name'        => 'required|string|max:255',
            'category'    => 'required|in:it,av',
            'serial'      => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:5120',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('equipments', 'public');
            $imageUrl = $path;
        }

        Equipment::create([
            'id'               => strtoupper($request->id),
            'name'             => $request->name,
            'category'         => $request->category,
            'serial'           => $request->serial ?? 'N/A',
            'description'      => $request->description,
            'status'           => 'Available',
            'icon'             => $request->category === 'it' ? 'fa-laptop' : 'fa-camera',
            'current_location' => $request->category === 'it' ? 'ห้องเก็บอุปกรณ์สารสนเทศ ชั้น 2' : 'ห้องโสตทัศน์ ชั้น 3',
            'lifecycle_state'  => 'Active',
            'image'            => $imageUrl,
        ]);

        return back()->with('success', "เพิ่มอุปกรณ์ {$request->name} สำเร็จแล้ว");
    }
}
