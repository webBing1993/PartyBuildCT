<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/13
 * Time: 10:02
 */

namespace app\home\controller;


use app\home\model\Focus;
use app\home\model\Learn;
use app\home\model\Notice;
use app\home\model\Picture;
use com\wechat\TPWechat;
use think\Config;
use think\Controller;

class Push extends Controller {
    /**
     * 订阅号每日定时推送
     */
    public function cron(){
        $Wechat = new TPWechat(Config::get('party'));
        $author = '城投党建';//推送作者
        $request = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];//域名
        //获取需要推送的数据
        $list = $this->pushList();
        //没有需要推送的消息,就只推每日一课
        if(!empty($list)){
            //先上传素材 media_id
            foreach($list as $k => $v){
                //class 1 通知公告  2第一聚焦 3手机党校
                $class = $v['class'];
                $data = array(
                    "media" => '@.'.$v['img']
                );
                $img = $Wechat->uploadForeverMedia($data,'thumb');
                $v['thumb_media_id'] = $img['media_id'];
                $id = $v['id'];
                if($class == 1) {
                    $link = 'notice/detail';
                } else if($class == 2){
                    $link = 'focus/detail';
                }else if($class == 3){
                    $link = 'learn/detail';
                }
                $v['content_source_url'] = "$request/home/$link/id/$id";
            }
            //图文素材列表
            $article = array();
            foreach ($list as $k =>$v ){
                $article['articles'][$k] = [
                    'thumb_media_id' => $v['thumb_media_id'],
                    'author' => $v['publisher'],
                    'title' => $v['title'],
                    'content_source_url' => $v['content_source_url'],
                    "content" => $v['content'],
                    "digest" => $v['title'],
                    "show_cover_pic" => 0,
                ];
            }
//            //最后一条加入每日一课
//            $article['articles'][count($article['articles'])] = [
//                    'thumb_media_id' => $image_id,
//                    'author' => $author,
//                    'title' =>'每日一课',
//                    'content_source_url' => "$request.$answer",
//                    "content" => "每日一课已经等候你多时了,点阅读全文开始答题!",
//                    "digest" => "休息一下,去答一下题吧",
//                    "show_cover_pic" => 1,
//                ];
            $lists =  $article;
            //上传多条图文素材
            $info = $Wechat ->uploadForeverArticles($lists);
            //消息群发
            $send = [
                "filter" => [
                    "is_to_all" => true
                ],
                "mpnews" =>[
                    "media_id" => $info['media_id']
                ],
                "msgtype" => "mpnews",
                "send_ignore_reprint" => 0
            ];
            $res = $Wechat ->sendGroupMassMessage($send);
            //发送成功 修改对应数据状态
            if($res['errcode'] == 0){
                return  $this->success('推送成功');
            }
        }
    }
    /**
     * 获取待推送的8条数据
     * @return array
     */
    public function pushList(){
        $count = 6; //总数据数量
        $count1 = 0;  //从第几条开始取数据
        $count2 = 0;
        $count3 = 0;
        $notice = new Notice();  // 通知公告
        $focus = new Focus();  // 第一聚焦
        $learn  = new Learn(); //手机党校
        $notice_check = false; //新闻数据状态 true为取空
        $focus_check = false;
        $learn_check = false;

        $all_list = array();
        //获取数据  取满8条 或者取不出数据退出循环
        while(true)
        {
            // 通知公告
            if(!$notice_check &&
                count($all_list) < $count)
            {
                //获取一条数据
                $res = $notice->where(['status' => ['egt',1],'push' => 1])->whereTime('create_time','d')->order('id desc')->limit($count1,2)->select();
                if(empty($res))
                {
                    $notice_check = true;
                }else {
                    $count1 ++;
                    $all_list = $this ->changeTpye($all_list,$res,1); //给每条数据增加类别判断
                }
            }
            // 第一聚焦
            if(!$focus_check &&
                count($all_list) < $count)
            {
                $res = $focus ->where(['status' => ['egt',1],'push' => 1])->whereTime('create_time','d')->order('id desc')->limit($count2,2)->select();
                if(empty($res))
                {
                    $focus_check = true;
                }else {
                    $count2 ++;
                    $all_list = $this ->changeTpye($all_list,$res,2);
                }
            }
            // 手机党校
            if(!$learn_check &&
                count($all_list) < $count)
            {
                $res = $learn ->where(['status' => ['egt',1],'push' => 1])->whereTime('create_time','d')->order('id desc')->limit($count3,2)->select();
                if(empty($res))
                {
                    $learn_check = true;
                }else {
                    $count3 ++;
                    $all_list = $this ->changeTpye($all_list,$res,3);
                }
            }
            if(count($all_list) >= $count ||
                ($notice_check && $focus_check && $learn_check ))
            {
                break;
            }
        }
        $arr = array();
        foreach ($all_list as $value) {
            foreach ($value as $v) {
                $arr[] = $v;
            }
        }
        return $arr;
    }
    /**
     * 进行数据区分
     * @param $list
     * @param $type
     */
    private function changeTpye($all,$list,$type){
        foreach ($list as $value) {
            //图片进行转化
            $img = Picture::get($value['front_cover']);
            $value['img'] = $img['path'];
            $value['class'] = $type;
        }
        array_push($all,$list);
        return $all;
    }
}