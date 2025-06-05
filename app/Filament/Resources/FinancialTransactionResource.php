<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialTransactionResource\Pages;
use App\Filament\Resources\FinancialTransactionResource\RelationManagers;
use App\Models\FinancialTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FinancialTransactionResource extends Resource
{
    protected static ?string $model = FinancialTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transaksi Keuangan';

    protected static ?string $modelLabel = 'Transaksi Keuangan';

    protected static ?string $pluralModelLabel = 'Transaksi Keuangan';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->default(today()),
                        Forms\Components\Select::make('type')
                            ->label('Jenis Transaksi')
                            ->required()
                            ->options([
                                'income' => 'Pemasukan',
                                'expense' => 'Pengeluaran'
                            ])
                            ->reactive(),
                        Forms\Components\Select::make('category')
                            ->label('Kategori')
                            ->required()
                            ->options(function (callable $get) {
                                $type = $get('type');
                                if ($type === 'income') {
                                    return [
                                        'fee' => 'Iuran',
                                        'donation' => 'Donasi',
                                        'event' => 'Acara',
                                        'other_income' => 'Lainnya'
                                    ];
                                } elseif ($type === 'expense') {
                                    return [
                                        'maintenance' => 'Pemeliharaan',
                                        'event' => 'Acara',
                                        'operational' => 'Operasional',
                                        'other_expense' => 'Lainnya'
                                    ];
                                }
                                return [];
                            })
                            ->reactive(),
                        Forms\Components\Select::make('fee_type_id')
                            ->label('Jenis Iuran')
                            ->relationship('feeType', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get) => $get('category') === 'fee'),
                        Forms\Components\Select::make('family_id')
                            ->label('Keluarga')
                            ->relationship('family', 'head_of_family')
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get) => in_array($get('category'), ['fee', 'donation'])),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->step(1000),
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('reference_number')
                            ->label('Nomor Referensi')
                            ->placeholder('No. Bukti/Kwitansi')
                            ->maxLength(100),
                        Forms\Components\FileUpload::make('receipt_path')
                            ->label('Bukti Transaksi')
                            ->image()
                            ->directory('receipts')
                            ->imageEditor()
                            ->maxSize(2048),
                    ])->columns(2),

                Forms\Components\Section::make('Status & Verifikasi')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Menunggu',
                                'verified' => 'Terverifikasi',
                                'rejected' => 'Ditolak'
                            ])
                            ->default('pending'),
                        Forms\Components\Select::make('verified_by')
                            ->label('Diverifikasi Oleh')
                            ->relationship('verifiedBy', 'name')
                            ->searchable()
                            ->preload()
                            ->visible(fn(callable $get) => in_array($get('status'), ['verified', 'rejected'])),
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->label('Waktu Verifikasi')
                            ->displayFormat('d/m/Y H:i')
                            ->visible(fn(callable $get) => in_array($get('status'), ['verified', 'rejected'])),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(2)
                            ->placeholder('Catatan tambahan...'),
                        Forms\Components\Hidden::make('created_by')
                            ->default(Auth::id()),
                    ])->columns(2),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'fee' => 'Iuran',
                        'donation' => 'Donasi',
                        'event' => 'Acara',
                        'maintenance' => 'Pemeliharaan',
                        'operational' => 'Operasional',
                        'other_income' => 'Lainnya (Pemasukan)',
                        'other_expense' => 'Lainnya (Pengeluaran)',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('feeType.name')
                    ->label('Jenis Iuran')
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('family.head_of_family')
                    ->label('Keluarga')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('No. Referensi')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('verifiedBy.name')
                    ->label('Diverifikasi')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Waktu Verifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran'
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'fee' => 'Iuran',
                        'donation' => 'Donasi',
                        'event' => 'Acara',
                        'maintenance' => 'Pemeliharaan',
                        'operational' => 'Operasional',
                        'other_income' => 'Lainnya (Pemasukan)',
                        'other_expense' => 'Lainnya (Pengeluaran)',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak'
                    ]),
                Tables\Filters\SelectFilter::make('fee_type')
                    ->label('Jenis Iuran')
                    ->relationship('feeType', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('min_amount')
                            ->label('Jumlah Minimum')
                            ->numeric()
                            ->prefix('Rp'),
                        Forms\Components\TextInput::make('max_amount')
                            ->label('Jumlah Maksimum')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_amount'],
                                fn(Builder $query, $amount): Builder => $query->where('amount', '>=', $amount)
                            )
                            ->when(
                                $data['max_amount'],
                                fn(Builder $query, $amount): Builder => $query->where('amount', '<=', $amount)
                            );
                    }),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Tanggal Mulai'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Tanggal Akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date)
                            )
                            ->when(
                                $data['end_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(FinancialTransaction $record): bool => $record->status === 'pending')
                    ->action(function (FinancialTransaction $record) {
                        $record->update([
                            'status' => 'verified',
                            'verified_by' => Auth::id(),
                            'verified_at' => now(),
                        ]);
                    })
                    ->requiresConfirmation(),
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
            'index' => Pages\ListFinancialTransactions::route('/'),
            'create' => Pages\CreateFinancialTransaction::route('/create'),
            'edit' => Pages\EditFinancialTransaction::route('/{record}/edit'),
        ];
    }
}
