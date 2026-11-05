<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    public function getSubheading(): ?string { return 'Tambahkan riset, foto, dan tim.'; }

    protected static string $resource = ProjectResource::class;
    protected static ?string $title = 'Tambah Kabar Riset';
    protected function getCreateFormAction(): \Filament\Actions\Action { return parent::getCreateFormAction()->label('Simpan project'); }
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action { return parent::getCreateAnotherFormAction()->label('Simpan & tambah lagi'); }
    public function getBreadcrumb(): string { return 'Tambah'; }
}
