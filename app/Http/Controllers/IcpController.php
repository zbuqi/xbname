<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Http\Middleware\BqFunction;

class IcpController extends Controller
{
    public function show(){
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET");
        header("Access-Control-Allow-Headers:x-requested-with,content-type");
        header("Content-Type:text/html,application/json; charset=utf-8");
        $name = 'xue-ui.com';
        $content = [];
        if ($name) {
            $domain = $this->getTopHost($name);
            $data = $this->queryIcp($name);
            if(!$data['code']){
                $content = $data;
            }else{
                if(!$data["data"]){
                    $content['code'] = 402;
                    $content['msg'] = "未备案";
                }else{
                    $content['code'] = 20000;
                    $content['msg'] = "查询成功";
                    $content['data']['name'] = $data["data"]->domain;
                    $content['data']['company_name'] = $data["data"]->unitName;
                    $content['data']['beian_type'] = $data["data"]->natureName;
                    $content['data']['beian_name'] = $data["data"]->serviceLicence;
                    $content['data']['beian_at'] = $data["data"]->updateRecordTime;
                }
            }
        } else {
            $content['code'] = 401;
            $content['msg'] = "缺少参数";
        }
        return $content;
    }
    public function queryIcp($domain){
        $token = $this->token();
        $url = "icpAbbreviateInfo/queryByCondition";
        $data = array('pageNum' => '', 'pageSize' => '', 'unitName' => $domain);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $content = "application/json;charset=UTF-8";
        $query = json_decode($this->curl_post($url, $data, $content, $token));
        $query = $query->params->list[0];
        $content = [];
        if(!$token){
            $content['code'] = 0;
            $content['msg'] = "服务器请求频率过高，请稍后再试";
        }else {
            $content['code'] = 1;
            $content['msg'] = "查询成功";
            $content['data'] = $query;
        }
        return $content ;
    }
    public function token(){
        $timeStamp = time();
        $authKey = md5("testtest" . $timeStamp);
        $url = 'auth';
        $data = "authKey=$authKey&timeStamp=$timeStamp";
        $content = "application/x-www-form-urlencoded;charset=UTF-8";
        $token = json_decode($this->curl_post($url, $data, $content, "0"));
        $token = $token->params->bussiness;
        return $token;
    }
    public function curl_post($url, $data, $Content, $token) {
        $ip = "101.".mt_rand(1,255).".".mt_rand(1,255).".".mt_rand(1,255);
        $ch = curl_init();
        $headers = array(
            "Content-Type: $Content",
            "Origin: https://beian.miit.gov.cn/",
            "Referer: https://beian.miit.gov.cn/",
            "token: $token",
            "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.87 Safari/537.36",
            "CLIENT-IP: $ip",
            "X-FORWARDED-FOR: $ip"
        );
        curl_setopt($ch, CURLOPT_URL, "https://hlwicpfwc.miit.gov.cn/icpproject_query/api/" . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
    public function getTopHost($url) {
        if (stristr($url, "http") === false) {
            $url = "http://" . $url;
        }
        $url = strtolower($url);
        $hosts = parse_url($url);
        $host = $hosts['host'];
        $data = explode('.', $host);
        $n = count($data);
        $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        $pregip = '/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/';
        if (($n > 2) && preg_match($preg, $host)) {
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } elseif (preg_match($pregip, $host)) {
            $host = $host;
        } else {
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        }
        return $host;
    }


}
