<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/4/20
 * Time: 10:17 AM
 */

namespace app\api\controller;

use app\api\model\Login as LoginModel;
class User extends Base {

	public function index()
	{
		return LoginModel::all();
	}
}