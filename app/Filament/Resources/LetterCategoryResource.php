<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterCategoryResource\Pages;
use App\Filament\Resources\LetterCategoryResource\RelationManagers;
use App\Models\LetterCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LetterCategoryResource extends Resource
{
    protected static ?string $model = LetterCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Kategori Surat';

    protected static ?string $modelLabel = 'Kategori Surat';

    protected static ?string $pluralModelLabel = 'Kategori Surat';

    protected static ?string $navigationGroup = 'Surat Menyurat';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Kategori')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Contoh: SKD, SKU, SKTM')
                            ->rules(['alpha_dash'])
                            ->helperText('Gunakan kode singkat untuk kategori (tanpa spasi, gunakan _ atau -)'),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Surat Keterangan Domisili'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Kategori aktif akan muncul dalam pilihan surat'),
                    ])->columns(2),

                Forms\Components\Section::make('Deskripsi & Template')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->placeholder('Jelaskan tentang kategori surat ini...')
                            ->helperText('Deskripsi singkat tentang jenis surat ini'),
                        Forms\Components\RichEditor::make('template')
                            ->label('Template Surat')
                            ->toolbarButtons([
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'table',
                                'undo',
                            ])
                            ->placeholder('Masukkan template surat di sini...')
                            ->helperText('Template ini akan digunakan sebagai dasar pembuatan surat. Gunakan placeholder seperti {nama}, {alamat}, dll.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->placeholder('Tidak ada deskripsi')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('complaint_letters_count')
                    ->label('Jumlah Surat')
                    ->counts('complaintLetters')
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua Status'),
                Tables\Filters\Filter::make('has_template')
                    ->label('Memiliki Template')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('template')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle_status')
                    ->label(fn(LetterCategory $record): string => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn(LetterCategory $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn(LetterCategory $record): string => $record->is_active ? 'danger' : 'success')
                    ->action(fn(LetterCategory $record) => $record->update(['is_active' => !$record->is_active]))
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn($records) => $records->each->update(['is_active' => true]))
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['is_active' => false]))
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
            'index' => Pages\ListLetterCategories::route('/'),
            'create' => Pages\CreateLetterCategory::route('/create'),
            'edit' => Pages\EditLetterCategory::route('/{record}/edit'),
        ];
    }
}
