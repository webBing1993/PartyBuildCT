<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/11/28
 * Time: 16:08
 */

namespace app\admin\controller;

use app\admin\model\Investment as InvestmentModel;
/**
 * Class Investment
 * @package app\admin\controller
 * 招商信息
 */
class Investment extends Admin {
    /**
     * 主页列表
     */
    public function index() {
        $map = array(
            'status' => array('eq',1),
        );
        $list = $this->lists('Investment',$map);
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
        $Model = new InvestmentModel();
        if(IS_POST) {
            $data = input('post.');
            $data['create_user'] = $_SESSION['think']['user_auth']['id'];
            if(empty($data['id'])) {
                unset($data['id']);
            }
            $data['boarding_time'] = time_format($data['boarding_time']);
            $info = $Model->validate('notice.investment')->save($data);
            if($info) {
                return $this->success("新增成功",Url('Investment/index'));
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
        $Model = new InvestmentModel();
        if(IS_POST) {
            $data = input('post.');
            $data['boarding_time'] = time_format($data['boarding_time']);
            $info = $Model->validate('notice.investment')->save($data,['id'=>input('id')]);
            if($info){
                return $this->success("修改成功",Url("Investment/index"));
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
        $info = InvestmentModel::where('id',$id)->update($data);
        if($info) {
            return $this->success("删除成功");
        }else{
            return $this->error("删除失败");
        }
    }
}