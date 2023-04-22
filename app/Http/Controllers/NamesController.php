<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;

class NamesController extends Controller
{
    public function show(){
        $data = file_get_contents('php://input');
        $res = [];
        if($data){
            $res['code'] = 20000;
            $res['message'] = '数据获取成功';
            $res['data'] = Names::paginate(50);
        }
        return $res;
    }
    public function add(){
        $data = file_get_contents('php://input');
        $data = json_decode($data);
        //获取最大id
        $maximum_id = Names::max('id');
        //数组查重
        $data = array_unique($data);
        $insert_data = [];
        foreach($data as $key=>$item){
            $insert_data[$key]['id'] = $key + $maximum_id + 1;
            $insert_data[$key]['name'] = $item;
        }
        $res = [];
        $res['code'] = 20000;
        $res['message'] = '数据提交成功';
        $res['data'] = Names::insert($insert_data);

        return $res;
    }
}
