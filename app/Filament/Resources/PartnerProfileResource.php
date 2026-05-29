<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerProfileResource\Pages;
use App\Models\PartnerProfile;
use App\Forms\Components\Base64Uploader;
use App\Services\ImageKitService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PartnerProfileResource extends Resource
{
    protected static ?string $model = PartnerProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Personal Notes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pasangan (Yuii / Meii)')
                    ->required(),
                
                Base64Uploader::make('icon_url')
                    ->label('Thumbnail Icon (Foto Profil)')
                    ->dehydrateStateUsing(function ($state) {
                        if (empty($state) || Str::startsWith($state, ['http://', 'https://'])) return $state;
                        return ImageKitService::uploadImage($state, '/profiles') ?? null;
                    }),

                Forms\Components\TagsInput::make('favorite_music')
                    ->label('Musik Favorit')
                    ->placeholder('Ketik judul lalu Enter')
                    ->helperText('Contoh: Pop, Yorushika, Kaze wo Hamu'),

                Forms\Components\TagsInput::make('traits')
                    ->label('Karakteristik / Sifat')
                    ->placeholder('Ketik sifat lalu Enter')
                    ->helperText('Contoh: Lucu, Penyayang, Suka Makan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon_url')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerProfiles::route('/'),
            'create' => Pages\CreatePartnerProfile::route('/create'),
            'edit' => Pages\EditPartnerProfile::route('/{record}/edit'),
        ];
    }
}