<?php

namespace App\Helpers;

class ComplaintHelper
{
    public static function getStatusBadge(string $status): array
    {
        return match ($status) {
            'submitted' => [
                'label' => '📤 DIAJUKAN',
                'color' => 'gray',
                'icon' => 'heroicon-o-paper-airplane'
            ],
            'in_review' => [
                'label' => '👀 SEDANG DITINJAU',
                'color' => 'blue',
                'icon' => 'heroicon-o-eye'
            ],
            'in_progress' => [
                'label' => '⚙️ SEDANG DIPROSES',
                'color' => 'yellow',
                'icon' => 'heroicon-o-cog-6-tooth'
            ],
            'resolved' => [
                'label' => '✅ SELESAI',
                'color' => 'green',
                'icon' => 'heroicon-o-check-circle'
            ],
            'closed' => [
                'label' => '🔒 DITUTUP',
                'color' => 'red',
                'icon' => 'heroicon-o-x-circle'
            ],
            default => [
                'label' => strtoupper($status),
                'color' => 'gray',
                'icon' => 'heroicon-o-question-mark-circle'
            ]
        };
    }

    public static function getApprovalBadge(string $approval): array
    {
        return match ($approval) {
            'pending' => [
                'label' => '⏳ MENUNGGU',
                'color' => 'orange',
                'icon' => 'heroicon-o-clock'
            ],
            'approved' => [
                'label' => '✅ DISETUJUI',
                'color' => 'green',
                'icon' => 'heroicon-o-check-badge'
            ],
            'rejected' => [
                'label' => '❌ DITOLAK',
                'color' => 'red',
                'icon' => 'heroicon-o-x-mark'
            ],
            default => [
                'label' => strtoupper($approval),
                'color' => 'gray',
                'icon' => 'heroicon-o-question-mark-circle'
            ]
        };
    }

    public static function getPriorityBadge(string $priority): array
    {
        return match ($priority) {
            'low' => [
                'label' => '🟢 RENDAH',
                'color' => 'success',
                'icon' => 'heroicon-o-arrow-down',
                'weight' => 1
            ],
            'medium' => [
                'label' => '🟡 SEDANG',
                'color' => 'warning',
                'icon' => 'heroicon-o-minus',
                'weight' => 2
            ],
            'high' => [
                'label' => '🟠 TINGGI',
                'color' => 'danger',
                'icon' => 'heroicon-o-arrow-up',
                'weight' => 3
            ],
            'urgent' => [
                'label' => '🔴 URGENT',
                'color' => 'primary',
                'icon' => 'heroicon-o-exclamation-triangle',
                'weight' => 4
            ],
            default => [
                'label' => strtoupper($priority),
                'color' => 'gray',
                'icon' => 'heroicon-o-question-mark-circle',
                'weight' => 0
            ]
        };
    }

    public static function getCategoryColor(string $category): string
    {
        $colors = [
            'Pengaduan Umum' => 'indigo',
            'Infrastruktur' => 'blue',
            'Keamanan' => 'red',
            'Kebersihan' => 'green',
            'Keuangan' => 'yellow',
            'Fasilitas' => 'purple',
            'Lingkungan' => 'emerald',
            'Sosial' => 'pink',
            'Administrasi' => 'gray',
            'Lainnya' => 'slate'
        ];

        return $colors[$category] ?? 'gray';
    }

    public static function getStatusProgress(string $status): int
    {
        return match ($status) {
            'submitted' => 25,
            'in_review' => 50,
            'in_progress' => 75,
            'resolved' => 100,
            'closed' => 100,
            default => 0
        };
    }

    public static function getNextStatus(string $currentStatus): ?string
    {
        return match ($currentStatus) {
            'submitted' => 'in_review',
            'in_review' => 'in_progress',
            'in_progress' => 'resolved',
            default => null
        };
    }

    public static function canEdit(string $status, string $approval): bool
    {
        return $status === 'submitted' && $approval === 'pending';
    }

    public static function canDownload(string $approval): bool
    {
        return $approval === 'approved';
    }

    public static function getTimelineSteps(): array
    {
        return [
            'submitted' => [
                'label' => 'Surat Diajukan',
                'description' => 'Pengaduan berhasil disubmit',
                'icon' => 'heroicon-o-paper-airplane'
            ],
            'in_review' => [
                'label' => 'Sedang Ditinjau',
                'description' => 'Admin sedang mereview pengaduan',
                'icon' => 'heroicon-o-eye'
            ],
            'in_progress' => [
                'label' => 'Sedang Diproses',
                'description' => 'Pengaduan sedang ditangani',
                'icon' => 'heroicon-o-cog-6-tooth'
            ],
            'resolved' => [
                'label' => 'Selesai',
                'description' => 'Pengaduan berhasil diselesaikan',
                'icon' => 'heroicon-o-check-circle'
            ]
        ];
    }
}
