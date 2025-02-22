<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->string('name');
            $table->enum('rent_type', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->enum('property_type', ['apartment', 'house', 'villa', 'commercial'])->default('apartment');
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->longText('description')->nullable();
            $table->longText('keyword')->nullable();
            $table->text('image')->nullable();
            $table->string('location');
            $table->integer('area_size')->unsigned();
            $table->integer('bedroom')->unsigned();
            $table->integer('bathroom')->unsigned();
            $table->integer('balcony')->unsigned()->nullable();
            $table->integer('kitchen')->unsigned()->nullable();
            $table->string('type')->nullable();
            $table->date('available_date')->nullable();
            $table->boolean('is_favourite')->default(0)->comment('0 = Not Favourite, 1 = Favourite');
            $table->boolean('is_delete')->default(0)->comment('0 = Not Deleted, 1 = Deleted');
            $table->enum('status', ['pending', 'approved', 'cancel', 'complete'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rents');
    }
};
