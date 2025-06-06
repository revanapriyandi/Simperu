<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use App\Models\ComplaintLetter;
use App\Services\ComplaintLetterPdfService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CreateComplaintLetter extends CreateRecord
{
    protected static string $resource = ComplaintLetterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $pdfService = new ComplaintLetterPdfService();

        // Add required fields
        $data['user_id'] = Auth::id();
        $data['submitted_by'] = Auth::id();
        $data['letter_number'] = $pdfService->generateLetterNumber();
        $data['letter_date'] = Carbon::now()->format('Y-m-d');
        $data['submitted_at'] = Carbon::now();
        $data['recipient'] = 'Pengurus Perumahan Villa Windaro Permai';
        $data['description'] = $data['content']; // Map content to description
        $data['status'] = 'submitted';

        return $data;
    }

    protected function afterCreate(): void
    {
        $pdfService = new ComplaintLetterPdfService();

        try {
            // Generate PDF
            $pdfPath = $pdfService->generatePdf($this->record);

            // Update record with PDF path
            $this->record->update(['pdf_path' => $pdfPath]);

            // Show success notification
            Notification::make()
                ->title('Pengaduan berhasil dibuat!')
                ->body('Surat pengaduan Anda telah dibuat dan dapat diunduh.')
                ->success()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('download')
                        ->label('Unduh Surat PDF')
                        ->url(route('complaint.download-pdf', $this->record->id))
                        ->openUrlInNewTab()
                ])
                ->send();
        } catch (\Exception $e) {
            // Log error but don't fail the creation
            Log::error('Failed to generate complaint PDF: ' . $e->getMessage());

            Notification::make()
                ->title('Pengaduan berhasil dibuat')
                ->body('Namun terjadi kesalahan saat membuat PDF. Silakan hubungi admin.')
                ->warning()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
