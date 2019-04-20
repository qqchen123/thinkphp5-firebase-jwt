<?php
namespace app\index\controller;

use think\Db;
use think\Request;
use think\response\Json;

class Index
{
    public function index()
    {
        $data = Db::table('qb_content')->where('id', 3)->select();
        if (!empty($data)) {
            return Json($data);
        }
    }

    public function hello($name = 'ThinkPHP5')
    {
        $data = Db::table('qb_content')->where('id', 3)->select();
        if (!empty($data)) {
            return Json($data);
        }
        return 'hello,' . $name;
    }
}
