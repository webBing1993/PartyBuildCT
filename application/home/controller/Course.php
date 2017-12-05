<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/9/12
 * Time: 16:12
 */

namespace app\home\controller;
use app\home\model\Browse;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\Picture;
use app\home\model\Notice as NoticeModel;
use app\home\model\WechatUser;


/**
 * Class Course
 * @package 三会一课
 */
class Course extends Base {
    /**
     * 主页
     */
    public function index(){

        return $this->fetch();
    }

    /**
     * 相关通知
     */
    public function relevant(){

        return $this->fetch();
    }

    /**
     * 相关通知列表
     */
    public function relevantlist(){


        return $this->fetch();
    }

    /**
     * 更多通知
     */
    public function relevantmore(){


    }

    /**
     * 会议情况
     */
    public function meet(){

        return $this->fetch();
    }

    /**
     * 会议情况列表页面
     */
    public function meetlist(){

        return $this->fetch();
    }

    /**
     * 会议更多
     */
    public function meetmore(){

    }

    /**
     * 党课情况
     */
    public function party(){

        return $this->fetch();
    }


    /**
     * 党课加载更多
     */
    public function partymore(){


    }
    /**
     * 创意组织生活  加载更多
     */
    public function regularmore() {

    }


}