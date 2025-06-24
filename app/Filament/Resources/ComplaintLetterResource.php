<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintLetterResource\Pages;
use App\Filament\Resources\ComplaintLetterResource\RelationManagers;
use App\Models\ComplaintLetter;
use App\Services\DigitalSignatureService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ComplaintLetterResource extends Resource
{
    protected static ?string $model = ComplaintLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Surat Pengaduan';

    protected static ?string $modelLabel = 'Surat Pengaduan';

    protected static ?string $pluralModelLabel = 'Surat Pengaduan';

    protected static ?string $navigationGroup = 'Surat Menyurat';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('letter_number')
                                    ->label('Nomor Surat')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori Surat')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disabled(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->label('Pengaju')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disabled(),
                                Forms\Components\DatePicker::make('letter_date')
                                    ->label('Tanggal Surat')
                                    ->required()
                                    ->disabled(),
                            ]),
                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                        Forms\Components\TextInput::make('recipient')
                            ->label('Tujuan Surat')
                            ->required()
                            ->maxLength(255)
                            ->disabled(),
                    ]),

                Forms\Components\Section::make('Isi Pengaduan')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Pengaduan')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Lampiran')
                            ->multiple()
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status Pengaduan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status Pengaduan')
                                    ->required()
                                    ->options([
                                        'submitted' => 'Diajukan',
                                        'in_review' => 'Sedang Ditinjau',
                                        'in_progress' => 'Sedang Diproses',
                                        'resolved' => 'Selesai',
                                        'closed' => 'Ditutup'
                                    ]),
                                Forms\Components\Select::make('priority')
                                    ->label('Prioritas')
                                    ->options([
                                        'low' => 'Rendah',
                                        'medium' => 'Sedang',
                                        'high' => 'Tinggi',
                                        'urgent' => 'Sangat Penting'
                                    ])
                                    ->disabled(),
                            ]),
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Tanggapan Admin')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Persetujuan & Tanda Tangan')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('approval_status')
                                    ->label('Status Persetujuan')
                                    ->options([
                                        'pending' => 'Menunggu Persetujuan',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak'
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('signed_by')
                                    ->label('Ditandatangani Oleh')
                                    ->formatStateUsing(fn($record) => $record?->signedBy?->name)
                                    ->disabled(),
                            ]),
                        Forms\Components\Textarea::make('approval_notes')
                            ->label('Catatan Persetujuan')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Placeholder::make('signature_status')
                            ->label('Status Tanda Tangan Digital')
                            ->content(function ($record) {
                                if (!$record) {
                                    return '<div class="text-gray-500">Surat belum dibuat</div>';
                                }

                                if ($record->approval_status === 'approved' && $record->signature_hash) {
                                    $signedDate = $record->signed_at?->format('d F Y H:i');
                                    return '<div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <div class="flex items-center text-green-800">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-medium">Surat telah ditandatangani secara digital</span>
                                        </div>
                                        <div class="mt-2 text-sm text-green-700">
                                            <p><strong>Tanggal:</strong> ' . $signedDate . '</p>
                                            <p><strong>Hash:</strong> <span class="font-mono text-xs">' . substr($record->signature_hash, 0, 32) . '...</span></p>
                                        </div>
                                    </div>';
                                }

                                return '<div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center text-yellow-800">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="font-medium">Menunggu persetujuan dan tanda tangan</span>
                                    </div>
                                </div>';
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Dokumen PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->schema([
                        Forms\Components\FileUpload::make('pdf_path')
                            ->label('File PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('complaint-letters')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('download_pdf')
                                ->label('Download PDF')
                                ->icon('heroicon-o-document-arrow-down')
                                ->color('primary')
                                ->visible(fn($record) => $record && $record->pdf_path)
                                ->url(fn($record) => route('admin.download-letter', $record))
                                ->openUrlInNewTab(),
                            Forms\Components\Actions\Action::make('verify_signature')
                                ->label('Verifikasi Tanda Tangan')
                                ->icon('heroicon-o-shield-check')
                                ->color('success')
                                ->visible(fn($record) => $record && $record->signature_hash)
                                ->url(fn($record) => route('verify-signature', $record->signature_hash))
                                ->openUrlInNewTab(),
                        ]),
                    ])
                    ->collapsible()
                    ->visible(fn($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('letter_number')
                                ->label('No. Surat')
                                ->weight('bold')
                                ->color('primary')
                                ->searchable()
                                ->sortable()
                                ->copyable()
                                ->copyMessage('Nomor surat disalin!')
                                ->copyMessageDuration(1500),
                            Tables\Columns\BadgeColumn::make('priority')
                                ->label('')
                                ->size('sm')
                                ->colors([
                                    'success' => 'low',
                                    'warning' => 'medium',
                                    'danger' => 'high',
                                    'primary' => 'urgent',
                                ])
                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                    'low' => 'RENDAH',
                                    'medium' => 'SEDANG',
                                    'high' => 'TINGGI',
                                    'urgent' => 'URGENT',
                                    default => $state,
                                }),
                        ]),
                        Tables\Columns\TextColumn::make('subject')
                            ->label('Subjek')
                            ->searchable()
                            ->limit(60)
                            ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                                $state = $column->getState();
                                if (strlen($state) <= 60) {
                                    return null;
                                }
                                return $state;
                            })
                            ->weight('medium')
                            ->color('gray'),
                        Tables\Columns\Layout\Grid::make(2)
                            ->schema([
                                Tables\Columns\TextColumn::make('user.name')
                                    ->label('Pengaju')
                                    ->searchable()
                                    ->icon('heroicon-m-user')
                                    ->color('info')
                                    ->weight('medium'),
                                Tables\Columns\TextColumn::make('category.name')
                                    ->label('Kategori')
                                    ->badge()
                                    ->color('indigo'),
                            ]),
                    ])->space(2),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\BadgeColumn::make('status')
                                ->label('')
                                ->colors([
                                    'gray' => 'submitted',
                                    'blue' => 'in_review',
                                    'yellow' => 'in_progress',
                                    'green' => 'resolved',
                                    'red' => 'closed',
                                ])
                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                    'submitted' => '📤 DIAJUKAN',
                                    'in_review' => '👀 DITINJAU',
                                    'in_progress' => '⚙️ DIPROSES',
                                    'resolved' => '✅ SELESAI',
                                    'closed' => '🔒 DITUTUP',
                                    default => $state,
                                }),
                            Tables\Columns\BadgeColumn::make('approval_status')
                                ->label('')
                                ->colors([
                                    'orange' => 'pending',
                                    'green' => 'approved',
                                    'red' => 'rejected',
                                ])
                                ->formatStateUsing(fn(string $state): string => match ($state) {
                                    'pending' => '⏳ MENUNGGU',
                                    'approved' => '✅ DISETUJUI',
                                    'rejected' => '❌ DITOLAK',
                                    default => $state,
                                }),
                        ]),
                        Tables\Columns\Layout\Grid::make(2)
                            ->schema([
                                Tables\Columns\TextColumn::make('submitted_at')
                                    ->label('Diajukan')
                                    ->since()
                                    ->icon('heroicon-m-clock')
                                    ->color('gray')
                                    ->size('sm'),
                                Tables\Columns\IconColumn::make('signature_hash')
                                    ->label('Digital Sign')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-shield-check')
                                    ->falseIcon('heroicon-o-shield-exclamation')
                                    ->trueColor('success')
                                    ->falseColor('warning')
                                    ->tooltip(fn($record) => $record->signature_hash ? 'Sudah ditandatangani digital' : 'Belum ditandatangani'),
                            ]),
                    ])->space(1),
                ])->from('md'),
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 1,
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Pengaduan')
                    ->options([
                        'submitted' => 'Diajukan',
                        'in_review' => 'Sedang Ditinjau',
                        'in_progress' => 'Sedang Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup'
                    ]),
                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Status Persetujuan')
                    ->options([
                        'pending' => 'Menunggu Persetujuan',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'urgent' => 'Sangat Penting'
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('signature_hash')
                    ->label('Tanda Tangan Digital')
                    ->trueLabel('Sudah ditandatangani')
                    ->falseLabel('Belum ditandatangani')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('signature_hash'),
                        false: fn(Builder $query) => $query->whereNull('signature_hash'),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalWidth(MaxWidth::SevenExtraLarge)
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\Action::make('preview_pdf')
                        ->label('Preview PDF')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function ($record) {
                            $pdfService = app(\App\Services\ComplaintLetterPdfService::class);
                            $pdfPath = $pdfService->generatePdf($record);
                            $record->update(['pdf_path' => $pdfPath]);

                            return redirect()->route('admin.preview-letter', $record);
                        })
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('download_pdf')
                        ->label('Download PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($record) {
                            $pdfService = app(\App\Services\ComplaintLetterPdfService::class);
                            $pdfPath = $pdfService->generatePdf($record);
                            $record->update(['pdf_path' => $pdfPath]);

                            return redirect()->route('admin.download-letter', $record);
                        }),
                    Tables\Actions\Action::make('approve')
                        ->label('Setujui')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn($record) => $record->approval_status === 'pending')
                        ->form([
                            Forms\Components\Textarea::make('approval_notes')
                                ->label('Catatan Persetujuan (Opsional)')
                                ->rows(3)
                                ->placeholder('Tambahkan catatan jika diperlukan...')
                        ])
                        ->action(function ($record, $data) {
                            $signatureService = app(DigitalSignatureService::class);

                            // Generate digital signature
                            $signatureData = $signatureService->generateSignature($record, Auth::id());

                            // Update with approval and signature
                            $record->update([
                                'approval_status' => 'approved',
                                'approval_notes' => $data['approval_notes'] ?? null,
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
                            $pdfPath = $signatureService->generateSignedPDF($record);
                            $record->update(['pdf_path' => $pdfPath]);

                            Notification::make()
                                ->title('Surat berhasil disetujui dan ditandatangani')
                                ->body('PDF telah dibuat dengan tanda tangan digital')
                                ->success()
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('download')
                                        ->label('Download PDF')
                                        ->url(route('admin.download-letter', $record))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn($record) => $record->approval_status === 'pending')
                        ->requiresConfirmation()
                        ->modalHeading('Tolak Surat Pengaduan')
                        ->modalDescription('Apakah Anda yakin ingin menolak surat pengaduan ini?')
                        ->form([
                            Forms\Components\Textarea::make('approval_notes')
                                ->label('Alasan Penolakan')
                                ->required()
                                ->rows(3)
                                ->placeholder('Jelaskan alasan penolakan secara detail...')
                        ])
                        ->action(function ($record, $data) {
                            $record->update([
                                'approval_status' => 'rejected',
                                'approval_notes' => $data['approval_notes'],
                                'processed_by' => Auth::id(),
                                'processed_at' => now(),
                                'status' => 'closed'
                            ]);

                            Notification::make()
                                ->title('Surat pengaduan ditolak')
                                ->body('Pengaju akan mendapat notifikasi penolakan')
                                ->warning()
                                ->send();
                        }),
                    Tables\Actions\Action::make('verify_signature')
                        ->label('Verifikasi Digital')
                        ->icon('heroicon-o-shield-check')
                        ->color('info')
                        ->visible(fn($record) => $record->signature_hash)
                        ->url(fn($record) => route('verify-signature', $record->signature_hash))
                        ->openUrlInNewTab(),
                ])
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('Aksi Surat')
                    ->button()
                    ->outlined()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaintLetters::route('/'),
            'create' => Pages\CreateComplaintLetter::route('/create'),
            'edit' => Pages\EditComplaintLetter::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('approval_status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
