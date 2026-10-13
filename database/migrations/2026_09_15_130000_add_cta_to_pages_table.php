<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // An optional in-content call to action, rendered as a real button
            // component instead of the "**CTA:** Label" convention the content
            // pack used to leave as plain text in the body.
            $table->string('cta_label')->nullable()->after('body');
            $table->string('cta_url')->nullable()->after('cta_label');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['cta_label', 'cta_url']);
        });
    }
};
