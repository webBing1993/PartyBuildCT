<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;

use app\home\model\Structure as StructureModel;
class Structure extends Base{
    /*
     * 组织架构主页
     */
    public function index(){
        $this ->anonymous();
        $this->assign('title',config('title'));
        $Model = new StructureModel();
        $list = $Model->getList();
        $this->assign('list',$list);
        return $this->fetch();
    }
    /*
     * 组织架构详情页
     */
    public function detail(){
        $this ->anonymous();
        $party = input('party');
        $this->assign('party',$party);
        return $this->fetch();
    }


    /*
     * 组织架构介绍页
     * */
    public function intro(){
        $Model = new StructureModel();
        $id = input('id');
        $detail = $Model->getDetail($id);
        $this->assign('detail',$detail);
        return $this->fetch();
    }


}
