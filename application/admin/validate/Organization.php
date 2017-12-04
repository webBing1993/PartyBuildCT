<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/4
 * Time: 17:21
 */

namespace app\admin\validate;


use think\Validate;

class Organization extends Validate {
    protected $rule = [
        'front_cover' => 'require',
        'title' => 'require',
        'participants' => 'require',
        'time' => 'require',
        'address' => 'require',
        'content' => 'require',
        'publisher' => 'require',
    ];

    protected $message = [
        'front_cover' => '封面图片不能为空',
        'title' => '标题不能为空',
        'participants' => '参加者人员不能为空',
        'time' => '通知时间不能为空',
        'address' => '地址不能为空',
        'content' => '内容不能为空',
        'publisher' => '发布人不能为空',
    ];

    protected $scene = [
        'one' => ['front_cover','title','participants','time','address','content','publisher'],
        'two' => ['front_cover','title','content','publisher'],
    ];
}