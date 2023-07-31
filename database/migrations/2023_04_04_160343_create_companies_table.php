<?php

use App\Models\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->text('address');
            $table->string('phone', 15);
            $table->string('logo')->nullable();
            $table->string('ip_address');
            $table->unsignedBigInteger('update_by');
            $table->timestamps();

            $table->foreign('update_by')->references('id')->on('users');
        });

        // create default one
        $content = new Company();
        $content->name = 'Sky Tracker';
        $content->address = 'Company Address here';
        $content->phone = '01700000000';
        $content->ip_address = 'http://127.0.0.1:8000/';
        $content->update_by = 1;
        $content->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
