<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Talk;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TalkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TalkResource\RelationManagers;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Talk::getForm());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(function(Talk $record) {
                        return Str::of($record->abstract)->limit(40);
                    }),
                // Tables\Columns\TextColumn::make('abstract')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('speaker.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('speaker.avatar')
                    ->circular()
                    ->label('Avatar')
                    ->defaultImageUrl(function($record) {
                        return 'https://ui-avatars.com/api/?background=0DBABC&color=fff&name=' . urlencode($record->speaker->name);
                    }),
                Tables\Columns\BooleanColumn::make('new_talk')
                    ->toggle(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'submitted' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'submitted' => 'heroicon-m-chevron-right',
                        'approved' => 'heroicon-m-check-badge',
                        'rejected' => 'heroicon-m-backspace',
                    }),
                Tables\Columns\IconColumn::make('length')
                    ->icon(fn (string $state): string => match ($state) {
                        'lightning' => 'heroicon-m-bolt',
                        'normal' => 'heroicon-m-megaphone',
                        'keynote' => 'heroicon-m-key',
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
                Tables\Filters\TernaryFilter::make('new_talk'),
                Tables\Filters\SelectFilter::make('status')
                ->multiple()
                ->options([
                    'submitted' => 'Submitted',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
                Tables\Filters\SelectFilter::make('speaker')
                    ->relationship('speaker', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('has_avatar')
                    ->label('show only speakers with avatars')
                    ->toggle()
                    ->query(function($query){
                        return $query->whereHas('speaker', function(Builder $query){
                            $query->whereNotNull('avatar');
                        });
                    }),
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
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
        ];
    }
}
