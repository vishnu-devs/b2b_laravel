<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Basic product information
            $table->text('description')->nullable()->after('name');
            $table->string('category')->after('description');
            $table->string('brand')->nullable()->after('category');
            $table->string('model')->nullable()->after('brand');
            
            // Inventory and pricing
            $table->integer('stock_quantity')->default(0)->after('price');
            $table->decimal('mrp', 10, 2)->after('stock_quantity');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('mrp');
            
            // Product specifications
            $table->json('specifications')->nullable()->after('discount_percentage');
            $table->json('highlights')->nullable()->after('specifications');
            
            // Media
            $table->string('main_image')->nullable()->after('highlights');
            $table->json('additional_images')->nullable()->after('main_image');
            
            // SEO and display
            $table->string('slug')->unique()->after('name');
            $table->boolean('is_featured')->default(false)->after('additional_images');
            $table->boolean('is_active')->default(true)->after('is_featured');
            
            // Ratings and reviews
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
            $table->integer('review_count')->default(0)->after('average_rating');
            
            // Shipping
            $table->decimal('weight', 8, 2)->nullable()->after('review_count'); // in grams
            $table->decimal('length', 8, 2)->nullable()->after('weight'); // in cm
            $table->decimal('width', 8, 2)->nullable()->after('length'); // in cm
            $table->decimal('height', 8, 2)->nullable()->after('width'); // in cm
            $table->boolean('free_shipping')->default(false)->after('height');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'category',
                'brand',
                'model',
                'stock_quantity',
                'mrp',
                'discount_percentage',
                'specifications',
                'highlights',
                'main_image',
                'additional_images',
                'slug',
                'is_featured',
                'is_active',
                'average_rating',
                'review_count',
                'weight',
                'length',
                'width',
                'height',
                'free_shipping'
            ]);
        });
    }
};