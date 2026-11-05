<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Filament\Tables\Columns\Column::configureUsing(function (\Filament\Tables\Columns\Column $column) {
            $column->extraCellAttributes(fn () => ['data-ces-label' => $column->getLabel()], merge: true);
        });
        \Filament\Tables\Table::configureUsing(function (\Filament\Tables\Table $table) {
            $table->hiddenFilterIndicators();
            $table->filtersTriggerAction(fn (\Filament\Actions\Action $action) => $action->label('Pilih data')->icon('heroicon-o-adjustments-horizontal')->button()->color('gray'));
        });
        \Illuminate\Support\Facades\View::composer('partials.dashboard-project-team', function ($view) {
            $view->with('dashboardSupervisor', \App\Models\TeamMember::where('is_supervisor', true)->orWhere('name', 'like', '%Hari Maghfiroh%')->orderByDesc('is_supervisor')->first());
        });
    }
}
