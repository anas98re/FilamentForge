<?php

namespace App\Filament\Resources\InvitationGroupResource\Pages;

use App\Filament\Resources\InvitationGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvitationGroup extends EditRecord
{
    protected static string $resource = InvitationGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
