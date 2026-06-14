<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AchievementPerLine extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Production Summary';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder =>
                ProductionReport::query()
                    ->with([
                        'line',
                        'part',
                        'shift',
                        'leader',
                        'details',
                    ])

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
            )

            ->columns([

                TextColumn::make('line.name')
                    ->label('Line')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('part.part_name')
                    ->label('Part')
                    ->searchable(),

                TextColumn::make('shift.name')
                    ->label('Shift'),

                TextColumn::make('leader.name')
                    ->label('Leader'),

                TextColumn::make('total_target')
                    ->label('Target')
                    ->getStateUsing(
                        fn ($record) =>
                        $record->details->sum('target_qty')
                    )
                    ->sortable(),

                TextColumn::make('total_actual')
                    ->label('Actual')
                    ->getStateUsing(
                        fn ($record) =>
                        $record->details->sum('actual_qty')
                    )
                    ->sortable(),

                TextColumn::make('achievement')
                    ->label('Achievement')
                    ->getStateUsing(function ($record) {

                        $target = $record
                            ->details
                            ->sum('target_qty');

                        $actual = $record
                            ->details
                            ->sum('actual_qty');

                        if ($target <= 0) {
                            return '0 %';
                        }

                        return round(
                            ($actual / $target) * 100,
                            2
                        ) . ' %';
                    })
                    ->badge()
                    ->color(function ($state) {

                        $value = (float) str_replace('%', '', $state);

                        if ($value >= 100) {
                            return 'success';
                        }

                        if ($value >= 85) {
                            return 'warning';
                        }

                        return 'danger';
                    }),

                TextColumn::make('status')
                    ->badge(),
            ])

            ->defaultPaginationPageOption(10);
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Manager',
        ]) ?? false;
    }
}