<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('effort_level')->default(1);
            $table->integer('base_reward')->default(0);
            $table->enum('status', ['open', 'negotiation', 'accepted', 'completed', 'cancelled'])->default('open');
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->foreignId('helper_id')->constrained('users')->onDelete('cascade');
            $table->json('conditions')->nullable();
            $table->decimal('extra_fee', 10, 2)->default(0);
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user')->constrained('users');
            $table->foreignId('to_user')->constrained('users');
            $table->integer('points');
            $table->enum('type', ['help', 'repayment', 'penalty']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('tasks');
    }
};