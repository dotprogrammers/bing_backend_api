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
        Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_category_id')->nullable();
            $table->text('profile_picture')->nullable();
            $table->text('cover_photo')->nullable();
            $table->longText('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('name')->nullable();
            $table->integer('age')->nullable();
            $table->integer('price')->nullable();
            $table->float('height')->nullable();
            $table->string('location')->nullable();
            $table->string('work_type')->nullable();
            $table->longText('keyword')->nullable();
            $table->boolean('is_favourite')->default(0)->nullable()->comment('0 = Not Favourite, 1 = Favourite');
            $table->integer('is_delete')->default(0)->nullable()->comment('0 = Not Deleted, 1 = Deleted');
            $table->enum('status', ['active', 'inactive'])->default('active')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_profiles');
    }
};
