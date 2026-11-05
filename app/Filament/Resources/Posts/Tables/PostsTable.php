<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\Layout\Stack::make([
                    \Filament\Tables\Columns\ViewColumn::make('title')->label('Judul berita')->view('filament.post-card')->searchable(['title','excerpt','slug']),
                ]),
            ])
            ->contentGrid(['md'=>2,'xl'=>3])
            ->modifyQueryUsing(fn($query)=>$query->with('project:id,name'))
            ->recordUrl(null)->recordAction(null)
            ->defaultSort('published_at','desc')
            ->emptyStateHeading('Belum ada kabar riset')
            ->emptyStateDescription('Tambahkan cerita kegiatan, temuan, dan perkembangan riset CES.')
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')->label('Publikasi')->options(['draft'=>'Draf','published'=>'Terbit']),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah')->button()->size('sm')->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
