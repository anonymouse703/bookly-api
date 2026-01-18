<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Enums\Service\Status;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('provider_id')
                    ->relationship('provider', 'name', function ($query) {
                        $query->where('role', 'provider'); 
                    })
                    ->required()
                    ->searchable()  
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('₱'),
                TextInput::make('duration')
                    ->numeric(),
                Select::make('status')
                    ->options(Status::class)
                    ->default('available')
                    ->required(),
            ]);
    }
}
