<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemoryResource\Pages;
use App\Models\Memory;
use App\Forms\Components\Base64Uploader;
use App\Services\ImageKitService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MemoryResource extends Resource
{
    protected static ?string $model = Memory::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Memories Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('memory_date')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Base64Uploader::make('image_url')
                    ->label('Upload Gambar Memory')
                    ->required()
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state) || Str::startsWith($state, ['http://', 'https://'])) {
                            return $state;
                        }
                        return ImageKitService::uploadImage($state, '/memories') ?? null;
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')->square(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('memory_date')->date()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemories::route('/'),
            'create' => Pages\CreateMemory::route('/create'),
            'edit' => Pages\EditMemory::route('/{record}/edit'),
        ];
    }
}