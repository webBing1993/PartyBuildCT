<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/9/12
 * Time: 15:56
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
 * Class News
 * @package 新闻动态/第一聚焦
 */
class News extends Base {
    /**
     * 主页
     */
    public function index(){

        return $this->fetch();
    }


    /**
     * 新闻内容页
     */
    public function detail(){

        return $this->fetch();
    }

    /**
     * 列表加载更多
     */
    public function listmore(){

    }

}