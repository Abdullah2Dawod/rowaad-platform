<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function home()        { return Inertia::render('Home'); }
    public function about()       { return Inertia::render('About'); }
    public function services()    { return Inertia::render('Services'); }
    public function sectors()     { return Inertia::render('Sectors'); }
    public function consultants() { return Inertia::render('Consultants'); }
    public function blog()        { return Inertia::render('Blog'); }
    public function contact()     { return Inertia::render('Contact'); }

    public function switchLocale(Request $request, string $locale)
    {
        if (!in_array($locale, ['ar', 'en'])) {
            abort(404);
        }
        session()->put('locale', $locale);
        cookie()->queue('locale', $locale, 60 * 24 * 365);
        return redirect()->back();
    }
}
