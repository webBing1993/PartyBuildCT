<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2018/1/4
 * Time: 13:21
 */

namespace app\home\model;


use think\Model;

class Structure extends Model {
    /**
     * 获取部门列表
     */
    public function getList() {
        $map = array(
            'status' => 1
        );
        $res = WechatDepartment::where($map)->select();
        return $res;
    }

    /**
     * 获取详情及领导列表
     */
    public function getDetail($id) {
        $map = array(
            'departmentid' => $id,
            'status' => 1,
        );
        $detail = $this->where($map)->find();
        //书记
        $info = array(
            'department' => $id,
            'position' => 1
        );
        $one = WechatUser::where($info)->select();
        $detail['one'] = $one;

        $info['position'] = 2;
        $two = WechatUser::where($info)->select();
        $detail['two'] = $two;

        $info['position'] = 3;
        $three = WechatUser::where($info)->select();
        $detail['three'] = $three;
        return $detail;
    }

}