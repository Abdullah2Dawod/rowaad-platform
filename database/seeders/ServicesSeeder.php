<?php

namespace Database\Seeders;

use App\Http\Controllers\ServiceRequestController;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        // Read from the original hardcoded catalog and persist.
        // Uses reflection so we can still call it even after catalog() moves to DB-backed.
        $ref     = new \ReflectionMethod(ServiceRequestController::class, 'legacyCatalog');
        $catalog = $ref->invoke(null);

        foreach ($catalog as $i => $s) {
            Service::updateOrCreate(
                ['slug' => $s['slug']],
                [
                    'title'        => $s['title'],
                    'tagline'      => $s['tagline'] ?? null,
                    'icon'         => $s['icon'] ?? null,
                    'hero_image'   => $s['hero_image'] ?? null,
                    'summary'      => $s['summary'] ?? null,
                    'includes'     => $s['includes'] ?? [],
                    'deliverables' => $s['deliverables'] ?? [],
                    'stats'        => $s['stats'] ?? [],
                    'featured'     => (bool) ($s['featured'] ?? false),
                    'is_active'    => true,
                    'sort_order'   => $i * 10,
                ]
            );
        }
    }
}
