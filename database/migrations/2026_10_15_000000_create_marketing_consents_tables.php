<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('requested_at');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->string('policy_version');
            $table->string('source');
            $table->text('access_token');
            $table->string('access_token_hash', 64)->unique();
            $table->timestamps();
        });

        Schema::create('marketing_consent_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_subscription_id')->constrained()->restrictOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('source');
            $table->string('policy_version');
            $table->timestamp('occurred_at');

            $table->index(['marketing_subscription_id', 'occurred_at'], 'marketing_consent_subscription_occurred_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_consent_events');
        Schema::dropIfExists('marketing_subscriptions');
    }
};
