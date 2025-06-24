<?php

namespace App\Filament\Resources\FinancialReportResource\Pages;

use App\Filament\Resources\FinancialReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListFinancialReports extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = FinancialReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('summary')
                ->label('Ringkasan Keuangan')
                ->icon('heroicon-o-chart-pie')
                ->color('primary')
                ->url(fn (): string => \App\Filament\Pages\FinancialSummary::getUrl()),
            
            Actions\CreateAction::make()
                ->label('Tambah Transaksi'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return FinancialReportResource::getWidgets();
    }
}
