<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;

class NamesController extends Controller
{
    public function show(){
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data);
        $data = Names::paginate($post_data->page_size);
        $res = [];
        if($data){
            $res['code'] = 20000;
            $res['message'] = '数据获取成功';
            $res['content'] = $data;

        }
        return $res;
    }
    public function add()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data);
        //获取最大id
        $maximum_id = Names::max('id');
        //数组查重
        $data = array_unique($data);
        $insert_data = [];
        foreach ($data as $key => $item) {
            $insert_data[$key]['id'] = $key + $maximum_id + 1;
            $insert_data[$key]['name'] = $item;
        }
        $name_insert = Names::insert($insert_data);
        if($name_insert){
            $res = [];
            $res['code'] = 20000;
            $res['message'] = '数据提交成功';
            $res['data'] = $name_insert;
            return $res;
        }
    }
	public function addExcel()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        //获取最大id
        $maximum_id = Names::max('id');
        foreach ($data as $key => $item) {
            $data[$key]['id'] = $key + $maximum_id + 1;
        }
        $name_insert = Names::insert($data);
        if($name_insert){
            $res = [];
            $res['code'] = 20000;
            $res['message'] = '数据提交成功';
            $res['data'] = $name_insert;
            return $res;
        }
    }
}
