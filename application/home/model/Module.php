<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/4
 * Time: 17:44
 */

namespace app\home\model;


use think\Model;
use think\Url;

class Module extends Model {
    /**
     * 获取模块管理数据
     */
    public function getModuleList() {
        $map = array(
            'status' => 1,
            'is_show' => 1
        );
        $res = $this->where($map)->select();
        foreach ($res as $value) {
            $value['url'] = Url($value['url']);
        }
        return $res;
    }
}