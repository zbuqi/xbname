<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Names extends Model
{
    /*
     * 设置数据表
     */
    protected $table = 'names';

    /*
     * $dates 日期格式转换, 布尔值类型转换
     */
    protected $casts = [
        'logon_at' => 'datetime:Y-m-d',
        'expired_at' => 'datetime:Y-m-d',
        'beian_at' => 'datetime:Y-m-d',
        'contact_at' => 'datetime:Y-m-d',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'is_beian' => 'boolean',
        'is_contact' => 'boolean'
    ];
}
