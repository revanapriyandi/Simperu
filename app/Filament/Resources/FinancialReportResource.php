<?php

namespace App\Filament\Resources;

use App\Models\FinancialTransaction;
use App\Models\PaymentSubmission;
use App\Models\FeeType;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\FinancialReportResource\Pages;

class FinancialReportResource extends Resource
{
    protected static ?string $model = FinancialTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $modelLabel = 'Laporan Keuangan';

    protected static ?string $pluralModelLabel = 'Laporan Keuangan';

    protected static ?string $navigationGroup = 'Laporan & Analisis';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Filter Laporan')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('year')
                                    ->label('Tahun')
                                    ->options(function () {
                                        $years = [];
                                        for ($i = 2020; $i <= now()->year + 1; $i++) {
                                            $years[$i] = $i;
                                        }
                                        return $years;
                                    })
                                    ->default(now()->year),
                                
                                Forms\Components\Select::make('month')
                                    ->label('Bulan')
                                    ->options([
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                                    ])
                                    ->placeholder('Semua Bulan'),
                                
                                Forms\Components\Select::make('type')
                                    ->label('Jenis Transaksi')
                                    ->options([
                                        'income' => 'Pemasukan',
                                        'expense' => 'Pengeluaran',
                                    ])
                                    ->placeholder('Semua Jenis'),
                            ]),
                    ])
                    ->columnSpanFull(),
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
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('feeType.name')
                    ->label('Jenis Iuran')
                    ->placeholder('Manual'),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('IDR')
                            ->label('Total'),
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'verified',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('family.head_of_family')
                    ->label('Keluarga')
                    ->placeholder('Manual'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Transaksi')
                    ->options([
                        'income' => 'Pemasukan',
                        'expense' => 'Pengeluaran',
                    ]),
                
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Iuran Sampah' => 'Iuran Sampah',
                        'Iuran Keamanan' => 'Iuran Keamanan',
                        'Iuran Dana Sosial' => 'Iuran Dana Sosial',
                        'Khas' => 'Khas',
                        'Operasional' => 'Operasional',
                        'Maintenance' => 'Maintenance',
                        'Kegiatan' => 'Kegiatan',
                        'Lainnya' => 'Lainnya',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'verified' => 'Terverifikasi',
                        'rejected' => 'Ditolak',
                    ]),
                
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
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('verify')
                        ->label('Verifikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'verified']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Transaksi')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi transaksi yang dipilih?'),
                ]),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->poll('60s');
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
            'index' => Pages\ListFinancialReports::route('/'),
            'create' => Pages\CreateFinancialReport::route('/create'),
            'view' => Pages\ViewFinancialReport::route('/{record}'),
            'edit' => Pages\EditFinancialReport::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['family', 'feeType']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['description', 'family.head_of_family', 'feeType.name'];
    }
}
