<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\TmpNames;
use App\Http\Middleware\BqFunction;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $name;

    /*
    * 最大尝试次数
    */
    public $tries = 1;

    /*
    * 任务执行的最大秒数。
    */
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $domain = base64_encode($name);
        $app_id = 'kbigoqlchunrijvq';
        $app_secret = 'a3U1SzA1czNXdTF5cENJOVV3WDZDdz09';
        $params = '?domain=' . $domain . '&app_id=' . $app_id . '&app_secret=' . $app_secret;
        $url = 'https://www.mxnzp.com/api/beian/search' . $params;
        $data = BqFunction::bq_curl_post($url);
        $this->name = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo $this->name . "<br>";
    }
}
