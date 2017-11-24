<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23
 * Time: 15:50
 */

namespace app\home\controller;
use app\home\model\Browse;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\Picture;
use app\home\model\WechatUser;
use think\Controller;
use app\home\model\News as NewsModel;
/**
 * 两学一做
 */
class Learn extends Base {
    /**
     * 主页
     */
    public function index(){

        return $this->fetch();
    }

    /**
     * 答题答案
     */
    public function answer(){

        return $this->fetch();
    }

    /**
     * 手机党校详情页
     */
    public function detail(){

        return $this->fetch();
    }

    /**
     * 党员先锋详情页
     */
    public function leadDetail(){

        return $this->fetch();
    }

}