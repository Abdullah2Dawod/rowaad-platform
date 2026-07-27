<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Replace unreliable Unsplash seed avatars on consultants with UI-Avatars
 * URLs generated from the consultant's Arabic name. UI-Avatars is a stable,
 * long-lived service that always returns a valid PNG based on the name +
 * a brand-colored background — so the same avatar always renders in the
 * admin list, edit form, and the public site.
 *
 * If a consultant has a locally-stored path (starts with 'consultants/' or
 * '/storage'), we leave it alone — that's a real user-uploaded photo.
 */
return new class extends Migration {
    public function up(): void
    {
        $rows = DB::table('consultants')->select('id', 'full_name_ar', 'full_name_en', 'avatar_path')->get();

        foreach ($rows as $c) {
            $current = (string) ($c->avatar_path ?? '');
            $isLocal = $current !== '' && ! str_starts_with($current, 'http');
            // Skip real local uploads
            if ($isLocal) continue;

            $name = trim($c->full_name_ar ?: $c->full_name_en ?: 'مستشار');
            // Strip common Arabic titles that would clutter initials
            $name = preg_replace('/^(د\.|أ\.|م\.|أ\.د\.|أ\.م\.)\s*/u', '', $name);

            $encoded = rawurlencode($name);
            // #2D4B7E = brand navy · #FFFFFF text · 400x400 · rounded portrait
            $new = "https://ui-avatars.com/api/?name={$encoded}&size=400&background=2D4B7E&color=FFFFFF&bold=true&format=png";

            DB::table('consultants')->where('id', $c->id)->update(['avatar_path' => $new]);
        }
    }

    public function down(): void
    {
        // Non-reversible — keep the new avatars.
    }
};
