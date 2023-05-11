<?php

namespace App\Http\Middleware;

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

}
