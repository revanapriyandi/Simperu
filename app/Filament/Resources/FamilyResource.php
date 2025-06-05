<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Filament\Resources\FamilyResource\RelationManagers;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Keluarga';

    protected static ?string $modelLabel = 'Keluarga';

    protected static ?string $pluralModelLabel = 'Data Keluarga';

    protected static ?string $navigationGroup = 'Data Warga';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Keluarga')
                    ->schema([
                        Forms\Components\TextInput::make('kk_number')
                            ->label('Nomor KK')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('1234567890123456'),
                        Forms\Components\TextInput::make('head_of_family')
                            ->label('Kepala Keluarga')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('wife_name')
                            ->label('Nama Istri')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('house_block')
                            ->label('Blok Rumah')
                            ->required()
                            ->maxLength(10)
                            ->placeholder('A-1, B-2, dst'),
                    ])->columns(2),

                Forms\Components\Section::make('Kontak & Status')
                    ->schema([
                        Forms\Components\TextInput::make('phone_1')
                            ->label('Telepon 1')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('phone_2')
                            ->label('Telepon 2')
                            ->tel()
                            ->maxLength(20),
                        Forms\Components\Select::make('house_status')
                            ->label('Status Rumah')
                            ->required()
                            ->options([
                                'owner' => 'Pemilik',
                                'tenant' => 'Penyewa',
                                'family' => 'Keluarga'
                            ])
                            ->default('owner'),
                        Forms\Components\Select::make('status')
                            ->label('Status Keluarga')
                            ->required()
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Tidak Aktif',
                                'moved' => 'Pindah'
                            ])
                            ->default('active'),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\TextInput::make('family_members_count')
                            ->label('Jumlah Anggota Keluarga')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        Forms\Components\TextInput::make('license_plate_1')
                            ->label('Plat Nomor Kendaraan 1')
                            ->maxLength(15)
                            ->placeholder('B 1234 XYZ'),
                        Forms\Components\TextInput::make('license_plate_2')
                            ->label('Plat Nomor Kendaraan 2')
                            ->maxLength(15)
                            ->placeholder('B 5678 ABC'),
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kk_number')
                    ->label('Nomor KK')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('head_of_family')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('house_block')
                    ->label('Blok Rumah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_1')
                    ->label('Telepon')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('house_status')
                    ->label('Status Rumah')
                    ->colors([
                        'success' => 'owner',
                        'warning' => 'tenant',
                        'info' => 'family',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'owner' => 'Pemilik',
                        'tenant' => 'Penyewa',
                        'family' => 'Keluarga',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('family_members_count')
                    ->label('Jumlah Anggota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                        'warning' => 'moved',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        'moved' => 'Pindah',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User Account')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('house_status')
                    ->label('Status Rumah')
                    ->options([
                        'owner' => 'Pemilik',
                        'tenant' => 'Penyewa',
                        'family' => 'Keluarga'
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Keluarga')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        'moved' => 'Pindah'
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFamilies::route('/'),
            'create' => Pages\CreateFamily::route('/create'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }
}
