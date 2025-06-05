<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyMemberResource\Pages;
use App\Filament\Resources\FamilyMemberResource\RelationManagers;
use App\Models\FamilyMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyMemberResource extends Resource
{
    protected static ?string $model = FamilyMember::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Anggota Keluarga';

    protected static ?string $modelLabel = 'Anggota Keluarga';

    protected static ?string $pluralModelLabel = 'Anggota Keluarga';

    protected static ?string $navigationGroup = 'Data Warga';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Keluarga')
                    ->schema([
                        Forms\Components\Select::make('family_id')
                            ->label('Keluarga')
                            ->relationship('family', 'head_of_family')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('kk_number')
                                    ->label('Nomor KK')
                                    ->required()
                                    ->unique(),
                                Forms\Components\TextInput::make('head_of_family')
                                    ->label('Kepala Keluarga')
                                    ->required(),
                                Forms\Components\TextInput::make('house_block')
                                    ->label('Blok Rumah')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('relationship')
                            ->label('Hubungan Keluarga')
                            ->required()
                            ->options([
                                'head' => 'Kepala Keluarga',
                                'wife' => 'Istri',
                                'child' => 'Anak',
                                'parent' => 'Orang Tua',
                                'other' => 'Lainnya'
                            ])
                            ->default('child'),
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->displayFormat('d/m/Y')
                            ->maxDate(today()),
                        Forms\Components\Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan'
                            ]),
                        Forms\Components\TextInput::make('occupation')
                            ->label('Pekerjaan')
                            ->maxLength(100)
                            ->placeholder('Pelajar, Karyawan, dll'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family.head_of_family')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family.house_block')
                    ->label('Blok')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('relationship')
                    ->label('Hubungan')
                    ->colors([
                        'danger' => 'head',
                        'warning' => 'wife',
                        'success' => 'child',
                        'info' => 'parent',
                        'gray' => 'other',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'head' => 'Kepala Keluarga',
                        'wife' => 'Istri',
                        'child' => 'Anak',
                        'parent' => 'Orang Tua',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->label('Umur')
                    ->getStateUsing(fn(FamilyMember $record): string => $record->age ? $record->age . ' tahun' : 'Tidak diketahui')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->colors([
                        'info' => 'male',
                        'warning' => 'female',
                    ])
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                        default => 'Tidak diketahui',
                    }),
                Tables\Columns\TextColumn::make('occupation')
                    ->label('Pekerjaan')
                    ->searchable()
                    ->placeholder('Tidak diketahui'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('family_id', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('relationship')
                    ->label('Hubungan Keluarga')
                    ->options([
                        'head' => 'Kepala Keluarga',
                        'wife' => 'Istri',
                        'child' => 'Anak',
                        'parent' => 'Orang Tua',
                        'other' => 'Lainnya'
                    ]),
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan'
                    ]),
                Tables\Filters\SelectFilter::make('family')
                    ->label('Keluarga')
                    ->relationship('family', 'head_of_family')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('age_range')
                    ->form([
                        Forms\Components\TextInput::make('min_age')
                            ->label('Umur Minimum')
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('max_age')
                            ->label('Umur Maksimum')
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_age'],
                                fn(Builder $query, $age): Builder => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) >= ?', [$age])
                            )
                            ->when(
                                $data['max_age'],
                                fn(Builder $query, $age): Builder => $query->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) <= ?', [$age])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListFamilyMembers::route('/'),
            'create' => Pages\CreateFamilyMember::route('/create'),
            'edit' => Pages\EditFamilyMember::route('/{record}/edit'),
        ];
    }
}
