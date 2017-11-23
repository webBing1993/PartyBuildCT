<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/23
 * Time: 14:58
 */

namespace app\admin\validate;


use think\Validate;

class Module extends Validate {
    protected $rule = [
        'icon' => 'require',
        'name' => 'require',
        'url' => 'require',
    ];

    protected $message = [
        'icon' => '图标不能为空',
        'name' =>  '名称不能为空',
        'url'  =>  '链接不能为空',
    ];
}