<?php

namespace App\Filament\Resources\DashboardCredentials\Pages;

use App\Filament\Resources\DashboardCredentials\DashboardCredentialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDashboardCredential extends CreateRecord
{
    public function getSubheading(): ?string { return 'Tambahkan akses kontrol untuk project.'; }

    protected static string $resource = DashboardCredentialResource::class;
    protected static ?string $title = 'Tambah akses project';
}
