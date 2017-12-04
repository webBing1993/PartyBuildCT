<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/4
 * Time: 17:21
 */

namespace app\admin\model;

class Organization extends Base {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}