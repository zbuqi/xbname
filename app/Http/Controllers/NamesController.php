<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;

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
        //获取最大id
        $maximum_id = TmpNames::max('id');
        //数组查重
        $data = array_unique($data);
        $insert_data = [];
        foreach ($data as $key => $item) {
            $insert_data[$key]['id'] = $key + $maximum_id + 1;
            $insert_data[$key]['name'] = $item;
            $insert_data[$key]['updated_at'] = $dqtime;
            $insert_data[$key]['created_at'] = $dqtime;
        }
        $name_insert = TmpNames::insert($insert_data);
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
        $res['code'] = 20000;
        if(count($cf_names) == 0){
            Names::insert($data);
            $res['message'] = '数据提交成功';
            $res['data'] = 1;
        }else{
            $res['message'] = '有重复数据';
            $res['data'] =  $cf_names;
        }
        return $res;
    }

    public function addBeianName()
    {
        $post_data = file_get_contents('php://input');;
        $data = TmpNames::where('is_beian', true)->get();

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $data = preg_replace('/"id":[0-9]+,/', '', $data);
        $data = preg_replace('/"query_num":[0-9]+,/', '', $data);
        $data = preg_replace('/true/', '1', $data);
        $data = json_decode($data, true);

        $mysql_datas = json_encode(Names::get(), JSON_UNESCAPED_UNICODE);
        $cf_names = [];
        //获取最大id
        $maximum_id = Names::max('id');
        foreach ($data as $key=>$item) {
            $data[$key]['id'] = $key + $maximum_id + 1;
            $is_repeat  = stripos($mysql_datas, $item['name']);
            if($is_repeat){
                $cf_names[] = $item;
            }
        }
        $res = [];
        $res['code'] = 20000;
        if(count($cf_names) == 0){
            if(Names::insert($data)){
                TmpNames::where('is_beian', true)->delete();
            }
            $res['message'] = '数据提交成功';
            $res['data'] = 1;
        }else{
            $res['message'] = '有重复数据';
            $res['data'] =  $cf_names;
        }
        return $res;
    }
}
