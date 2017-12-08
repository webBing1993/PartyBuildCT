<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/9/12
 * Time: 16:12
 */

namespace app\home\controller;
use app\home\model\Comment;
use app\home\model\Course as CourseModel;
use app\home\model\Like;

/**
 * Class Course
 * @package 三会一课
 */
class Course extends Base {
    /**
     * 主页
     */
    public function index(){
        $Model = new CourseModel;
        $list = $Model->getIndexList();
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 相关通知
     */
    public function relevant(){
        $this->anonymous();
        $Model = new CourseModel();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(5,$userId,$id);
        }
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(5,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(5,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 相关通知列表
     */
    public function relevantlist(){
        $Model = new CourseModel();
        $list = $Model->getListMore(1);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * 会议情况
     */
    public function meet(){
        $this->anonymous();
        $Model = new CourseModel();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(5,$userId,$id);
        }
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(5,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(5,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 会议情况列表页面
     */
    public function meetlist(){
        $Model = new CourseModel();
        $list = $Model->getListMore(2);
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 党课情况
     */
    public function party(){
        $this->anonymous();
        $Model = new CourseModel();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(5,$userId,$id);
        }
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(5,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(5,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 加载更多
     */
    public function more(){
        $Model = new CourseModel();
        $data = input('post.');
        $list = $Model->getListMore($data['type'],$data['length']);
        if($list) {
            return $this->success("加载成功","",$list);
        }else {
            return $this->error("加载失败");
        }
    }



}