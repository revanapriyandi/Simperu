<?php

namespace App\Filament\Resident\Resources;

use App\Models\ComplaintLetter;
use App\Models\LetterCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Filament\Support\Enums\MaxWidth;
use Filament\Notifications\Notification;
use App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

class ComplaintLetterResource extends Resource
{
    protected static ?string $model = ComplaintLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Surat Pengaduan';

    protected static ?string $modelLabel = 'Surat Pengaduan';

    protected static ?string $pluralModelLabel = 'Surat Pengaduan';

    protected static ?string $navigationGroup = 'Layanan Surat';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Informasi Dasar')
                        ->icon('heroicon-m-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\Select::make('category_id')
                                        ->label('Jenis Surat')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->placeholder('Pilih jenis surat yang akan diajukan')
                                        ->helperText('Pilih kategori sesuai dengan keperluan Anda')
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            if ($state) {
                                                $category = LetterCategory::find($state);
                                                if ($category) {
                                                    $count = ComplaintLetter::whereHas('category', function ($query) use ($category) {
                                                        $query->where('code', $category->code);
                                                    })->whereYear('created_at', now()->year)->count() + 1;

                                                    $letterNumber = sprintf('%03d/%s/PVWP/%s/%s', $count, $category->code, strtoupper(now()->format('m')), now()->year);
                                                    $set('letter_number', $letterNumber);
                                                }
                                            }
                                        }),
                                    Forms\Components\Select::make('priority')
                                        ->label('Tingkat Prioritas')
                                        ->required()
                                        ->options([
                                            'low' => '🟢 Rendah - Tidak mendesak',
                                            'medium' => '🟡 Sedang - Perlu penanganan',
                                            'high' => '🟠 Tinggi - Segera ditangani',
                                            'urgent' => '🔴 Sangat Penting - Darurat'
                                        ])
                                        ->default('medium')
                                        ->helperText('Pilih sesuai tingkat kepentingan pengaduan'),
                                ]),
                            Forms\Components\TextInput::make('letter_number')
                                ->label('Nomor Surat')
                                ->disabled()
                                ->dehydrated()
                                ->helperText('Nomor surat akan dibuat otomatis'),
                            Forms\Components\TextInput::make('subject')
                                ->label('Subjek/Perihal')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Contoh: Pengaduan kebersihan lingkungan')
                                ->helperText('Tulis subjek pengaduan dengan jelas dan singkat'),
                            Forms\Components\TextInput::make('recipient')
                                ->label('Tujuan Surat')
                                ->required()
                                ->default('Pengurus Perumahan Villa Windaro Permai')
                                ->helperText('Kepada siapa surat ini ditujukan'),
                        ]),

                    Forms\Components\Wizard\Step::make('Isi Pengaduan')
                        ->icon('heroicon-m-document-text')
                        ->schema([
                            Forms\Components\RichEditor::make('content')
                                ->label('Isi Pengaduan')
                                ->required()
                                ->placeholder('Jelaskan pengaduan Anda secara detail...')
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'bulletList',
                                    'orderedList',
                                ]),
                            Forms\Components\FileUpload::make('attachments')
                                ->label('Lampiran Pendukung')
                                ->multiple()
                                ->directory('complaint-attachments')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'])
                                ->maxSize(5120)
                                ->imagePreviewHeight('200')
                                ->helperText('Upload foto atau dokumen pendukung (maksimal 5MB per file)')
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Wizard\Step::make('Konfirmasi')
                        ->icon('heroicon-m-check-circle')
                        ->schema([
                            Forms\Components\Placeholder::make('preview')
                                ->label('Ringkasan Pengaduan')
                                ->content(function ($get) {
                                    $category = $get('category_id') ? LetterCategory::find($get('category_id'))?->name : 'Belum dipilih';
                                    $subject = $get('subject') ?: 'Belum diisi';
                                    $priority = match ($get('priority')) {
                                        'low' => '🟢 Rendah',
                                        'medium' => '🟡 Sedang',
                                        'high' => '🟠 Tinggi',
                                        'urgent' => '🔴 Sangat Penting',
                                        default => 'Belum dipilih'
                                    };

                                    return "<div class='space-y-2'>
                                        <p><strong>Kategori:</strong> {$category}</p>
                                        <p><strong>Subjek:</strong> {$subject}</p>
                                        <p><strong>Prioritas:</strong> {$priority}</p>
                                        <p class='text-sm text-gray-600 mt-4'>Pastikan semua informasi sudah benar sebelum mengirim.</p>
                                    </div>";
                                }),
                            Forms\Components\Checkbox::make('agreement')
                                ->label('Saya menyatakan bahwa informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan')
                                ->required()
                                ->accepted(),
                        ]),
                ])
                    ->columnSpanFull()
                    ->skippable(false)
                    ->persistStepInQueryString(),

                // Status Section - Only visible for existing records
                Forms\Components\Section::make('Status & Persetujuan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('status')
                                    ->label('Status Pengaduan')
                                    ->formatStateUsing(fn($record) => $record?->status_label)
                                    ->disabled(),
                                Forms\Components\TextInput::make('approval_status')
                                    ->label('Status Persetujuan')
                                    ->formatStateUsing(fn($record) => $record?->approval_status_label)
                                    ->disabled(),
                            ]),
                        Forms\Components\Textarea::make('admin_response')
                            ->label('Tanggapan Admin')
                            ->disabled()
                            ->rows(3),
                        Forms\Components\Textarea::make('approval_notes')
                            ->label('Catatan Persetujuan')
                            ->disabled()
                            ->rows(2)
                            ->visible(fn($record) => $record && $record->approval_notes),
                    ])
                    ->visible(fn($record) => $record !== null)
                    ->collapsible(),

                // Digital Signature Section
                Forms\Components\Section::make('Tanda Tangan Digital')
                    ->schema([
                        Forms\Components\Placeholder::make('signature_info')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record || $record->approval_status !== 'approved') {
                                    return '<div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-yellow-800">⏳ Surat belum disetujui</p>
                                        <p class="text-sm text-yellow-600 mt-1">Tanda tangan digital akan muncul setelah surat disetujui oleh admin</p>
                                    </div>';
                                }

                                $signedDate = $record->signed_at?->format('d F Y H:i');
                                $signedBy = $record->signedBy?->name;

                                return '<div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-green-800 font-semibold">✅ Surat telah disetujui dan ditandatangani</p>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p><strong>Ditandatangani oleh:</strong> ' . $signedBy . '</p>
                                        <p><strong>Tanggal:</strong> ' . $signedDate . '</p>
                                    </div>
                                </div>';
                            }),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('download_pdf')
                                ->label('Download Surat PDF')
                                ->icon('heroicon-o-document-arrow-down')
                                ->color('success')
                                ->visible(fn($record) => $record && $record->approval_status === 'approved')
                                ->url(fn($record) => route('resident.download-letter', $record)),
                            Forms\Components\Actions\Action::make('verify_signature')
                                ->label('Verifikasi Tanda Tangan')
                                ->icon('heroicon-o-shield-check')
                                ->color('info')
                                ->visible(fn($record) => $record && $record->signature_hash)
                                ->url(fn($record) => route('verify-signature', $record->signature_hash))
                                ->openUrlInNewTab(),
                        ]),
                    ])
                    ->visible(fn($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where('submitted_by', Auth::id())
                    ->orderBy('submitted_at', 'desc')
            )
            ->defaultPaginationPageOption(10)
            ->poll('60s')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('letter_number')
                                ->label('No. Surat')
                                ->weight('bold')
                                ->color('primary')
                                ->copyable()
                                ->copyMessage('Nomor surat disalin!')
                                ->copyMessageDuration(1500)
                                ->searchable(),
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
                            ->limit(60)
                            ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                                $state = $column->getState();
                                if (strlen($state) <= 60) {
                                    return null;
                                }
                                return $state;
                            })
                            ->searchable()
                            ->weight('medium')
                            ->color('gray'),
                        Tables\Columns\Layout\Grid::make(2)
                            ->schema([
                                Tables\Columns\TextColumn::make('category.name')
                                    ->label('Kategori')
                                    ->badge()
                                    ->color('indigo'),
                                Tables\Columns\TextColumn::make('submitted_at')
                                    ->label('Diajukan')
                                    ->since()
                                    ->icon('heroicon-m-clock')
                                    ->color('gray')
                                    ->size('sm'),
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
                                Tables\Columns\IconColumn::make('signature_hash')
                                    ->label('Digital Sign')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-shield-check')
                                    ->falseIcon('heroicon-o-shield-exclamation')
                                    ->trueColor('success')
                                    ->falseColor('warning')
                                    ->tooltip(fn($record) => $record->signature_hash ? 'Ditandatangani digital' : 'Belum ditandatangani'),
                                Tables\Columns\TextColumn::make('updated_at')
                                    ->label('Update Terakhir')
                                    ->since()
                                    ->icon('heroicon-m-arrow-path')
                                    ->color('secondary')
                                    ->size('sm'),
                            ]),
                    ])->space(1),
                ])->from('md'),
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 1,
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'submitted' => 'Diajukan',
                        'in_review' => 'Sedang Ditinjau',
                        'in_progress' => 'Sedang Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup'
                    ]),
                Tables\Filters\SelectFilter::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'urgent' => 'Sangat Penting'
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalWidth(MaxWidth::FourExtraLarge)
                        ->icon('heroicon-o-eye')
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->visible(fn($record) => $record->status === 'submitted' && $record->approval_status === 'pending')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning'),
                    Tables\Actions\Action::make('preview_pdf')
                        ->label('Preview Surat')
                        ->icon('heroicon-o-eye')
                        ->color('primary')
                        ->action(function ($record) {
                            $pdfService = app(\App\Services\ComplaintLetterPdfService::class);
                            $pdfPath = $pdfService->generatePdf($record);
                            $record->update(['pdf_path' => $pdfPath]);

                            return redirect()->route('resident.preview-letter', $record);
                        })
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('download_pdf')
                        ->label('Download Surat')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(function ($record) {
                            $pdfService = app(\App\Services\ComplaintLetterPdfService::class);
                            $pdfPath = $pdfService->generatePdf($record);
                            $record->update(['pdf_path' => $pdfPath]);

                            return redirect()->route('resident.download-letter', $record);
                        }),
                    Tables\Actions\Action::make('verify')
                        ->label('Verifikasi Digital')
                        ->icon('heroicon-o-shield-check')
                        ->color('info')
                        ->visible(fn($record) => $record->signature_hash)
                        ->url(fn($record) => route('verify-signature', $record->signature_hash))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('track_status')
                        ->label('Lacak Status')
                        ->icon('heroicon-o-magnifying-glass')
                        ->color('gray')
                        ->modalWidth(MaxWidth::TwoExtraLarge)
                        ->modalContent(fn($record) => view('filament.modals.track-complaint-status', ['record' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup'),
                ])
                    ->icon('heroicon-m-ellipsis-horizontal')
                    ->tooltip('Aksi Surat')
                    ->button()
                    ->outlined()
            ])
            ->bulkActions([
                // No bulk actions for residents
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaintLetters::route('/'),
            'create' => Pages\CreateComplaintLetter::route('/create'),
            'view' => Pages\ViewComplaintLetter::route('/{record}'),
            'edit' => Pages\EditComplaintLetter::route('/{record}/edit'),
        ];
    }

    public static function canDelete($record): bool
    {
        return false; // Residents cannot delete complaint letters
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('submitted_by', Auth::id())
            ->where('status', 'submitted')
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
