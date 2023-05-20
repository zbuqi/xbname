<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class WhoisController extends Controller
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
                $data = $this->queryWhois($domain);
                if($data['domainStatus']){
                    $res['code'] = 200;
                    $res['msg'] = '查询成功';
                    $res['data']['name'] = $data['domainName'];
                    $res['data']['logon_at'] = $data['registrantDate'];
                    $res['data']['expired_at'] = $data['expirationDate'];
                    $res['data']['registrar'] = $data['registrar'];
                    $res['data']['whoisAllInfo'] = $data['whoisAllInfo'];
                }else{
                    $res['code'] = 403;
                    $res['msg'] = '可能未被注册（或被注册局保留、限制注册），暂无域名注册信息';
                }
            }else{
                $res['code'] = 402;
                $res['msg'] = '域名输入错误，或者暂不支持该后缀域名';
            }
        } else {
            $res['code'] = 401;
            $res['msg'] = "缺少参数";
        }
        return $res;
    }

    /*调采集函数，根据域名采集whois信息*/
    public function queryWhois($domain) {
        $url = 'https://whois.xinnet.com/domainWhois/queryWhois?';
        $src = $url . 'domainName=' . $domain . '&refreshFlag=true';
        $data = $this->curl_post($src,'');
        return $data;
    }

    //设置连接抓取页面内容
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
