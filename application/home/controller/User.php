<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/20
 * Time: 15:34
 */

namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\Picture;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use think\Controller;
use think\Db;
use think\Request;

/**
 * Class User
 * 用户个人中心
 */
class User extends Base {
    /**
     * 个人中心主页
     */
    public function index(){

        return $this->fetch();
    }

    /**
     * 个人信息页
     */
    public function personal(){

        return $this->fetch();
    }

    /**
     * 设置头像
     */
    public function setHeader(){
        return $this->fetch();
    }

}
