<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Services\ResearchContentService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->records(fn (?string $search, ?array $filters, int $page, int|string $recordsPerPage) =>
                app(ResearchContentService::class)->paginate($search, $filters, $page, $recordsPerPage))
            ->columns([
                Stack::make([
                    ViewColumn::make('title')->label('Kabar Riset')->view('filament.research-card')->searchable(),
                ]),
            ])
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->recordTitleAttribute('title')
            ->recordUrl(null)->recordAction(null)
            ->emptyStateHeading('Belum ada kabar riset')
            ->emptyStateDescription(null)
            ->filters([
                SelectFilter::make('status')->label('Publikasi')->options(['draft' => 'Draf', 'published' => 'Terbit']),
                SelectFilter::make('content_type')->label('Jenis')->options(['project' => 'Project', 'post' => 'Berita']),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('edit')->label('Ubah')->button()->size('sm')->color('primary')
                    ->url(fn (array $record): string => $record['edit_url']),
            ])
            ->selectCurrentPageOnly()
            ->toolbarActions([
                BulkActionGroup::make([
                    self::bulkAction('delete', 'Hapus', 'danger'),
                    self::bulkAction('restore', 'Pulihkan', 'primary')
                        ->visible(fn (HasTable $livewire): bool => filled($livewire->tableFilters['trashed']['value'] ?? null)),
                    self::bulkAction('forceDelete', 'Hapus permanen', 'danger')
                        ->visible(fn (HasTable $livewire): bool => filled($livewire->tableFilters['trashed']['value'] ?? null)),
                ]),
            ]);
    }

    private static function bulkAction(string $operation, string $label, string $color): BulkAction
    {
        return BulkAction::make($operation)->label($label)->color($color)->requiresConfirmation()
            ->action(function (Collection $records, HasTable $livewire) use ($operation): void {
                foreach ($records as $record) {
                    app(ResearchContentService::class)->apply($record, $operation);
                }
                $livewire->resetTable();
            })->deselectRecordsAfterCompletion();
    }
}
