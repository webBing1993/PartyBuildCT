<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/5
 * Time: 9:47
 */

namespace app\home\model;


class Focus extends Module {
    /**
     * 获取推荐三条数据
     */
    public function getTopThree() {
        $map = array(
            'recommend' => 1,
            'status' => 1
        );
        $order = array("create_time desc");
        $res = $this->where($map)->order($order)->limit(3)->select();
        return $res;
    }

    /**
     * 获取列表页数据及加载更多
     */
    public function getList($length = 0) {
        $map = array(
            'status' => 1
        );
        $order = array("create_time desc");
        $field = array("id,front_cover,title,create_time");
        $res = $this->where($map)->order($order)->field($field)->limit($length,6)->select();
        foreach ($res as $value) {
            $pic = Picture::get($value['front_cover']);
            $value['path'] = $pic['path'];
            $value['time'] = date("Y-m-d",$value['create_time']);
        }
        return $res;
    }
    
    /**
     * 获取详情
     */
    public function getDetail($id) {
        $this->where('id',$id)->setInc('views');
        $res = $this->get($id);
        return $res;
    }
}