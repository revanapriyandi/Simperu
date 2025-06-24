<?php

namespace App\Notifications;

use App\Models\ComplaintLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ComplaintLetter $complaint,
        public string $action,
        public ?string $notes = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subject = match ($this->action) {
            'approved' => 'Surat Pengaduan Disetujui',
            'rejected' => 'Surat Pengaduan Ditolak',
            'status_updated' => 'Status Pengaduan Diperbarui',
            default => 'Update Surat Pengaduan'
        };

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Terdapat update pada surat pengaduan Anda:')
            ->line('**Nomor Surat:** ' . $this->complaint->letter_number)
            ->line('**Subjek:** ' . $this->complaint->subject);

        if ($this->action === 'approved') {
            $message->line('✅ Surat pengaduan Anda telah **DISETUJUI** oleh admin.')
                ->line('Anda dapat mengunduh surat yang telah ditandatangani melalui dashboard.')
                ->action('Download Surat', route('resident.download-letter', $this->complaint));
        } elseif ($this->action === 'rejected') {
            $message->line('❌ Surat pengaduan Anda **DITOLAK** oleh admin.');
            if ($this->notes) {
                $message->line('**Alasan:** ' . $this->notes);
            }
        } else {
            $message->line('Status terbaru: **' . $this->getStatusLabel() . '**');
        }

        if ($this->notes && $this->action !== 'rejected') {
            $message->line('**Catatan Admin:** ' . $this->notes);
        }

        return $message
            ->action('Lihat Detail', route('filament.resident.resources.complaint-letters.view', $this->complaint))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    public function toArray($notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'letter_number' => $this->complaint->letter_number,
            'subject' => $this->complaint->subject,
            'action' => $this->action,
            'status' => $this->complaint->status,
            'approval_status' => $this->complaint->approval_status,
            'notes' => $this->notes,
            'title' => $this->getNotificationTitle(),
            'body' => $this->getNotificationBody(),
            'icon' => $this->getNotificationIcon(),
            'color' => $this->getNotificationColor(),
        ];
    }

    private function getStatusLabel(): string
    {
        return match ($this->complaint->status) {
            'submitted' => 'Diajukan',
            'in_review' => 'Sedang Ditinjau',
            'in_progress' => 'Sedang Diproses',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
            default => $this->complaint->status
        };
    }

    private function getNotificationTitle(): string
    {
        return match ($this->action) {
            'approved' => '✅ Surat Pengaduan Disetujui',
            'rejected' => '❌ Surat Pengaduan Ditolak',
            'status_updated' => '📋 Status Pengaduan Diperbarui',
            default => '📄 Update Surat Pengaduan'
        };
    }

    private function getNotificationBody(): string
    {
        $base = "Surat {$this->complaint->letter_number} - {$this->complaint->subject}";

        return match ($this->action) {
            'approved' => $base . ' telah disetujui dan dapat diunduh.',
            'rejected' => $base . ' ditolak oleh admin.',
            'status_updated' => $base . ' status diperbarui menjadi: ' . $this->getStatusLabel(),
            default => $base . ' telah diperbarui.'
        };
    }

    private function getNotificationIcon(): string
    {
        return match ($this->action) {
            'approved' => 'heroicon-o-check-circle',
            'rejected' => 'heroicon-o-x-circle',
            'status_updated' => 'heroicon-o-arrow-path',
            default => 'heroicon-o-document-text'
        };
    }

    private function getNotificationColor(): string
    {
        return match ($this->action) {
            'approved' => 'success',
            'rejected' => 'danger',
            'status_updated' => 'info',
            default => 'primary'
        };
    }
}
