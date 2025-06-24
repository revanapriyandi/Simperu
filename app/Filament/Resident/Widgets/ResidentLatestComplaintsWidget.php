<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class ResidentLatestComplaintsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ComplaintLetter::query()
                    ->where('submitted_by', Auth::id())
                    ->latest('submitted_at')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('No. Surat')
                    ->searchable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 40) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'submitted',
                        'blue' => 'in_review',
                        'yellow' => 'in_progress',
                        'green' => 'resolved',
                        'red' => 'closed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'submitted' => 'Diajukan',
                        'in_review' => 'Ditinjau',
                        'in_progress' => 'Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup',
                        default => $state,
                    }),

                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Persetujuan')
                    ->colors([
                        'orange' => 'pending',
                        'green' => 'approved',
                        'red' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(ComplaintLetter $record): string => route('filament.resident.resources.complaint-letters.view', $record)),

                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->visible(fn(ComplaintLetter $record): bool => $record->approval_status === 'approved')
                    ->url(fn(ComplaintLetter $record): string => route('resident.download-letter', $record))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('create')
                    ->label('Buat Pengaduan Baru')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(route('filament.resident.resources.complaint-letters.create')),

                Tables\Actions\Action::make('view_all')
                    ->label('Lihat Semua')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(route('filament.resident.resources.complaint-letters.index')),
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Pengaduan Terbaru Anda';
    }
}
