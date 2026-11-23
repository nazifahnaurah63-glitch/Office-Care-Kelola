<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('duty_rosters', function (Blueprint $table) {
            $table->id();

            // Tanggal atau periode piket
            $table->date('period');

            // Maksimal 4 anggota dari tabel users
            $table->foreignId('member_1_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('member_2_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('member_3_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('member_4_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Status jadwal
            $table->string('status')->default('Active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duty_rosters');
    }
};
