<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/12/1
 * Time: 16:15
 */

namespace app\admin\controller;

use app\admin\model\Pioneer as PioneerModel;
/**
 * Class Pioneer
 * @package app\admin\controller
 * 党员先锋
 */
class Pioneer extends Admin {
    /**
     * 主页列表
     */
    public function index() {
        $map = array(
            'status' => array('eq',1),  // 推送  未推送
        );
        $list = $this->lists('Pioneer',$map);
        int_to_string($list,array(
            'status' => array(0=>"未审核",1=>'已发布'),
            'recommend' => array(0=>"未推荐",1=>"推荐")
        ));
        $this->assign('list',$list);

        return $this->fetch();
    }

    /**
     * 新增
     */
    public function add() {
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if(empty($data['id'])) {
                unset($data['id']);
            }
            $Model = new PioneerModel();
            $info = $Model->validate(true)->save($data);
            if($info) {
                return $this->success("新增成功",Url('Pioneer/index'));
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
        if(IS_POST) {
            $data = input('post.');
            $Model = new PioneerModel();
            $info = $Model->validate(true)->save($data,['id'=>input('id')]);
            if($info){
                return $this->success("修改成功",Url("Pioneer/index"));
            }else{
                return $this->get_update_error_msg($Model->getError());
            }
        }else{
            $this->default_pic();
            $id = input('id');
            $msg = PioneerModel::get($id);
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
        $info = PioneerModel::where('id',$id)->update($data);
        if($info) {
            return $this->success("删除成功");
        }else{
            return $this->error("删除失败");
        }
    }
} 