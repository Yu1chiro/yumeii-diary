<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Models\Story;
use App\Forms\Components\Base64Uploader;
use App\Services\ImageKitService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Memories Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Story Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Story::class, 'slug', ignoreRecord: true),
                        Forms\Components\Textarea::make('excerpt')
                            ->maxLength(65535)
                            ->columnSpanFull()
                            ->helperText('Ringkasan singkat yang muncul di halaman depan.'),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Publishing')
                    ->schema([
                        // Buat grid untuk membatasi ukuran Base64Uploader
                        Forms\Components\Grid::make(3) // Membagi area menjadi 3 kolom
                            ->schema([
                                Base64Uploader::make('featured_image_url')
                                    ->label('Cover Cerita')
                                    ->required()
                                    ->dehydrateStateUsing(function ($state) {
                                        if (empty($state) || Str::startsWith($state, ['http://', 'https://'])) {
                                            return $state;
                                        }
                                        return ImageKitService::uploadImage($state, '/stories') ?? null;
                                    })
                                    ->columnSpan(1), // Hanya memakai 1 dari 3 kolom agar tidak terlalu lebar
                            ]),

                        Forms\Components\Repeater::make('images')
                            ->relationship('images')
                            ->label('Foto Pendukung (Carousel)')
                            ->schema([
                                // Grid di dalam repeater agar uploader tampil compact
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Base64Uploader::make('image_url')
                                            ->label('Upload Foto')
                                            ->required()
                                            ->dehydrateStateUsing(function ($state) {
                                                if (empty($state) || Str::startsWith($state, ['http://', 'https://'])) {
                                                    return $state;
                                                }
                                                return ImageKitService::uploadImage($state, '/stories/carousel') ?? null;
                                            })
                                            ->columnSpan(1),
                                    ])
                            ])
                            ->grid(2) // Menampilkan repeater items secara berdampingan (2 kolom)
                            ->collapsible()
                            ->addActionLabel('Tambah Slide Foto')
                            ->itemLabel(fn (array $state): ?string => 'Slide Tambahan')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_published')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image_url')->label('Image')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}