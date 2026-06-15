<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Inertia\Inertia;
use Inertia\Response;

class BorrowPageController extends Controller
{
    public function index(): Response
    {
        $equipments = Equipment::where('status', 'Available')
            ->orderBy('id')
            ->get()
            ->map(fn ($e) => [
                'id'     => $e->id,
                'name'   => $e->name,
                'serial' => $e->serial,
                'icon'   => $e->icon,
                'image'  => $e->image_url,
            ]);

        return Inertia::render('BorrowRequest', compact('equipments'));
    }
}
