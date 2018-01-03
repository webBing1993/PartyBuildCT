<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2018/1/3
 * Time: 14:01
 */

namespace app\admin\model;


use think\Model;

class Structure extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}