<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;

use App\Http\Middleware\BqFunction;

class TmpNamesController extends Controller
{
    public function update(){
        $name = TmpNames::where('is_beian', false)->first();

        $domain = base64_encode($name->name);
        $app_id = 'kbigoqlchunrijvq';
        $app_secret = 'a3U1SzA1czNXdTF5cENJOVV3WDZDdz09';
        $params = '?domain=' . $domain . '&app_id=' . $app_id . '&app_secret=' . $app_secret;
        $url = 'https://www.mxnzp.com/api/beian/search' . $params;
        $data = BqFunction::bq_curl_post($url);
        #$data = json_decode($data);

        /*
        if($data['code'] == 0){
            $content = TmpNames::where('name', $name->name)->update(['query_num' => $name->query_nunm+1]);
        }
        */

        return $data;
    }

}
