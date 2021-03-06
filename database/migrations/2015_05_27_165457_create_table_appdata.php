<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTableAppdata extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlite_app')->create('local_data', function ($table) {

            $table->increments('id');
            $table->string('alias', 100)->nullable();
            $table->string('ip', 100)->nullable();
            $table->integer('plant_id')->unsigned();
            $table->timestamps();

        });

        Schema::table('sqlite_app', function($table) {

            $table->foreign('user_id')->references('id')->on('users');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlite_app')->drop('local_data');
    }

}
