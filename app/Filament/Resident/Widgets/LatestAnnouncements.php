<?php

namespace App\Filament\Resident\Widgets;

use App\Models\Announcement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAnnouncements extends BaseWidget
{
    protected static ?string $heading = 'Pengumuman Terbaru';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Announcement::query()
                    ->where('is_active', true)
                    ->where('publish_date', '<=', now())
                    ->latest('publish_date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(60)
                    ->weight('bold'),
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
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(
                        fn(Announcement $record): string =>
                        route('filament.resident.resources.announcements.view', $record)
                    ),
            ])
            ->paginated(false);
    }
}
