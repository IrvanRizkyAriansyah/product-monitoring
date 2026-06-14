<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class LinePerformanceStats extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 2;

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $reports = ProductionReport::query()
            ->with(['line', 'details'])

            ->when(
                $this->filters['line_id'] ?? null,
                fn ($q, $id) => $q->where('line_id', $id)
            )

            ->when(
                $this->filters['shift_id'] ?? null,
                fn ($q, $id) => $q->where('shift_id', $id)
            )

            ->when(
                $this->filters['status'] ?? null,
                fn ($q, $status) => $q->where('status', $status)
            )

            ->when(
                $this->filters['start_date'] ?? null,
                function ($q, $date) {
                    $q->whereHas(
                        'details',
                        fn ($detail) =>
                        $detail->whereDate(
                            'report_date',
                            '>=',
                            $date
                        )
                    );
                }
            )

            ->when(
                $this->filters['end_date'] ?? null,
                function ($q, $date) {
                    $q->whereHas(
                        'details',
                        fn ($detail) =>
                        $detail->whereDate(
                            'report_date',
                            '<=',
                            $date
                        )
                    );
                }
            )

            ->get()
            ->groupBy('line_id');

        $performances = collect();

        foreach ($reports as $lineId => $items) {

            $target = $items
                ->flatMap->details
                ->sum('target_qty');

            $actual = $items
                ->flatMap->details
                ->sum('actual_qty');

            $achievement = $target > 0
                ? round(($actual / $target) * 100, 2)
                : 0;

            $performances->push([
                'line' => $items->first()->line?->name ?? '-',
                'achievement' => $achievement,
            ]);
        }

        $best = $performances
            ->sortByDesc('achievement')
            ->first();

        $worst = $performances
            ->sortBy('achievement')
            ->first();

        return [

            Stat::make(
                '🏆 Best Line',
                $best['line'] ?? '-'
            )
                ->description(
                    ($best['achievement'] ?? 0) . '% Achievement'
                )
                ->color('success'),

            Stat::make(
                '⚠ Worst Line',
                $worst['line'] ?? '-'
            )
                ->description(
                    ($worst['achievement'] ?? 0) . '% Achievement'
                )
                ->color('danger'),

        ];
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Manager',
        ]) ?? false;
    }
}