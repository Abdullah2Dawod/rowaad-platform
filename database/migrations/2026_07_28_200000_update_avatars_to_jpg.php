<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Switch every seeded consultant avatar_path from the letter-initial PNGs
 * to real portrait JPGs bundled in public/images/consultants/avatars/.
 * Only touches rows whose path still points at the previous PNG naming
 * scheme — leaves any real user upload alone.
 */
return new class extends Migration {
    public function up(): void
    {
        $rows = DB::table('consultants')->select('id', 'avatar_path')->get();
        foreach ($rows as $c) {
            $p = (string) ($c->avatar_path ?? '');
            $expected = "images/consultants/avatars/consultant-{$c->id}.png";
            if ($p !== $expected) continue;

            $newPath = "images/consultants/avatars/consultant-{$c->id}.jpg";
            if (! file_exists(public_path($newPath))) continue;

            DB::table('consultants')->where('id', $c->id)->update([
                'avatar_path' => $newPath,
            ]);
        }
    }

    public function down(): void { /* non-reversible */ }
};
