<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/27
 * Time: 11:17
 */

namespace app\admin\validate;


use think\Validate;

class Notice extends Validate {
    protected $rule = [
        'front_cover' => 'require',
        'title' => 'require',
        'summary' => 'require',
        'content' => 'require',
        'conclusion' => 'require',
        'theme' => 'require',
        'time' => 'require',
        'address' => 'require',
        'publisher' => 'require',
        'name' => 'require',
        'type' => 'require',
        'contacts' => 'require',
        'telephone' => 'require',
        'intentional' => 'require',
        'boarding_time' => 'require',
    ];

    protected $message = [
        'front_cover' => '封面图片不能为空',
        'title' => '标题不能为空',
        'summary' => '简介不能为空',
        'content' => '内容不能为空',
        'conclusion' => '结论不能为空',
        'theme' => '主题不能为空',
        'time' => '时间不能为空',
        'address' => '地址不能为空',
        'publisher' => '发布人不能为空',
        'name' => '企业名称不能为空',
        'type' => '企业类型不能为空',
        'contacts' => '联系人不能为空',
        'telephone' => '联系方式不能为空',
        'intentional' => '意向房源不能为空',
        'boarding_time' => '登记时间不能为空',
    ];

    protected $scene = [
        'notice' => ['front_cover','title','theme','time','address','content','publisher'],
        'water' => ['title','summary','content','conclusion','publisher'],
        'investment' => ['front_cover','name','type','content','address','contacts','telephone','intentional','boarding_time'],
    ];
}