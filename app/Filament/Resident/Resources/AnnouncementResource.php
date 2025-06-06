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

    protected static ?string $navigationGroup = 'Informasi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Pengumuman')
                            ->disabled(),
                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Pengumuman')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('type')
                            ->label('Jenis Pengumuman')
                            ->options([
                                'info' => 'Informasi',
                                'urgent' => 'Penting',
                                'event' => 'Acara',
                                'financial' => 'Keuangan'
                            ])
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('publish_date')
                            ->label('Tanggal Publikasi')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->select(['id', 'title', 'type', 'publish_date', 'created_at', 'excerpt'])
                    ->where('is_active', true)
                    ->where('publish_date', '<=', now())
            )
            ->defaultPaginationPageOption(25) // Add pagination
            ->poll('60s') // Reduce polling frequency
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
                Tables\Columns\TextColumn::make('publish_date')
                    ->label('Tanggal Publikasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('publish_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Pengumuman')
                    ->options([
                        'info' => 'Informasi',
                        'urgent' => 'Penting',
                        'event' => 'Acara',
                        'financial' => 'Keuangan'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for residents
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnnouncements::route('/'),
            'view' => Pages\ViewAnnouncement::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
