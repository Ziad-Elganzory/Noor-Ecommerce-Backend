<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Date;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('User Name')
                    ->required()
                    ->placeholder('John Doe'),
                TextInput::make('email')
                    ->label('Email Address')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord:true)
                    ->placeholder('email@example.com'),
                TextInput::make('password')
                    ->label('Password')
                    ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->password()
                    ->dehydrated(fn($state)=>filled($state))
                    ->placeholder('********'),
                DatePicker::make('birth_date')
                    ->label('Birth Date')
                    ->placeholder('MM/DD/YYYY'),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Verified At')
                    ->default(Date::now())
                    ->nullable()
                    ->placeholder('MM/DD/YYYY HH:MM'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('User Name'),
                TextColumn::make('email')
                    ->searchable()
                    ->label('Email Address'),
                TextColumn::make('birth_date')
                    ->label('Birth Date'),
                TextColumn::make('age')
                    ->label('Age')
                    ->getStateUsing(fn($record) => $record->getAge())
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Email Verified At'),
                TextColumn::make('created_at')
                    ->label('Created At'),
                TextColumn::make('updated_at')
                    ->label('Updated At'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
