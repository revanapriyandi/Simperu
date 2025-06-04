<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageContentResource\Pages;
use App\Models\LandingPageContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;

class LandingPageContentResource extends Resource
{
    protected static ?string $model = LandingPageContent::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Landing Page Content';
    protected static ?string $navigationGroup = 'Website Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\Select::make('section')
                        ->options([
                            'hero' => 'Hero Section',
                            'about' => 'About Section',
                            'benefits' => 'Benefits Section',
                            'workflow' => 'Workflow Section',
                            'services' => 'Services Section',
                            'testimonials' => 'Testimonials Section',
                            'contact' => 'Contact Section',
                            'footer' => 'Footer Section',
                        ])
                        ->required()
                        ->reactive(),

                    Forms\Components\TextInput::make('title')->maxLength(255),
                    Forms\Components\TextInput::make('subtitle')->maxLength(255),
                    Forms\Components\Textarea::make('description')->rows(3),
                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])
                ->columns(2),

            Forms\Components\Section::make('Media')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('images')
                        ->collection('images')
                        ->image()
                        ->maxFiles(1),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('hero_images')
                        ->collection('hero_images')
                        ->image()
                        ->multiple()
                        ->maxFiles(5)
                        ->reorderable()
                        ->visible(fn(Forms\Get $get) => $get('section') === 'hero'),
                ])
                ->collapsed(),

            Forms\Components\Section::make('Action Button')
                ->schema([
                    Forms\Components\TextInput::make('button_text')->maxLength(100),
                    Forms\Components\TextInput::make('button_link')->url()->maxLength(255),
                ])
                ->columns(2)
                ->collapsed(),

            Forms\Components\Section::make('Dynamic Content')
                ->schema([
                    self::getContentBuilder(),
                ])
                ->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'hero' => 'success',
                        'about' => 'info',
                        'benefits' => 'warning',
                        'services' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')->limit(50)->searchable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section'),
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->reorderable('sort_order');
    }

    protected static function getContentBuilder(): Builder
    {
        return Builder::make('content')
            ->blocks([
                Block::make('feature_list')
                    ->label('Feature List')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('icon')->placeholder('heroicon-o-star'),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\Textarea::make('description')->rows(2),
                                Forms\Components\TextInput::make('color')->default('blue'),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),
                Block::make('benefit_grid')
                    ->label('Benefits Grid')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\TextInput::make('icon')->placeholder('heroicon-o-check-circle'),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\Textarea::make('description')->rows(2),
                            ])
                            ->columns(3)
                            ->collapsible(),
                    ]),
                Block::make('workflow_steps')
                    ->label('Workflow Steps')
                    ->schema([
                        Forms\Components\Select::make('target_user')
                            ->options(['admin' => 'Admin/Pengurus', 'resident' => 'Warga'])
                            ->required(),
                        Forms\Components\Repeater::make('steps')
                            ->schema([
                                Forms\Components\TextInput::make('icon')->placeholder('heroicon-o-key'),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\Textarea::make('description')->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),
                Block::make('service_categories')
                    ->label('Service Categories')
                    ->schema([
                        Forms\Components\Repeater::make('categories')
                            ->schema([
                                Forms\Components\TextInput::make('category')->required(),
                                Forms\Components\TextInput::make('icon')->placeholder('heroicon-o-cog-6-tooth'),
                                Forms\Components\Select::make('color')
                                    ->options([
                                        'blue' => 'Blue',
                                        'emerald' => 'Emerald',
                                        'red' => 'Red',
                                        'yellow' => 'Yellow',
                                        'purple' => 'Purple',
                                    ])
                                    ->default('blue'),
                                Forms\Components\Repeater::make('items')
                                    ->simple(Forms\Components\TextInput::make('text')->required()),
                            ])
                            ->collapsible(),
                    ]),
            ])
            ->collapsible();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPageContents::route('/'),
            'create' => Pages\CreateLandingPageContent::route('/create'),
            'edit' => Pages\EditLandingPageContent::route('/{record}/edit'),
        ];
    }
}
