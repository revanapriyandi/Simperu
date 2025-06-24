<?php

namespace App\Http\Controllers;

use App\Models\ComplaintLetter;
use App\Services\DigitalSignatureService;
use App\Services\ComplaintLetterPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LetterController extends Controller
{
    public function __construct(
        private DigitalSignatureService $signatureService,
        private ComplaintLetterPdfService $pdfService
    ) {}

    /**
     * Download letter PDF for residents
     */
    public function downloadResidentLetter(ComplaintLetter $letter): BinaryFileResponse
    {
        // Check if user owns this letter
        if ($letter->submitted_by !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Check if letter is approved and has PDF
        if ($letter->approval_status !== 'approved' || !$letter->pdf_path) {
            abort(404, 'PDF not available');
        }

        $filePath = Storage::disk('public')->path($letter->pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $filename = "Surat-{$letter->letter_number}.pdf";
        // Sanitize filename to remove / and \
        $filename = str_replace(['/', '\\'], '_', $filename);

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Download letter PDF for admin
     */
    public function downloadAdminLetter(ComplaintLetter $letter): BinaryFileResponse
    {
        // Check if letter has PDF
        if (!$letter->pdf_path) {
            abort(404, 'PDF not available');
        }

        $filePath = Storage::disk('public')->path($letter->pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        $filename = "Surat-{$letter->letter_number}.pdf";
        // Sanitize filename to remove / and \
        $filename = str_replace(['/', '\\'], '_', $filename);

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Verify digital signature (public access)
     */
    public function verifySignature(string $hash)
    {
        $letter = $this->signatureService->verifySignature($hash);

        if (!$letter) {
            return view('verification.invalid', [
                'hash' => $hash
            ]);
        }

        return view('verification.valid', [
            'letter' => $letter->load(['category', 'user', 'signedBy']),
            'hash' => $hash
        ]);
    }

    /**
     * Generate QR code for verification
     */
    public function generateVerificationQR(ComplaintLetter $letter)
    {
        if (!$letter->signature_hash) {
            abort(404, 'Letter not digitally signed');
        }

        $verificationUrl = route('verify-signature', $letter->signature_hash);

        // Generate QR code
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(200)
            ->margin(10)
            ->generate($verificationUrl);

        return response($qrCode, 200, [
            'Content-Type' => 'image/svg+xml'
        ]);
    }

    /**
     * Approve letter and generate digital signature
     */
    public function approveLetter(Request $request, ComplaintLetter $letter)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:1000'
        ]);

        if ($letter->approval_status !== 'pending') {
            return response()->json([
                'message' => 'Letter is already processed'
            ], 400);
        }

        try {
            // Generate digital signature
            $signatureData = $this->signatureService->generateSignature($letter, Auth::id());

            // Update letter with approval and signature
            $letter->update([
                'approval_status' => 'approved',
                'approval_notes' => $request->approval_notes,
                'digital_signature' => $signatureData['signature'],
                'signature_hash' => $signatureData['hash'],
                'barcode_path' => $signatureData['barcode_path'],
                'signed_at' => $signatureData['signed_at'],
                'signed_by' => Auth::id(),
                'processed_by' => Auth::id(),
                'processed_at' => now(),
                'status' => 'in_review'
            ]);

            // Generate new PDF with signature
            $pdfPath = $this->signatureService->generateSignedPDF($letter);
            $letter->update(['pdf_path' => $pdfPath]);

            return response()->json([
                'message' => 'Letter approved successfully',
                'pdf_url' => route('admin.download-letter', $letter),
                'verification_url' => route('verify-signature', $signatureData['hash'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to approve letter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject letter
     */
    public function rejectLetter(Request $request, ComplaintLetter $letter)
    {
        $request->validate([
            'approval_notes' => 'required|string|max:1000'
        ]);

        if ($letter->approval_status !== 'pending') {
            return response()->json([
                'message' => 'Letter is already processed'
            ], 400);
        }

        $letter->update([
            'approval_status' => 'rejected',
            'approval_notes' => $request->approval_notes,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            'status' => 'closed'
        ]);

        return response()->json([
            'message' => 'Letter rejected successfully'
        ]);
    }

    /**
     * Preview letter PDF for residents
     */
    public function previewResidentLetter(ComplaintLetter $letter): BinaryFileResponse
    {
        // Check if user owns this letter
        if ($letter->submitted_by !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // Generate or get existing PDF
        if (!$letter->pdf_path) {
            $pdfPath = $this->pdfService->generatePdf($letter);
            $letter->update(['pdf_path' => $pdfPath]);
        }

        $filePath = Storage::disk('public')->path($letter->pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Preview-Surat-' . $letter->letter_number . '.pdf"'
        ]);
    }

    /**
     * Preview letter PDF for admin
     */
    public function previewAdminLetter(ComplaintLetter $letter): BinaryFileResponse
    {
        // Generate or get existing PDF
        if (!$letter->pdf_path) {
            $pdfPath = $this->pdfService->generatePdf($letter);
            $letter->update(['pdf_path' => $pdfPath]);
        }

        $filePath = Storage::disk('public')->path($letter->pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Preview-Surat-' . $letter->letter_number . '.pdf"'
        ]);
    }
}
