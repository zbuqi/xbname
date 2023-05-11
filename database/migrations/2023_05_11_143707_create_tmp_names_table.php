<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmpNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tmp_names', function (Blueprint $table) {
            $table->id();
            $table->string('name',10)->unique()->comment('域名');
            $table->integer('is_beian')->default('0')->comment('是否备案，0未备案，1已备案');
            $table->string('company_name','100')->nullable()->comment('备案公司名字');
            $table->string('beian_type','100')->nullable()->comment('备案类型，企业或者个人');
            $table->string('beian_name','100')->nullable()->comment('备案号');
            $table->string('site_name','100')->nullable()->comment('网站名称');
            $table->timestamp('beian_at')->nullable()->comment('备案审核时间');
            $table->integer('query_num')->default('0')->comment('查询次数');
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
        Schema::dropIfExists('tmp_names');
    }
}
