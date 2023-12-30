<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCosmoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cosmo_logs', function(Blueprint $table){
            $table->id();
            $table->string('uid')->unique()->nullable();
            $table->string('type')->nullable();
            $table->json('exception')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('flag')->nullable();
            $table->string('status')->nullable();
            $table->string('urgency')->nullable();
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
        Schema::dropIfExists('cosmo_logs');
    }
}