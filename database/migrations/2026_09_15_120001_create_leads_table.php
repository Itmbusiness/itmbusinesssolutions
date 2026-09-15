<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * The PRD models every enquiry — guest or signed-in — as a single Lead, so the
 * earlier `service_requests` table folds into this one rather than sitting
 * alongside it as a second, near-identical concept.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organisation_name')->nullable();
            $table->string('organisation_status')->nullable();
            $table->string('goal')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_channel');
            $table->string('urgency')->nullable();
            $table->string('source')->default('website');
            $table->string('status')->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('internal_notes')->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        if (! Schema::hasTable('service_requests')) {
            return;
        }

        foreach (DB::table('service_requests')->orderBy('id')->get() as $old) {
            $user = DB::table('users')->where('id', $old->user_id)->first();

            DB::table('leads')->insert([
                'reference' => 'LEAD-'.Str::upper(Str::random(8)),
                'user_id' => $old->user_id,
                'name' => $user->name ?? 'Unknown',
                'email' => $user->email ?? 'unknown@example.com',
                'goal' => $old->goal,
                'message' => $old->message,
                'preferred_channel' => $old->preferred_channel,
                'status' => $old->status === 'new' ? 'new' : $old->status,
                'consent_at' => $old->created_at,
                'created_at' => $old->created_at,
                'updated_at' => $old->updated_at,
            ]);
        }

        Schema::dropIfExists('service_requests');
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
