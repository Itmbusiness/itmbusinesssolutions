<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A timestamped activity log per consultant customer, so a consultant has a
 * record of what's been done without needing a separate tool.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultant_customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultant_customer_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultant_customer_notes');
    }
};
