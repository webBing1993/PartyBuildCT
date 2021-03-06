<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/20
 * Time: 13:47
 */

namespace app\home\controller;
use app\home\model\Module;
use app\home\model\Picture;
use app\home\model\Notice as NoticeModel;
use think\Db;


/**
 * 党建主页
 */
class Index extends Base {
    /**
     * 首页
     * @return mixed
     */
    public function index() {
        $ModuleModel = new Module();
        $module = $ModuleModel->getModuleList();
        $this->assign('module',$module);

        $list = $this->moreDataList();
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function moreDataList(){
        $len = input('length');
        if(empty($len)) { $len = 0;}
        $map = array(
            'status' => 1,
            'recommend' => 1
        );
        $res = Db::field('title,create_time,publisher,id,front_cover,list_type,0 as type')
            ->table('pb_notice')
            ->where($map)
            ->union("SELECT title,create_time,publisher,id,front_cover,list_type,0 as type FROM pb_focus where status=1 AND recommend=1")
            ->union("SELECT title,create_time,publisher,id,front_cover,list_type,type FROM pb_course where status=1 AND recommend=1 order by create_time desc limit $len,8")
            ->select();

        foreach ($res as $k=>$v) {
            $img = Picture::get($v['front_cover']);
            $res[$k]['path'] = $img['path'];
            $res[$k]['time'] = date("Y-m-d",$v['create_time']);
            switch ($v['list_type']) {
                case 1:
                    $res[$k]['type_text'] = "通知公告";
                    $res[$k]['url'] = Url('Notice/detail?id='.$v['id']);
                    break;
                case 2:
                    $res[$k]['type_text'] = "第一聚焦";
                    $res[$k]['url'] = Url('Focus/detail?id='.$v['id']);
                    break;
                case 3:
                    if($v['type'] == 1) {
                        $res[$k]['type_text'] = "相关通知";
                        $res[$k]['url'] = Url('Course/relevant?id='.$v['id']);
                    }elseif($v['type'] == 2) {
                        $res[$k]['type_text'] = "会议情况";
                        $res[$k]['url'] = Url('Course/meet?id='.$v['id']);
                    }else {
                        $res[$k]['type_text'] = "党课情况";
                        $res[$k]['url'] = Url('Course/party?id='.$v['id']);
                    }
                    break;
                default:
                    break;
            }
        }
        if($len == 0) {
            return $res;
        }else {
            if (empty($res)) {
                return $this->error('数据为空!');
            } else {
                return $this->success('数据获取成功!',null,$res);
            }
        }
    }
}