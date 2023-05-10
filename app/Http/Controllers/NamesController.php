<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;

class NamesController extends Controller
{
    public function show(){
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data);
        $data = Names::orderBy('expired_at', 'asc')->paginate($post_data->page_size);
        $res = [];
        if($data){
            $res['code'] = 20000;
            $res['message'] = '数据获取成功';
            $res['content'] = $data;
        }
        return $res;
    }

    public function edit(){
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data, true);
        $post_data['updated_at'] = date('Y-m-d H:i:s', time());
        $data = Names::where('id', $post_data['id'])->update($post_data);
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
        $dqtime = date('Y-m-d H:i:s', time());
        //数组查重
        $data = array_unique($data);
        $res = [];
        $res['code'] = 20000;
        $res['message'] = '数据提交成功';
        for($i=0; $i<count($data); $i++){
            $res['data'] = $data[$i];
            echo $res = json_encode($res);
        }

    }

    /*
    public function add()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data);
        $dqtime = date('Y-m-d H:i:s', time());
        //获取最大id
        $maximum_id = Names::max('id');
        //数组查重
        $data = array_unique($data);
        $insert_data = [];
        foreach ($data as $key => $item) {
            $insert_data[$key]['id'] = $key + $maximum_id + 1;
            $insert_data[$key]['name'] = $item;
            $insert_data[$key]['updated_at'] = $dqtime;
            $insert_data[$key]['created_at'] = $dqtime;
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
    */

	public function addExcel()
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $mysql_datas = json_encode(Names::get(), JSON_UNESCAPED_UNICODE);
        $dqtime = date('Y-m-d H:i:s', time());
        $cf_names = [];
        //获取最大id
        $maximum_id = Names::max('id');
        foreach ($data as $key => $item) {
            $data[$key]['id'] = $key + $maximum_id + 1;
            $data[$key]['updated_at'] = $dqtime;
            $data[$key]['created_at'] = $dqtime;
            $is_repeat  = stripos($mysql_datas, $item['name']);
            if($is_repeat){
                $cf_names[] = $item;
            }
        }
        $res = [];
        if(count($cf_names) == 0){
            Names::insert($data);
            #Names::updateOrInsert($data);
            $res['message'] = '数据提交成功';
            $res['data'] = 1;
        }else{
            $res['message'] = '有重复数据';
            $res['data'] =  $cf_names;
        }
        return $res;
    }
}
