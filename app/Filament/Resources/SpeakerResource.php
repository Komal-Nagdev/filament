<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Speaker;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Tables\Columns\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SpeakerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpeakerResource\RelationManagers;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('twitter_handle')
                    ->required()
                    ->maxLength(255),
                Forms\Components\CheckboxList::make('qualifications')
                    ->columnSpanFull()
                    ->searchable()
                    ->bulkToggleable()
                    ->options([
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
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('twitter_handle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit' => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
