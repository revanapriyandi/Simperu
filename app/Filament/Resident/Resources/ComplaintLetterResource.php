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
use App\Filament\Resident\Resources\ComplaintLetterResource\Pages;

class ComplaintLetterResource extends Resource
{
    protected static ?string $model = ComplaintLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Surat Pengaduan';

    protected static ?string $modelLabel = 'Surat Pengaduan';

    protected static ?string $pluralModelLabel = 'Surat Pengaduan';

    protected static ?string $navigationGroup = 'Layanan';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek Pengaduan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('priority')
                            ->label('Prioritas')
                            ->required()
                            ->options([
                                'low' => 'Rendah',
                                'medium' => 'Sedang',
                                'high' => 'Tinggi',
                                'urgent' => 'Sangat Penting'
                            ])
                            ->default('medium'),
                        Forms\Components\RichEditor::make('content')
                            ->label('Isi Pengaduan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Lampiran (Opsional)')
                            ->multiple()
                            ->directory('complaint-attachments')
                            ->acceptedFileTypes(['image/*', 'application/pdf'])
                            ->maxSize(5120) // 5MB
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status Pengaduan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'submitted' => 'Diajukan',
                                'in_review' => 'Sedang Ditinjau',
                                'in_progress' => 'Sedang Diproses',
                                'resolved' => 'Selesai',
                                'closed' => 'Ditutup'
                            ])
                            ->disabled()
                            ->default('submitted'),
                        Forms\Components\RichEditor::make('admin_response')
                            ->label('Tanggapan Admin')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->select(['id', 'subject', 'category_id', 'priority', 'status', 'submitted_at', 'updated_at'])
                    ->with(['category:id,name'])
                    ->where('submitted_by', Auth::id())
            )
            ->defaultPaginationPageOption(25) // Add pagination
            ->poll('60s') // Reduce polling frequency
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50)
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'secondary' => 'low',
                        'warning' => 'medium',
                        'danger' => 'high',
                        'success' => 'urgent',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'low' => 'Rendah',
                        'medium' => 'Sedang',
                        'high' => 'Tinggi',
                        'urgent' => 'Sangat Penting',
                        default => $state,
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'submitted',
                        'info' => 'in_review',
                        'primary' => 'in_progress',
                        'success' => 'resolved',
                        'secondary' => 'closed',
                    ])
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'submitted' => 'Diajukan',
                        'in_review' => 'Sedang Ditinjau',
                        'in_progress' => 'Sedang Diproses',
                        'resolved' => 'Selesai',
                        'closed' => 'Ditutup',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'submitted'),
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
}
