<?php

namespace App\Services;

use App\Models\ComplaintLetter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Dompdf\Dompdf;
use Dompdf\Options;

class DigitalSignatureService
{
    protected BarcodeGeneratorPNG $barcodeGenerator;
    protected BarcodeGeneratorHTML $barcodeHtmlGenerator;

    public function __construct()
    {
        $this->barcodeGenerator = new BarcodeGeneratorPNG();
        $this->barcodeHtmlGenerator = new BarcodeGeneratorHTML();
    }

    /**
     * Generate digital signature hash untuk surat
     */
    public function generateSignatureHash(ComplaintLetter $letter, User $signer): string
    {
        $data = [
            'letter_id' => $letter->id,
            'letter_number' => $letter->letter_number,
            'signer_id' => $signer->id,
            'signer_name' => $signer->name,
            'timestamp' => now()->timestamp,
            'content_hash' => hash('sha256', $letter->content ?? ''),
        ];

        return hash('sha256', json_encode($data));
    }

    /**
     * Generate barcode untuk digital signature
     */
    public function generateBarcode(string $signatureHash, string $format = 'png'): string
    {
        $barcodeData = [
            'hash' => $signatureHash,
            'timestamp' => now()->timestamp,
            'system' => 'SIMPERU-VWP',
        ];

        $barcodeContent = json_encode($barcodeData);

        if ($format === 'html') {
            return $this->barcodeHtmlGenerator->getBarcode($barcodeContent, $this->barcodeHtmlGenerator::TYPE_CODE_128);
        }

        return base64_encode($this->barcodeGenerator->getBarcode($barcodeContent, $this->barcodeGenerator::TYPE_CODE_128));
    }

    /**
     * Save barcode image ke storage
     */
    public function saveBarcodeImage(string $signatureHash): string
    {
        $barcodeData = [
            'hash' => $signatureHash,
            'timestamp' => now()->timestamp,
            'system' => 'SIMPERU-VWP',
        ];

        $barcodeContent = json_encode($barcodeData);
        $barcodeImage = $this->barcodeGenerator->getBarcode($barcodeContent, $this->barcodeGenerator::TYPE_CODE_128);
        
        $filename = 'barcodes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($filename, $barcodeImage);
        
        return $filename;
    }

    /**
     * Sign surat dengan digital signature
     */
    public function signLetter(ComplaintLetter $letter, User $signer, array $options = []): bool
    {
        $signatureHash = $this->generateSignatureHash($letter, $signer);
        $barcodePath = $this->saveBarcodeImage($signatureHash);

        $letter->update([
            'digital_signature' => $signatureHash,
            'signature_hash' => $signatureHash,
            'barcode_path' => $barcodePath,
            'signed_at' => now(),
            'signed_by' => $signer->id,
            'approval_status' => $options['approval_status'] ?? 'approved',
            'approval_notes' => $options['approval_notes'] ?? null,
            'status' => $options['status'] ?? 'approved',
        ]);

        return true;
    }

    /**
     * Verify digital signature
     */
    public function verifySignature(ComplaintLetter $letter): bool
    {
        if (empty($letter->digital_signature) || empty($letter->signed_by)) {
            return false;
        }

        $signer = User::find($letter->signed_by);
        if (!$signer) {
            return false;
        }

        $expectedHash = $this->generateSignatureHash($letter, $signer);
        return hash_equals($expectedHash, $letter->digital_signature);
    }

    /**
     * Generate PDF surat dengan digital signature
     */
    public function generateSignedPDF(ComplaintLetter $letter): string
    {
        $templateData = $this->prepareTemplateData($letter);
        $html = $this->renderLetterTemplate($letter, $templateData);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'letters/' . $letter->letter_number . '_signed.pdf';
        Storage::disk('public')->put($filename, $dompdf->output());

        $letter->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Prepare data untuk template surat
     */
    protected function prepareTemplateData(ComplaintLetter $letter): array
    {
        $organization = [
            'name' => 'Perumahan Villa Windaro Permai',
            'address' => 'Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292',
            'logo_path' => public_path('images/logo.png'),
        ];

        $signatureInfo = [];
        if ($letter->signed_by) {
            $signer = User::find($letter->signed_by);
            $signatureInfo = [
                'signer_name' => $signer->name ?? 'Admin',
                'signer_title' => 'Pengurus Perumahan Villa Windaro Permai',
                'signed_at' => $letter->signed_at?->locale('id')->isoFormat('D MMMM Y'),
                'signature_hash' => $letter->signature_hash,
                'barcode_path' => $letter->barcode_path ? Storage::disk('public')->path($letter->barcode_path) : null,
            ];
        }

        return [
            'organization' => $organization,
            'letter' => [
                'number' => $letter->letter_number,
                'date' => $letter->letter_date?->locale('id')->isoFormat('D MMMM Y'),
                'subject' => $letter->subject,
                'recipient' => $letter->recipient,
                'content' => $letter->content,
                'category' => $letter->category?->name,
            ],
            'signature' => $signatureInfo,
            'user' => [
                'name' => $letter->user?->name,
                'family' => $letter->user?->family,
            ],
        ];
    }

    /**
     * Render template surat berdasarkan kategori
     */
    protected function renderLetterTemplate(ComplaintLetter $letter, array $data): string
    {
        // Semua surat menggunakan template yang sama: complaint-letter.blade.php
        return view("letters.templates.complaint-letter", $data)->render();
    }

    /**
     * Get barcode as base64 untuk preview
     */
    public function getBarcodePreview(string $signatureHash): string
    {
        return $this->generateBarcode($signatureHash, 'png');
    }

    /**
     * Get barcode as HTML untuk web display
     */
    public function getBarcodeHtml(string $signatureHash): string
    {
        return $this->generateBarcode($signatureHash, 'html');
    }
}
