<?php

namespace App\Filament\Resident\Resources;

use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resident\Resources\AnnouncementResource\Pages;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?string $modelLabel = 'Pengumuman';

    protected static ?string $pluralModelLabel = 'Pengumuman';

    protected static ?string $navigationGroup = 'Informasi & Komunikasi';

    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul')
                            ->disabled(),
                        Forms\Components\Select::make('type')
                            ->label('Jenis')
                            ->options([
                                'info' => 'Informasi',
                                'urgent' => 'Penting',
                                'event' => 'Acara',
                                'financial' => 'Keuangan',
                            ])
                            ->disabled(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Pengumuman')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('publish_date')
                            ->label('Tanggal Publikasi')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Announcement::query()
                    ->where('is_active', true)
                    ->where('publish_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('expire_date')
                            ->orWhere('expire_date', '>', now());
                    })
                    ->latest('publish_date')
            )
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->label('Judul')
                        ->weight('bold')
                        ->searchable()
                        ->limit(60),
                    Tables\Columns\Layout\Grid::make(3)
                        ->schema([
                            Tables\Columns\BadgeColumn::make('type')
                                ->label('Jenis')
                                ->colors([
                                    'info' => 'info',
                                    'urgent' => 'danger',
                                    'event' => 'warning',
                                    'financial' => 'success',
                                ])
                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                    'info' => '📢 Informasi',
                                    'urgent' => '🚨 Penting',
                                    'event' => '🎉 Acara',
                                    'financial' => '💰 Keuangan',
                                    default => $state,
                                }),
                            Tables\Columns\TextColumn::make('publish_date')
                                ->label('Dipublikasi')
                                ->since()
                                ->icon('heroicon-m-calendar'),
                            Tables\Columns\IconColumn::make('is_active')
                                ->label('Status')
                                ->boolean()
                                ->trueIcon('heroicon-o-eye')
                                ->falseIcon('heroicon-o-eye-slash')
                                ->trueColor('success')
                                ->falseColor('gray'),
                        ]),
                    Tables\Columns\TextColumn::make('content')
                        ->label('Isi')
                        ->html()
                        ->limit(120)
                        ->wrap(),
                ]),
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 1,
            ])
            ->defaultSort('publish_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'info' => 'Informasi',
                        'urgent' => 'Penting',
                        'event' => 'Acara',
                        'financial' => 'Keuangan',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Baca')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => $record->title)
                    ->modalWidth('lg'),
            ])
            ->bulkActions([
                // No bulk actions for residents
            ])
            ->emptyStateIcon('heroicon-o-megaphone')
            ->emptyStateHeading('Belum ada pengumuman')
            ->emptyStateDescription('Pengumuman dari pengurus akan muncul di sini');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Residents cannot create announcements
    }

    public static function canEdit($record): bool
    {
        return false; // Residents cannot edit announcements
    }

    public static function canDelete($record): bool
    {
        return false; // Residents cannot delete announcements
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_active', true)
            ->where('publish_date', '>=', now()->subDays(7))
            ->where('publish_date', '<=', now())
            ->count();
        
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
