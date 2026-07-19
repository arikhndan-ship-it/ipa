<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestArticles;
use App\Filament\Widgets\RecentNotifications;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\SetAdminLocale;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('panel-khandan')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->brandName('Khandan')
            ->brandLogo(asset( app()->getLocale() === 'ckb' ? 'images/logo-ckb.png' : 'images/logo-en.png' ))
            ->darkModeBrandLogo(asset( app()->getLocale() === 'ckb' ? 'images/logo-ckb.png' : 'images/logo-en.png' ))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->colors([
                'primary' => '#CC0000',
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => '#3B82F6',
                'success' => '#10B981',
                'warning' => '#F59E0B',
            ])
            ->font('Inter')
            ->sidebarWidth('17rem')
            ->collapsibleNavigationGroups(true)
            ->sidebarCollapsibleOnDesktop(true)
            ->maxContentWidth('full')
            ->topNavigation(false)
            ->breadcrumbs(true)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                StatsOverview::class,
                LatestArticles::class,
                RecentNotifications::class,
            ])
            ->navigationGroups([
                'Content',
                'Engagement',
                'Advertising',
                'System',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetAdminLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\FilamentAdminAccess::class,
            ])
            ->renderHook(
                'panels::sidebar.footer',
                fn (): string => view('filament.language-switcher')->render(),
            );
    }
}
