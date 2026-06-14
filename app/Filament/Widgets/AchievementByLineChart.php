<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class AchievementByLineChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Achievement by Line';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
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
                    $q->whereHas('details', function ($query) use ($date) {
                        $query->whereDate(
                            'report_date',
                            '>=',
                            $date
                        );
                    });
                }
            )

            ->when(
                $this->filters['end_date'] ?? null,
                function ($q, $date) {
                    $q->whereHas('details', function ($query) use ($date) {
                        $query->whereDate(
                            'report_date',
                            '<=',
                            $date
                        );
                    });
                }
            )

            ->get()
            ->groupBy('line_id');

        $labels = [];
        $data = [];

        foreach ($reports as $items) {

            $line = $items->first()->line?->name;

            $target = $items
                ->flatMap->details
                ->sum('target_qty');

            $actual = $items
                ->flatMap->details
                ->sum('actual_qty');

            $achievement = $target > 0
                ? round(($actual / $target) * 100, 2)
                : 0;

            $labels[] = $line;
            $data[] = $achievement;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Achievement (%)',
                    'data' => $data,

                    'backgroundColor' => [
                        '#22C55E',
                        '#3B82F6',
                        '#F59E0B',
                        '#EF4444',
                        '#8B5CF6',
                        '#06B6D4',
                    ],
                ],
            ],

            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                ],
            ],

            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
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