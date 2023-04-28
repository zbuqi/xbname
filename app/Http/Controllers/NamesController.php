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
        $update_names = Names::where([['logon_at',null], ['expired_at',null]])->get();
        $res = [];
        if($data){
            $res['code'] = 20000;
            $res['message'] = '数据获取成功';
            $res['content'] = $data;
            $res['update_names'] = $update_names;
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
        $insert_data = [];
        foreach ($data as $key => $item) {
            $insert_data[$key]['id'] = $key + $maximum_id + 1;
            $insert_data[$key]['name'] = $item['域名'];
			$insert_data[$key]['company_name'] = $item['单位名称'];
			$insert_data[$key]['beian_type'] = $item['单位性质'];
			$insert_data[$key]['beian_name'] = $item['ICP备案号'];
			$insert_data[$key]['site_name'] = $item['网站名称'];
			$insert_data[$key]['beian_at'] = date('Y-m-d', strtotime($item['审核时间']));
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

    public function updateDel()
    {
        #$names = Names::where([['logon_at',null], ['expired_at',null]])->get();
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($data, true);
        $url = 'https://tool.chinaz.com/DomainDel?wd=' . $post_data;

        $res = [];
        $res['code'] = 20000;
        $res['message'] = '数据更新成功';
        $res['data'] = $url;
        return $res;

        /*
        $url = 'https://tool.chinaz.com/DomainDel?wd=' . $post_data;
        $data = $this->bq_curl_post($url);
        preg_match_all('/class="fr zTContrig"><span>([^<]*)/',$data,$match);
        $data = Names::where('name', $item->name)->update(['logon_at' => $match[1][1], 'expired_at' => $match[1][2]]);
        if($data){
            $res = [];
            $res['code'] = 20000;
            $res['message'] = '数据更新成功';
            $res['data'] = $upnum;
        }
        return $res;
        */
    }




    //设置连接抓取页面内容
    public function bq_curl_post($url){
        $header = array(
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',
        );
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url, //设置链接
            CURLOPT_RETURNTRANSFER => 1, //设置以文件流的形式返回
            CURLOPT_HTTPHEADER => $header, //设置HTTP头
            CURLOPT_CONNECTTIMEOUT => 5, //在发起连接前等待的时间
            CURLOPT_FOLLOWLOCATION => 1, 
            CURLOPT_MAXREDIRS => 3, //指定最多的http重定向的数量与ation一起使用
            CURLOPT_SSL_VERIFYPEER => false,  //禁止https  
            CURLOPT_HEADER => true,
        );
        curl_setopt_array($ch,$options);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

}
