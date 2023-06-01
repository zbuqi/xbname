<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;

class BqFunction
{
    /*
     * 自定义函数
     * 采集操作
     *
     */
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
        if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200'){
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($content, $headerSize);
        }
        curl_close($ch);
        return $body;
    }
    function curl_post($url,$data){
        $data  = json_encode($data);
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }
    function curl_get($url){
        $headerArray =array("Content-type:application/json;","Accept:application/json");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true);
        return $output;
    }
    /*批量更新*/
    public function updateBatch($tableName = "", $multipleData = array()){

        if( $tableName && !empty($multipleData) ) {
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";
            $q = "UPDATE ".$tableName." SET ";
            foreach ( $updateColumn as $uColumn ) {
                $q .=  $uColumn." = CASE ";
                foreach( $multipleData as $data ) {
                    $q .= "WHEN ".$referenceColumn." = '".$data[$referenceColumn]."' THEN '".$data[$uColumn]."' ";
                }
                $q .= "ELSE ".$uColumn." END, ";
            }
            foreach( $multipleData as $data ) {
                $whereIn .= "'".$data[$referenceColumn]."', ";
            }
            $q = rtrim($q, ", ")." WHERE ".$referenceColumn." IN (".  rtrim($whereIn, ', ').")";
            // Update
            return DB::update(DB::raw($q));
        } else {
            return false;
        }

    }


}
