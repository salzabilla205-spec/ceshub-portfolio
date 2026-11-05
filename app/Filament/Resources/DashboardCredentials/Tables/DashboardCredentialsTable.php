<?php

namespace App\Filament\Resources\DashboardCredentials\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DashboardCredentialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name')
                    ->label('Project')
                    ->wrap()
                    ->searchable(),
                TextColumn::make('username')
                    ->label('Nama pengguna')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir diubah')
                    ->wrap()
                    ->dateTime('d M Y, H:i'),
            ])
            ->recordActions([
                Action::make('lihat_password')
                    ->label('Lihat kata sandi')
                    ->button()->size('sm')->color('primary')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn ($record) => 'Kata sandi untuk ' . $record->project->name)
                    ->modalDescription(function ($record) {
                        abort_unless(auth()->user()?->is_admin, 403);
                        \Illuminate\Support\Facades\Log::info('Admin viewed project credential', ['user_id'=>auth()->id(), 'project_id'=>$record->project_id]);
                        return $record->plain_password;
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                EditAction::make()->label('Ubah')->button()->size('sm')->color('primary'),
                DeleteAction::make()->label('Hapus')->button()->size('sm')->color('danger'),
            ]);
    }
}
