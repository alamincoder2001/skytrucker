<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('mobile', 11);
            $table->string('new_sim', 5);
            $table->string('new_sim_gift', 5)->nullable();
            $table->string('app_install', 5);
            $table->string('app_install_gift', 5)->nullable();
            $table->string('toffee', 5);
            $table->string('toffee_gift', 5)->nullable();
            $table->string('sell_package', 5);
            $table->string('sell_gb', 20)->nullable();
            $table->string('recharge_package', 5);
            $table->string('recharge_amount', 20)->nullable();
            $table->string('voice', 5);
            $table->string('voice_amount', 20)->nullable();
            $table->unsignedBigInteger('area_id');
            $table->string('location')->nullable();
            $table->string('program', 40)->nullable();
            $table->string('experience', 40)->nullable();
            $table->string('app_experience', 40)->nullable();
            $table->string('gaming', 40)->nullable();
            $table->text('event')->nullable();
            $table->string('service', 40)->nullable();
            $table->text('future')->nullable();
            // $table->string('image')->nullable();
            $table->string('status', 1)->default('a');
            $table->string('otp', 6);
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
        Schema::dropIfExists('data_entries');
    }
}
