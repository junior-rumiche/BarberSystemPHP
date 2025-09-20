<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\ManageCategories;
use App\Models\Category;
use App\StatusEnum;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Categorías';

    protected static ?string $modelLabel = 'Categoría';

    protected static ?string $pluralModelLabel = 'Categorías';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, $set) {
                        if ($operation !== 'edit') {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash'])
                    ->helperText('Se genera automáticamente desde el nombre. Puedes editarlo si es necesario.'),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->label('Imagen de Portada')
                    ->image()
                    ->directory('categories')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->imagePreviewHeight('250')
                    ->panelLayout('integrated'),
                Toggle::make('status')
                    ->label('Estado Activo')
                    ->helperText('Activa o desactiva esta categoría')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' || $state === true)
                    ->dehydrateStateUsing(fn ($state) => $state ? 'active' : 'inactive'),
            ]);
    }

    public static function viewForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->disabled(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled(),
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->disabled()
                    ->columnSpanFull(),
                FileUpload::make('cover_image')
                    ->label('Imagen de Portada')
                    ->image()
                    ->disabled()
                    ->columnSpanFull()
                    ->imagePreviewHeight('250')
                    ->panelLayout('integrated'),
                Toggle::make('status')
                    ->label('Estado Activo')
                    ->disabled()
                    ->onColor('success')
                    ->offColor('danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' || $state === true),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nombre')
                    ->size('lg')
                    ->weight('bold'),
                TextEntry::make('slug')
                    ->label('Slug')
                    ->copyable()
                    ->copyMessage('Slug copiado'),
                TextEntry::make('description')
                    ->label('Descripción')
                    ->placeholder('Sin descripción')
                    ->columnSpanFull(),
                ImageEntry::make('cover_image')
                    ->label('Imagen de Portada')
                    ->height(200)
                    ->placeholder('Sin imagen')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                        default => $state,
                    }),
                TextEntry::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i:s')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i:s')
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copiado')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'success',    // 🟢 Verde para Activo
                        'inactive' => 'danger',   // 🔴 Rojo para Inactivo
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state instanceof StatusEnum ? $state->value : $state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                        default => $state,
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                    ])
                    ->default('active'),
                \Filament\Tables\Filters\Filter::make('only_active')
                    ->label('Solo Activos')
                    ->query(fn ($query) => $query->where('status', 'active'))
                    ->default(),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Ver')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->form(fn (Schema $schema) => static::viewForm($schema))
                        ->modalHeading('Ver Categoría')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar'),
                    EditAction::make()
                        ->label('Editar')
                        ->icon('heroicon-o-pencil')
                        ->color('warning'),
                    Action::make('deactivate')
                        ->label('Desactivar')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($record) {
                            $record->update(['status' => 'inactive']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Categoría')
                        ->modalDescription('¿Estás seguro de que quieres desactivar esta categoría? Esto la marcará como inactiva.')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn ($record) => ($record->status instanceof StatusEnum ? $record->status->value : $record->status) !== 'inactive'),
                    Action::make('activate')
                        ->label('Activar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($record) {
                            $record->update(['status' => 'active']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Activar Categoría')
                        ->modalDescription('¿Estás seguro de que quieres activar esta categoría?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn ($record) => ($record->status instanceof StatusEnum ? $record->status->value : $record->status) === 'inactive'),
                    DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Categoría')
                        ->modalDescription('¿Estás seguro de que quieres eliminar esta categoría? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar'),
                ])
                    ->label('Acciones')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('bulk_activate')
                        ->label('Activar Seleccionados')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->update(['status' => 'active']))
                        ->requiresConfirmation()
                        ->modalHeading('Activar Categorías')
                        ->modalDescription('¿Estás seguro de que quieres activar las categorías seleccionadas?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar'),
                    BulkAction::make('bulk_deactivate')
                        ->label('Desactivar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive']))
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Categorías')
                        ->modalDescription('¿Estás seguro de que quieres desactivar las categorías seleccionadas?')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar'),

                    DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Categorías')
                        ->modalDescription('¿Estás seguro de que quieres eliminar las categorías seleccionadas? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar')
                        ->modalCancelActionLabel('Cancelar'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategories::route('/'),
        ];
    }
}
