<?php

namespace App\Http\Controllers;

use App\Models\ComplaintLetter;
use App\Services\ComplaintLetterPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ComplaintLetterController extends Controller
{
    protected ComplaintLetterPdfService $pdfService;

    public function __construct(ComplaintLetterPdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Download complaint letter PDF
     */
    public function downloadPdf(ComplaintLetter $complaintLetter): BinaryFileResponse|Response
    {
        // Check if user owns this complaint or is admin
        if (Auth::user()->role !== 'admin' && $complaintLetter->submitted_by !== Auth::id()) {
            abort(403, 'Unauthorized access to complaint letter.');
        }

        // Check if PDF exists, if not generate it
        if (empty($complaintLetter->pdf_path) || !Storage::disk('public')->exists($complaintLetter->pdf_path)) {
            try {
                $pdfPath = $this->pdfService->generatePdf($complaintLetter);
                $complaintLetter->update(['pdf_path' => $pdfPath]);
            } catch (\Exception $e) {
                return response('Error generating PDF: ' . $e->getMessage(), 500);
            }
        }

        $filePath = Storage::disk('public')->path($complaintLetter->pdf_path);

        if (!file_exists($filePath)) {
            return response('PDF file not found.', 404);
        }

        $filename = 'Surat_Pengaduan_' . $complaintLetter->letter_number . '.pdf';
        $filename = str_replace(['/', '\\'], '_', $filename); // Clean filename

        return response()->download($filePath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * View PDF in browser
     */
    public function viewPdf(ComplaintLetter $complaintLetter): Response
    {
        // Check if user owns this complaint or is admin
        if (Auth::user()->role !== 'admin' && $complaintLetter->submitted_by !== Auth::id()) {
            abort(403, 'Unauthorized access to complaint letter.');
        }

        // Check if PDF exists, if not generate it
        if (empty($complaintLetter->pdf_path) || !Storage::disk('public')->exists($complaintLetter->pdf_path)) {
            try {
                $pdfPath = $this->pdfService->generatePdf($complaintLetter);
                $complaintLetter->update(['pdf_path' => $pdfPath]);
            } catch (\Exception $e) {
                return response('Error generating PDF: ' . $e->getMessage(), 500);
            }
        }

        $fileContents = Storage::disk('public')->get($complaintLetter->pdf_path);

        return response($fileContents, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Surat_Pengaduan_' . str_replace(['/', '\\'], '_', $complaintLetter->letter_number) . '.pdf"'
        ]);
    }

    /**
     * Regenerate PDF for complaint letter
     */
    public function regeneratePdf(ComplaintLetter $complaintLetter): \Illuminate\Http\JsonResponse
    {
        // Check if user owns this complaint or is admin
        if (Auth::user()->role !== 'admin' && $complaintLetter->submitted_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Delete old PDF if exists
            if ($complaintLetter->pdf_path) {
                Storage::disk('public')->delete($complaintLetter->pdf_path);
            }

            // Generate new PDF
            $pdfPath = $this->pdfService->generatePdf($complaintLetter);

            // Update record
            $complaintLetter->update(['pdf_path' => $pdfPath]);

            return response()->json([
                'success' => true,
                'message' => 'PDF berhasil dibuat ulang.',
                'download_url' => route('complaint.download-pdf', $complaintLetter->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
