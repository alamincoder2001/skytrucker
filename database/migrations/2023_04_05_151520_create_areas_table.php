<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('latitude', 40)->nullable();
            $table->string('longitude', 40)->nullable();
            $table->string('zip_code', 40)->nullable();
            $table->text('camera')->nullable();
            $table->string('status', 1)->default('a');
            $table->string('ip_address', 40);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('update_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('areas');
    }
}
