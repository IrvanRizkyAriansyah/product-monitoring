<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('production_report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
                ->constrained('production_reports')
                ->cascadeOnDelete();

            $table->time('report_date');

            $table->integer('target_qty')
                ->default(0);

            $table->integer('actual_qty')
                ->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production__report__details');
    }
};
