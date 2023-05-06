<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('name', 20)->unique()->comment('用户名');
            $table->integer('phone')->nullable()->comment('手机号');
            $table->string('password', 40)->comment('密码');
            $table->timestamp('created_at')->nullable()->comment('注册时间');
            $table->timestamp('phone_verified_at')->nullable()->comment('手机认证时间');
        });
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
