<?php

namespace App\Filament\Resident\Resources;

use App\Models\PaymentSubmission;
use App\Models\FeeType;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resident\Resources\PaymentSubmissionResource\Pages;

class PaymentSubmissionResource extends Resource
{
    protected static ?string $model = PaymentSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pengajuan Pembayaran';

    protected static ?string $modelLabel = 'Pengajuan Pembayaran';

    protected static ?string $pluralModelLabel = 'Pengajuan Pembayaran';

    protected static ?string $navigationGroup = 'Administrasi Keuangan';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('fee_type_id')
                            ->label('Jenis Iuran')
                            ->relationship('feeType', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Pembayaran')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000)
                            ->placeholder('Masukkan jumlah yang dibayarkan'),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->required()
                            ->default(today())
                            ->maxDate(today()),
                    ])->columns(2),

                Forms\Components\Section::make('Periode Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('period_month')
                            ->label('Bulan Periode')
                            ->required()
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari', 
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ])
                            ->default(date('n'))
                            ->helperText('Bulan untuk periode iuran yang dibayar'),
                        Forms\Components\Select::make('period_year')
                            ->label('Tahun Periode')
                            ->required()
                            ->options(array_combine(
                                range(date('Y') - 1, date('Y') + 1),
                                range(date('Y') - 1, date('Y') + 1)
                            ))
                            ->default(date('Y'))
                            ->helperText('Tahun untuk periode iuran yang dibayar'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan (Opsional)')
                            ->rows(3)
                            ->placeholder('Tambahkan catatan jika diperlukan...')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Bukti Pembayaran')
                    ->schema([
                        Forms\Components\FileUpload::make('receipt_path')
                            ->label('Upload Bukti Pembayaran')
                            ->required()
                            ->image()
                            ->directory('payment-receipts')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) => $query->with(['feeType', 'family'])
                    ->whereHas(
                        'family.user',
                        fn(Builder $q) => $q->where('id', Auth::id())
                    )
            )
            ->defaultPaginationPageOption(25) // Add pagination limit
            ->poll('30s') // Reduce polling frequency
            ->columns([
                Tables\Columns\TextColumn::make('feeType.name')
                    ->label('Jenis Iuran')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.head_of_family')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->getStateUsing(
                        fn($record) =>
                        date('F Y', mktime(0, 0, 0, $record->period_month, 1, $record->period_year))
                    )
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu Verifikasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Verifikasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ]),
                Tables\Filters\SelectFilter::make('fee_type_id')
                    ->label('Jenis Iuran')
                    ->relationship('feeType', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                // No bulk actions for residents
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentSubmissions::route('/'),
            'create' => Pages\CreatePaymentSubmission::route('/create'),
            'view' => Pages\ViewPaymentSubmission::route('/{record}'),
            'edit' => Pages\EditPaymentSubmission::route('/{record}/edit'),
        ];
    }

    public static function canDelete($record): bool
    {
        return false; // Residents cannot delete submissions
    }
}
