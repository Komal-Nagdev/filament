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
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Columns\ImageColumn;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Speaker::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->defaultImageUrl(function($record) {
                        return 'https://ui-avatars.com/api/?background=0DBABC&color=fff&name=' . urlencode($record->name);
                    }),
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
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar')
                            ->circular(),
                        Group::make()
                        ->columnSpan(2)
                        ->columns(2)
                        ->schema([
                            TextEntry::make('name'),
                            TextEntry::make('email'),
                            TextEntry::make('twitter_handle')
                                ->label('Twitter')
                                ->url(function($record) {
                                    return 'https://twitter.com/'.$record->twitter_handle;
                                })
                                ->getStateUsing(function($record) {
                                    if(isset($record->twitter_handle)) {
                                        return '@'.$record->twitter_handle;
                                    }
                                    return '-';
                                }),
                            TextEntry::make('has_spoken')
                                ->getStateUsing(function($record) {
                                    return $record->talks()->where('status', 'approved')->count() > 0 ? 'Previous Speaker' : 'Has Not Spoken Yet';
                                })
                                ->badge()
                                ->color(function($state){
                                    if($state === 'Previous Speaker') {
                                        return 'success';
                                    }
                                    return 'primary';

                                }),
                        ]),
                    ]),
                Section::make('Other Information')
                    ->schema([
                        TextEntry::make('bio')
                            ->html()
                            ->extraAttributes(['class' => 'prose dark:prose-invert']),
                        TextEntry::make('qualifications')
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ])
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
            // 'edit' => Pages\EditSpeaker::route('/{record}/edit'),
            'view' => Pages\ViewSpeaker::route('/{record}'),
        ];
    }
}
