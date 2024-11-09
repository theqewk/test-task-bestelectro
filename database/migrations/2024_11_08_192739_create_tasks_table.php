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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['low', 'normal', 'high', 'blocking']);
            $table->enum('status', ['new', 'in_progress', 'completed', 'rejected']);
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('assignee_id')->nullable()->constrained('users');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
