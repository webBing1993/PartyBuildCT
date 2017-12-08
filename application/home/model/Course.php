<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/6
 * Time: 17:01
 */

namespace app\home\model;


use think\Model;

class Course extends Model {
    /**
     * 获取主页列表
     */
    public function getIndexList() {
        $map = array(
            'status' => 1
        );
        $order = array("create_time desc");
        $map['type'] = 1;
        $one = $this->where($map)->order($order)->limit(2)->select();
        foreach ($one as $value) {
            if($value['time'] > time()) {
                $value['is_conduct'] = 1;
            }else {
                $value['is_conduct'] = 0;
            }
        }
        $map['type'] = 2;
        $two = $this->where($map)->order($order)->limit(2)->select();
        $map['type'] = 3;
        $three = $this->where($map)->order($order)->limit(3)->select();
        $res = array(
            'one' => $one,
            'two' => $two,
            'three' => $three
        );
        return $res;
    }

    /**
     * 获取相关通知
     *
     */
    public function getListMore($type,$len = 0) {
        $map = array(
            'type' => $type,
            'status' => 1
        );
        $order = array("create_time desc");
        $res = $this->where($map)->order($order)->limit($len,8)->select();
        if($res) {
            foreach ($res as $value) {
                if($value['time'] > time()) {
                    $value['is_conduct'] = 1;
                }else {
                    $value['is_conduct'] = 0;
                }
                $pic = Picture::get($value['front_cover']);
                $value['path'] = $pic['path'];
                $value['create_time_text'] = date('Y-m-d',$value['create_time']);
            }
            return $res;
        }else {
            return 0;
        }

    }
}