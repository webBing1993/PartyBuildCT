<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/23
 * Time: 14:49
 */

namespace app\admin\model;


use think\Model;

class Module extends Model {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}