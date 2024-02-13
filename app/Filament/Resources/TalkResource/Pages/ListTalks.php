<?php

namespace App\Filament\Resources\TalkResource\Pages;

use App\Filament\Resources\TalkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Talks'),
            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved')),
            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
            'submitted' => Tab::make('Submitted')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'submitted')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
