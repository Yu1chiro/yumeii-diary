<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use App\Forms\Components\Base64Uploader;
use App\Services\ImageKitService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Media';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gallery Info')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('layout_style')
                            ->options([
                                'standard' => 'Standard',
                                'grid-2x2' => 'Grid 2x2',
                                'grid-3x3' => 'Grid 3x3',
                                'polaroid' => 'Polaroid Memanjang',
                                'photobooth' => 'Photobooth Premium',
                            ])
                            ->required()
                            ->default('polaroid')
                            ->helperText('Pilih variasi layout estetik untuk frontend.'),
                    ])->columns(2),

                Forms\Components\Section::make('Upload Multiple Images')
                    ->schema([
                        Forms\Components\Repeater::make('images_url')
                            ->label('Daftar Gambar')
                            ->schema([
                                Base64Uploader::make('url')
                                    ->label('Pilih Gambar')
                                    ->required()
                                    ->dehydrateStateUsing(function ($state) {
                                        if (empty($state) || Str::startsWith($state, ['http://', 'https://'])) {
                                            return $state;
                                        }
                                        return ImageKitService::uploadImage($state, '/galleries') ?? null;
                                    })
                            ])
                            ->grid(2) // Agar tampilannya jadi 2 kolom (Kiri Kanan) supaya lebih rapi
                            ->addActionLabel('Tambah Gambar Baru')
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 'Foto Galeri'),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Karena bentuknya JSON Array, kita pakai View kustom atau cara sederhana ini:
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('layout_style')->colors(['primary']),
                Tables\Columns\TextColumn::make('images_url')
                    ->label('Total Foto')
                    ->formatStateUsing(fn ($state) => count((array) $state) . ' Foto'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}