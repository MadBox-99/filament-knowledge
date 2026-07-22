<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $categoriesTable = config('filament-knowledge.table_names.categories', 'knowledge_categories');

        Schema::create(config('filament-knowledge.table_names.articles', 'knowledge_articles'), function (Blueprint $table) use ($categoriesTable): void {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained($categoriesTable)->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('filament-knowledge.table_names.articles', 'knowledge_articles'));
    }
};
