<?php

namespace App\Filament\Resources\Parts\Tables;

use App\Imports\PartImport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class PartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('part_no')
                    ->searchable(),
                TextColumn::make('part_name')
                    ->searchable(),
                TextColumn::make('std_run')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
                                <div style="
                                    padding:12px;
                                    border:1px solid #e5e7eb;
                                    border-radius:8px;
                                    background:#f9fafb;
                                ">
                                    <div style="font-weight:600;margin-bottom:6px;">
                                        Import Part
                                    </div>

                                    <div style="color:#6b7280;margin-bottom:10px;">
                                        Download template Excel dan isi data sesuai format yang telah disediakan.
                                    </div>

                                    <a href="' . asset('templates/part_import_template.xlsx') . '"
                                        target="_blank"
                                        style="color:#2563eb;font-weight:600;text-decoration:none;">
                                        📥 Download Template Excel
                                    </a>
                                </div>
                            '
                            )),

                        FileUpload::make('file')
                            ->disk('public')
                            ->directory('imports')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/vnd.ms-excel',
                            ])
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        Excel::import(
                            new PartImport,
                            Storage::disk('public')->path($data['file'])
                        );
                    })
            ]);
    }
}
