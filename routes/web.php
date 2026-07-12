<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConsultantApplicationController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\FeasibilityStudyController;
use App\Http\Controllers\InvestmentOpportunityController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ============= PUBLIC =============
Route::get('/',            fn () => Inertia::render('Home'))->name('home');
Route::get('/about',       fn () => Inertia::render('About'))->name('about');
Route::get('/services',    fn () => Inertia::render('Services', [
    'catalog' => App\Http\Controllers\ServiceRequestController::catalog(),
]))->name('services');
Route::get('/sectors',     fn () => Inertia::render('Sectors'))->name('sectors');
Route::get('/blog',        fn () => Inertia::render('Blog'))->name('blog');
Route::get ('/contact', fn () => Inertia::render('Contact'))->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Legal
Route::get('/privacy', fn () => Inertia::render('Legal/Privacy'))->name('legal.privacy');
Route::get('/terms',   fn () => Inertia::render('Legal/Terms'))->name('legal.terms');

// Consultants directory
Route::get('/consultants',              [ConsultantController::class, 'index'])->name('consultants.index');
Route::get('/consultants/{consultant}', [ConsultantController::class, 'show'])->name('consultants.show');

// Feasibility studies marketplace + custom request
Route::get('/feasibility-studies',               [FeasibilityStudyController::class, 'index'])->name('feasibility.index');
Route::get('/feasibility-studies/{feasibility}', [FeasibilityStudyController::class, 'show'])->name('feasibility.show');
Route::get ('/feasibility-request', [App\Http\Controllers\FeasibilityRequestController::class, 'create'])->name('feasibility.request.create');
Route::post('/feasibility-request', [App\Http\Controllers\FeasibilityRequestController::class, 'store'])->name('feasibility.request.store');

// Newsletter subscriptions
Route::post('/newsletter/subscribe',            [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get ('/newsletter/confirm/{token}',      [App\Http\Controllers\NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get ('/newsletter/unsubscribe/{token}',  [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Investment opportunities (public — B2B)
Route::get ('/investments',                    [InvestmentOpportunityController::class, 'index'])->name('investments.index');
Route::get ('/investments/{investment}',       [InvestmentOpportunityController::class, 'show'])->name('investments.show');
Route::post('/investments/{investment}/apply', [InvestmentOpportunityController::class, 'apply'])->name('investments.apply');

// Service detail + request
Route::get ('/services/{slug}',   [ServiceRequestController::class, 'show'])->name('services.show');
Route::post('/services/request',  [ServiceRequestController::class, 'store'])->name('services.request');

// ============= CONSULTANT APPLICATION (GUEST-FRIENDLY) =============
// Consultants apply as guests — account is created within the wizard itself.
Route::prefix('become-a-consultant')->name('consultant.apply.')->group(function () {
    Route::get ('/',                [ConsultantApplicationController::class, 'start'])->name('start');
    Route::get ('/step-{step}',     [ConsultantApplicationController::class, 'step'])
         ->where('step', '[1-3]')->name('step');
    Route::post('/step-1',          [ConsultantApplicationController::class, 'saveStep1'])->name('save1');
    Route::post('/step-2',          [ConsultantApplicationController::class, 'saveStep2'])->name('save2');
    Route::post('/step-3',          [ConsultantApplicationController::class, 'saveStep3'])->name('save3');
    Route::get ('/pending',         [ConsultantApplicationController::class, 'pending'])->name('pending');
    Route::get ('/rejected',        [ConsultantApplicationController::class, 'rejected'])->name('rejected');
});

// Locale switcher
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['ar', 'en'], true)) {
        session(['locale' => $locale]);
    }
    return back();
})->name('locale.switch');

// ============= AUTHENTICATED =============
Route::middleware('auth')->group(function () {
    // Profile — accessible even without email verification (so user can update it)
    Route::get   ('/profile',          [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch ('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::post  ('/profile/avatar',   [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar',   [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::patch ('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Printable invoice — admin only (enforced in controller)
    Route::get('/invoices/{booking}', [InvoiceController::class, 'show'])->name('invoices.show');
});

// Verified users only — booking + feasibility submit require verified email
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get ('/bookings',                     [BookingController::class, 'index'])->name('bookings.index');
    Route::get ('/bookings/create/{consultant}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store/{consultant}',  [BookingController::class, 'store'])->name('bookings.store');
    Route::get ('/bookings/{booking}/pay',       [BookingController::class, 'pay'])->name('bookings.pay');
    Route::post('/bookings/{booking}/pay',       [BookingController::class, 'processPayment'])->name('bookings.process');
    Route::get ('/bookings/{booking}',           [BookingController::class, 'show'])->name('bookings.show');

    Route::get ('/feasibility/submit', [FeasibilityStudyController::class, 'createForm'])->name('feasibility.submit');
    Route::post('/feasibility/submit', [FeasibilityStudyController::class, 'store'])->name('feasibility.store');
});

// ─── Live chat API (any authenticated user + consultants + admins) ───
Route::middleware('auth')->prefix('api/chat')->group(function () {
    Route::post('/start',                       [ChatController::class, 'start'])->name('chat.start');
    Route::get ('/{conversation}/messages',     [ChatController::class, 'messages'])->name('chat.messages');
    Route::post('/{conversation}/send',         [ChatController::class, 'send'])->name('chat.send');
    Route::post('/{conversation}/close',        [ChatController::class, 'close'])->name('chat.close');
});

require __DIR__ . '/auth.php';
