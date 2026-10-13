<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A simple follow-up reminder tied to a consultant's own customer — not a
 * project-management suite, just "follow up with X on Friday".
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultant_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('consultant_customer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('due_on')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['consultant_id', 'completed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultant_tasks');
    }
};
