<?php

namespace App\Filament\Resources\InvitationGroupResource\Pages;

use App\Filament\Resources\InvitationGroupResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions; // استيراد

class CreateInvitationGroup extends CreateRecord
{
    protected static string $resource = InvitationGroupResource::class;

    // يمكنك تجاوز ميثود getCreateFormAction
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()->label(__('Add New Group')); // تغيير النص هنا
    }

    // أو يمكنك تجاوز ميثود getFormActions
    // protected function getFormActions(): array
    // {
    //     return [
    //         Actions\Action::make('create')
    //             ->label(__('Add New Group')) // تغيير النص
    //             ->submit('create')
    //             ->keyBindings(['mod+s']),
    //         ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
    //         $this->getCancelFormAction(),
    //     ];
    // }

    // للتأكد من أن زر الإنشاء الرئيسي في الأعلى يحصل على نفس التغيير
    // هذا عادةً ما يكون في صفحة ListRecords
}
