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
        Schema::create('datasheets', function (Blueprint $table) {
            $table->id();

            $table->string('patient_id')->nullable();
            $table->string('religion')->nullable();
            $table->string('tutor')->nullable();
            $table->string('socioeconomic')->nullable();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('cp')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('occupation')->nullable();
            $table->string('nationality')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('scholarship')->nullable();
            $table->string('screening')->nullable();
            $table->string('birthdate')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('different_capacity')->default(0)->nullable();

            //Ubication
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->default(1)->constrained()->onDelete('set null');
            $table->unsignedBigInteger('municipality_id')->nullable();
            $table->foreign('municipality_id')->references('id')->on('municipalities')->default(1)->constrained()->onDelete('set null');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->default(1)->onDelete('set null');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullable()->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasheets');
    }
};
