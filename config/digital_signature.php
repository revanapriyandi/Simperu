<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Digital Signature Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the digital signature
    | system used in the complaint letter management.
    |
    */

    'algorithm' => env('SIGNATURE_ALGORITHM', 'sha256'),

    'expiry_days' => env('SIGNATURE_EXPIRY_DAYS', 365),

    'qr_code' => [
        'size' => env('QR_CODE_SIZE', 200),
        'margin' => env('QR_CODE_MARGIN', 10),
        'format' => env('QR_CODE_FORMAT', 'png'),
    ],

    'letterhead' => [
        'title' => 'Perumahan Villa Windaro Permai',
        'address' => 'Jl. Amarta, RT 03/RW 01 Kelurahan Delima, Kecamatan Binawidya, Kota Pekanbaru, Riau 28292',
        'logo_path' => 'images/logo.png',
        'phone' => '(0761) 123456',
        'email' => 'admin@villawindaro.com',
    ],

    'pdf' => [
        'paper_size' => 'a4',
        'orientation' => 'portrait',
        'margins' => [
            'top' => '2.5cm',
            'right' => '2cm', 
            'bottom' => '2.5cm',
            'left' => '2cm',
        ],
    ],

    'storage' => [
        'signatures_path' => 'signatures',
        'letters_path' => 'letters',
        'attachments_path' => 'complaint-attachments',
    ],

    'verification' => [
        'base_url' => env('APP_URL', 'http://localhost'),
        'route_prefix' => 'letter/verify',
    ],

    'approval_workflow' => [
        'auto_generate_pdf' => true,
        'notify_resident' => true,
        'require_approval_notes' => false,
    ],

    'letter_numbering' => [
        'format' => '%03d/%s/PVWP/%s/%s', // count/category/month/year
        'reset_yearly' => true,
    ],
];
