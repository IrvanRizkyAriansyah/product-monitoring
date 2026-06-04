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
        Schema::create('downtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
                ->constrained('production_reports')
                ->cascadeOnDelete();

            $table->foreignId('machine_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->integer('duration_minutes')
                ->default(0);

            $table->string('reason');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downtimes');
    }
};
