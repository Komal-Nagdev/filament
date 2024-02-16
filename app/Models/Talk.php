<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Forms;

class Talk extends Model
{
    use HasFactory;

    const TALK_LENGTH = [
        'lightning' => 'Lightning - 15 Minutes',
        'normal' => 'Normal - 30 Minutes',
        'keynote' => 'Keynote'
    ];

    const TALK_STATUS = [
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected'
    ];

    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm()
    {
        return
        [
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('speaker_id')
                ->relationship('speaker', 'name')
                ->required(),
            Forms\Components\Select::make('length')
                ->options(self::TALK_LENGTH)
                ->required(),
            Forms\Components\Select::make('status')
                ->options(self::TALK_STATUS)
                ->required(),
            Forms\Components\Textarea::make('abstract')
                ->rows(4)
                ->columnSpanFull()
                ->required()
                ->maxLength(255),
        ];
    }

    public function approve() : void 
    {
        $this->status = 'approved';
        $this->save();
    }

    public function reject() : void 
    {
        $this->status = 'rejected';
        $this->save();
    }

}
