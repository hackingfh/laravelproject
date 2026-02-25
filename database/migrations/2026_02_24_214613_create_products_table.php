<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->nullable()->constrained('collections')->nullOnDelete();
            $table->string('name', 255);
            $table->string('slug', 255)->unique()->index();
            $table->string('reference', 32)->unique();
            $table->decimal('price', 10, 2)->index();
            $table->string('material')->nullable()->index();
            $table->string('movement')->nullable()->index();
            $table->json('complications')->nullable();
            $table->json('images')->nullable();
            $table->string('case_diameter')->nullable();
            $table->string('case_thickness')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('sku', 64)->unique()->index();
            $table->boolean('is_visible')->default(true)->index();
            $table->boolean('is_swiss_made')->default(true)->index();
            $table->unsignedInteger('warranty_years')->default(2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
