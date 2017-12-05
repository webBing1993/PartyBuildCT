<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/5
 * Time: 16:40
 */

namespace app\home\model;


use think\Model;

class Question extends Model {
    //获取题目编号
    public function Option(){
        $questionId = $this->getData('id');
        $Option = new Option();
        //根据题目编号获取所有选项信息
        $option=$Option->where('question_id='.$questionId)->select();
        $arr=array();
        foreach($option as $value){
            if($value->content!==""){
                $arr[]=$value->style.'.&nbsp;'.$value->content;
            }
        }
        return $arr;
    }

    //获取正确答案
    public function Right(){
        $lists=array(
            1 => "A",
            2 => "B",
            3 => "C",
            4 => "D"
        );
        $rights=$this->getData('value');
        $arr=explode(":",$rights);
        foreach($arr as $value){
            $right[]=$lists[$value];
        }
        return $right;
    }
}