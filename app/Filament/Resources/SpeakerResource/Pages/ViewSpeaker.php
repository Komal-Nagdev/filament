<?php

namespace App\Filament\Resources\SpeakerResource\Pages;

use Filament\Actions;
use App\Models\Speaker;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\SpeakerResource;

class ViewSpeaker extends ViewRecord
{
    protected static string $resource = SpeakerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->slideOver()
                ->form(Speaker::getForm()),
            Actions\DeleteAction::make()
            ->before(function ($record, Actions\DeleteAction $action) {
                if($record->talks()->count() > 0) {
                Notification::make()
                    ->title('Errors!')
                    ->body("You can't delete this speaker because some talks are assigned.")
                    ->status('danger')
                    ->send();
                    $action->cancel();
                }
            }),
        ];
    }
}
