<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2018/1/3
 * Time: 13:58
 */

namespace app\admin\controller;

use app\admin\model\Structure as StructureModel;
use app\admin\model\WechatDepartment;

/**
 * Class Structure
 * @package app\admin\controller
 * 组织架构
 */
class Structure extends Admin {
    /**
     * 主页列表
     */
    public function index() {
        $map = array(
            'status' => array('eq',1),  // 推送  未推送
        );
        $list = $this->lists('Structure',$map);
        int_to_string($list,array(
            'status' => array(1=>'已发布'),
        ));
        $this->assign('list',$list);

        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        $Model = new StructureModel();
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if(empty($data['id'])) {
                unset($data['id']);
            }
            $info = $Model->validate(true)->save($data);
            if($info) {
                return $this->success("新增成功",Url('Structure/index'));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $department = WechatDepartment::all();
            $this->assign('depart',$department);
            $this->assign('msg','');

            return $this->fetch('edit');
        }
    }

    /**
     * 修改
     */
    public function edit() {
        $Model = new StructureModel();
        if(IS_POST) {
            $data = input('post.');
            $info = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($info){
                return $this->success("修改成功",Url("Structure/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $department = WechatDepartment::all();
            $this->assign('depart',$department);
            $id = input('id');
            $msg = $Model::get($id);
            $this->assign('msg',$msg);

            return $this->fetch();
        }
    }

    /**
     * 删除
     */
    public function del() {
        $id = input('id');
        $data['status'] = '-1';
        $info = StructureModel::where('id',$id)->update($data);
        if($info) {
            return $this->success("删除成功");
        }else{
            return $this->error("删除失败");
        }
    }
}