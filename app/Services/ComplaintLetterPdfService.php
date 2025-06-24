<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\ComplaintLetter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LandingPageSetting;
use Illuminate\Support\Facades\Storage;

class ComplaintLetterPdfService
{
    public function generatePdf(ComplaintLetter $complaintLetter): string
    {
        // Get complaint letter data with relationships
        $complaintLetter->load(['user', 'category']);

        // Get housing association settings
        $housingName = LandingPageSetting::where('key', 'site_name')->value('value') ?? 'SIMPERU';
        $housingAddress = LandingPageSetting::where('key', 'contact_address')->value('value') ?? 'Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292';
        $contactPhone = LandingPageSetting::where('key', 'contact_phone')->value('value') ?? '+62 812-3456-7890';
        $contactEmail = LandingPageSetting::where('key', 'contact_email')->value('value') ?? 'info@simperu.id';

        // Prepare data for PDF
        $data = [
            'housing_name' => $housingName,
            'housing_address' => $housingAddress,
            'contact_phone' => $contactPhone,
            'contact_email' => $contactEmail,
            'letter_number' => $complaintLetter->letter_number,
            'letter_date' => Carbon::parse($complaintLetter->letter_date)->locale('id')->isoFormat('D MMMM Y'),
            'current_date' => Carbon::parse($complaintLetter->submitted_at)->locale('id')->isoFormat('D MMMM Y'),
            'current_city' => 'Pekanbaru', // You can make this configurable
            'complaint' => $complaintLetter,
            'user' => $complaintLetter->user,
            'category' => $complaintLetter->category,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.complaint-letter', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Times-Roman',
                'margin_top' => 71,
                'margin_bottom' => 71,
                'margin_left' => 71,
                'margin_right' => 71,
                'dpi' => 96,
                'enable_php' => true,
            ]);

        // Generate filename
        $filename = 'surat-pengaduan-' . $complaintLetter->letter_number . '.pdf';

        // Save to storage
        $pdfPath = 'complaint-letters/' . $filename;
        Storage::disk('public')->put($pdfPath, $pdf->output());

        return $pdfPath;
    }

    public function generateLetterNumber(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Get the latest letter number for current month
        $latestLetter = ComplaintLetter::whereYear('letter_date', $currentYear)
            ->whereMonth('letter_date', $currentMonth)
            ->orderBy('id', 'desc')
            ->first();

        // Generate sequential number
        $sequence = 1;
        if ($latestLetter) {
            // Extract sequence from existing letter number
            // Format: 001/PENGADUAN/PVWP/V/2025
            $parts = explode('/', $latestLetter->letter_number);
            if (count($parts) >= 1) {
                $sequence = intval($parts[0]) + 1;
            }
        }

        // Format: 001/PENGADUAN/PVWP/V/2025
        $monthRoman = $this->numberToRoman($currentMonth);
        return sprintf('%03d/PENGADUAN/PVWP/%s/%s', $sequence, $monthRoman, $currentYear);
    }

    private function numberToRoman($number): string
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $romans[$number] ?? (string)$number;
    }
}
