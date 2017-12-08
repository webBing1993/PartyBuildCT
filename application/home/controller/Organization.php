<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 16:51
 */
namespace app\home\controller;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\Organization as OrganizationModel;
use think\Db;

/**
 * 组织活动
 */
class Organization extends Base {
    /**
     * 组织活动首页
     */
    public function index(){
        $Model = new OrganizationModel();
        if(IS_POST) {
            $data = input('post.');
            $res = $Model->getMoreList($data['length'],$data['type']);
            if($res) {
                return $this->success("加载成功","",$res);
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
     * 活动通知详情页
     */
    public function detail(){
        $this->anonymous();
        $Model = new OrganizationModel();
        $id = input('id');
        $userId = session('userId');

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(6,$userId,$id);
        }        
        $detail = $Model->get($id);

        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(6,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(6,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 活动展示详情页
     */
    public function detail2(){
        $this->anonymous();
        $Model = new OrganizationModel();
        $id = input('id');
        $userId = session('userId');

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(6,$userId,$id);
        }
        $detail = $Model->get($id);

        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(6,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(6,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

}