<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityPhotoResource\Pages;
use App\Filament\Resources\ActivityPhotoResource\RelationManagers;
use App\Models\ActivityPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ActivityPhotoResource extends Resource
{
    protected static ?string $model = ActivityPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Foto Kegiatan';

    protected static ?string $modelLabel = 'Foto Kegiatan';

    protected static ?string $pluralModelLabel = 'Foto Kegiatan';

    protected static ?string $navigationGroup = 'Galeri';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Forms\Components\DatePicker::make('activity_date')
                            ->label('Tanggal Kegiatan')
                            ->required()
                            ->default(today()),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tampilkan di Featured')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Foto Kegiatan')
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('photos')
                            ->label('Upload Foto')
                            ->collection('photos')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1920')
                            ->imageResizeTargetHeight('1080')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Informasi Upload')
                    ->schema([
                        Forms\Components\Select::make('uploaded_by')
                            ->label('Diupload Oleh')
                            ->relationship('uploader', 'name')
                            ->searchable()
                            ->preload()
                            ->default(function () {
                                return \Illuminate\Support\Facades\Auth::id();
                            })
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->collection('photos')
                    ->conversion('thumb')
                    ->size(60)
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('activity_date')
                    ->label('Tanggal Kegiatan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('photos_count')
                    ->label('Jumlah Foto')
                    ->counts('media')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('uploader.name')
                    ->label('Diupload Oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('activity_date', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
                Tables\Filters\Filter::make('activity_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('activity_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('activity_date', '<=', $date),
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
            'index' => Pages\ListActivityPhotos::route('/'),
            'create' => Pages\CreateActivityPhoto::route('/create'),
            'edit' => Pages\EditActivityPhoto::route('/{record}/edit'),
        ];
    }
}
