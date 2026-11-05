<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\CesDashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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
            ->brandName('Control Energy System')
            ->brandLogo(fn () => view('filament.studio-brand'))
            ->brandLogoHeight('auto')
            ->font('Inter', url: asset('fonts/filament/filament/inter/index.css'), provider: \Filament\FontProviders\LocalFontProvider::class)
            ->darkMode(false)
            ->topbar(false)
            ->breadcrumbs(false)
            ->globalSearch(false)
            ->maxContentWidth(\Filament\Support\Enums\Width::Full)
            ->sidebarWidth('228px')
            ->collapsedSidebarWidth('64px')
            ->collapsibleNavigationGroups(false)
            ->userMenu(position: \Filament\Enums\UserMenuPosition::Sidebar)
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups(['Barang lab', 'Konten website', 'Pengaturan'])
            ->renderHook('panels::user-menu.before', fn () => view('filament.studio-sidebar-links'))
            ->renderHook('panels::footer', fn () => view('filament.studio-footer'))
            ->renderHook('panels::page.header.heading.before', fn () => view('filament.sidebar-toggle'))
            ->renderHook('panels::page.header.actions.after', fn () => view('filament.inventory-template-button'), scopes: \App\Filament\Resources\Inventory\Pages\ListInventoryItems::class)
            ->renderHook('panels::head.end', fn () => new \Illuminate\Support\HtmlString('<link rel="stylesheet" href="'.asset('css/admin-ces.css').'?v='.filemtime(public_path('css/admin-ces.css')).'">'))
            ->renderHook('panels::head.end', fn () => new \Illuminate\Support\HtmlString('<link rel="stylesheet" href="'.asset('css/admin-studio.css').'?v='.filemtime(public_path('css/admin-studio.css')).'">'))
            ->login(\App\Filament\Auth\Login::class)
            ->colors([
                'primary' => Color::hex('#164c68'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                CesDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,

            ])
            ->middleware([
                \App\Http\Middleware\AdminLocale::class,
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
