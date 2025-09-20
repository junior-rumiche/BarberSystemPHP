<?php

namespace App\Filament\Resources\Services;

use App\Filament\Resources\Services\Pages\ManageServices;
use App\Models\Service;
use App\Models\Category;
use App\ServiceStatusEnum;
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
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Servicios';

    protected static ?string $modelLabel = 'Servicio';

    protected static ?string $pluralModelLabel = 'Servicios';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                
                TextInput::make('name')
                    ->label('Nombre del Servicio')
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
                
                Toggle::make('status')
                    ->label('Estado Activo')
                    ->helperText('Activa o desactiva este servicio')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' || $state === true)
                    ->dehydrateStateUsing(fn ($state) => $state ? 'active' : 'inactive'),
                
                TextInput::make('price')
                    ->label('Precio (S/)')
                    ->numeric()
                    ->prefix('S/')
                    ->step(0.01)
                    ->minValue(0)
                    ->required(),
                
                TextInput::make('time_estimate_minutes')
                    ->label('Duración (minutos)')
                    ->numeric()
                    ->suffix('min')
                    ->minValue(1)
                    ->placeholder('Ej: 30'),
                
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->columnSpanFull(),
                
                FileUpload::make('cover_image_url')
                    ->label('Imagen del Servicio')
                    ->image()
                    ->directory('services')
                    ->visibility('public')
                    ->imagePreviewHeight('300')
                    ->panelLayout('integrated')
                    ->columnSpanFull(),
            ]);
    }

    public static function viewForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->disabled(),
                
                TextInput::make('name')
                    ->label('Nombre del Servicio')
                    ->disabled(),
                
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled(),
                
                Toggle::make('status')
                    ->label('Estado Activo')
                    ->disabled()
                    ->onColor('success')
                    ->offColor('danger')
                    ->formatStateUsing(fn ($state) => $state === 'active' || $state === true),
                
                TextInput::make('price')
                    ->label('Precio (S/)')
                    ->prefix('S/')
                    ->disabled(),
                
                TextInput::make('time_estimate_minutes')
                    ->label('Duración (minutos)')
                    ->suffix('min')
                    ->disabled(),
                
                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->disabled()
                    ->columnSpanFull(),
                
                FileUpload::make('cover_image_url')
                    ->label('Imagen del Servicio')
                    ->image()
                    ->disabled()
                    ->imagePreviewHeight('300')
                    ->panelLayout('integrated')
                    ->columnSpanFull(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Infolists\Components\Section::make('Información del Servicio')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('category.name')
                            ->label('Categoría')
                            ->inlineLabel()
                            ->badge()
                            ->color('info'),
                        
                        \Filament\Infolists\Components\TextEntry::make('name')
                            ->label('Nombre del Servicio')
                            ->inlineLabel(),
                        
                        \Filament\Infolists\Components\TextEntry::make('slug')
                            ->label('Slug')
                            ->inlineLabel()
                            ->copyable()
                            ->copyMessage('Slug copiado'),
                        
                        \Filament\Infolists\Components\TextEntry::make('status')
                            ->label('Estado')
                            ->inlineLabel()
                            ->badge()
                            ->color(fn ($state): string => match ($state instanceof ServiceStatusEnum ? $state->value : $state) {
                                'active' => 'success',
                                'inactive' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state): string => match ($state instanceof ServiceStatusEnum ? $state->value : $state) {
                                'active' => 'Activo',
                                'inactive' => 'Inactivo',
                                default => $state,
                            }),
                        
                        \Filament\Infolists\Components\TextEntry::make('price')
                            ->label('Precio (S/)')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($state): string => 'S/ ' . number_format($state, 2))
                            ->color('success')
                            ->weight('bold'),
                        
                        \Filament\Infolists\Components\TextEntry::make('time_estimate_minutes')
                            ->label('Duración (minutos)')
                            ->inlineLabel()
                            ->formatStateUsing(fn ($state): string => $state ? $state . ' min' : 'No especificado')
                            ->badge()
                            ->color('warning'),
                        
                        \Filament\Infolists\Components\TextEntry::make('description')
                            ->label('Descripción')
                            ->inlineLabel()
                            ->placeholder('Sin descripción')
                            ->columnSpanFull(),
                        
                        \Filament\Infolists\Components\ImageEntry::make('cover_image_url')
                            ->label('Imagen del Servicio')
                            ->height(300)
                            ->width(400)
                            ->placeholder('Sin imagen')
                            ->columnSpanFull(),
                        
                        \Filament\Infolists\Components\Fieldset::make('Información del Sistema')
                            ->schema([
                                \Filament\Infolists\Components\TextEntry::make('created_at')
                                    ->label('Creado')
                                    ->dateTime('d/m/Y H:i:s'),
                                
                                \Filament\Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Actualizado')
                                    ->dateTime('d/m/Y H:i:s'),
                            ])
                            ->columns(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('cover_image_url')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),
                
                TextColumn::make('name')
                    ->label('Servicio')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('PEN')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),
                
                TextColumn::make('formatted_time')
                    ->label('Duración')
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state): string => match ($state instanceof ServiceStatusEnum ? $state->value : $state) {
                        'active' => 'success',    // 🟢 Verde para Activo
                        'inactive' => 'danger',   // 🔴 Rojo para Inactivo
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state): string => match ($state instanceof ServiceStatusEnum ? $state->value : $state) {
                        'active' => 'Activo',
                        'inactive' => 'Inactivo',
                        default => $state,
                    })
                    ->searchable(),
                
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copiado')
                    ->toggleable(isToggledHiddenByDefault: true),
                
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
                
                \Filament\Tables\Filters\SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                
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
                        ->modalHeading('Ver Servicio')
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
                        ->modalHeading('Desactivar Servicio')
                        ->modalDescription('¿Estás seguro de que quieres desactivar este servicio? Esto lo marcará como inactivo.')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn ($record) => ($record->status instanceof ServiceStatusEnum ? $record->status->value : $record->status) !== 'inactive'),
                    Action::make('activate')
                        ->label('Activar')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($record) {
                            $record->update(['status' => 'active']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Activar Servicio')
                        ->modalDescription('¿Estás seguro de que quieres activar este servicio?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar')
                        ->visible(fn ($record) => ($record->status instanceof ServiceStatusEnum ? $record->status->value : $record->status) === 'inactive'),
                    DeleteAction::make()
                        ->label('Eliminar')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Servicio')
                        ->modalDescription('¿Estás seguro de que quieres eliminar este servicio? Esta acción no se puede deshacer.')
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
                        ->modalHeading('Activar Servicios')
                        ->modalDescription('¿Estás seguro de que quieres activar los servicios seleccionados?')
                        ->modalSubmitActionLabel('Sí, activar')
                        ->modalCancelActionLabel('Cancelar'),
                    BulkAction::make('bulk_deactivate')
                        ->label('Desactivar Seleccionados')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive']))
                        ->requiresConfirmation()
                        ->modalHeading('Desactivar Servicios')
                        ->modalDescription('¿Estás seguro de que quieres desactivar los servicios seleccionados?')
                        ->modalSubmitActionLabel('Sí, desactivar')
                        ->modalCancelActionLabel('Cancelar'),
                    DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Servicios')
                        ->modalDescription('¿Estás seguro de que quieres eliminar los servicios seleccionados? Esta acción no se puede deshacer.')
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
            'index' => ManageServices::route('/'),
        ];
    }
}
