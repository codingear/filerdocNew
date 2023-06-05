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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->text('capacity_suffers')->nullable();
            $table->text('allergy_medicine')->nullable();
            $table->text('family_history')->nullable();
            $table->text('non_pathological_history')->nullable();
            $table->text('pathological_history',)->nullable();
            $table->text('gynecological_history')->nullable();
            $table->text('administered_vaccine')->nullable();
            $table->text('archived')->nullable();
            $table->text('perinatal_history')->nullable();
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
