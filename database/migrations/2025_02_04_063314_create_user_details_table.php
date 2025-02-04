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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('cover_photo')->nullable();
            $table->text('profile_picture')->nullable();
            $table->longText('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('phone')->nullable();
            $table->integer('is_phone_verified')->default(0)->nullable()->comment('0 = Not Verified, 1 = Verified');
            $table->string('email')->nullable();
            $table->integer('is_email_verified')->default(0)->nullable()->comment('0 = Not Verified, 1 = Verified');
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->unsignedBigInteger('blood_group')->nullable()->comment('Blood Category ID');
            $table->string('team')->nullable()->comment('Blood Group Team');
            $table->string('location')->nullable()->comment('Blood Group Location');
            $table->text('description')->nullable()->comment('Blood Group Description');
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('upazila')->nullable();
            $table->string('skill')->nullable();
            $table->string('education')->nullable();
            $table->boolean('is_available')->default(0)->nullable()->comment('0 = Blood Donation Not Available, 1 = Blood Donation Available');
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
        Schema::dropIfExists('user_details');
    }
};
