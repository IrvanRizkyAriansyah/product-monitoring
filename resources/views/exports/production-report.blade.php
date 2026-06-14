<table border="1">

    <thead>

        <tr>
            <td colspan="{{ 8 + (count(iterator_to_array($dates)) * 2) }}"
                align="center"
                style="background-color:#4472C4;color:white;">
                <b>PRODUCTION REPORT</b>
            </td>
        </tr>

        <tr></tr>

        <tr>
            <td><b>Line</b></td>
            <td>{{ $lineName }}</td>
        </tr>

        <tr>
            <td><b>Shift</b></td>
            <td>{{ $shiftName }}</td>
        </tr>

        <tr>
            <td><b>Periode</b></td>
            <td>{{ $startDate }} s/d {{ $endDate }}</td>
        </tr>

        <tr>
            <td><b>Printed By</b></td>
            <td>{{ auth()->user()->name }}</td>
        </tr>

        <tr>
            <td><b>Print Date</b></td>
            <td>{{ now()->format('d/m/Y H:i') }}</td>
        </tr>

        <tr></tr>

        <tr>

            <th rowspan="2">NO</th>
            <th rowspan="2">NAMA PART</th>
            <th rowspan="2">PART NO</th>
            <th rowspan="2">PROSES</th>
            <th rowspan="2">MESIN</th>

            <th colspan="{{ count(iterator_to_array($dates)) }}">
                PLANNING
            </th>

            <th colspan="{{ count(iterator_to_array($dates)) }}">
                ACTUAL
            </th>

            <th rowspan="2">
                JUMLAH PRODUKSI
            </th>

            <th rowspan="2">
                PLANNING PRODUKSI
            </th>

            <th rowspan="2">
                PERSENTASE
            </th>

        </tr>

        <tr>

            @foreach($dates as $date)
                <th>{{ $date->format('d') }}</th>
            @endforeach

            @foreach($dates as $date)
                <th>{{ $date->format('d') }}</th>
            @endforeach

        </tr>

    </thead>

    <tbody>

    @php
        $no = 1;
    @endphp

    @foreach($reports as $report)

        @foreach(
            $report->details
                ->groupBy('part_process_id')
            as $partProcessId => $details
        )

            @php

                $partProcess =
                    $details->first()->partProcess;

                $totalTarget =
                    $details->sum('target_qty');

                $totalActual =
                    $details->sum('actual_qty');

            @endphp

            <tr>

                <td>{{ $no++ }}</td>

                <td>
                    {{ $report->part?->part_name }}
                </td>

                <td>
                    {{ $report->part?->part_no }}
                </td>

                <td>
                    {{ $partProcess?->process?->process_name }}
                </td>

                <td>
                    {{ $partProcess?->machine?->machine_name }}
                </td>

                {{-- PLANNING --}}

                @foreach($dates as $date)

                    @php

                        $item =
                            $details->first(
                                function($detail)
                                use ($date){

                                    return
                                    \Carbon\Carbon::parse(
                                        $detail->report_date
                                    )->toDateString()

                                    ==

                                    $date->toDateString();

                                }
                            );

                    @endphp

                    <td>
                        {{ $item?->target_qty ?? 0 }}
                    </td>

                @endforeach

                {{-- ACTUAL --}}

                @foreach($dates as $date)

                    @php

                        $item =
                            $details->first(
                                function($detail)
                                use ($date){

                                    return
                                    \Carbon\Carbon::parse(
                                        $detail->report_date
                                    )->toDateString()

                                    ==

                                    $date->toDateString();

                                }
                            );

                    @endphp

                    <td>
                        {{ $item?->actual_qty ?? 0 }}
                    </td>

                @endforeach

                <td>
                    {{ $totalActual }}
                </td>

                <td>
                    {{ $totalTarget }}
                </td>

                <td>
                    {{
                        $totalTarget > 0
                        ? round(
                            ($totalActual / $totalTarget) * 100,
                            2
                        )
                        : 0
                    }}%
                </td>

            </tr>

        @endforeach

    @endforeach

    </tbody>

</table>