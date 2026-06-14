<?php

namespace App\Filament\Resources\ProductionReports\RelationManagers;

use App\Filament\Resources\ProductionReports\ProductionReportResource;
use App\Models\PartProcess;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    //protected static ?string $relatedResource = ProductionReportResource::class;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('report_date')
                    ->required()
                    ->default(now())
                    ->disabled(
                        fn() => Auth::user()?->hasRole('Operator')
                    ),

                Select::make('part_process_id')
                    ->label('Process & Machine')
                    ->relationship(
                        'partProcess',
                        'id'
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn($record) =>
                        $record->process->process_name
                            . ' - ' .
                            $record->machine->machine_name
                    )
                    ->options(function ($livewire) {

                        $partId = $livewire->ownerRecord->part_id;

                        return PartProcess::query()
                            ->where('part_id', $partId)
                            ->with([
                                'process',
                                'machine',
                            ])
                            ->get()
                            ->mapWithKeys(fn($item) => [
                                $item->id =>
                                $item->process->process_name
                                    . ' - ' .
                                    $item->machine->machine_name
                            ]);
                    })
                    ->searchable()
                    ->preload()
                    ->disabled(
                        fn() => Auth::user()?->hasRole('Operator')
                    )
                    ->required(),

                TextInput::make('target_qty')
                    ->label('Target')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->disabled(
                        fn() =>
                        ! Auth::user()?->hasAnyRole([
                            'Leader',
                            'Super Admin',
                        ])
                    ),

                TextInput::make('actual_qty')
                    ->label('Actual')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->disabled(
                        fn() =>
                        ! Auth::user()?->hasAnyRole([
                            'Leader',
                            'Operator',
                            'Super Admin',
                        ])
                    ),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('report_date')
                    ->date()
                    ->sortable(),

                TextColumn::make('partProcess.process.process_name')
                    ->label('Process')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('partProcess.machine.machine_name')
                    ->label('Machine')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('target_qty')
                    ->label('Target')
                    ->sortable(),

                TextColumn::make('actual_qty')
                    ->label('Actual')
                    ->sortable(),
            ])

            ->headerActions([
                CreateAction::make()
                    ->visible(
                        fn() =>
                        Auth::user()?->hasAnyRole([
                            'Leader',
                            'Super Admin',
                        ])
                    ),
            ])

            ->recordActions([

                EditAction::make(),

                DeleteAction::make()
                    ->visible(
                        fn() =>
                        Auth::user()?->hasAnyRole([
                            'Leader',
                            'Super Admin',
                        ])
                    ),
            ]);
    }
}
