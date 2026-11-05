<?php

namespace App\Filament\Resources\TeamMembers\Pages;

use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeamMembers extends ListRecords
{
    public function getSubheading(): ?string { return 'Kelola anggota dan pembimbing CES.'; }

    protected static string $resource = TeamMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah anggota')->icon('heroicon-o-plus'),
        ];
    }
}
