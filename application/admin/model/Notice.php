<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/27
 * Time: 11:16
 */

namespace app\admin\model;


class Notice extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 1
    ];
}