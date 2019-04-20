<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/4/20
 * Time: 10:15 AM
 */

namespace app\api\controller;


use Firebase\JWT\JWT;
use app\api\model\Login as LoginModel;
use think\response\Json;

class Login {

	public function index()
	{
		$data = input('get.');
		$username = htmlspecialchars($data['username']);
		$password = htmlspecialchars($data['password']);
		$user = LoginModel::where('username',$username)->find();
		if (!empty($user)) {
			if ($username === $user['username'] && $password === $user['password']) {
				$msg = [
					'status' => 1001,
					'msg' => '登录成功',
					'jwt' => self::createJwt($user['id'])
				];
				return json($msg);
			} else {
				$msg = [
					'status' => 1002,
					'msg' => '账号密码错误'
				];
				return json($msg);

			}
		} else {
			$msg = [
				'status' => 1002,
				'msg' => '请输入账号密码'
			];
			return json($msg);
		}
	}

	public function createJwt($userId)
	{
		$key = md5('nobita'); //jwt的签发密钥，验证token的时候需要用到
		$time = time(); //签发时间
		$expire = $time + 14400; //过期时间
		$token = array(
			"user_id" => $userId,
			"iss" => "http://tp5.test",//签发组织
			"aud" => "http://tp5.test", //签发作者
			"iat" => $time,
			"nbf" => $time,
			"exp" => $expire
		);
		$jwt = JWT::encode($token, $key);
		return $jwt;
	}
}