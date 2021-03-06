<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/21
 * Time: 15:58
 */

namespace app\home\controller;

use app\home\model\Browse;
use app\home\model\Comment;
use app\home\model\Like;
use app\user\model\WechatUser;
use think\Config;
use think\Controller;
use com\wechat\TPQYWechat;
use think\Cookie;
use think\Db;

class Base extends Controller {
    public function _initialize(){
//        session('userId','fa01047e6ea1e356cacc67eb75af40a7');
//        session('header','/home/images/vistor.jpg');
//        session('nickname','游客');
        session('requestUri', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
        $cookie = Cookie::get('dypb')['user'];
        $userId = session('userId');
        if(Config::get('WEB_SITE_CLOSE')){
            return $this->error('站点维护中，请稍后访问~');
        }

        //如果是游客的话默认userid为visitors
        if($userId == 'visitor'){
            session('nickname','游客');
            session('header','/home/images/common/vistor.jpg');
        }else{
            //微信认证
            $Wechat = new TPQYWechat(Config::get('party'));
            // 1用户认证是否登陆
            if(empty($cookie)) {
                $this->redirect('Verify/memberslogin');//跳转登录页
            }else {
                Cookie::init(Config::get('cookie'));
                session('userId',Cookie::get('dypb')['user']);
            }

            // 2获取jsapi_ticket
            $jsApiTicket = session('jsapiticket');
            if(empty($jsApiTicket) || $jsApiTicket=='') {
                session('jsapiticket', $Wechat->getJsTicket()); // 官方7200,设置7000防止误差
            }
        }
    }
    
    /**
     * 微信官方认证URL
     */
    public function oauth(){
        $Wechat = new TPQYWechat(Config::get('party'));
        $Wechat->valid();
    }

    /**
     * 判断是否是游客登录
     */
    public function anonymous() {
        $userId = session('userId');
        //如果userId等于visitor  则为游客登录，否则则正常显示
        if($userId == 'visitor'){
            $this->assign('visit', 1);
        }else{
            $this->assign('visit', 0);
        }
    }

    /**
     * 获取企业号签名
     */
    public function jssdk(){
        $Wechat = new TPQYWechat(Config::get('party'));
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $jsSign = $Wechat->getJsSign($url);
        $this->assign("jsSign", $jsSign);
    }

    /**
     * 默认图片
     * $front_pic 封面图片id：1-10
     * $list_pic 列表图片id：35-44
     * $carousel_pic 轮播图片id: 45-54
     */
    public function default_pic(){
        //随机轮播图
        $c = array('1'=>'a','2'=>'b','3'=>'c','4'=>'d','5'=>'e','6'=>'f','7'=>'g','8'=>'h','9'=>'i','10'=>'j','11'=>'k','12'=>'l','13'=>'m','14'=>'n','15'=>'o',
            '16'=>'p','17'=>'q','18'=>'r','19'=>'s','20'=>'t','21'=>'u','22'=>'v','23'=>'w','24'=>'x','25'=>'y','26'=>'z');
        $carousel_pic1 = array_rand($c,1);
        $this->assign('p1',$carousel_pic1);
        $carousel_pic2 = array_rand($c,1);
        $this->assign('p2',$carousel_pic2);
        $carousel_pic3 = array_rand($c,1);
        $this->assign('p3',$carousel_pic3);

    }

    /**
     * 点赞，$type,$aid
     * type值：表类型
     */
    public function like(){
        $uid = session('userId'); //点赞人
        $type = input('type'); //获取点赞类型
        $aid = input('aid');
        switch ($type) {    //根据类别获取表明
            case 0:
                $table = "comment";
                break;
            case 1:
                $table = "notice";
                break;
            case 2:
                $table = "water";
                break;
            case 3:
                $table = "focus";
                break;
            case 4:
                $table = "learn";
                break;
            case 5:
                $table = "course";
                break;
            case 6:
                $table = "organization";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $data = array(
            'type' => $type,
            'table' => $table,
            'aid' => $aid,
            'uid' => $uid,
        );
        $likeModel = new Like();
        $like = $likeModel->where($data)->find();
        if(empty($like)) {  //点赞
            $res = $likeModel->create($data);
            if($res) {
                //点赞成功积分+1
                WechatUser::where('userid',$uid)->setInc('score',1);
                //更新数据
                Db::name($table)->where('id',$aid)->setInc('likes');
                return $this->success("点赞成功");
            }else {
                return $this->error("点赞失败");
            }
        }else { //取消
            $result = $likeModel::where($data)->delete();
            if($result) {
                //取消成功积分-1
                WechatUser::where('userid',$uid)->setDec('score',1);
                Db::name($table)->where('id',$aid)->setDec('likes');
                return $this->success("取消成功");
            }else {
                return $this->error("取消失败");
            }
        }
    }

    /**
     * 评论，$type,$aid,$content
     * type值：表类型
     */
    public function comment(){
        if(IS_POST){
            $uid = session('userId');
            $type = input('type');
            $aid = input('aid');
            switch ($type) {    //根据类别获取表明
                case 1:
                    $table = "notice";
                    break;
                case 2:
                    $table = "water";
                    break;
                case 3:
                    $table = "focus";
                    break;
                case 4:
                    $table = "learn";
                    break;
                case 5:
                    $table = "course";
                    break;
                case 6:
                    $table = "organization";
                    break;
                default:
                    return $this->error("无该数据表");
                    break;
            }
            $commentModel = new Comment();
            $data = array(
                'content' => input('content'),
                'type' => $type,
                'aid' => $aid,
                'uid' => $uid,
                'table' => $table,
            );
            $res = $commentModel->create($data);
            if($res) {  //返回comment数组
                //评论成功增加1分
                WechatUser::where('userid',$uid)->setInc('score',1);
                //更新主表数据
                $map['id'] = $res['aid'];   //文章id
                $model = Db::name($table)->where($map)->setInc('comments');
                if($model) {
                    $user = WechatUser::where('userid',$uid)->find();    //获取用户头像和昵称
                    $nickname = ($user['nickname']) ? $user['nickname'] : $user['name'];
//                    $header =  ($user['header']) ? $user['header'] : $user['avatar'];
                    //返回用户数据
                    $jsonData = array(
                        'id' => $res['id'],
                        'time' => date("Y-m-d",time()),
                        'content' => input('content'),
                        'nickname' => $nickname,
                        'header' => $user['header'],
                        'type' => $type,
                        'create_time' => time(),
                        'likes' => 0,
                        'status' => 1,
                    );
                    return $this->success("评论成功","",$jsonData);
                }else {
                    return $this->error("评论失败");
                }
            }else {
                return $this->error($commentModel->getError());
            }
        }
    }

    /**
     * 加载更多评论
     */
    public function morecomment(){
        $uid = session('userId');
        $len = input('length');
        $map = array(
            'type' => input('type'),
            'aid' => input('aid'),
        );
        //敏感词屏蔽
        $badword = array(
            '法轮功','法轮','FLG','六四','6.4','flg'
        );
        $badword1 = array_combine($badword,array_fill(0,count($badword),'***'));
        $comment = Comment::where($map)->order('likes desc,create_time desc')->limit($len,7)->select();
        if($comment) {
            foreach ($comment as $value) {
                $user = WechatUser::where('userid',$value['uid'])->find();
                $value['nickname'] = $user['name'];
                $value['header'] = $user['header'];
                $value['time'] = date('Y-m-d',$value['create_time']);
                $value['content'] = strtr($value['content'], $badword1);
                $map1 = array(
                    'type' => 0,
                    'aid' => $value['id'],
                    'uid' => $uid,
                    'status' => 0,
                );
                $like = Like::where($map1)->find();
                ($like) ? $value['is_like'] = 1 : $value['is_like'] = 0;
            }
            return $this->success("加载成功","",$comment);
        }else{
            return $this->error("没有更多");
        }

    }

    /**
     * 浏览记录
     */
    public function browser($type,$uid,$aid) {
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "notice";
                break;
            case 2:
                $table = "water";
                break;
            case 3:
                $table = "focus";
                break;
            case 4:
                $table = "learn";
                break;
            case 5:
                $table = "course";
                break;
            case 6:
                $table = "organization";
                break;
            case 7:
                $table = "investment";
                break;
            case 8:
                $table = "pioneer";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $data = array(
            'type' => $type,
            'uid' => $uid,
            'aid' => $aid,
            'table' => $table
        );
        $browserModel = new Browse();
        $history = $browserModel->where($data)->find();

        if(!$history && $aid != 0){
            $s['score'] = array('exp','`score`+1');
            $browserModel->create($data);
            WechatUser::where('userid',$uid)->update($s);
        }
    }
}