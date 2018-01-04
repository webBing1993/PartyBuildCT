<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/4
 * Time: 17:21
 */

namespace app\admin\controller;

use app\admin\model\Organization as OrganizationModel;
/**
 * Class Organization
 * @package app\admin\controller
 * 组织活动
 */
class Organization extends Admin {
    /**
     * 主页列表
     */
    public function index() {
        $map = array(
            'status' => array('eq',1),
        );
        $list = $this->lists('Organization',$map);
        int_to_string($list,array(
            'type' => array(1=>"活动通知",2=>"活动展示"),
            'status' => array(1=>'已发布'),
        ));
        $this->assign('list',$list);

        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        $Model = new OrganizationModel();
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if(empty($data['id'])) {
                unset($data['id']);
            }
            if($data['type'] == 1) {
                if($data['class'] == 1) {
                    if(empty($data['time'])) {
                        return $this->error("时间不能为空!");
                    }else {
                        $data['time'] = strtotime($data['time']);
                    }
                    $info = $Model->validate('Organization.one')->save($data); 
                }else {
                    unset($data['time']);
                    $info = $Model->validate('Organization.two')->save($data); 
                }
            }else {
                unset($data['time']);
                $info = $Model->validate('Organization.two')->save($data);
            }
            if($info) {
                return $this->success("新增成功",Url('Organization/index'));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $this->assign('msg','');

            return $this->fetch('edit');
        }
    }

    /**
     * 修改
     */
    public function edit() {
        $Model = new OrganizationModel();
        if(IS_POST) {
            $data = input('post.');
            if($data['type'] == 1) {
                if($data['class'] == 1) {
                    if(empty($data['time'])) {
                        return $this->error("时间不能为空!");
                    }else {
                        $data['time'] = strtotime($data['time']);
                    }
                    $info = $Model->validate('Organization.one')->save($data,['id'=>input('id')]);
                }else {
                    unset($data['time']);
                    $info = $Model->validate('Organization.two')->save($data,['id'=>input('id')]);
                }
            }else {
                unset($data['time']);
                $info = $Model->validate('Organization.two')->save($data,['id'=>input('id')]);
            }
            if($info){
                return $this->success("修改成功",Url("Organization/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
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
        $info = OrganizationModel::where('id',$id)->update($data);
        if($info) {
            return $this->success("删除成功");
        }else{
            return $this->error("删除失败");
        }
    }
}