<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 14:11
 */
namespace app\home\controller;
use app\home\model\Comment;
use app\home\model\Investment;
use app\home\model\Like;
use app\home\model\Notice as NoticeModel;
use app\home\model\Water;

/**
 * 通知公告
 */
class Notice extends Base {
    /**
     * 通知公告
     */
    public function index(){
        $this->anonymous();
        $Model = new NoticeModel;
        if(IS_POST) {
            $data = input('post.');
            $list = $Model->getMoreList($data['length'],$data['type']);
            if($list) {
                return $this->success("加载成功","",$list);
            }else {
                return $this->error("加载失败");
            }
        }else {
            $list = $Model->getIndexList();
            $this->assign('list',$list);
            return $this->fetch();  
        }
    }

    /**
     * 通知公告详情页
     */
    public function detail(){
        $this->anonymous();
        $Model = new NoticeModel();
        $id = input('id');
        $userId = session('userId');

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(1,$userId,$id);
        }
        $detail = $Model->get($id);
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(1,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(1,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 水质报表详情页
     */
    public function qualitydetail(){
        $this->anonymous();
        $Model = new Water();
        $id = input('id');
        $userId = session('userId');

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(2,$userId,$id);
        }
        $detail = $Model->get($id);
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(2,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(2,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 招商信息详情页
     */
    public function investment(){
        $this->anonymous();
        $Model = new Investment();
        $id = input('id');
        $userId = session('userId');

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(7,$userId,$id);
        }
        $detail = $Model->get($id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }


}