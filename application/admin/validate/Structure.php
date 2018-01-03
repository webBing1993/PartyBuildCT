<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2018/1/3
 * Time: 14:02
 */

namespace app\admin\validate;


use think\Validate;

class Structure extends Validate {
    protected $rule = [
        'departmentid' => 'require',
        'name' => 'require',
        'content' => 'require',
    ];

    protected $message = [
        'departmentid' => '请选择对应的部门',
        'name' => '支部名称不能为空',
        'content' => '部门简介不能为空',
    ];
}