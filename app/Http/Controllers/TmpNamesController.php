<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;
use App\Jobs\ProcessPodcast;

use App\Http\Middleware\BqFunction;

class TmpNamesController extends Controller
{
    public function update()
    {
        $names = TmpNames::where('is_beian', false)->where('query_num', 0)->first();
        if($names != ""){
            echo '空';
            $domain = base64_encode($names->name);
            $app_id = 'kbigoqlchunrijvq';
            $app_secret = 'a3U1SzA1czNXdTF5cENJOVV3WDZDdz09';
            $params = '?domain=' . $domain . '&app_id=' . $app_id . '&app_secret=' . $app_secret;
            $url = 'https://www.mxnzp.com/api/beian/search' . $params;
            $data = BqFunction::bq_curl_post($url);
            $data = json_decode($data, true);

            if ($data['code'] == 0) {
                $content = TmpNames::where('name', $names->name)->update(['query_num' => $names->query_nunm+1]);
            }else{
                if($data['data']['passTime'] == ''){
                    $data['data']['passTime'] = null;
                }
                $content = TmpNames::where('name', $names->name)->update(
                    ['is_beian'=> 1, 'company_name'=>$data['data']['unit'], 'beian_type'=> $data['data']['type'], 'beian_name'=> $data['data']['icpCode'], 'site_name'=>$data['data']['name'], 'beian_at'=> $data['data']['passTime'],'query_num' => $names->query_nunm+1]
                );
            }
            echo $names->name;
            print_r($data);
            print_r($content);
        }
    }

}
