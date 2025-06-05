<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?string $modelLabel = 'Pengumuman';

    protected static ?string $pluralModelLabel = 'Pengumuman';

    protected static ?string $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Pengumuman')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Pengumuman')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label('Jenis Pengumuman')
                            ->required()
                            ->options([
                                'info' => 'Informasi',
                                'urgent' => 'Penting',
                                'event' => 'Acara',
                                'financial' => 'Keuangan'
                            ])
                            ->default('info'),
                    ]),

                Forms\Components\Section::make('Pengaturan Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Forms\Components\DateTimePicker::make('publish_date')
                            ->label('Tanggal Publikasi')
                            ->required()
                            ->default(now()),
                        Forms\Components\DateTimePicker::make('expire_date')
                            ->label('Tanggal Kadaluarsa')
                            ->after('publish_date'),
                    ])->columns(3),

                Forms\Components\Section::make('Notifikasi Telegram')
                    ->schema([
                        Forms\Components\Toggle::make('send_telegram')
                            ->label('Kirim ke Telegram')
                            ->default(false),
                        Forms\Components\Placeholder::make('telegram_info')
                            ->label('Info')
                            ->content('Pengumuman akan dikirim otomatis ke grup Telegram warga jika diaktifkan.')
                            ->hidden(fn(Forms\Get $get) => !$get('send_telegram')),
                    ]),

                Forms\Components\Section::make('Informasi Pembuat')
                    ->schema([
                        Forms\Components\Select::make('created_by')
                            ->label('Dibuat Oleh')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                return \Illuminate\Support\Facades\Auth::id();
                            })
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'info' => 'info',
                        'success' => 'urgent',
                        'warning' => 'event',
                        'primary' => 'financial',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'info' => 'Informasi',
                        'urgent' => 'Penting',
                        'event' => 'Acara',
                        'financial' => 'Keuangan',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('publish_date')
                    ->label('Tanggal Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expire_date')
                    ->label('Kadaluarsa')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Tidak ada'),
                Tables\Columns\IconColumn::make('send_telegram')
                    ->label('Telegram')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-airplane')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('publish_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'info' => 'Informasi',
                        'urgent' => 'Penting',
                        'event' => 'Acara',
                        'financial' => 'Keuangan'
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                Tables\Filters\TernaryFilter::make('send_telegram')
                    ->label('Kirim Telegram'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }
}
