<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    public function show(){
        //$post_data = file_get_contents('php://input');
        //$get_data = $token;
        $token = "admin-token";
        $res = [];
        $res["code"] = 20000;
        $res["data"] = [
            "roles" => ["admin"],
            "introduction" => "I am a super administrator",
            "avatar" => "https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif",
            "name" => "Super Admin"
        ];
        return $res;
    }
    public function valid(){
        $post_data = file_get_contents('php://input');
        $data = User::where('name',$post_data)->first();

        $res = [];
        $res['code'] = 20000;
        $res['message'] = '数据获取成功';
        if($data['name']){
            $res['content'] = true;
        }else{
            $res['content'] = false;
        }
        return $res;
    }
    public function login(){
        $post_data = file_get_contents('php://input');
        $post_data = json_decode($post_data, true);
        $data = User::where('name', $post_data['username'])->first();

        if($data['password'] == $post_data['password']){
            $res['code'] = 20000;
            $res['data']['token'] = 'admin-token';
        }else{
            $res['code'] = 60204;
            $res['message'] = '账号和密码不正确';
        }
        return $res;
    }
}
