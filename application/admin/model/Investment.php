<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/28
 * Time: 16:08
 */

namespace app\admin\model;


class Investment extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}