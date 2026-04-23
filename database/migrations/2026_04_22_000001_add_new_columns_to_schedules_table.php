<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('status', ['draft', 'published', 'closed'])->default('published');

            // Drop old column and add the new properly named column
            if (Schema::hasColumn('schedules', 'slot_duration')) {
                $table->dropColumn('slot_duration');
            }
            $table->integer('slot_duration_minutes')->default(30);

            $table->integer('break_minutes')->default(0);
            $table->json('blocked_times')->nullable();
        });
    }

    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['status', 'slot_duration_minutes', 'break_minutes', 'blocked_times']);
            if (!Schema::hasColumn('schedules', 'slot_duration')) {
                $table->integer('slot_duration')->nullable();
            }
        });
    }
};
