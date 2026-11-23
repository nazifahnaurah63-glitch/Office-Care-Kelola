<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('duty_rosters', function (Blueprint $table) {
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });

        // Pertahankan jadwal lama dengan menjadikan period
        // sebagai tanggal mulai sekaligus tanggal selesai.
        DB::table('duty_rosters')->update([
            'start_date' => DB::raw('period'),
            'end_date' => DB::raw('period'),
        ]);
    }

    public function down(): void
    {
        Schema::table('duty_rosters', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
};
