<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramNotificationResource\Pages;
use App\Filament\Resources\TelegramNotificationResource\RelationManagers;
use App\Models\TelegramNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TelegramNotificationResource extends Resource
{
    protected static ?string $model = TelegramNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Notifikasi Telegram';

    protected static ?string $modelLabel = 'Notifikasi Telegram';

    protected static ?string $pluralModelLabel = 'Notifikasi Telegram';

    protected static ?string $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Notifikasi')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Jenis Notifikasi')
                            ->required()
                            ->options([
                                'announcement' => 'Pengumuman',
                                'complaint_letter' => 'Surat Pengaduan',
                                'payment_reminder' => 'Pengingat Pembayaran',
                                'activity_update' => 'Update Kegiatan',
                                'system_alert' => 'Peringatan Sistem',
                                'manual' => 'Manual'
                            ])
                            ->default('manual'),
                        Forms\Components\TextInput::make('reference_id')
                            ->label('ID Referensi')
                            ->numeric()
                            ->placeholder('ID dari data terkait (opsional)')
                            ->helperText('ID dari announcement, complaint letter, dll yang terkait'),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Menunggu',
                                'sent' => 'Terkirim',
                                'failed' => 'Gagal'
                            ])
                            ->default('pending'),
                    ])->columns(2),

                Forms\Components\Section::make('Pesan')
                    ->schema([
                        Forms\Components\Textarea::make('message')
                            ->label('Pesan')
                            ->required()
                            ->rows(5)
                            ->placeholder('Tulis pesan yang akan dikirim...')
                            ->helperText('Pesan akan dikirim ke grup Telegram warga'),
                    ]),

                Forms\Components\Section::make('Status Pengiriman')
                    ->schema([
                        Forms\Components\DateTimePicker::make('sent_at')
                            ->label('Waktu Kirim')
                            ->displayFormat('d/m/Y H:i')
                            ->disabled()
                            ->helperText('Akan diisi otomatis saat notifikasi berhasil dikirim'),
                        Forms\Components\Textarea::make('error_message')
                            ->label('Pesan Error')
                            ->rows(3)
                            ->disabled()
                            ->placeholder('Pesan error akan muncul di sini jika pengiriman gagal'),
                    ])->columns(1)
                    ->visible(fn(?TelegramNotification $record) => $record && $record->exists),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'info' => 'announcement',
                        'warning' => 'complaint_letter',
                        'danger' => 'payment_reminder',
                        'success' => 'activity_update',
                        'gray' => 'system_alert',
                        'primary' => 'manual',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'announcement' => 'Pengumuman',
                        'complaint_letter' => 'Surat Pengaduan',
                        'payment_reminder' => 'Pengingat Pembayaran',
                        'activity_update' => 'Update Kegiatan',
                        'system_alert' => 'Peringatan Sistem',
                        'manual' => 'Manual',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('reference_id')
                    ->label('ID Ref')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Pesan')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(function (TelegramNotification $record): string {
                        return $record->message;
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Waktu Kirim')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum terkirim')
                    ->sortable(),
                Tables\Columns\TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(30)
                    ->placeholder('—')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Notifikasi')
                    ->options([
                        'announcement' => 'Pengumuman',
                        'complaint_letter' => 'Surat Pengaduan',
                        'payment_reminder' => 'Pengingat Pembayaran',
                        'activity_update' => 'Update Kegiatan',
                        'system_alert' => 'Peringatan Sistem',
                        'manual' => 'Manual'
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'sent' => 'Terkirim',
                        'failed' => 'Gagal'
                    ]),
                Tables\Filters\Filter::make('sent_today')
                    ->label('Dikirim Hari Ini')
                    ->query(fn(Builder $query): Builder => $query->whereDate('sent_at', today())),
                Tables\Filters\Filter::make('failed_notifications')
                    ->label('Notifikasi Gagal')
                    ->query(fn(Builder $query): Builder => $query->where('status', 'failed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(TelegramNotification $record): bool => $record->status === 'pending'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('resend')
                    ->label('Kirim Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn(TelegramNotification $record): bool => in_array($record->status, ['failed', 'pending']))
                    ->action(function (TelegramNotification $record) {
                        $record->update([
                            'status' => 'pending',
                            'sent_at' => null,
                            'error_message' => null,
                        ]);
                        // Here you would typically dispatch a job to send the notification
                        // dispatch(new SendTelegramNotification($record));
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('mark_sent')
                    ->label('Tandai Terkirim')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(TelegramNotification $record): bool => $record->status === 'pending')
                    ->action(function (TelegramNotification $record) {
                        $record->update([
                            'status' => 'sent',
                            'sent_at' => now(),
                        ]);
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('mark_as_sent')
                        ->label('Tandai Terkirim')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'sent',
                                        'sent_at' => now(),
                                    ]);
                                }
                            });
                        })
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('resend_failed')
                        ->label('Kirim Ulang Gagal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if (in_array($record->status, ['failed', 'pending'])) {
                                    $record->update([
                                        'status' => 'pending',
                                        'sent_at' => null,
                                        'error_message' => null,
                                    ]);
                                }
                            });
                        })
                        ->requiresConfirmation(),
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
            'index' => Pages\ListTelegramNotifications::route('/'),
            'create' => Pages\CreateTelegramNotification::route('/create'),
            'edit' => Pages\EditTelegramNotification::route('/{record}/edit'),
        ];
    }
}
