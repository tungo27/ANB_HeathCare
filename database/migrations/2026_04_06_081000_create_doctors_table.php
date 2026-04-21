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
        Schema::create('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->primary(); // FK to users
            $table->unsignedBigInteger('specialty_id')->nullable(); // FK to specialties
            $table->string('qualification')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true); // Bác sĩ có đang nhận lịch không?

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        
    }
};
