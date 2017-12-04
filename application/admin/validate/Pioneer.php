<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/4
 * Time: 10:20
 */

namespace app\admin\validate;


use think\Validate;

class Pioneer extends Validate {
    protected $rule = [
        'front_cover' => 'require',
        'name' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'front_cover' => '照片不能为空',
        'name' =>  '标题不能为空',
        'content'  =>  '内容不能为空',
    ];
}