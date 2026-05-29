<?php

namespace App\Filament\Resources\PartnerProfileResource\Pages;

use App\Filament\Resources\PartnerProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnerProfile extends EditRecord
{
    protected static string $resource = PartnerProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
