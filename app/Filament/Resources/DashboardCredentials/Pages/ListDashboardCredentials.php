<?php

namespace App\Filament\Resources\DashboardCredentials\Pages;

use App\Filament\Resources\DashboardCredentials\DashboardCredentialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDashboardCredentials extends ListRecords
{
    public function getSubheading(): ?string { return 'Kelola akses kontrol tiap project.'; }

    protected static string $resource = DashboardCredentialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah akses project')->icon('heroicon-o-plus'),
        ];
    }
}
