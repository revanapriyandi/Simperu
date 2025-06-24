<?php

namespace App\Filament\Resources\FamilyResource\RelationManagers;

use App\Models\FamilyMember;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Anggota Keluarga';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
            Tables\Columns\TextColumn::make('nik')->label('NIK')->searchable(),
            Tables\Columns\TextColumn::make('relationship')->label('Hubungan')->formatStateUsing(fn($state) => match ($state) {
                'head' => 'Kepala Keluarga',
                'wife' => 'Istri',
                'child' => 'Anak',
                'parent' => 'Orang Tua',
                'other' => 'Lainnya',
                default => $state,
            }),
            Tables\Columns\TextColumn::make('birth_date')->label('Tanggal Lahir')->date(),
            Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin')->formatStateUsing(fn($state) => $state === 'male' ? 'Laki-laki' : ($state === 'female' ? 'Perempuan' : '-')),
            Tables\Columns\TextColumn::make('occupation')->label('Pekerjaan'),
        ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('keluarkan')
                    ->label('Keluarkan')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['relationship' => 'other']);
                    }),
            ]);
    }
}
