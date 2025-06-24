<?php

namespace App\Filament\Resident\Resources;

use App\Models\Family;
use App\Models\FamilyMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resident\Resources\FamilyResource\Pages;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Profil Keluarga';

    protected static ?string $modelLabel = 'Data Keluarga';

    protected static ?string $pluralModelLabel = 'Data Keluarga';

    protected static ?string $navigationGroup = 'Data & Profil';

    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kepala Keluarga')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kk_number')
                                    ->label('Nomor Kartu Keluarga')
                                    ->disabled(),
                                Forms\Components\TextInput::make('head_of_family')
                                    ->label('Nama Kepala Keluarga')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('wife_name')
                                    ->label('Nama Istri')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('house_block')
                                    ->label('Blok Rumah')
                                    ->required()
                                    ->maxLength(10),
                            ]),
                    ]),

                Forms\Components\Section::make('Kontak & Status')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone_1')
                                    ->label('Nomor HP 1')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('phone_2')
                                    ->label('Nomor HP 2')
                                    ->tel()
                                    ->maxLength(20),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('house_status')
                                    ->label('Status Rumah')
                                    ->options([
                                        'owner' => 'Milik Sendiri',
                                        'tenant' => 'Sewa',
                                        'family' => 'Tinggal dengan Keluarga',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('family_members_count')
                                    ->label('Jumlah Anggota Keluarga')
                                    ->numeric()
                                    ->default(1)
                                    ->disabled(),
                            ]),
                    ]),

                Forms\Components\Section::make('Kendaraan')
                    ->icon('heroicon-o-truck')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('license_plate_1')
                                    ->label('Plat Nomor 1')
                                    ->maxLength(15),
                                Forms\Components\TextInput::make('license_plate_2')
                                    ->label('Plat Nomor 2')
                                    ->maxLength(15),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Family::query()
                    ->where('user_id', Auth::id())
                    ->with(['members'])
            )
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('kk_number')
                            ->label('No. KK')
                            ->weight('bold')
                            ->color('primary')
                            ->copyable()
                            ->copyMessage('Nomor KK disalin!'),
                        Tables\Columns\TextColumn::make('head_of_family')
                            ->label('Kepala Keluarga')
                            ->weight('medium')
                            ->icon('heroicon-m-user'),
                        Tables\Columns\Layout\Grid::make(2)
                            ->schema([
                                Tables\Columns\TextColumn::make('house_block')
                                    ->label('Blok')
                                    ->badge()
                                    ->color('success'),
                                Tables\Columns\TextColumn::make('family_members_count')
                                    ->label('Jumlah Anggota')
                                    ->badge()
                                    ->color('info')
                                    ->suffix(' orang'),
                            ]),
                    ]),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\BadgeColumn::make('house_status')
                            ->label('Status Rumah')
                            ->colors([
                                'success' => 'owner',
                                'warning' => 'tenant',
                                'info' => 'family',
                            ])
                            ->formatStateUsing(fn(string $state): string => match ($state) {
                                'owner' => '🏠 Milik Sendiri',
                                'tenant' => '🏠 Sewa',
                                'family' => '👨‍👩‍👧‍👦 Tinggal dengan Keluarga',
                                default => $state,
                            }),
                        Tables\Columns\Layout\Grid::make(2)
                            ->schema([
                                Tables\Columns\TextColumn::make('phone_1')
                                    ->label('HP 1')
                                    ->icon('heroicon-m-phone')
                                    ->copyable(),
                                Tables\Columns\TextColumn::make('license_plate_1')
                                    ->label('Plat 1')
                                    ->icon('heroicon-m-truck')
                                    ->placeholder('-'),
                            ]),
                    ])->space(2),
                ])->from('md'),
            ])
            ->contentGrid([
                'md' => 1,
            ])
            ->filters([
                // No filters needed for personal data
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->bulkActions([
                // No bulk actions for family data
            ])
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateHeading('Data keluarga tidak ditemukan')
            ->emptyStateDescription('Hubungi admin untuk menambahkan data keluarga Anda');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilies::route('/'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Residents cannot create family data
    }

    public static function canDelete($record): bool
    {
        return false; // Residents cannot delete family data
    }

    public static function canView($record): bool
    {
        return $record->user_id === Auth::id();
    }

    public static function canEdit($record): bool
    {
        return $record->user_id === Auth::id();
    }
}
