<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/15
 * Time: 16:52
 */

namespace app\home\controller;


use app\home\model\WechatUser;
use think\Controller;

/**
 * Class Preview
 * @package app\home\controller
 * 二维码预览
 */
class Preview extends Controller {
    // 主页
    public function index() {
        $Model = new  WechatUser();
        $id = input('id');
        $userId = input('userid');
        $detail = $Model->getDetail($id,$userId);
        $this->assign('detail',$detail);
        return $this->fetch();
    }
}