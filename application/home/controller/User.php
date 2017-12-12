<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/20
 * Time: 15:34
 */

namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\Picture;
use app\home\model\WechatUser;
use think\Controller;
use think\Db;

/**
 * Class User
 * 用户个人中心
 */
class User extends Base {
    /**
     * 个人中心主页
     */
    public function index(){
        //游客判断
        $this->anonymous();
        $userId = session('userId');
        $user = WechatUser::where('userid',$userId)->find();
        $this->assign('user',$user);
        return $this->fetch();
    }

    /**
     * 个人信息页
     */
    public function personal(){
        $Model = new  WechatUser();
        $id = input('id');
        //生成二维码
        $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
        $res = $Model->scerweima($url);
//        $detail = $Model->get($id);
//        $this->assign('detail',$detail);
//        return $this->fetch();
    }

    /**
     * 设置头像
     */
    public function setHeader(){
        $userId = session('userId');
        $header = input('header');
        $map['header'] = $header;
        $info = WechatUser::where('userid',$userId)->update($map);
        if($info){
            return $this->success("修改成功");
        }else{
            return $this->error("修改失败");
        }
    }

}
