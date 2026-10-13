<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A consultant's own customer book. Deliberately separate from `leads` —
 * a consultant's customers are theirs, sourced and managed independently of
 * ITM's own client pipeline, so the two must never share a table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultant_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultant_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('stage')->default('new');
            $table->timestamps();

            $table->index(['consultant_id', 'stage']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultant_customers');
    }
};
