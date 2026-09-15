<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The public site's content spine. Everything the marketing site renders comes
 * from these tables so the owner can change it without a deploy — the PRD's
 * "configure services, prices, requirements and content without a software
 * release" requirement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('summary')->nullable();
            $table->text('body')->nullable();
            $table->json('audience')->nullable(); // "Who this category helps"
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('primary_keyword')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_published')->default(true);
            $table->date('reviewed_on')->nullable();
            $table->date('next_review_date')->nullable();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('summary', 500)->nullable();
            $table->text('intro')->nullable();
            $table->json('suited_to')->nullable();     // "Is this service right for you?"
            $table->json('includes')->nullable();      // "What the service can include"
            $table->json('requirements')->nullable();  // "Starting requirements" checklist
            $table->json('steps')->nullable();         // "How it works"
            $table->text('charges_note')->nullable();
            $table->text('processing_note')->nullable();
            $table->text('source_note')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('primary_keyword')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->date('reviewed_on')->nullable();
            $table->date('next_review_date')->nullable();
            $table->string('reviewer')->nullable();
            $table->timestamps();
        });

        // Self-referencing many-to-many: "Related services" on each service page.
        Schema::create('related_services', function (Blueprint $table) {
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_service_id')->constrained('services')->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->primary(['service_id', 'related_service_id']);
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('summary', 500)->nullable();
            $table->text('body')->nullable();
            $table->json('includes')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('primary_keyword')->nullable();
            $table->string('author')->nullable();
            $table->string('reviewer')->nullable();
            $table->date('reviewed_on')->nullable();
            $table->date('next_review_date')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        // Polymorphic: a service page's FAQs, plus the standalone /faq page.
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('faqable');
            $table->string('question');
            $table->text('answer');
            $table->string('group')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Editable static pages: about, contact, privacy, terms, and similar.
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_indexable')->default(true);
            // System pages have a dedicated route/template and must not be deleted.
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('related_services');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
};
