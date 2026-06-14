<?php

namespace App\Exports;

use App\Models\Line;
use App\Models\ProductionReport;
use App\Models\Shift;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductionReportExport implements FromView
{
    protected $startDate;
    protected $endDate;
    protected $lineId;
    protected $shiftId;

    public function __construct(
        $startDate,
        $endDate,
        $lineId = null,
        $shiftId = null
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->lineId = $lineId;
        $this->shiftId = $shiftId;
    }

    public function view(): View
    {
        // $query = ProductionReport::query()
        //     ->with([
        //         'line',
        //         'part',
        //         'details',
        //     ])
        //     ->whereBetween(
        //         'report_date',
        //         [
        //             $this->startDate,
        //             $this->endDate,
        //         ]
        //     );

        $reports = ProductionReport::query()
            ->with([
                'line',
                'part',
                'details',
                'details.partProcess.process',
                'details.partProcess.machine',
            ])
            ->whereHas('details', function ($query) {
                $query->whereBetween(
                    'report_date',
                    [
                        $this->startDate,
                        $this->endDate,
                    ]
                );
            })
            ->when(
                $this->lineId,
                fn ($query) =>
                $query->where(
                    'line_id',
                    $this->lineId
                )
            )
            ->when(
                $this->shiftId,
                fn ($query) =>
                $query->where('shift_id', $this->shiftId)
            )
            ->get();

        $dates = CarbonPeriod::create(
            $this->startDate,
            $this->endDate
        );

        return view(
            'exports.production-report',
            [
                'reports' => $reports,
                'dates' => $dates,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'lineName' => $this->lineId
                    ? Line::find($this->lineId)?->name
                    : 'All Line',
                'shiftName' => $this->shiftId
                    ? Shift::find($this->shiftId)?->name
                    : 'All Shift',
            ]
        );
    }
}
