<?php

namespace App\Filament\Resources\RegionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\RegionResource;

class EditRegion extends EditRecord
{
    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->before(function ($record, Actions\DeleteAction $action) {
                if($record->venue()->count() > 0) {
                Notification::make()
                    ->title('Errors!')
                    ->body("You can't delete this Region because it is already assigned to some venues.")
                    ->status('danger')
                    ->send();
                    $action->cancel();
                }
            }),
        ];
    }
}
