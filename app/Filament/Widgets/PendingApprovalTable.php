<?php

namespace App\Filament\Widgets;

use App\Models\ProductionReport;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PendingApprovalTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 9;

    protected static ?string $heading = 'Pending Approval';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder =>
                ProductionReport::query()
                    ->with([
                        'line',
                        'part',
                        'leader',
                        'details',
                    ])
                    ->where('status', 'submitted')

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

                    ->latest()
            )

            ->columns([

                TextColumn::make('line.name')
                    ->label('Line')
                    ->sortable(),

                TextColumn::make('part.part_name')
                    ->label('Part')
                    ->sortable(),

                TextColumn::make('leader.name')
                    ->label('Leader')
                    ->sortable(),

                TextColumn::make('details_count')
                    ->label('Days')
                    ->counts('details'),

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

            ])

            ->recordActions([

                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                    }),

                Action::make('view')
                    ->url(fn ($record) =>
                        route(
                            'filament.admin.resources.production-reports.edit',
                            $record
                        )
                    )
                    ->icon('heroicon-o-eye'),

            ])

            ->paginated([5, 10, 25])

            ->defaultPaginationPageOption(5);
    }

    public static function canView(): bool
    {
        return Auth::user()?->hasAnyRole([
            'Super Admin',
            'Manager',
        ]) ?? false;
    }
}