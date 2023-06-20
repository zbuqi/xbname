<?php

namespace App\Jobs;

use App\Http\Controllers\IcpController as Icp;
use App\Http\Middleware\BqFunction;
use App\Models\TmpNames;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateBa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $name;
    protected $host;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $name, $host)
    {
        $this->id = $id;
        $this->name = $name;
        $this->host = $host;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $BqFunction = new BqFunction;
        $url = 'http://' . $this->host . '/?domain=' . $this->name;
        $data = $BqFunction->curl_get($url);
        $content = [];
        if($data['code'] == 200){
            $content['name'] = $this->name;
            $content['query_num'] = 1;
            $content['is_beian'] = 1;
            $content['company_name'] = $data['data']['unitName'];
            $content['beian_type'] = $data['data']['natureName'];
            $content['beian_name'] = $data['data']['serviceLicence'];
            $content['beian_at'] = $data['data']['updateRecordTime'];
        }elseif($data['code'] == 403){
            $content['name'] = $this->name;
            $content['query_num'] = 1;
        }
        if($content){
            TmpNames::where('id', $this->id)->update($content);
            print_r($content);
        }else{
            print_r($data);
        }
    }
}
