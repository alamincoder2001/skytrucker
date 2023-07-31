<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('email')->unique();
            $table->string('type', 20);
            $table->unsignedBigInteger('team_leader_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('status', 1)->default('a');
            $table->string('latitude', 20)->nullable();
            $table->string('longitude', 20)->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('update_by')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('team_leader_id')->references('id')->on('users');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('added_by')->references('id')->on('users');
            $table->foreign('update_by')->references('id')->on('users');
        });

        // create default one
        $user = new User();
        $user->name = 'Mr. Admin';
        $user->email = 'admin@mail.com';
        $user->type = 'admin';
        $user->username = 'admin';
        $user->password = bcrypt(1);
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
