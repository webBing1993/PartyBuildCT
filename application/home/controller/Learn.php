<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/23
 * Time: 15:50
 */

namespace app\home\controller;
use app\home\model\Answer;
use app\home\model\Comment;
use app\home\model\Learn as LearnModel;
use app\home\model\Like;
use app\home\model\Pioneer;
use app\home\model\Question;
use app\home\model\WechatUser;

/**
 * 两学一做
 */
class Learn extends Base {
    /**
     * 主页
     */
    public function index(){
        $Model = new LearnModel();
        $list = $Model->getIndexList();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function listmore() {
        $Model = new LearnModel();
        $data = input('post.');
        $list = $Model->getMoreList($data['length'],$data['type']);
        if($list) {
            return $this->success("加载成功","",$list);
        }else {
            return $this->error("加载失败");
        }
    }
    /**
     * 答题答案
     */
    public function answer(){
        $id = input('id');
        $Answers = Answer::get($id);
        $Qid = json_decode($Answers->question_id);
        $rights = json_decode($Answers->value);
        $re = array();
        foreach($Qid as $key => $value){
            $re[$key] = Question::get($value);
            $re[$key]['right'] = $rights[$key];
        }
        $this->assign('question',$re);
        $this->assign('score',$Answers['score']);
        return $this->fetch();
    }

    /**
     * 练练身手提交
     */
    public function commit(){
        // 获取用户提交数据
        $data = input('post.');
        $arr = $data['data'];
        $question = array();
        $status = array();
        $options = array();
        $Right = array();
        $score = 0;
        foreach($arr as $key => $value){
            $Question = Question::get($value[0]);
            switch($Question->value){
                case 1:
                    $right = "A";
                    break;
                case 2:
                    $right = "B";
                    break;
                case 3:
                    $right = "C";
                    break;
                case 4:
                    $right = "D";
                    break;

            }
            $Right[$key+1] = $right;
            $question[$key] = $value[0];
            $options[$key] = $value[1];
            // 判断 题目的对错
            if($value[1] == $Question->value){
                $status[$key] = 1;
                $score ++;
            }else{
                $status[$key] = 0;
            }
        }
        //将获取的数据进行json格式转化
        $questions = json_encode($question);
        $rights = json_encode($options);
        $status = json_encode($status);
        $users = session('userId');
        //将分数添加至用户积分排名
        $wechatModel = new WechatUser();
        $wechatModel->where('userid',$users)->setInc('score',$score);
        //  存储 表
        $Answers = new Answer();
        $Answers->userid = $users;
        $Answers->question_id = $questions;
        $Answers->value = $rights;
        $Answers->status = $status;
        $Answers->score = $score;
        $Answers->create_time = time();
        $res = $Answers->save();
        if($res){
            return $this->success('提交成功',array('id' =>$res,'score'=>$score,'right'=>$Right));
        }else{
            return $this->error('提交失败');
        }
    }

    /**
     * 手机党校详情页
     */
    public function detail(){
        $this->anonymous();
        $Model = new LearnModel();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(4,$userId,$id);
        }
        //获取点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(4,$id,$userId);
        $detail['is_like'] = $like;
        $this->assign('detail',$detail);
        //获取评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(4,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 党员先锋详情页
     */
    public function pioneer(){
        $this->anonymous();
        $Model = new Pioneer();
        $id = input('id');
        $userId = session('userId');
        $detail = $Model->get($id);

        $Model->where('id',$id)->setInc("views");
        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(8,$userId,$id);
        }
        $this->assign('detail',$detail);
        return $this->fetch();
    }

}