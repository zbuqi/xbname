<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmpNames extends Model
{
    /*
     * 设置数据表
     */
    protected $table = 'tmp_names';

    /*
     * $dates 日期格式转换, 布尔值类型转换
     */
    protected $casts = [
        'beian_at' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'is_beian' => 'boolean',
    ];
}
