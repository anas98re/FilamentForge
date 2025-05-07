<?php

namespace App\Filament\Resources\InvitationGroupResource\Pages;

use App\Filament\Resources\InvitationGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvitationGroups extends ListRecords
{
    protected static string $resource = InvitationGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label(__('Register New Group')), 
        ];
    }
}
