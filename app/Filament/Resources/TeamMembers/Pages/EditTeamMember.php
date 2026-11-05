<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeamMember extends EditRecord
{
    public function getSubheading(): ?string { return 'Perbarui profil dan keanggotaan tim.'; }

    protected static ?string $title = 'Ubah anggota';
    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
