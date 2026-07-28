<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Move consultant avatar_path from the /storage disk to the committed
 * /public/images/ tree so images always deploy with the code (the disk
 * folder is gitignored and prior downloads failed intermittently on
 * production, leaving broken image cells for a subset of consultants).
 */
return new class extends Migration {
    public function up(): void
    {
        $rows = DB::table('consultants')->select('id', 'avatar_path')->get();

        foreach ($rows as $c) {
            $current = (string) ($c->avatar_path ?? '');
            if ($current === '') continue;

            // If it's already pointing to public/images or is external, leave it
            if (str_starts_with($current, 'http')) continue;
            if (str_starts_with($current, 'images/')) continue;
            if (str_starts_with($current, '/images/')) continue;

            // Normalise storage path -> images path
            // 'consultants/avatars/consultant-7.png' -> 'images/consultants/avatars/consultant-7.png'
            $newPath = 'images/consultants/avatars/consultant-' . $c->id . '.png';

            // Only update if the target file actually exists in public/images/
            $target = public_path($newPath);
            if (! file_exists($target)) continue;

            DB::table('consultants')->where('id', $c->id)->update([
                'avatar_path' => $newPath,
            ]);
        }
    }

    public function down(): void
    {
        // Non-reversible.
    }
};
