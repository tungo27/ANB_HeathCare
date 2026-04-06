<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; 

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doctor_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->unique();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('appointment_id')
                ->references('id')->on('appointments')
                ->onDelete('cascade');
            $table->foreign('patient_id')
                ->references('user_id')->on('patients')
                ->onDelete('cascade');
            $table->foreign('doctor_id')
                ->references('user_id')->on('doctors')
                ->onDelete('cascade');
        });

        DB::statement('ALTER TABLE doctor_ratings ADD CONSTRAINT chk_rating_range CHECK (rating BETWEEN 1 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_ratings');
    }
};
