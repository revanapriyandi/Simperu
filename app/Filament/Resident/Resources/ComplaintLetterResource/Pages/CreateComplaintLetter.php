<?php

namespace App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

use App\Filament\Resident\Resources\ComplaintLetterResource;
use App\Models\ComplaintLetter;
use App\Models\LetterCategory;
use App\Services\DigitalSignatureService;
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
        // Remove the agreement checkbox from data
        unset($data['agreement']);
        
        // Generate letter number if not set
        if (empty($data['letter_number']) && !empty($data['category_id'])) {
            $category = LetterCategory::find($data['category_id']);
            if ($category) {
                $data['letter_number'] = $this->generateLetterNumber($category->code);
            }
        }

        // Add required fields
        $data['user_id'] = Auth::id();
        $data['submitted_by'] = Auth::id();
        $data['letter_date'] = Carbon::now()->format('Y-m-d');
        $data['submitted_at'] = Carbon::now();
        $data['description'] = strip_tags($data['content']); // Clean HTML for description
        $data['status'] = 'submitted';
        $data['approval_status'] = 'pending';
        
        // Set default recipient if not provided
        if (empty($data['recipient'])) {
            $data['recipient'] = 'Pengurus Perumahan Villa Windaro Permai';
        }

        return $data;
    }
    
    private function generateLetterNumber($categoryCode): string
    {
        $count = ComplaintLetter::whereHas('category', function($query) use ($categoryCode) {
            $query->where('code', $categoryCode);
        })->whereYear('created_at', now()->year)->count() + 1;
        
        return sprintf('%03d/%s/PVWP/%s/%s', $count, $categoryCode, strtoupper(now()->format('m')), now()->year);
    }

    protected function afterCreate(): void
    {
        $signatureService = new DigitalSignatureService();

        try {
            // Generate initial PDF (unsigned)
            $pdfPath = $signatureService->generateSignedPDF($this->record);

            // Update record with PDF path
            $this->record->update(['pdf_path' => $pdfPath]);

            // Show success notification
            Notification::make()
                ->title('🎉 Pengaduan berhasil diajukan!')
                ->body('Surat pengaduan Anda telah dibuat dengan nomor ' . $this->record->letter_number . '. Menunggu persetujuan dari admin.')
                ->success()
                ->duration(7000)
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->label('Lihat Detail')
                        ->url($this->getResource()::getUrl('view', ['record' => $this->record]))
                        ->markAsRead(),
                ])
                ->send();
        } catch (\Exception $e) {
            // Log error but don't fail the creation
            Log::error('Failed to generate complaint PDF: ' . $e->getMessage());

            Notification::make()
                ->title('✅ Pengaduan berhasil dibuat')
                ->body('Nomor surat: ' . $this->record->letter_number . '. PDF akan dibuat setelah disetujui admin.')
                ->warning()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
