<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
              Grid::make(['default' => 1, 'lg' => 3])->columnSpanFull()
               ->extraAttributes(['class' => 'ces-member-form'])
               ->schema([
                Group::make([
                 Group::make([
                TextInput::make('name')->label('Nama')
                    ->required(),
                TextInput::make('role')->label('Peran'),
                TextInput::make('angkatan')
                    ->label('Angkatan')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->placeholder('Contoh: 2022'),
                TextInput::make('email')
                    ->label('Alamat email')
                    ->email(),
                TextInput::make('linkedin_url')->label('LinkedIn')
                    ->url()->columnSpanFull(),
                 ])->columns(['default' => 1, 'sm' => 2]),
                 Group::make([
                Toggle::make('is_active')
                    ->required()
                    ->label('Masih Aktif')
                    ->helperText('Nonaktifkan jika sudah lulus.'),
                Toggle::make('is_supervisor')
                    ->label('Dosen Pembimbing')
                    ->helperText('Untuk Pak Hari di halaman Tim.'),
                 ])->columns(['default' => 1, 'sm' => 2]),
                ])->columnSpan(['default' => 1, 'lg' => 2]),
                FileUpload::make('photo')->label('Foto profil')
                    ->image()->disk('uploads')->directory('team-members')
                    ->imageEditor()->imageEditorAspectRatios(['1:1'])
                    ->imagePreviewHeight('120')
                    ->imageResizeTargetWidth('600')->imageResizeTargetHeight('600'),
               ]),
            ]);
    }
}
