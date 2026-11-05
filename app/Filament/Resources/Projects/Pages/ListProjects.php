<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\Action;
use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\ListRecords;

class ListProjects extends ListRecords
{
    public function getSubheading(): ?string { return 'Kelola riset dan project CES.'; }

    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_post')->label('Tambah Kabar Riset')
                ->icon('heroicon-o-plus')->url(PostResource::getUrl('create')),
        ];
    }
}
