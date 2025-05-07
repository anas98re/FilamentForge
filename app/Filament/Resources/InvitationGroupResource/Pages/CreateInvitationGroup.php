<?php

namespace App\Filament\Resources\InvitationGroupResource\Pages;

use App\Filament\Resources\InvitationGroupResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions; 

class CreateInvitationGroup extends CreateRecord
{
    protected static string $resource = InvitationGroupResource::class;

    /**
     * Override the default create form action to customize its properties, like the label.
     *
     * @return Actions\Action
     */
    protected function getCreateFormAction(): Actions\Action
    {
        // Call the parent method to get the default action, then customize its label.
        return parent::getCreateFormAction()->label(__('Add New Group')); // Change the label text here
    }

    /**
     * Alternatively, you can override the entire getFormActions method
     * if you need more control over all form actions (e.g., Create, Create and Create Another, Cancel).
     *
     * @return array
     */
    // protected function getFormActions(): array
    // {
    //     return [
    //         Actions\Action::make('create')
    //             ->label(__('Add New Group')) // Change the label
    //             ->submit('create')
    //             ->keyBindings(['mod+s']), // Example: Add keybinding
    //         // Include "Create and Create Another" action if enabled
    //         ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
    //         // Include the "Cancel" action
    //         $this->getCancelFormAction(),
    //     ];
    // }

    // Note:
    // To ensure the main "Create" button (usually in the header of the ListRecords page)
    // also reflects this label change, you would typically override the getHeaderActions()
    // method in the corresponding ListRecords page (e.g., ListInvitationGroups).
    // This class (CreateRecord) primarily handles the actions within the creation form itself.
}
