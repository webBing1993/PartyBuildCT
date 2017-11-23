<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 14:11
 */
namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\WechatDepartmentUser;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;

/**
 * 通知公告
 */
class Organization extends Base {
    /**
     * 通知公告
     */
    public function index(){

        return $this->fetch();
    }

    /**
     * 通知公告详情页
     */
    public function detail(){

        return $this->fetch();
    }

    /**
     * 水质报表详情页
     */
    public function qualityDetail(){

        return $this->fetch();
    }

    /**
     * 招商信息详情页
     */
    public function investment(){

        return $this->fetch();
    }


}