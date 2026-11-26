<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cleaning_activities', function (Blueprint $table) {
            $table->string('status')->default('Selesai')->change();
        });
    }

    public function down(): void
    {
        Schema::table('cleaning_activities', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }
};