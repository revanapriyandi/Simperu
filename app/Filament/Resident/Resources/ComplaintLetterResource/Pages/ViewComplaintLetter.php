<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use App\Services\ComplaintLetterPdfService;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class ViewComplaintLetter extends ViewRecord
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn() => $this->record->status === 'submitted'),

            Actions\Action::make('download_pdf')
                ->label('Unduh Surat PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn() => !empty($this->record->pdf_path))
                ->url(fn() => route('complaint.download-pdf', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('regenerate_pdf')
                ->label('Buat Ulang PDF')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Buat Ulang Surat PDF')
                ->modalDescription('Apakah Anda yakin ingin membuat ulang surat PDF? File PDF lama akan diganti.')
                ->action(function () {
                    try {
                        $pdfService = new ComplaintLetterPdfService();

                        // Delete old PDF if exists
                        if ($this->record->pdf_path) {
                            Storage::disk('public')->delete($this->record->pdf_path);
                        }

                        // Generate new PDF
                        $pdfPath = $pdfService->generatePdf($this->record);

                        // Update record
                        $this->record->update(['pdf_path' => $pdfPath]);

                        Notification::make()
                            ->title('PDF berhasil dibuat ulang!')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal membuat PDF')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Add status widget if needed
        ];
    }
}
