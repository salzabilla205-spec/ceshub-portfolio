<?php

namespace App\Filament\Resources\DashboardCredentials\Schemas;

use App\Models\Project;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DashboardCredentialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('project_id')
                ->label('Project')
                ->options(Project::query()->pluck('name', 'id'))
                ->searchable()
                ->required(),
            TextInput::make('username')
                ->label('Username')
                ->required(),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->required(fn (string $context): bool => $context === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->helperText('Kosongkan kalau tidak ingin mengubah password saat edit.'),
        ]);
    }
}