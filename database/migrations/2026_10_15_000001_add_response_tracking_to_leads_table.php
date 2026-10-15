<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('first_responded_at')->nullable();
            $table->foreignId('response_recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->index(['first_responded_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['first_responded_at', 'created_at']);
            $table->dropConstrainedForeignId('response_recorded_by');
            $table->dropColumn(['assigned_at', 'first_responded_at']);
        });
    }
};
