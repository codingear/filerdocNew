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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('last_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->rememberToken();

            //Extra
            $table->string('photo')->default('default.png');
            $table->string('alias')->default('Dr.');
            $table->boolean('locked')->default(0);

            //Assistant and patient relation
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users');

            //Patient relation
            $table->boolean('assistant')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
