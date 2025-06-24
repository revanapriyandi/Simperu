<?php

namespace App\Services;

use App\Models\ComplaintLetter;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DigitalSignatureService
{
    /**
     * Generate digital signature for complaint letter
     */
    public function generateSignature(ComplaintLetter $letter, $adminId): array
    {
        $timestamp = Carbon::now();
        $signatureData = [
            'letter_id' => $letter->id,
            'letter_number' => $letter->letter_number,
            'signed_by' => $adminId,
            'signed_at' => $timestamp->toISOString(),
            'hash' => $this->generateHash($letter, $adminId, $timestamp)
        ];

        // Generate barcode/QR code
        $barcodeData = $this->generateBarcode($signatureData);
        
        return [
            'signature' => json_encode($signatureData),
            'hash' => $signatureData['hash'],
            'barcode_path' => $barcodeData['path'],
            'signed_at' => $timestamp
        ];
    }

    /**
     * Generate hash for signature verification
     */
    private function generateHash(ComplaintLetter $letter, $adminId, $timestamp): string
    {
        $data = $letter->id . $letter->letter_number . $adminId . $timestamp->timestamp;
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Generate barcode/QR code for signature
     */
    private function generateBarcode(array $signatureData): array
    {
        $filename = 'signatures/barcode_' . $signatureData['letter_id'] . '_' . time() . '.png';
        
        // Create verification URL or data
        $verificationData = [
            'letter_id' => $signatureData['letter_id'],
            'hash' => $signatureData['hash'],
            'verify_url' => url('/verify-signature/' . $signatureData['hash'])
        ];

        // Generate QR Code
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(10)
            ->generate(json_encode($verificationData));

        // Store QR code
        Storage::disk('public')->put($filename, $qrCode);

        return [
            'path' => $filename,
            'full_path' => Storage::disk('public')->path($filename)
        ];
    }

    /**
     * Generate PDF with letterhead and signature
     */
    public function generateSignedPDF(ComplaintLetter $letter): string
    {
        $templateData = $this->prepareTemplateData($letter);
        
        $pdf = Pdf::loadView('pdf.signed-letter', $templateData)
            ->setPaper('a4', 'portrait');

        $filename = 'letters/signed_' . $letter->letter_number . '_' . time() . '.pdf';
        $pdfContent = $pdf->output();
        
        Storage::disk('public')->put($filename, $pdfContent);
        
        return $filename;
    }

    /**
     * Prepare template data for PDF generation
     */
    private function prepareTemplateData(ComplaintLetter $letter): array
    {
        return [
            'letter' => $letter->load(['category', 'user', 'signedBy']),
            'letterhead' => [
                'title' => 'Perumahan Villa Windaro Permai',
                'address' => 'Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292',
                'logo_path' => public_path('images/logo.png')
            ],
            'signature_info' => [
                'barcode_path' => $letter->barcode_path ? Storage::disk('public')->path($letter->barcode_path) : null,
                'signed_date' => $letter->signed_at?->format('d F Y'),
                'signed_by' => $letter->signedBy?->name,
                'verification_code' => $letter->signature_hash
            ]
        ];
    }

    /**
     * Verify digital signature
     */
    public function verifySignature(string $hash): ?ComplaintLetter
    {
        return ComplaintLetter::where('signature_hash', $hash)->first();
    }

    /**
     * Get letter category template
     */
    public function getLetterTemplate(ComplaintLetter $letter): array
    {
        $baseTemplate = [
            'recipient' => $letter->recipient,
            'subject' => $letter->subject,
            'content' => $letter->content,
            'date' => $letter->letter_date->format('d F Y'),
            'location' => 'Pekanbaru'
        ];

        // Add category-specific template data
        return match ($letter->category->code) {
            'LNG' => array_merge($baseTemplate, ['type' => 'Surat Lingkungan']),
            'FST' => array_merge($baseTemplate, ['type' => 'Surat Fasilitas']),
            'KLH' => array_merge($baseTemplate, ['type' => 'Surat Keterangan Kelahiran']),
            'KMT' => array_merge($baseTemplate, ['type' => 'Surat Keterangan Kematian']),
            'IZA' => array_merge($baseTemplate, ['type' => 'Surat Izin Acara']),
            'PMT' => array_merge($baseTemplate, ['type' => 'Surat Peminjaman Tempat']),
            default => $baseTemplate
        };
    }
}
