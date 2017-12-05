<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/9/12
 * Time: 15:56
 */

namespace app\home\controller;
use app\home\model\Comment;
use app\home\model\Focus as FocusModel;
use app\home\model\Like;
use think\Controller;
/**
 * Class Focus
 * @package 新闻动态/第一聚焦
 */
class Focus extends Base {
    /**
     * 主页
     */
    public function index(){
        $Model = new FocusModel();
        $top = $Model->getTopThree();
        $this->assign('top',$top);
        $list = $Model->getList();
        $this->assign('list',$list);
        return $this->fetch();
    }


    /**
     * 新闻内容页
     */
    public function detail(){
        $this->anonymous();
        $Model = new FocusModel();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(3,$userId,$id);
        }
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(3,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(3,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 列表加载更多
     */
    public function listmore(){
        $Model = new FocusModel();
        $length = input('length');
        $list = $Model->getList($length);
        if($list) {
            return $this->success("加载更多","",$list);
        }else{
            return $this->error("加载失败");
        }
    }

}