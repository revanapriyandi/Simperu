<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintLetterResource\Pages;
use App\Filament\Resources\ComplaintLetterResource\RelationManagers;
use App\Models\ComplaintLetter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->schema([
                        Forms\Components\TextInput::make('letter_number')
                            ->label('Nomor Surat')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->default(fn() => 'SPG/' . date('Y') . '/' . str_pad(ComplaintLetter::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT)),
                        Forms\Components\Select::make('user_id')
                            ->label('Pengaju')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori Surat')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('letter_date')
                            ->label('Tanggal Surat')
                            ->required()
                            ->default(today()),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('recipient')
                            ->label('Tujuan Surat')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Kepada: Pengurus RT/RW/Kelurahan'),
                        Forms\Components\RichEditor::make('description')
                            ->label('Isi Pengaduan')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status & Proses')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Menunggu',
                                'processed' => 'Diproses',
                                'in_progress' => 'Sedang Berlangsung',
                                'completed' => 'Selesai',
                                'rejected' => 'Ditolak'
                            ])
                            ->default('pending'),
                        Forms\Components\RichEditor::make('admin_notes')
                            ->label('Catatan Admin')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('pdf_path')
                            ->label('File PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('complaint-letters')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengaju')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('letter_date')
                    ->label('Tanggal Surat')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processed',
                        'primary' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'processed' => 'Diproses',
                        'in_progress' => 'Sedang Berlangsung',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                Tables\Columns\IconColumn::make('pdf_path')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray'),
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
                        'processed' => 'Diproses',
                        'in_progress' => 'Sedang Berlangsung',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak'
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListComplaintLetters::route('/'),
            'create' => Pages\CreateComplaintLetter::route('/create'),
            'edit' => Pages\EditComplaintLetter::route('/{record}/edit'),
        ];
    }
}
