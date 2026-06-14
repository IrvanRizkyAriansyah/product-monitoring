<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReportDetail;
use App\Models\Line;
use Filament\Forms\Components\Select;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;

class ProductionTrend extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected ?string $heading = 'Target vs Actual';

    protected int | string | array $columnSpan = 'full';

    public ?string $range = '7';

    public ?string $line_id = null;

    protected ?string $maxHeight = '350px';

    protected function getFormSchema(): array
    {
        return [
            Select::make('range')
                ->label('Periode')
                ->options([
                    '7' => '7 Hari',
                    '30' => '30 Hari',
                    '90' => '90 Hari',
                ])
                ->default('7'),

            Select::make('line_id')
                ->label('Line')
                ->options(
                    Line::pluck('name', 'id')->toArray()
                )
                ->searchable()
                ->placeholder('Semua Line'),
        ];
    }

    protected function getData(): array
    {
        $query = ProductionReportDetail::query()
            ->with('report')

            ->when(
                $this->filters['start_date'] ?? null,
                fn ($q, $date) =>
                $q->whereDate(
                    'report_date',
                    '>=',
                    $date
                )
            )

            ->when(
                $this->filters['end_date'] ?? null,
                fn ($q, $date) =>
                $q->whereDate(
                    'report_date',
                    '<=',
                    $date
                )
            )

            ->when(
                $this->filters['line_id'] ?? $this->line_id,
                function ($q, $id) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where(
                            'line_id',
                            $id
                        )
                    );
                }
            )

            ->when(
                $this->filters['shift_id'] ?? null,
                function ($q, $id) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where(
                            'shift_id',
                            $id
                        )
                    );
                }
            )

            ->when(
                $this->filters['status'] ?? null,
                function ($q, $status) {
                    $q->whereHas(
                        'report',
                        fn ($report) =>
                        $report->where(
                            'status',
                            $status
                        )
                    );
                }
            );

        $range = (int) ($this->range ?? 7);

        $query->whereDate(
            'report_date',
            '>=',
            now()->subDays($range)
        );

        $reports = $query
            ->selectRaw("
                report_date,
                SUM(target_qty) as total_target,
                SUM(actual_qty) as total_actual
            ")
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Target',
                    'data' => $reports
                        ->pluck('total_target')
                        ->toArray(),

                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',

                    'tension' => 0.3,
                ],

                [
                    'label' => 'Actual',
                    'data' => $reports
                        ->pluck('total_actual')
                        ->toArray(),

                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16,185,129,0.2)',

                    'tension' => 0.3,
                ],
            ],

            'labels' => $reports
                ->pluck('report_date')
                ->map(
                    fn ($date) =>
                    \Carbon\Carbon::parse($date)
                        ->format('d M')
                )
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],

            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],

            'maintainAspectRatio' => false,
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Manager',
            'Leader',
        ]) ?? false;
    }
}