<?php

namespace App\Filament\Resources\VenueResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VenueResource;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->before(function ($record, Actions\DeleteAction $action) {
                if($record->conferences()->count() > 0) {
                Notification::make()
                    ->title('Errors!')
                    ->body("You can't delete this Venue because it is already booked for some conferences.")
                    ->status('danger')
                    ->send();
                    $action->cancel();
                }
            }),
        ];
    }
}
