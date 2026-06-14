<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class TopLeaderRanking extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Top Leader Performance';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductionReport::query()

                    ->join(
                        'production_report_details',
                        'production_reports.id',
                        '=',
                        'production_report_details.report_id'
                    )

                    ->join(
                        'users',
                        'production_reports.leader_id',
                        '=',
                        'users.id'
                    )

                    ->selectRaw("
                        production_reports.leader_id as id,
                        users.name as leader_name,

                        COUNT(DISTINCT production_reports.id) as total_reports,

                        ROUND(
                            (
                                SUM(production_report_details.actual_qty)
                                /
                                NULLIF(
                                    SUM(production_report_details.target_qty),
                                    0
                                )
                            ) * 100,
                            2
                        ) as avg_achievement
                    ")

                    ->when(
                        $this->filters['start_date'] ?? null,
                        fn ($q, $date) =>
                        $q->whereDate(
                            'production_report_details.report_date',
                            '>=',
                            $date
                        )
                    )

                    ->when(
                        $this->filters['end_date'] ?? null,
                        fn ($q, $date) =>
                        $q->whereDate(
                            'production_report_details.report_date',
                            '<=',
                            $date
                        )
                    )

                    ->when(
                        $this->filters['line_id'] ?? null,
                        fn ($q, $id) =>
                        $q->where(
                            'production_reports.line_id',
                            $id
                        )
                    )

                    ->when(
                        $this->filters['shift_id'] ?? null,
                        fn ($q, $id) =>
                        $q->where(
                            'production_reports.shift_id',
                            $id
                        )
                    )

                    ->when(
                        $this->filters['status'] ?? null,
                        fn ($q, $status) =>
                        $q->where(
                            'production_reports.status',
                            $status
                        )
                    )

                    ->groupBy(
                        'production_reports.id',
                        'production_reports.leader_id',
                        'users.name'
                    )
            )

            ->defaultSort('avg_achievement', 'desc')

            ->columns([

                TextColumn::make('leader_name')
                    ->label('Leader'),

                TextColumn::make('total_reports')
                    ->label('Reports')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('avg_achievement')
                    ->label('Achievement')
                    ->suffix('%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 100 => 'success',
                        $state >= 85 => 'warning',
                        default => 'danger',
                    }),

            ])

            ->paginated([5]);
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Manager',
        ]) ?? false;
    }
}