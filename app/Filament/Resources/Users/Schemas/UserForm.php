<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\User\Role;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Full Name')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->default(fn() => Str::password(
                        length: 12,
                        letters: true,
                        numbers: true,
                        symbols: true,
                        spaces: false
                    ))
                    ->minLength(8)
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(string $operation): bool => $operation === 'create')
                    ->revealable()
                    ->rule(
                        Password::min(8)
                            ->letters()
                            ->numbers()
                            ->symbols()
                            ->mixedCase()
                    )
                    ->inlineSuffix()
                    ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500),
                Select::make('role')
                    ->label('Role')
                    ->options(Role::class)
                    ->default(Role::Customer)
                    ->required(),
                Fieldset::make('Contact Information')
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(20),
                        TextInput::make('mobile')
                            ->label('Mobile')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Textarea::make('address')
                    ->label('Address')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }
}
