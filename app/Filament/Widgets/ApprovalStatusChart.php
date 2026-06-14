<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class ApprovalStatusChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 6;

    protected ?string $heading = 'Approval Status';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $query = ProductionReport::query()

            ->when(
                $this->filters['line_id'] ?? null,
                fn ($q, $id) => $q->where('line_id', $id)
            )

            ->when(
                $this->filters['shift_id'] ?? null,
                fn ($q, $id) => $q->where('shift_id', $id)
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
            );

        return [
            'datasets' => [
                [
                    'data' => [
                        (clone $query)->where('status', 'draft')->count(),
                        (clone $query)->where('status', 'submitted')->count(),
                        (clone $query)->where('status', 'approved')->count(),
                        (clone $query)->where('status', 'rejected')->count(),
                    ],

                    'backgroundColor' => [
                        '#6B7280', // Draft
                        '#F59E0B', // Submitted
                        '#22C55E', // Approved
                        '#EF4444', // Rejected
                    ],

                    'hoverOffset' => 10,
                ],
            ],

            'labels' => [
                'Draft',
                'Submitted',
                'Approved',
                'Rejected',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],

            'cutout' => '60%',
            'maintainAspectRatio' => false,
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