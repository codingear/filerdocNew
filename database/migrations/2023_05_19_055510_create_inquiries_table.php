<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('weight')->nullable();
            $table->string('size')->nullable();
            $table->string('temperature')->nullable();
            $table->string('sat')->nullable();
            $table->string('fc')->nullable();
            $table->string('pc')->nullable();
            $table->string('fr')->nullable();
            $table->string('dxt')->nullable();

            $table->string('glycemia')->nullable();
            $table->string('hba1c')->nullable();
            $table->string('ta')->nullable();
            $table->string('triglycerides')->nullable();
            $table->string('cholesterol')->nullable();
            $table->string('uric_acid')->nullable();
            $table->text('patient_notes')->nullable();
            $table->text('clinical_signs')->nullable();
            $table->text('inherited_family_history')->nullable();
            $table->text('pathological_history')->nullable();
            $table->text('last_24_hours')->nullable();

            $table->text('reason')->nullable();
            $table->text('medications')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('suffering')->nullable();
            $table->text('exploration')->nullable();
            $table->text('cabinet_studies')->nullable();
            $table->text('treatment')->nullable();
            $table->string('age')->nullable();
            $table->string('height_percentile')->nullable();
            $table->string('pc_percentile')->nullable();
            $table->text('other_studies')->nullable();

            //Patient relation
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            //Doctor relation
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiries');
    }
};
