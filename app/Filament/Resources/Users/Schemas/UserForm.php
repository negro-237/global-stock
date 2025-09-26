<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Adresse Email')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (Get $get) => !$get('record'))
                    ->minLength(8)
                    ->maxLength(255)
                    ->same('passwordConfirmation'),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->label('Confirmer le mot de passe')
                    ->password()
                    ->dehydrated(false)
                    ->required(fn (Get $get) => !$get('record'))
                    ->minLength(8)
                    ->maxLength(255),
            ]);
    }
}
