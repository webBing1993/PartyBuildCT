<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/23
 * Time: 17:22
 */

namespace app\admin\model;


class Focus extends Base {
    protected $insert = [
        'views' => 0,
        'likes' => 0,
        'comments' => 0,
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}