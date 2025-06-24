<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSubmissionResource\Pages;
use App\Filament\Resources\PaymentSubmissionResource\RelationManagers;
use App\Models\PaymentSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class PaymentSubmissionResource extends Resource
{
    protected static ?string $model = PaymentSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Pengajuan Pembayaran';

    protected static ?string $modelLabel = 'Pengajuan Pembayaran';

    protected static ?string $pluralModelLabel = 'Pengajuan Pembayaran';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('family_id')
                            ->label('Keluarga')
                            ->relationship('family', 'head_of_family')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('fee_type_id')
                            ->label('Jenis Iuran')
                            ->relationship('feeType', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Detail Periode & Jumlah')
                    ->schema([
                        Forms\Components\Select::make('period_month')
                            ->label('Bulan')
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
                                12 => 'Desember',
                            ])
                            ->default(date('n')),
                        Forms\Components\Select::make('period_year')
                            ->label('Tahun')
                            ->required()
                            ->options(array_combine(
                                range(date('Y') - 2, date('Y') + 1),
                                range(date('Y') - 2, date('Y') + 1)
                            ))
                            ->default(date('Y')),
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah Pembayaran')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\DatePicker::make('payment_date')
                            ->label('Tanggal Pembayaran')
                            ->required()
                            ->default(today()),
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

                Forms\Components\Section::make('Verifikasi Admin')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Menunggu Verifikasi',
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak'
                            ])
                            ->default('pending'),
                        Forms\Components\RichEditor::make('admin_notes')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('verified_by')
                            ->label('Diverifikasi Oleh')
                            ->relationship('verifier', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.head_of_family')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feeType.name')
                    ->label('Jenis Iuran')
                    ->badge(),
                Tables\Columns\TextColumn::make('period')
                    ->label('Periode')
                    ->formatStateUsing(function (PaymentSubmission $record): string {
                        $months = [
                            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                        ];
                        return $months[$record->period_month] . ' ' . $record->period_year;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                Tables\Columns\ImageColumn::make('receipt_path')
                    ->label('Bukti')
                    ->square()
                    ->size(40),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Diajukan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('verifier.name')
                    ->label('Diverifikasi')
                    ->placeholder('Belum diverifikasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak'
                    ])
                    ->default('pending'),
                Tables\Filters\SelectFilter::make('fee_type')
                    ->label('Jenis Iuran')
                    ->relationship('feeType', 'name'),
                Tables\Filters\Filter::make('period_year')
                    ->form([
                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(
                                range(date('Y') - 5, date('Y') + 1),
                                range(date('Y') - 5, date('Y') + 1)
                            )),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['year'],
                            fn(Builder $query, $year): Builder => $query->where('period_year', $year),
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui pembayaran ini?')
                    ->form([
                        Forms\Components\RichEditor::make('admin_notes')
                            ->label('Catatan Admin (Opsional)')
                            ->placeholder('Tambahkan catatan verifikasi jika diperlukan...')
                    ])
                    ->action(function (PaymentSubmission $record, array $data) {
                        $record->update([
                            'status' => 'approved',
                            'admin_notes' => $data['admin_notes'] ?? null,
                            'verified_by' => \Filament\Facades\Filament::auth()->id(),
                            'verified_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Disetujui')
                            ->body('Pembayaran telah berhasil disetujui dan dicatat.')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Berikan alasan penolakan pembayaran ini:')
                    ->form([
                        Forms\Components\RichEditor::make('admin_notes')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->placeholder('Jelaskan mengapa pembayaran ini ditolak...')
                    ])
                    ->action(function (PaymentSubmission $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'],
                            'verified_by' => \Filament\Facades\Filament::auth()->id(),
                            'verified_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Ditolak')
                            ->body('Pembayaran telah ditolak dengan alasan yang diberikan.')
                            ->warning()
                            ->send();
                    }),
                
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->modalHeading(fn($record) => 'Detail Pembayaran - ' . $record->feeType->name)
                    ->modalContent(fn($record) => view('filament.admin.payment-detail-modal', compact('record')))
                    ->modalWidth('2xl'),
                
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'pending'),
                
                Tables\Actions\Action::make('download_receipt')
                    ->label('Unduh Bukti')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn($record) => $record->receipt_path)
                    ->url(fn($record) => Storage::url($record->receipt_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Setujui Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Setujui Pembayaran Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menyetujui semua pembayaran yang dipilih?')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'approved',
                                        'verified_by' => \Filament\Facades\Filament::auth()->id(),
                                        'verified_at' => now(),
                                    ]);
                                }
                            });
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Batch Disetujui')
                                ->body($records->count() . ' pembayaran telah berhasil disetujui.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->isAdmin()),
                ]),
            ])
            ->poll('30s')
            ->deferLoading()
            ->striped();
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
            'index' => Pages\ListPaymentSubmissions::route('/'),
            'create' => Pages\CreatePaymentSubmission::route('/create'),
            'edit' => Pages\EditPaymentSubmission::route('/{record}/edit'),
        ];
    }
}
