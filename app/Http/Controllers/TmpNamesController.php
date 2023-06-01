<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;
use App\Jobs\ProcessPodcast;
use App\Http\Controllers\IcpController as Icp;


use App\Http\Middleware\BqFunction;

class TmpNamesController extends Controller
{
    public function show()
    {
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data);
        $data = TmpNames::where('is_beian', true)->get();
        $no_query = TmpNames::where('query_num', '0')->count();

        $content = [];
        if($data != ""){
            foreach($data as $key=>$item){
                $content[$key]['num'] = $item['num'];
                $content[$key]['name'] = $item['name'];
                $content[$key]['company_name'] = $item['company_name'];
                $content[$key]['beian_type'] = $item['beian_type'];
                $content[$key]['beian_name'] = $item['beian_name'];
                $content[$key]['site_name'] = $item['site_name'];
                $content[$key]['beian_at'] = $item['beian_at'];
            }
        }
        $res = [];
        $res['code'] = 20000;
        $res['message'] = '数据获取成功';
        $res['content'] = $content;
        $res['no_query'] = $no_query;
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

    public function update()
    {
        $names = TmpNames::where('is_beian', false)->where('query_num', 0)->take(20)->get();
        $content = [];
        $icp = new Icp;
        $bq = new BqFunction;
        if($names->count()){
            foreach ($names as $key=>$item) {
                $data = $icp->queryIcp($item->name);
                $content[$key]['name'] = $item->name;
                $content[$key]['query_num'] = $item->query_nunm+1;
                if($data['code'] == 200){
                    $content[$key]['is_beian'] = 1;
                    $content[$key]['company_name'] = $data['data']->unitName;
                    $content[$key]['beian_type'] = $data['data']->natureName;
                    $content[$key]['beian_name'] = $data['data']->serviceLicence;
                    $content[$key]['beian_at'] = $data['data']->updateRecordTime;
                }else{
                    $content[$key]['is_beian'] = 0;
                    $content[$key]['company_name'] = null;
                    $content[$key]['beian_type'] = null;
                    $content[$key]['beian_name'] = null;
                    $content[$key]['beian_at'] = '2023-06-01 00:00:00';
                }
                print_r($content[$key]);
                echo "<br>";
                sleep(1);
            }
        }
        if(count($content)){
            $q = $bq->updateBatch('tmp_names', $content);
            if($q){
                print_r($q);
                echo '数据更新成功';
            }else{
                echo "更新数据为空";
            }
        }

    }

    /*
    public function update()
    {
        for($i=1; $i<10; $i++) {
            $names = TmpNames::where('is_beian', false)->where('query_num', 0)->first();
            if ($names != "") {
                echo '空';
                $domain = base64_encode($names->name);
                $app_id = 'kbigoqlchunrijvq';
                $app_secret = 'a3U1SzA1czNXdTF5cENJOVV3WDZDdz09';
                $params = '?domain=' . $domain . '&app_id=' . $app_id . '&app_secret=' . $app_secret;
                $url = 'https://www.mxnzp.com/api/beian/search' . $params;
                $data = BqFunction::bq_curl_post($url);
                $data = json_decode($data, true);

                if ($data['code'] == 0) {
                    $content = TmpNames::where('name', $names->name)->update(['query_num' => $names->query_nunm + 1]);
                } else {
                    if ($data['data']['passTime'] == '') {
                        $data['data']['passTime'] = null;
                    }
                    $content = TmpNames::where('name', $names->name)->update(
                        ['is_beian' => 1, 'company_name' => $data['data']['unit'], 'beian_type' => $data['data']['type'], 'beian_name' => $data['data']['icpCode'], 'site_name' => $data['data']['name'], 'beian_at' => $data['data']['passTime'], 'query_num' => $names->query_nunm + 1]
                    );
                }
                echo $names->name;
                print_r($data);
                print_r($content);
                echo '<br>';
            }
            sleep(1);
        }
    }
    */

    public function ces(){
        $data = TmpNames::where('is_beian', false)->where('query_num', 0)->take(2)->get();
        $content = [];
        foreach($data as $key=>$item){
            $content[$key]['name'] = $item['name'];
            $content[$key]['beian_name'] = $item['company_name'];
            $content[$key]['is_beian'] = 1;
            $content[$key]['beian_type'] = $item['beian_type'];
            $content[$key]['beian_name'] = $item['beian_name'];
            $content[$key]['site_name'] = $item['site_name'];
            $content[$key]['beian_at'] = $item['beian_at'];
            $content[$key]['query_num'] = $item['query_num'] +1;
        }
        print_r($content);
        $q = BqFunction::updateBatch('tmp_names', $content);
        echo "<br>";
        echo "<br>";
        echo "<br>";
        print_r($q);
    }
}
