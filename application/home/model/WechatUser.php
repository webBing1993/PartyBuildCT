<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/5
 * Time: 17:10
 */

namespace app\home\model;


use think\Model;

class WechatUser extends Model {
    /**
     * 获取详情及数据统计
     */
    public function getDetail($id,$userId) {
        $res = $this->get($id);
        //组织活动Organization
        $map = array(
            'uid' => $userId,
            'type' => 6
        );
        $res['org1'] = Browse::where($map)->count();
        $res['org2'] = Organization::where(['status' => 1])->count();
        //学习情况learn
        $map['type'] = 4;
        $res['learn1'] = Browse::where($map)->count();
        $res['learn2'] = Learn::where(['status' => 1])->count();
        $map['type'] = 5;
        $res['course1'] = Browse::where($map)->count();
        $res['course2'] = Course::where(['status' => 1])->count();
        return $res;
    }
}