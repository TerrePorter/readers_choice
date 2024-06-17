<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Campaigns\Models\Campaign;
use Modules\Campaigns\Models\Categories;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("tag");
            $table->integer('type');
            $table->timestamps();
        });

        Schema::create('campaigns_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Campaign::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Categories::class)->constrained()->cascadeOnDelete();
            $table->integer('sort');
        });

        Schema::create('categories_types', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("title");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('campaign_category');
        Schema::dropIfExists('categories_types');
    }
};
