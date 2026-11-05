<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\ViewColumn::make('name')->label('Nama / peran')->view('filament.team-name')->searchable(['name','role']),
                TextColumn::make('angkatan')->label('Angkatan')->placeholder('—'),
                TextColumn::make('status')->label('Status')->state(fn($record)=>$record->is_supervisor?'Pembimbing':($record->is_active?'Aktif':'Alumni'))->badge()->color(fn($record)=>$record->is_active?'primary':'gray'),
                TextColumn::make('projects.name')->label('Project')->placeholder('—')->wrap(),
                TextColumn::make('email')->label('Alamat email')->searchable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('linkedin_url')->label('LinkedIn')->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('created_at')->label('Ditambahkan')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')->label('Terakhir diubah')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault:true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('member_status')->label('Status anggota')->options(['active'=>'Aktif','alumni'=>'Alumni','supervisor'=>'Pembimbing'])->query(fn($query,array $data)=>match($data['value']??null){'active'=>$query->where('is_active',true),'alumni'=>$query->where('is_active',false),'supervisor'=>$query->where('is_supervisor',true),default=>$query}),
            ])
            ->recordActions([
                EditAction::make()->label('Ubah')->button()->size('sm')->color('primary'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
