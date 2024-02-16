<?php

namespace App\Filament\Resources\TalkResource\Pages;

use App\Filament\Resources\TalkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Talk;
class ListTalks extends ListRecords
{
    protected static string $resource = TalkResource::class;


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Talks'. ' ' .Talk::query()->count()),
            'approved' => Tab::make('Approved'. ' ' .Talk::query()->where('status', 'approved')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved')),
            'rejected' => Tab::make('Rejected'. ' ' .Talk::query()->where('status', 'rejected')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected')),
            'submitted' => Tab::make('Submitted'. ' ' .Talk::query()->where('status', 'submitted')->count())
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
