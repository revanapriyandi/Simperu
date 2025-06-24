<?php
// Jalankan script ini sekali untuk membuat template_import_keluarga.xlsx di public/
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();

// Sheet 1: families
$famSheet = $spreadsheet->getActiveSheet();
$famSheet->setTitle('families');
$famSheet->fromArray([
    ['kk_number', 'head_of_family', 'wife_name', 'house_block', 'phone_1', 'phone_2', 'house_status', 'status', 'family_members_count', 'license_plate_1', 'license_plate_2'],
    ['123456789', 'Budi', 'Siti', 'A1', '081234', '082345', 'owner', 'active', 4, 'B1234CD', 'D5678EF'],
    ['987654321', 'Andi', 'Rina', 'B2', '083456', '084567', 'sewa', 'active', 3, 'F1234GH', 'H5678IJ'],
]);

// Sheet 2: members
$memSheet = $spreadsheet->createSheet();
$memSheet->setTitle('members');
$memSheet->fromArray([
    ['kk_number', 'name', 'nik', 'relationship', 'birth_date', 'gender', 'occupation'],
    ['123456789', 'Budi', '3201010000000001', 'Kepala Keluarga', '1980-01-01', 'L', 'Karyawan'],
    ['123456789', 'Siti', '3201010000000002', 'Istri', '1982-02-02', 'P', 'Ibu Rumah Tangga'],
    ['123456789', 'Andi', '3201010000000003', 'Anak', '2005-03-03', 'L', 'Pelajar'],
    ['123456789', 'Ani', '3201010000000004', 'Anak', '2008-04-04', 'P', 'Pelajar'],
    ['987654321', 'Andi', '3201010000000005', 'Kepala Keluarga', '1975-05-05', 'L', 'Wiraswasta'],
    ['987654321', 'Rina', '3201010000000006', 'Istri', '1978-06-06', 'P', 'Ibu Rumah Tangga'],
    ['987654321', 'Bayu', '3201010000000007', 'Anak', '2002-07-07', 'L', 'Mahasiswa'],
]);

$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/../../public/template_import_keluarga.xlsx');

echo "File template_import_keluarga.xlsx berhasil dibuat di folder public/.\n";
