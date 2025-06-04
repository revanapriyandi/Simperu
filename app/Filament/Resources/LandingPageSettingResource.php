<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandingPageSettingResource\Pages;
use App\Models\LandingPageSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LandingPageSettingResource extends Resource
{
    protected static ?string $model = LandingPageSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Landing Page Settings';
    protected static ?string $navigationGroup = 'Website Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Setting Details')
                ->schema([
                    Forms\Components\TextInput::make('key')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('label')->required()->maxLength(255),
                    Forms\Components\Select::make('group')
                        ->options([
                            'general' => 'General',
                            'contact' => 'Contact Information',
                            'social' => 'Social Media',
                            'seo' => 'SEO Settings',
                            'branding' => 'Branding',
                        ])
                        ->required()
                        ->default('general'),
                    Forms\Components\Select::make('type')
                        ->options([
                            'text' => 'Text',
                            'textarea' => 'Textarea',
                            'boolean' => 'Boolean',
                            'integer' => 'Number',
                            'url' => 'URL',
                            'email' => 'Email',
                            'image' => 'Image',
                        ])
                        ->required()
                        ->reactive()
                        ->default('text'),
                    Forms\Components\Textarea::make('description')->rows(2),
                ])
                ->columns(2),

            Forms\Components\Section::make('Setting Value')
                ->schema([
                    Forms\Components\TextInput::make('value')
                        ->visible(fn(Forms\Get $get) => in_array($get('type'), ['text', 'integer']))
                        ->numeric(fn(Forms\Get $get) => $get('type') === 'integer'),
                    Forms\Components\Textarea::make('value')
                        ->rows(4)
                        ->visible(fn(Forms\Get $get) => $get('type') === 'textarea'),
                    Forms\Components\TextInput::make('value')
                        ->url()
                        ->visible(fn(Forms\Get $get) => $get('type') === 'url'),
                    Forms\Components\TextInput::make('value')
                        ->email()
                        ->visible(fn(Forms\Get $get) => $get('type') === 'email'),
                    Forms\Components\Toggle::make('boolean_value')
                        ->visible(fn(Forms\Get $get) => $get('type') === 'boolean')
                        ->reactive()
                        ->afterStateUpdated(fn(Forms\Set $set, $state) => $set('value', $state ? '1' : '0')),
                    Forms\Components\FileUpload::make('value')
                        ->image()
                        ->directory('landing-page')
                        ->visibility('public')
                        ->visible(fn(Forms\Get $get) => $get('type') === 'image'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('label')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('group')->badge()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('value')->limit(50),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group'),
                Tables\Filters\SelectFilter::make('type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLandingPageSettings::route('/'),
            'create' => Pages\CreateLandingPageSetting::route('/create'),
            'edit' => Pages\EditLandingPageSetting::route('/{record}/edit'),
        ];
    }
}
