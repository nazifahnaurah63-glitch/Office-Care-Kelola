<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cleaning_activities', function (Blueprint $table) {
            if (!Schema::hasColumn('cleaning_activities', 'tanggal')) {
                $table->date('tanggal')->nullable();
            }
            if (!Schema::hasColumn('cleaning_activities', 'jenis_aktivitas')) {
                $table->string('jenis_aktivitas')->nullable();
            }
            if (!Schema::hasColumn('cleaning_activities', 'foto')) {
                $table->string('foto')->nullable();
            }
            if (!Schema::hasColumn('cleaning_activities', 'employee_name')) {
                $table->string('employee_name')->nullable();
            }
            if (!Schema::hasColumn('cleaning_activities', 'status')) {
                $table->string('status')->default('Selesai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cleaning_activities', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'jenis_aktivitas', 'foto', 'employee_name', 'status']);
        });
    }
};