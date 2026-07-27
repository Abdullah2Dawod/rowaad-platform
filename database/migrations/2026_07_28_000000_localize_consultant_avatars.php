<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Download every consultant's remote avatar (UI-Avatars or otherwise) into
 * local storage/app/public/consultants/avatars/ so avatar_path is always a
 * relative /storage/... path served by the same origin. No third-party
 * hotlink protection, no CORS, no external dependency — the image just
 * works, everywhere, forever.
 */
return new class extends Migration {
    public function up(): void
    {
        Storage::disk('public')->makeDirectory('consultants/avatars');

        $rows = DB::table('consultants')
            ->select('id', 'full_name_ar', 'avatar_path')
            ->get();

        foreach ($rows as $c) {
            $current = (string) ($c->avatar_path ?? '');
            // Only process http/https URLs; skip already-local paths
            if (! str_starts_with($current, 'http')) continue;

            try {
                $res = Http::timeout(15)->get($current);
                if (! $res->successful()) continue;

                // Determine extension from content-type
                $type = strtolower($res->header('Content-Type') ?? 'image/png');
                $ext  = match (true) {
                    str_contains($type, 'jpeg') || str_contains($type, 'jpg') => 'jpg',
                    str_contains($type, 'webp') => 'webp',
                    str_contains($type, 'svg') => 'svg',
                    default => 'png',
                };

                $relPath = "consultants/avatars/consultant-{$c->id}.{$ext}";
                Storage::disk('public')->put($relPath, $res->body());

                DB::table('consultants')->where('id', $c->id)->update([
                    'avatar_path' => $relPath,
                ]);
            } catch (\Throwable $e) {
                // Leave the URL intact if download fails — accessor still works
                continue;
            }
        }
    }

    public function down(): void
    {
        // Non-reversible: keep local files.
    }
};
