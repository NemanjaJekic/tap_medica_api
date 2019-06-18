<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppoitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appoitments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('api_id');

            $table->date('booked_date');
            $table->time('booked_time');
            $table->boolean('status');

            $table->integer('clinic_id')->unsigned()->nullable();
            $table->integer('doctor_id')->unsigned()->nullable();
            $table->integer('patient_id')->unsigned()->nullable();
            $table->integer('speciality_id')->unsigned()->nullable();

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
        Schema::dropIfExists('appoitments');
    }
}
