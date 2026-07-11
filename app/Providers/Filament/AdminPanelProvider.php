<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Support\Facades\Blade;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // NO ->login() — auth goes through the custom /login page for ALL roles.
            // Unauthenticated visits to /admin redirect to the named 'login' route.
            ->brandName('رواد بلا حدود')
            ->brandLogo(asset('images/rowaad-logo-symbol.png'))
            ->darkModeBrandLogo(asset('images/rowaad-logo-symbol-dark.png'))
            ->brandLogoHeight('2.25rem')
            ->favicon(asset('images/rowaad-logo-symbol.png'))
            ->colors([
                'primary' => Color::hex('#3DAFB9'),
                'gray'    => Color::Slate,
                'info'    => Color::hex('#2D4B7E'),
            ])
            ->font('Alexandria')
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('<style>' . file_get_contents(resource_path('css/filament/admin/theme.css')) . '</style>')
            )

            // Realtime database notifications: bell icon updates via Livewire polling
            ->databaseNotifications()
            ->databaseNotificationsPolling('6s')

            // User dropdown (top-right) — quick links per role
            ->userMenuItems([
                'settings' => MenuItem::make()
                    ->label('الإعدادات')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->url(fn (): string => auth()->user()?->role === 'admin' ? url('/admin/settings') : url('/admin/consultant-profile'))
                    ->visible(fn (): bool => in_array(auth()->user()?->role, ['admin', 'consultant'], true)),
                'wallet' => MenuItem::make()
                    ->label('محفظتي')
                    ->icon('heroicon-o-wallet')
                    ->url(fn (): string => url('/admin/wallet'))
                    ->visible(fn (): bool => auth()->user()?->role === 'consultant'),
                'visit_site' => MenuItem::make()
                    ->label('زيارة الموقع')
                    ->icon('heroicon-o-globe-alt')
                    ->url(fn (): string => url('/'))
                    ->openUrlInNewTab(),
            ])

            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('16rem')
            ->maxContentWidth('full')
            ->navigationGroups([
                NavigationGroup::make('نظرة عامة')->collapsible(false),
                NavigationGroup::make('العمليات')->collapsible(false),
                NavigationGroup::make('إدارة المستشارين')->collapsible(false),
                NavigationGroup::make('المحتوى')->collapsible(false),
                NavigationGroup::make('إدارة المستخدمين')->collapsible(false),
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\DashboardHero::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\FinancialsOverview::class,
                \App\Filament\Widgets\LatestBookings::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
