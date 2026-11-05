<?php

namespace App\Filament\Resources\DashboardCredentials\Pages;

use App\Filament\Resources\DashboardCredentials\DashboardCredentialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDashboardCredential extends EditRecord
{
    public function getSubheading(): ?string { return 'Perbarui akses kontrol project.'; }

    protected static string $resource = DashboardCredentialResource::class;
    protected static ?string $title = 'Ubah akses project';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
