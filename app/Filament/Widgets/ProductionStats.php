<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use App\Models\ProductionReportDetail;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductionStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $detailQuery = ProductionReportDetail::query()

            ->when(
                $this->filters['start_date'] ?? null,
                fn ($q, $date) =>
                $q->whereDate('report_date', '>=', $date)
            )

            ->when(
                $this->filters['end_date'] ?? null,
                fn ($q, $date) =>
                $q->whereDate('report_date', '<=', $date)
            )

            ->when(
                $this->filters['line_id'] ?? null,
                function ($q, $id) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where('line_id', $id)
                    );
                }
            )

            ->when(
                $this->filters['shift_id'] ?? null,
                function ($q, $id) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where('shift_id', $id)
                    );
                }
            )

            ->when(
                $this->filters['status'] ?? null,
                function ($q, $status) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where('status', $status)
                    );
                }
            );

        $headerQuery = ProductionReport::query()

            ->when(
                $this->filters['line_id'] ?? null,
                fn ($q, $id) =>
                $q->where('line_id', $id)
            )

            ->when(
                $this->filters['shift_id'] ?? null,
                fn ($q, $id) =>
                $q->where('shift_id', $id)
            )

            ->when(
                $this->filters['status'] ?? null,
                fn ($q, $status) =>
                $q->where('status', $status)
            );

        $target = (clone $detailQuery)->sum('target_qty');

        $actual = (clone $detailQuery)->sum('actual_qty');

        $achievement = $target > 0
            ? round(($actual / $target) * 100, 2)
            : 0;

        $pending = (clone $headerQuery)
            ->where('status', 'submitted')
            ->count();

        return [

            Stat::make(
                'Total Target',
                number_format($target)
            )
                ->description('Target produksi')
                ->descriptionIcon('heroicon-m-flag')
                ->color('primary'),

            Stat::make(
                'Total Actual',
                number_format($actual)
            )
                ->description('Aktual produksi')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make(
                'Achievement',
                $achievement . '%'
            )
                ->description(
                    $achievement >= 100
                        ? 'Target tercapai'
                        : 'Belum mencapai target'
                )
                ->descriptionIcon(
                    $achievement >= 100
                        ? 'heroicon-m-arrow-trending-up'
                        : 'heroicon-m-arrow-trending-down'
                )
                ->color(
                    match (true) {
                        $achievement >= 100 => 'success',
                        $achievement >= 85 => 'warning',
                        default => 'danger',
                    }
                ),

            Stat::make(
                'Pending Approval',
                $pending
            )
                ->description('Menunggu approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color(
                    $pending > 0
                        ? 'warning'
                        : 'success'
                ),

        ];
    }
}