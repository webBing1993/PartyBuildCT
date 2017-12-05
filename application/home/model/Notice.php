<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/5
 * Time: 14:20
 */

namespace app\home\model;


use think\Model;

class Notice extends Model {
    /**
     * 获取首页列表
     */
    public function getIndexList() {
        $map = array(
            'status' => 1,
        );
        $order = array("create_time desc");
        $one = $this->where($map)->order($order)->limit(8)->select();
        $two = Water::where($map)->order($order)->limit(8)->select();
        $three = Investment::where($map)->order($order)->limit(8)->select();
        $res = array(
            'one' => $one,
            'two' => $two,
            'three' => $three
        );
        return $res;
    }

    /**
     * 加载更多
     */
    public function getMoreList($len=0,$type) {
        $map = array(
            'status' => 1,
        );
        $order = array("create_time desc");
        switch ($type) {
            case 1:
                $res = $this->where($map)->order($order)->limit($len,8)->select();
                break;
            case 2:
                $res = Water::where($map)->order($order)->limit($len,8)->select();
                break;
            case 3:
                $res = Investment::where($map)->order($order)->limit($len,8)->select();
                break;
            default:
                $res = null;
                break;
        }
        if($res) {
            foreach ($res as $value) {
                $value['time'] = date("Y-m-d",$value['create_time']);
            }
            return $res;
        }else{
            return 0;
        }

    }
}