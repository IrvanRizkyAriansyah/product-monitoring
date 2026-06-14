<?php

namespace App\Filament\Resources\ProductionReports\Tables;

use App\Filament\Resources\ProductionReports\ProductionReportResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\Reject;
use App\Models\Reject_Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use pxlrbt\FilamentExcel\Actions\ExportAction;
use App\Exports\ProductionReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\DatePicker;
use App\Models\Line;
use App\Models\Shift;

class ProductionReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('line.name')
                    ->label('Line')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('part.part_name')
                    ->label('Part')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('shift.name')
                    ->label('Shift')
                    ->sortable(),
                TextColumn::make('leader.name')
                    ->label('Leader')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('report_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('total_target')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_actual')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('achievement')
                    ->numeric()
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('line_id')
                    ->label('Line')
                    ->relationship('line', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('shift_id')
                    ->label('Shift')
                    ->relationship('shift', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn($record): bool => ProductionReportResource::canEdit($record)),
                DeleteAction::make()
                    ->visible(fn($record): bool => ProductionReportResource::canDelete($record)),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(
                        fn($record) =>
                        Auth::user()?->hasAnyRole([
                            'Manager',
                            'Super Admin',
                        ])
                            && $record->status === 'submitted'
                    )
                    ->action(function ($record) {

                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(
                        fn($record) =>
                        Auth::user()?->hasAnyRole([
                            'Manager',
                            'Super Admin',
                        ])
                            && $record->status === 'submitted'
                    )
                    ->action(function ($record) {

                        $record->update([
                            'status' => 'rejected',
                            // 'approved_by' => Auth::id(),
                            // 'approved_at' => now(),
                        ]);
                    }),

            ])
            ->toolbarActions([
                // ExportAction::make()
                //     ->label('Export Excel'),
                //     // ->fileName(fn () => 'production-report-' . now()->format('Ymd-His')),
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
                Action::make('export')

                    ->label('Export Excel')

                    ->icon('heroicon-o-document-arrow-down')

                    ->form([

                        DatePicker::make('start_date')
                            ->default(now())
                            ->required()
                            ->live(),

                        DatePicker::make('end_date')
                            ->default(now())
                            ->required()
                            ->minDate(fn ($get) => $get('start_date')),

                        Select::make('line_id')
                            ->label('Line')
                            ->searchable()
                            ->options(
                                Line::pluck(
                                    'name',
                                    'id'
                                )
                            ),

                        Select::make('shift_id')
                            ->label('Shift')
                            ->searchable()
                            ->options(
                                Shift::pluck(
                                    'name',
                                    'id'
                                )
                            ),

                    ])

                    ->action(function ($data) {
                        $line = $data['line_id']
                            ? Line::find($data['line_id'])?->name
                            : 'All-Line';

                        $fileName =
                            'Production_Report_' .
                            $line .
                            '_' .
                            $data['start_date'] .
                            '_to_' .
                            $data['end_date'] .
                            '.xlsx';

                        return Excel::download(

                            new ProductionReportExport(
                                $data['start_date'],
                                $data['end_date'],
                                $data['line_id'],
                                $data['shift_id'],
                            ),

                            $fileName
                        );
                    }),

            ]);
    }
}
