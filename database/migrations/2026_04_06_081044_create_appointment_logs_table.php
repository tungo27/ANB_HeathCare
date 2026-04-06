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
        Schema::create('appointment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->enum('old_status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->nullable();
            $table->enum('new_status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show']);
            $table->unsignedBigInteger('changed_by');
            $table->text('note')->nullable();
            $table->timestamp('changed_at')->useCurrent();

            $table->foreign('appointment_id')
                ->references('id')->on('appointments')
                ->onDelete('cascade');
            $table->foreign('changed_by')
                ->references('id')->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_logs');
    }
};
