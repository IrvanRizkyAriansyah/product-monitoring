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
        Schema::create('part_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('process_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('machine_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('sequence')
                ->default(1);

            $table->decimal(
                'std_run',
                10,
                2
            )->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_processes');
    }
};
