<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 16:51
 */
namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\WechatDepartmentUser;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use think\Controller;
use think\Db;

/**
 * 组织活动
 */
class Organization extends Base {
    /**
     * 组织活动首页
     */
    public function index(){

        return $this->fetch();
    }


    /**
     * 活动通知详情页
     */
    public function detail(){

        return $this->fetch();
    }

    /**
     * 活动展示详情页
     */
    public function detail2(){

        return $this->fetch();
    }

}