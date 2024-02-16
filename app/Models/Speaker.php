<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyMany;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    const QUALIFICATIONS = [
        'busineess-leader' => 'Businness Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First time Speaker',
        'hometown-hero' => 'HomeTown Hero',
        'humanitarian' => 'Works in Humanitarian Field',
        'laracasts-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Large Twitter Following',
        'youtube-influencer' => 'Large Youtube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective',
    ];

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public function talks(): HasMany
    {
        return $this->hasMany(Talk::class);
    }

    public static function getForm()
    {
        return
        [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('avatar')
                ->image()
                ->avatar()
                ->imageEditor()
                ->circleCropper()
                ->maxSize(1024 * 1024 * 10),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\RichEditor::make('bio')
                ->maxLength(65535)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('twitter_handle')
                ->maxLength(255),
            Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options(self::QUALIFICATIONS)->columns(3),
        ];
    }
}
