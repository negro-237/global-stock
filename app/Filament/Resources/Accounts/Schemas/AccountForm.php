<?php

namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Schemas;
use Filament\Forms;
use Filament\Schemas\Components\Utilities\Get;

class AccountForm
{
    public static function configure(Schemas\Schema $schema): Schemas\Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Informations Principales de l\'entreprise')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Numéro de téléphone')
                            ->unique(ignoreRecord: true)
                            ->tel()
                            ->maxLength(20)
                    ])
                    ->columns(1),
                Schemas\Components\Section::make('Informations Utilisateur Principal')
                    ->schema([
                        Forms\Components\TextInput::make('user_name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_email')
                            ->label('Adresse Email')
                            ->required()
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('user_password')
                            ->label('Mot de passe')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (Get $get) => !$get('record'))
                            ->minLength(8)
                            ->maxLength(255)
                            ->same('user_passwordConfirmation'),
                        Forms\Components\TextInput::make('user_passwordConfirmation')
                            ->label('Confirmer le mot de passe')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn (Get $get) => !$get('record'))
                            ->minLength(8)
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->visibleOn('create')

            ])
            ->columns(1);
    }
}
