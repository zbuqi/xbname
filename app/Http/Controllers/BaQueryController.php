<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Names;
use App\Models\TmpNames;
use App\Jobs\ProcessPodcast;
use App\Http\Controllers\IcpController as Icp;


use App\Http\Middleware\BqFunction;

class BaQueryController extends Controller
{
    public function show($id)
    {
        $icp = new Icp;
        $data = TmpNames::findOrFail($id);
        $queryIcp = $icp->queryIcp($data->name);
        $content = [];
        $content['name'] = $data->name;
        $content['query_num'] = $data->query_nunm+1;
        if($data['code'] == 200){
            $content['is_beian'] = 1;
            $content['company_name'] = $queryIcp['data']->unitName;
            $content['beian_type'] = $queryIcp['data']->natureName;
            $content['beian_name'] = $queryIcp['data']->serviceLicence;
            $content['beian_at'] = $queryIcp['data']->updateRecordTime;
        }
        $update = TmpNames::where('id', $id)->update($content);
        $res = $content;
        $res['isUpdate'] = $update;
        return $res;
    }

    public function list()
    {
        $names = TmpNames::where('is_beian', false)->where('query_num', 0)->take(60)->get();
        $content = [];
        foreach($names as $item){
            $content[] = $item->id;
        }
        return view('ba_query', ['ids' => $content]);
    }
}
