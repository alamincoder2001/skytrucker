<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('mobile', 11);
            $table->unsignedBigInteger('area_id');
            $table->string('my_bl', 20);
            $table->string('gift', 20)->nullable();
            $table->string('status', 1)->default('a');
            $table->string('ip_address', 40);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('update_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('added_by')->references('id')->on('users');
            $table->foreign('update_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gamings');
    }
}
