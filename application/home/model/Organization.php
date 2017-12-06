<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/6
 * Time: 11:04
 */

namespace app\home\model;


use think\Model;

class Organization extends Model {

    /**
     * 获取首页数据列表
     */
    public function getIndexList() {
        $map = array(
            'status' => 1
        );
        $order = array("create_time desc");
        $map['type'] = 1;
        $one = $this->where($map)->order($order)->limit(6)->select();
        foreach ($one as $value) {
            if($value['time'] > time()) {
                $value['is_conduct'] = 1;
            }else {
                $value['is_conduct'] = 0;
            }
        }
        $map['type'] = 2;
        $two = $this->where($map)->order($order)->limit(6)->select();
        $res = array(
            'one' => $one,
            'two' => $two
        );
        return $res;
    }

    /**
     * 主页加载更多
     * $length $type
     */
    public function getMoreList($len = 0,$type) {
        $map = array(
            'status' => 1,
            'type' => $type
        );
        $order = array("create_time desc");
        $res = $this->where($map)->order($order)->limit($len,6)->select();
        foreach ($res as $value) {
            $pic = Picture::get($value['front_cover']);
            $value['path'] = $pic['path'];
            $value['create_time'] = date("Y-m-d",$value['create_time']);
        }
        return $res;
    }
}