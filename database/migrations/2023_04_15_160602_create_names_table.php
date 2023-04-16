<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('names', function (Blueprint $table) {
            $table->id();
            $table->string('name',10)->comment('域名');
            $table->timestamp('logon_at')->nullable()->comment('注册时间');
            $table->timestamp('expired_at')->nullable()->comment('过期时间');
            $table->integer('is_beian')->default('0')->comment('是否备案，0未备案，1已备案');
            $table->string('company_name','100')->nullable()->comment('备案公司名字');
            $table->string('beian_type','100')->nullable()->comment('备案类型，企业或者个人');
            $table->timestamp('beian_at')->nullable()->comment('备案审核时间');
            $table->integer('phone')->nullable()->comment('联系方式');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('names');
    }
}
