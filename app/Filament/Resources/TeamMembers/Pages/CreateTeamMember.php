<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTeamMember extends CreateRecord
{
    public function getSubheading(): ?string { return 'Tambahkan profil anggota atau pembimbing.'; }

    protected static ?string $title = 'Tambah anggota';
    protected static string $resource = TeamMemberResource::class;

    protected function getCreateFormAction(): \Filament\Actions\Action { return parent::getCreateFormAction()->label('Simpan anggota'); }
    protected function getCreateAnotherFormAction(): \Filament\Actions\Action { return parent::getCreateAnotherFormAction()->label('Simpan & tambah lagi'); }
}
