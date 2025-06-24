<?php

namespace App\Filament\Widgets;

use App\Models\ActivityPhoto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestActivities extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Terbaru';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 6;

    public function table(Table $table): Table
    {
        return $table->query(
            ActivityPhoto::query()
                ->with(['uploader'])
                ->latest()
                ->limit(5)
        )
            ->columns([
                Tables\Columns\ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(100)
                    ->wrap(),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Dibuat oleh')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->url(fn(ActivityPhoto $record) => "/admin/activity-photos/{$record->id}/edit")
                    ->openUrlInNewTab(),
            ]);
    }
}
