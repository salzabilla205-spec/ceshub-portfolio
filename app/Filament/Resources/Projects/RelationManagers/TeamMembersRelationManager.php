<?php
namespace App\Filament\Resources\Projects\RelationManagers;
use App\Filament\Resources\TeamMembers\TeamMemberResource;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'teamMembers';
    protected static ?string $relatedResource = TeamMemberResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('pivot.role_in_project')
                    ->label('Role in Project'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('role_in_project')
                            ->label('Role in Project'),
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }
}