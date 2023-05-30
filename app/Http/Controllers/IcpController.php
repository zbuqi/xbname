<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class IcpController extends Controller
{
    public function show(){
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET");
        header("Access-Control-Allow-Headers:x-requested-with,content-type");
        header("Content-Type:text/html,application/json; charset=utf-8");
        $res = [];
        if (array_key_exists('domain', $_GET) && $_GET['domain']) {
            $domain = $this->getTopHost($_GET['domain']);
            if ($domain) {
                $data = $this->queryIcp($domain);
                if ($data['code'] == 200) {
                    $res['code'] = 200;
                    $res['msg'] = "查询成功";
                    $res['data']['name'] = $data["data"]->domain;
                    $res['data']['company_name'] = $data["data"]->unitName;
                    $res['data']['beian_type'] = $data["data"]->natureName;
                    $res['data']['beian_name'] = $data["data"]->serviceLicence;
                    $res['data']['beian_at'] = $data["data"]->updateRecordTime;
                } else {
                    $res = $data;
                }
            }else{
                $res['code'] = 404;
                $res['msg'] = '域名输入错误，或者暂不支持该后缀域名';
            }
        } else {
            $res['code'] = 401;
            $res['msg'] = "缺少参数";
        }
        return $res;
    }

    /*调采集函数，根据token采集备案信息*/
    public function queryIcp($domain){
        $token = $this->token();
        $url = "icpAbbreviateInfo/queryByCondition";
        $data = array('pageNum' => '', 'pageSize' => '', 'unitName' => $domain);
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $content = "application/json;charset=UTF-8";
        $query = json_decode($this->curl_post($url, $data, $content, $token));
        $query = $query->params->list;
        $res = [];
        if(!$token){
            $res['code'] = 402;
            $res['msg'] = "服务器请求频率过高，请稍后再试";
        }elseif(!$query) {
            $res['code'] = 403;
            $res['msg'] = "未备案";
        }else{
            $res['code'] = 200;
            $res['msg'] = "查询成功";
            $res['data'] = $query[0];
        }
        return $res ;
    }

    /*调采集函数获取token*/
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

    /*信息采集函数*/
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

    /*检查输入的域名链接*/
    public function getTopHost($url) {
        if (stristr($url, "http") === false) {
            $url = "http://" . $url;
        }
        $url = strtolower($url);
        $hosts = parse_url($url);
        $host = $hosts['host'];
        $data = explode('.', $host);
        $n = count($data);
        $preg3 = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
        $preg2 = '/[\w].+\.(com|net|org|gov|edu|cn|top|xyz|cc)$/';
        #$pregip = '/((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})(\.((2(5[0-5]|[0-4]\d))|[0-1]?\d{1,2})){3}/';

        if (($n == 3) && preg_match($preg3, $host)) {
            $host = $data[$n - 3] . '.' . $data[$n - 2] . '.' . $data[$n - 1];
        } elseif (($n == 2) && preg_match($preg2, $host)) {
            $host = $data[$n - 2] . '.' . $data[$n - 1];
        } else {
            $host = '';
        }
        return $host;
    }


}
