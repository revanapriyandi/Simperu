<?php

namespace App\Filament\Resident\Widgets;

use App\Models\ComplaintLetter;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentComplaintLettersWidget extends BaseWidget
{
    protected static ?string $heading = 'Surat Pengaduan Terbaru';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ComplaintLetter::query()
                    ->where('submitted_by', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('No. Surat')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'submitted',
                        'info' => 'in_review',
                        'primary' => 'in_progress',
                        'success' => 'resolved',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'submitted' => 'Diajukan',
                        'in_review' => 'Ditinjau',
                        'in_progress' => 'Diproses',
                        'resolved' => 'Selesai',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('approval_status')
                    ->label('Persetujuan')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn (ComplaintLetter $record): string => route('filament.resident.resources.complaint-letters.view', $record)),
            ])
            ->emptyStateHeading('Belum ada surat pengaduan')
            ->emptyStateDescription('Klik tombol "Ajukan Surat Pengaduan" untuk membuat surat baru')
            ->emptyStateIcon('heroicon-o-document-text');
    }
}
