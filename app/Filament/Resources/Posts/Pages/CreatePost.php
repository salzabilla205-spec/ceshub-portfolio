<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    public function getSubheading(): ?string { return 'Bagikan berita atau kegiatan riset.'; }

    protected static string $resource = PostResource::class;
    protected static ?string $title = 'Tambah Kabar Riset';
    protected function getCreateFormAction(): \Filament\Actions\Action { return parent::getCreateFormAction()->label('Simpan berita'); }
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action { return parent::getCreateAnotherFormAction()->label('Simpan & tambah lagi'); }
    public function getBreadcrumb(): string { return 'Tambah'; }
}
