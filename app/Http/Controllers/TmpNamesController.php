<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;
use App\Jobs\ProcessPodcast;

use App\Http\Middleware\BqFunction;

class TmpNamesController extends Controller
{
    public function show()
    {
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data);
        $data = TmpNames::where('is_beian', true)->get();
        $no_query = TmpNames::where('query_num', '0')->get();

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
    
    /*
    public function update()
    {
        $names = TmpNames::where('is_beian', false)->where('query_num', 0)->take(2)->get();
        foreach($names as $item){
            if ($item->name != "") {
                $domain = base64_encode($item->name);
                $app_id = 'kbigoqlchunrijvq';
                $app_secret = 'a3U1SzA1czNXdTF5cENJOVV3WDZDdz09';
                $params = '?domain=' . $domain . '&app_id=' . $app_id . '&app_secret=' . $app_secret;
                $url = 'https://www.mxnzp.com/api/beian/search' . $params;
                $data = BqFunction::bq_curl_post($url);
                $data = json_decode($data, true);
                if ($data['code'] == 0) {

                } else {

                }
            }
            sleep(1);
        }

        /*
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
        */
    //}

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
}
