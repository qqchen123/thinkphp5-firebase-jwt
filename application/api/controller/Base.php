<?php
/**
 * Created by PhpStorm.
 * User: a
 * Date: 2019/4/8
 * Time: 2:19 PM
 */

namespace app\api\controller;

use Firebase\JWT\JWT;
use think\App;
use think\Controller;

class Base extends Controller
{
    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->checkToken();
    }

    public function checkToken()
    {
        $header = $this->request->header();
        if (!isset($header['authorization'])) {
            echo json_encode([
                'status' => 1002,
                'msg'    => 'Token不存在,拒绝访问'
            ]);
            exit;
        } else {
            $checkJwtToken = $this->verifyJwt($header['authorization']);
            if ($checkJwtToken['status'] == 1001) {
                return true;
            }
        }
    }
    //校验jwt权限API
    protected function verifyJwt($jwt)
    {
        $key = md5('nobita');
        try {
            $jwtAuth  = json_encode(JWT::decode($jwt, $key, array('HS256')));
            $authInfo = json_decode($jwtAuth, true);
            $msg      = [];
            if (!empty($authInfo['user_id'])) {
                $msg = [
                    'status' => 1001,
                    'msg'    => 'Token验证通过'
                ];
            } else {
                $msg = [
                    'status' => 1002,
                    'msg'    => 'Token验证不通过,用户不存在'
                ];
            }
            return $msg;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            echo json_encode([
                'status' => 1002,
                'msg'    => 'Token无效'
            ]);
            exit;
        } catch (\Firebase\JWT\ExpiredException $e) {
            echo json_encode([
                'status' => 1003,
                'msg'    => 'Token过期'
            ]);
            exit;
        } catch (Exception $e) {
            return $e;
        }
    }
}
