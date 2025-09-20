<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Resources\Skills\SkillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSkills extends ManageRecords
{
    protected static string $resource = SkillResource::class;

    protected static ?string $title = 'Habilidades';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Habilidad')
                ->createAnother(false),
        ];
    }
}