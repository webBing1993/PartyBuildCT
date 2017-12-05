<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/5
 * Time: 15:55
 */

namespace app\home\model;

use think\Model;
class Learn extends Model {
    /**
     * 获取主页数据
     */
    public function getIndexList() {
        $map = array(
            'status' => 1
        );
        $order = array("create_time desc");
        //手机党校
        $learn = $this->where($map)->order($order)->limit(8)->select();    
        //练练身手
        $arr = Question::all(['type'=>0]);
        foreach($arr as $value){
            $ids[] = $value->id;
        }
        //随机获取单选的题目
        $num = 5;//题目数目
        $data=array();
        while(true){
            if(count($data) == $num){
                break;
            }
            $index = mt_rand(0,count($ids)-1);
            $res = $ids[$index];
            if(!in_array($res,$data)){
                $data[] = $res;
            }
        }
        foreach($data as $value){
            $question[] = Question::get($value);
        }
        //党员先锋
        $pioneer = Pioneer::where($map)->order($order)->limit(8)->select();
        $res = array(
            'learn' => $learn,
            'question' => $question,
            'pioneer' => $pioneer
        );
        return $res;
    }

    /**
     * 手机党校 type1，党员先锋type3加载更多
     */
    public function getMoreList($len=0,$type){
        $map = array(
            'status' => 1
        );
        $order = array("create_time desc");
        if($type == 1) {
            $res = $this->where($map)->order($order)->limit($len,8)->select();
        }else {
            $res = Pioneer::where($map)->order($order)->limit($len,8)->select();
        }
        if($res) {
            foreach ($res as $value) {
                $pic = Picture::get($value['front_cover']);
                $value['path'] = $pic['path'];
            }
            return $res;
        }else {
            return 0;
        }
    }
}