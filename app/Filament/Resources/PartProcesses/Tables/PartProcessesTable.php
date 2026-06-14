<?php

namespace App\Filament\Resources\PartProcesses\Tables;

use App\Imports\PartProcessImport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

class PartProcessesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('part.part_name')
                    ->searchable(),

                TextColumn::make('process.process_name'),

                TextColumn::make('machine.machine_name'),

                TextColumn::make('sequence'),

                TextColumn::make('std_run'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make('import')
                    ->label('Import Excel')
                    ->icon('heroicon-o-document-arrow-up')
                    ->form([

                        Placeholder::make('template')
                            ->hiddenLabel()
                            ->content(new \Illuminate\Support\HtmlString(
                                '
                            <div style="margin-bottom:8px;">
                                Download template terlebih dahulu sebelum import.
                            </div>

                            <a href="' . asset('templates/part_process_template.xlsx') . '"
                                target="_blank"
                                style="color:#2563eb;font-weight:600">
                                📥 Download Template Excel
                            </a>
                            '
                            )),

                        FileUpload::make('file')
                            ->disk('public')
                            ->directory('imports')
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        \Maatwebsite\Excel\Facades\Excel::import(
                            new \App\Imports\PartProcessImport(),
                            \Illuminate\Support\Facades\Storage::disk('public')
                                ->path($data['file'])
                        );
                    })
            ]);
    }
}
