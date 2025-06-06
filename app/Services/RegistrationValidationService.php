<?php

namespace App\Services;

use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Collection;

class RegistrationValidationService
{
    /**
     * Validasi NIK apakah sudah terdaftar dalam sistem
     */
    public function validateNik(string $nik): array
    {
        $result = [
            'is_registered' => false,
            'user' => null,
            'family_member' => null,
            'family' => null,
            'message' => null,
            'can_register_as_family_member' => false
        ];

        // Cek di tabel users (kepala keluarga)
        $user = User::where('nik', $nik)->first();
        if ($user) {
            $family = Family::where('user_id', $user->id)->first();
            $result = [
                'is_registered' => true,
                'user' => $user,
                'family' => $family,
                'message' => "NIK sudah terdaftar sebagai kepala keluarga atas nama: {$user->name}",
                'can_register_as_family_member' => false
            ];
            return $result;
        }

        // Cek di tabel family_members
        $familyMember = FamilyMember::where('nik', $nik)->with('family')->first();
        if ($familyMember) {
            $result = [
                'is_registered' => true,
                'family_member' => $familyMember,
                'family' => $familyMember->family,
                'message' => "NIK sudah terdaftar sebagai anggota keluarga atas nama: {$familyMember->name} di keluarga {$familyMember->family->head_of_family}",
                'can_register_as_family_member' => false
            ];
            return $result;
        }

        return $result;
    }

    /**
     * Validasi apakah nomor KK sudah terdaftar
     */
    public function validateKkNumber(string $kkNumber): array
    {
        $result = [
            'is_registered' => false,
            'family' => null,
            'user' => null,
            'message' => null,
            'can_register_as_family_member' => false
        ];

        $family = Family::where('kk_number', $kkNumber)->with('user')->first();

        if ($family) {
            $result = [
                'is_registered' => true,
                'family' => $family,
                'user' => $family->user,
                'message' => "Nomor KK sudah terdaftar atas nama kepala keluarga: {$family->head_of_family}",
                'can_register_as_family_member' => true
            ];
        }

        return $result;
    }

    /**
     * Cek apakah seseorang bisa mendaftar sebagai anggota keluarga
     */
    public function canRegisterAsFamilyMember(string $kkNumber, string $nik = null): array
    {
        $result = [
            'can_register' => false,
            'family' => null,
            'message' => null,
            'registration_type' => null
        ];

        // Validasi KK
        $kkValidation = $this->validateKkNumber($kkNumber);

        if (!$kkValidation['is_registered']) {
            $result['message'] = 'Nomor KK tidak terdaftar. Silakan daftar sebagai kepala keluarga terlebih dahulu.';
            return $result;
        }

        // Jika NIK disediakan, validasi NIK
        if ($nik) {
            $nikValidation = $this->validateNik($nik);
            if ($nikValidation['is_registered']) {
                $result['message'] = $nikValidation['message'];
                return $result;
            }
        }

        $result = [
            'can_register' => true,
            'family' => $kkValidation['family'],
            'message' => 'Anda dapat mendaftar sebagai anggota keluarga',
            'registration_type' => 'family_member'
        ];

        return $result;
    }

    /**
     * Cek apakah seseorang bisa mendaftar sebagai kepala keluarga baru
     */
    public function canRegisterAsHeadOfFamily(string $kkNumber, string $nik = null): array
    {
        $result = [
            'can_register' => false,
            'message' => null,
            'registration_type' => null
        ];

        // Validasi KK
        $kkValidation = $this->validateKkNumber($kkNumber);

        if ($kkValidation['is_registered']) {
            $result['message'] = $kkValidation['message'] . '. Jika Anda anggota keluarga ini, gunakan opsi registrasi anggota keluarga.';
            return $result;
        }

        // Jika NIK disediakan, validasi NIK
        if ($nik) {
            $nikValidation = $this->validateNik($nik);
            if ($nikValidation['is_registered']) {
                $result['message'] = $nikValidation['message'];
                return $result;
            }
        }

        $result = [
            'can_register' => true,
            'message' => 'Anda dapat mendaftar sebagai kepala keluarga baru',
            'registration_type' => 'head_of_family'
        ];

        return $result;
    }

    /**
     * Deteksi otomatis jenis registrasi berdasarkan data yang diberikan
     */
    public function detectRegistrationType(string $kkNumber, string $nik = null): array
    {
        // Cek apakah bisa daftar sebagai kepala keluarga
        $headOfFamilyCheck = $this->canRegisterAsHeadOfFamily($kkNumber, $nik);

        if ($headOfFamilyCheck['can_register']) {
            return $headOfFamilyCheck;
        }

        // Jika tidak bisa sebagai kepala keluarga, cek sebagai anggota keluarga
        $familyMemberCheck = $this->canRegisterAsFamilyMember($kkNumber, $nik);

        return $familyMemberCheck;
    }

    /**
     * Validasi data registrasi lengkap
     */
    public function validateRegistrationData(array $data): array
    {
        $result = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'registration_type' => null,
            'family' => null,
            'message' => null
        ];

        $kkNumber = $data['kk_number'] ?? null;
        $nik = $data['nik'] ?? null;
        $email = $data['email'] ?? null;

        // Validasi email unik
        if ($email && User::where('email', $email)->exists()) {
            $result['valid'] = false;
            $result['errors'][] = 'Email sudah terdaftar dalam sistem.';
            $result['message'] = 'Email sudah terdaftar dalam sistem.';
        }

        // Validasi NIK jika disediakan
        if ($nik) {
            $nikValidation = $this->validateNik($nik);
            if ($nikValidation['is_registered']) {
                $result['valid'] = false;
                $result['errors'][] = $nikValidation['message'];
                $result['message'] = $nikValidation['message'];
            }
        }

        // Deteksi jenis registrasi
        if ($kkNumber && $result['valid']) {
            $registrationType = $this->detectRegistrationType($kkNumber, $nik);

            if (!$registrationType['can_register']) {
                $result['valid'] = false;
                $result['errors'][] = $registrationType['message'];
                $result['message'] = $registrationType['message'];
            } else {
                $result['registration_type'] = $registrationType['registration_type'];
                $result['family'] = $registrationType['family'] ?? null;
            }
        }

        return $result;
    }
}
