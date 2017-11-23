<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/23
 * Time: 14:48
 */

namespace app\admin\controller;

use app\admin\model\Module as ModuleModel;
/**
 * Class Module
 * @package app\admin\controller
 * 首页模块管理
 */
class Module extends Admin {
    /**
     * 主页列表
     */
    public function index() {
        $map = array(
            'status' => array('eq',1),  // 推送  未推送
        );
        $list = $this->lists('Module',$map);
        int_to_string($list,array(
            'is_show' => array(0=>"隐藏",1=>'显示'),
        ));
        $this->assign('list',$list);

        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        $Model = new ModuleModel();
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if(empty($data['id'])) {
                unset($data['id']);
            }
            $info = $Model->validate(true)->save($data);
            if($info) {
                return $this->success("新增成功",Url('Module/index'));
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
        $Model = new ModuleModel();
        if(IS_POST) {
            $data = input('post.');
            $info = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($info){
                return $this->success("修改成功",Url("Module/index"));
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
        $info = ModuleModel::where('id',$id)->update($data);
        if($info) {
            return $this->success("删除成功");
        }else{
            return $this->error("删除失败");
        }
    }
    
    /**
     * 改变是否显示状态
     */
    public function setShow($id,$value = 1) {
        $result = ModuleModel::where('id', $id)->update(['is_show' => $value]);
        if($result) {
            return $this->success("操作成功");
        } else {
            return $this->error("操作成功");
        }
    }
}