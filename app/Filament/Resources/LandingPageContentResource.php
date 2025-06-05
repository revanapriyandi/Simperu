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
                            'contact' => 'Contact Section',
                            'statistics' => 'Statistics Section',
                            'announcements' => 'Announcements Section',
                            'footer' => 'Footer Section',
                        ])
                        ->required()
                        ->reactive(),

                    Forms\Components\TextInput::make('title')
                        ->maxLength(255)
                        ->label('Title'),

                    Forms\Components\TextInput::make('subtitle')
                        ->maxLength(255)
                        ->label('Subtitle'),

                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->label('Description'),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Active'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->label('Sort Order'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Media')
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('hero_images')
                        ->collection('hero_images')
                        ->image()
                        ->multiple()
                        ->maxFiles(5)
                        ->reorderable()
                        ->visible(fn(Forms\Get $get) => $get('section') === 'hero')
                        ->label('Hero Images'),
                ])
                ->collapsed(),

            Forms\Components\Section::make('Action Button')
                ->schema([
                    Forms\Components\TextInput::make('button_text')
                        ->maxLength(100)
                        ->label('Button Text'),

                    Forms\Components\TextInput::make('button_link')
                        ->maxLength(255)
                        ->label('Button Link'),
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
                        'workflow' => 'primary',
                        'services' => 'purple',
                        'contact' => 'orange',
                        'statistics' => 'blue',
                        'announcements' => 'green',
                        'footer' => 'gray',
                        default => 'secondary',
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtitle')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('section')
                    ->options([
                        'hero' => 'Hero',
                        'about' => 'About',
                        'benefits' => 'Benefits',
                        'workflow' => 'Workflow',
                        'services' => 'Services',
                        'contact' => 'Contact',
                        'statistics' => 'Statistics',
                        'announcements' => 'Announcements',
                        'footer' => 'Footer',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    protected static function getContentBuilder(): Builder
    {
        return Builder::make('content')
            ->blocks([
                Block::make('feature_list')
                    ->label('Feature List')
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-star')
                                    ->label('Icon Class'),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Feature Title'),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2)
                                    ->label('Description'),
                                Forms\Components\Select::make('color')
                                    ->options([
                                        'blue' => 'Blue',
                                        'green' => 'Green',
                                        'red' => 'Red',
                                        'yellow' => 'Yellow',
                                        'purple' => 'Purple',
                                        'indigo' => 'Indigo',
                                    ])
                                    ->default('blue'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                    ]),

                Block::make('benefit_grid')
                    ->label('Benefits Grid')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        Forms\Components\Repeater::make('benefits')
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-check-circle')
                                    ->label('Icon Class'),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Benefit Title'),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2)
                                    ->label('Description'),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                    ]),

                Block::make('workflow_steps')
                    ->label('Workflow Steps')
                    ->icon('heroicon-o-arrow-path')
                    ->schema([
                        Forms\Components\Select::make('target_user')
                            ->options([
                                'admin' => 'Admin/Pengurus',
                                'resident' => 'Warga'
                            ])
                            ->required()
                            ->label('Target User'),
                        Forms\Components\Repeater::make('steps')
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-key')
                                    ->label('Icon Class'),
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Step Title'),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2)
                                    ->label('Step Description'),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['title'] ?? null),
                    ]),

                Block::make('service_categories')
                    ->label('Service Categories')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Repeater::make('categories')
                            ->schema([
                                Forms\Components\TextInput::make('category')
                                    ->required()
                                    ->label('Category Name'),
                                Forms\Components\TextInput::make('icon')
                                    ->placeholder('heroicon-o-cog-6-tooth')
                                    ->label('Icon Class'),
                                Forms\Components\Select::make('color')
                                    ->options([
                                        'blue' => 'Blue',
                                        'emerald' => 'Emerald',
                                        'red' => 'Red',
                                        'yellow' => 'Yellow',
                                        'purple' => 'Purple',
                                        'indigo' => 'Indigo',
                                        'pink' => 'Pink',
                                        'gray' => 'Gray',
                                    ])
                                    ->default('blue'),
                                Forms\Components\Repeater::make('items')
                                    ->simple(
                                        Forms\Components\TextInput::make('text')
                                            ->required()
                                            ->label('Service Item')
                                    )
                                    ->label('Service Items'),
                            ])
                            ->collapsible()
                            ->itemLabel(fn(array $state): ?string => $state['category'] ?? null),
                    ]),

                Block::make('statistics')
                    ->label('Statistics Display')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\Toggle::make('show_families')
                            ->label('Show Total Families')
                            ->default(true),
                        Forms\Components\Toggle::make('show_members')
                            ->label('Show Total Members')
                            ->default(true),
                        Forms\Components\Toggle::make('show_announcements')
                            ->label('Show Total Announcements')
                            ->default(true),
                        Forms\Components\Toggle::make('show_houses')
                            ->label('Show Active Houses')
                            ->default(true),
                    ]),

                Block::make('contact_info')
                    ->label('Contact Information')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->label('Email Address'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->label('Phone Number'),
                        Forms\Components\Textarea::make('address')
                            ->rows(2)
                            ->label('Address'),
                        Forms\Components\TextInput::make('whatsapp')
                            ->url()
                            ->label('WhatsApp Link'),
                    ]),

                Block::make('social_links')
                    ->label('Social Media Links')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Forms\Components\TextInput::make('facebook')
                            ->url()
                            ->label('Facebook URL'),
                        Forms\Components\TextInput::make('instagram')
                            ->url()
                            ->label('Instagram URL'),
                        Forms\Components\TextInput::make('twitter')
                            ->url()
                            ->label('Twitter URL'),
                    ]),
            ])
            ->collapsible();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPageContents::route('/'),
            'create' => Pages\CreateLandingPageContent::route('/create'),
            'view' => Pages\ViewLandingPageContent::route('/{record}'),
            'edit' => Pages\EditLandingPageContent::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Landing Page Content';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Landing Page Contents';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
