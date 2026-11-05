<?php

namespace App\Filament\Resources\DashboardCredentials;

use App\Filament\Resources\DashboardCredentials\Pages\CreateDashboardCredential;
use App\Filament\Resources\DashboardCredentials\Pages\EditDashboardCredential;
use App\Filament\Resources\DashboardCredentials\Pages\ListDashboardCredentials;
use App\Filament\Resources\DashboardCredentials\Schemas\DashboardCredentialForm;
use App\Filament\Resources\DashboardCredentials\Tables\DashboardCredentialsTable;
use App\Models\DashboardCredential;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DashboardCredentialResource extends Resource
{
    protected static ?string $navigationLabel = 'Akses Kontrol Project';
    protected static ?string $pluralModelLabel = 'Akses Kontrol Project';
    protected static ?int $navigationSort = 4;

    protected static ?string $model = DashboardCredential::class;
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';
    protected static ?string $recordTitleAttribute = 'username';

    public static function form(Schema $schema): Schema
    {
        return DashboardCredentialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DashboardCredentialsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDashboardCredentials::route('/'),
            'create' => CreateDashboardCredential::route('/create'),
            'edit' => EditDashboardCredential::route('/{record}/edit'),
        ];
    }
 public static function getNavigationGroup(): ?string { return 'Pengaturan'; }
}