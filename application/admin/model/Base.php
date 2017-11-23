<?php
/**
 * Created by PhpStorm.
 * User: 虚空之翼 <183700295@qq.com>
 * Date: 16/5/20
 * Time: 10:11
 */

namespace app\admin\model;

use Endroid\QrCode\QrCode;
use think\Model;

class Base extends Model
{
    protected $autoWriteTimestamp = false;

    //获取新增用户名称
    public function cuser(){
        return $this->hasOne('Member','id','create_user');
    }

    //获取更新用户
    public function uuser(){
        return $this->hasOne('Member','id','update_user');
    }

    /**
     * 利用时间戳及英文字母生成16位随机数
     */
    function foo() {
        $o = $last = '';
        do {
            $last = $o;
            usleep(10);
            $t = explode(' ', microtime());
            $o = substr(base_convert(strtr($t[0].$t[1].$t[1],'.',''),10,36),0,16);
        }while($o == $last);
        return $o;
    }
}