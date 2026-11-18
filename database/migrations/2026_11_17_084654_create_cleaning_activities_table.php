
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cleaning_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cleaning_task_id')
                  ->nullable()
                  ->constrained('cleaning_tasks')
                  ->nullOnDelete();

            $table->string('employee_name');
            $table->string('activity_name');
            $table->string('area');
            $table->text('description')->nullable();

            $table->enum('status', ['pending', 'completed'])
                  ->default('pending');

            $table->timestamp('activity_date')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_activities');
    }
};
