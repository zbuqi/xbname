<?php

namespace App\Jobs;

use App\Http\Controllers\IcpController as Icp;
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(2);
        $icp = new Icp;
        $data = TmpNames::findOrFail($this->id);
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
        if($data['code'] == 200 or $data['code'] == 403){
            $update = TmpNames::where('id', $this->id)->update($content);
        }
    }
}
