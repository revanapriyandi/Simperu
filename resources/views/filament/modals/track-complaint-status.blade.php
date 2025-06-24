<div class="p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Status Pengaduan: {{ $record->letter_number }}
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
            {{ $record->subject }}
        </p>
    </div>

    <!-- Status Timeline -->
    <div class="space-y-6">
        <!-- Submitted -->
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Surat Diajukan</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $record->submitted_at?->format('d F Y H:i') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Surat pengaduan berhasil diajukan dan menunggu review admin
                </p>
            </div>
        </div>

        <!-- In Review -->
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                @if (in_array($record->status, ['in_review', 'in_progress', 'resolved']))
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 border-2 border-gray-300 rounded-full"></div>
                @endif
            </div>
            <div class="flex-1">
                <h4
                    class="text-sm font-medium {{ in_array($record->status, ['in_review', 'in_progress', 'resolved']) ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                    Sedang Ditinjau Admin
                </h4>
                @if ($record->approval_status === 'approved')
                    <p class="text-sm text-green-600">
                        Disetujui pada {{ $record->processed_at?->format('d F Y H:i') }}
                    </p>
                @elseif($record->approval_status === 'rejected')
                    <p class="text-sm text-red-600">
                        Ditolak pada {{ $record->processed_at?->format('d F Y H:i') }}
                    </p>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Menunggu review dan persetujuan
                    </p>
                @endif
            </div>
        </div>

        <!-- In Progress -->
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                @if (in_array($record->status, ['in_progress', 'resolved']))
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 border-2 border-gray-300 rounded-full"></div>
                @endif
            </div>
            <div class="flex-1">
                <h4
                    class="text-sm font-medium {{ in_array($record->status, ['in_progress', 'resolved']) ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                    Sedang Diproses
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $record->status === 'in_progress' ? 'Pengaduan sedang ditangani' : 'Menunggu proses penanganan' }}
                </p>
            </div>
        </div>

        <!-- Resolved -->
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                @if ($record->status === 'resolved')
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                @else
                    <div class="w-8 h-8 border-2 border-gray-300 rounded-full"></div>
                @endif
            </div>
            <div class="flex-1">
                <h4
                    class="text-sm font-medium {{ $record->status === 'resolved' ? 'text-gray-900 dark:text-white' : 'text-gray-400' }}">
                    Selesai
                </h4>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $record->status === 'resolved' ? 'Pengaduan berhasil diselesaikan' : 'Menunggu penyelesaian' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Admin Response -->
    @if ($record->admin_response)
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd"></path>
                </svg>
                Tanggapan Admin
            </h4>
            <p class="text-sm text-blue-800 dark:text-blue-200">
                {{ $record->admin_response }}
            </p>
        </div>
    @endif

    <!-- Approval Notes -->
    @if ($record->approval_notes)
        <div
            class="mt-4 p-4 bg-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-50 dark:bg-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-900/20 rounded-lg border border-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-200 dark:border-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-800">
            <h4
                class="text-sm font-medium text-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-900 dark:text-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-100 mb-2">
                {{ $record->approval_status === 'approved' ? '✅ Catatan Persetujuan' : '❌ Alasan Penolakan' }}
            </h4>
            <p
                class="text-sm text-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-800 dark:text-{{ $record->approval_status === 'approved' ? 'green' : 'red' }}-200">
                {{ $record->approval_notes }}
            </p>
        </div>
    @endif

    <!-- Digital Signature Info -->
    @if ($record->signature_hash)
        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
            <h4 class="text-sm font-medium text-green-900 dark:text-green-100 mb-2">
                🔒 Tanda Tangan Digital
            </h4>
            <div class="text-sm text-green-800 dark:text-green-200 space-y-1">
                <p><strong>Status:</strong> Surat telah ditandatangani secara digital</p>
                <p><strong>Ditandatangani oleh:</strong> {{ $record->signedBy?->name ?? 'Pengurus' }}</p>
                <p><strong>Tanggal:</strong> {{ $record->signed_at?->format('d F Y H:i') }}</p>
                <p><strong>Hash Verifikasi:</strong> <span
                        class="font-mono text-xs">{{ substr($record->signature_hash, 0, 32) }}...</span></p>
            </div>
        </div>
    @endif

    <!-- Current Status Summary -->
    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Status Saat Ini</h4>
        <div class="flex items-center space-x-4">
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $record->status === 'submitted' ? 'bg-gray-100 text-gray-800' : '' }}
                {{ $record->status === 'in_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $record->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $record->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $record->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}
            ">
                {{ match ($record->status) {
                    'submitted' => '📤 Diajukan',
                    'in_review' => '👀 Sedang Ditinjau',
                    'in_progress' => '⚙️ Sedang Diproses',
                    'resolved' => '✅ Selesai',
                    'closed' => '🔒 Ditutup',
                    default => $record->status,
                } }}
            </span>
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                {{ $record->approval_status === 'pending' ? 'bg-orange-100 text-orange-800' : '' }}
                {{ $record->approval_status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $record->approval_status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
            ">
                {{ match ($record->approval_status) {
                    'pending' => '⏳ Menunggu Persetujuan',
                    'approved' => '✅ Disetujui',
                    'rejected' => '❌ Ditolak',
                    default => $record->approval_status,
                } }}
            </span>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
            Terakhir diupdate: {{ $record->updated_at?->format('d F Y H:i') }}
        </p>
    </div>
</div>
