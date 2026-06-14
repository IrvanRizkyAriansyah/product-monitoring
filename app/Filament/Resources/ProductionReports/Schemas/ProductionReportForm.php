<?php

namespace App\Filament\Resources\ProductionReports\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProductionReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('line_id')
                    ->label('Line')
                    ->relationship('line', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn () => Auth::user()?->hasRole('Operator')),

                Select::make('part_id')
                    ->label('Part')
                    ->relationship('part', 'part_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn () => Auth::user()?->hasRole('Operator')),

                Select::make('shift_id')
                    ->label('Shift')
                    ->relationship('shift', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn () => Auth::user()?->hasRole('Operator')),

                Select::make('leader_id')
                    ->label('Leader')
                    ->relationship('leader', 'name')
                    ->default(fn () => Auth::id())
                    ->disabled(
                        fn () => ! Auth::user()?->hasRole('Super Admin')
                    )
                    ->dehydrated(),

                Hidden::make('status')
                    ->default('draft'),

                // Repeater::make('details')
                //     ->relationship()
                //     ->label('Production Detail')
                //     ->schema([

                //         DatePicker::make('report_date')
                //             ->required(),

                //         TextInput::make('target_qty')
                //             ->label('Target')
                //             ->numeric()
                //             ->default(0)
                //             ->required()
                //             ->disabled(
                //                 fn () =>
                //                 ! Auth::user()?->hasAnyRole([
                //                     'Leader',
                //                     'Super Admin',
                //                 ])
                //             ),

                //         TextInput::make('actual_qty')
                //             ->label('Actual')
                //             ->numeric()
                //             ->default(0)
                //             ->required()
                //             ->disabled(
                //                 fn () =>
                //                 ! Auth::user()?->hasAnyRole([
                //                     'Operator',
                //                     'Super Admin',
                //                 ])
                //             ),
                //     ])
                //     ->defaultItems(1)
                //     ->addActionLabel('Tambah Detail Produksi')
                //     ->columnSpanFull(),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}