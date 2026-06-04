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
        Schema::create('rejects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')
                ->constrained('production_reports')
                ->cascadeOnDelete();

            $table->foreignId('reject_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('qty')
                ->default(0);

            $table->text('notes')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rejects');
    }
};
