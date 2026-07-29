<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Inertia\Inertia;
use Inertia\Response;

class SectorController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Sectors', [
            'sectors' => Sector::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn (Sector $s) => [
                    'id'          => $s->id,
                    'slug'        => $s->slug,
                    'name'        => $s->name_ar,
                    'name_en'     => $s->name_en,
                    'description' => $s->description_ar,
                    'icon'        => $s->icon,
                    'color'       => $s->color,
                    'featured'    => (bool) $s->featured,
                    'highlights'  => $s->highlights ?? [],
                ])
                ->values(),
        ]);
    }
}
