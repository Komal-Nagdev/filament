<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public static function getForm()
    {
        return
        [
            Section::make('Conference Details')
           // ->description('Provide some basic information about the conference')
            ->icon('heroicon-o-information-circle')
            ->collapsible()
            ->columns(2)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Conference Name')
                    ->columnSpanFull()
                    ->required()
                    ->placeholder("Enter Conference Name")
                    ->maxLength(60),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->required(),
                Fieldset::make('Status')
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ])
                        ->required(),
                    Forms\Components\Toggle::make('is_published')
                        ->default(false)
                        ->inline()
                        ->required(),
                ]),
            ]),
            Section::make('Location')
            ->icon('heroicon-o-map-pin')
            ->collapsible()
            ->columns(2)
            ->schema([
                Forms\Components\Select::make('region_id')
                    ->live()
                    ->relationship('region', 'name')
                    ->required(),
                Forms\Components\Select::make('venue_id')
                    ->searchable()
                    ->required()
                    ->preload()
                    ->relationship('venue', 'name', modifyQueryUsing: function(Builder $query, Forms\Get $get) {
                        return $query->where('region_id' , $get('region_id'));
                    }),
            ]),
            Section::make('Speakers')
            ->icon('heroicon-o-speaker-wave')
            ->collapsible()
            ->columns(3)
            ->schema([
                Forms\Components\CheckboxList::make('Speakers')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->required()
                ->relationship('speakers', 'name')
                ->options(Speaker::all()->pluck('name', 'id'))->columns(3),
            ]),
        ];
    }
}
