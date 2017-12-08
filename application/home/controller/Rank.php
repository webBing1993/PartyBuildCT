<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/10/19
 * Time: 19:09
 */

namespace app\home\controller;
use app\home\model\Browse;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\WechatDepartment;
use app\home\model\WechatDepartmentUser;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use think\Controller;
use think\Db;

/**
 * Class Rank
 * 排行榜
 */
class Rank extends Base {
    /**
     * 个人中心 支部排行榜
     */
    public function personal(){
        $this->anonymous();
        $wechatModel = new WechatUser();
        $userId = session('userId');
        //个人信息
        $personal = $wechatModel::where('userid',$userId)->find();

        //总榜
        $con['score'] = array('neq',0);
        $all  = $wechatModel->where($con)->order('score desc')->limit(50)->select();
        foreach ($all as $k => $value){
            if($value['userid'] == $userId){
                $personal['rank'] = $k+1;
            }
        }
        $this->assign('all',$all);
        $this->assign('personal',$personal);

        $map['status'] = array('egt',0);
        //本周浏览
        $browse = Browse::where($map)->whereTime('create_time','w')->select();
        $list1 = array();
        foreach ($browse as $value){
            $k = $value['user_id'];
            $list1[$k][] = $value;
        }
        $new1 = array();
        foreach ($list1 as $u => $val){
            $count = count($list1[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new1[] = $cen;
        }
        //本周评论
        $comment = Comment::where($map)->whereTime('create_time','w')->select();
        $list2 = array();
        foreach ($comment as $value){
            $k = $value['uid'];
            $list2[$k][] = $value;
        }
        $new2 = array();
        foreach ($list2 as $u => $val){
            $count = count($list2[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new2[] = $cen;
        }
        //本周点赞
        $like = Like::where($map)->whereTime('create_time','w')->select();
        $list3 = array();
        foreach ($like as $value){
            $k = $value['uid'];
            $list3[$k][] = $value;
        }
        $new3 = array();
        foreach ($list3 as $u => $val){
            $count = count($list3[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new3[] = $cen;
        }

        //先第一组、第二组比较，相同累加，不同删除，合并到过渡数组center中
        $center = array();
        foreach ($new1 as $k1 => $v1){
            foreach ($new2 as $k2 => $v2){
                if($v1['userid'] == $v2['userid']){
                    $cen['userid'] = $v2['userid'];
                    $cen['score'] = $v1['score'] + $v2['score'];
                    $center[] = $cen;
                    unset($new2[$k2]);
                    unset($new1[$k1]);
                }else{

                }
            }
        }
        $center = array_merge($center,$new2,$new1);     //unset掉重复值，重组成新数组

        //过渡数组与第三组比较同里，结果存入final2
        $final2 = array();
        foreach ($center as $f => $val){
            foreach ($new3 as $k3 => $v3){
                if($val['userid'] == $v3['userid']){
                    $cen['userid'] = $v3['userid'];
                    $cen['score'] = $val['score'] + $v3['score'];
                    $final2[] = $cen;
                    unset($new3[$k3]);
                    unset($center[$f]);
                }
            }
        }
        $final2 = array_merge($final2,$new3,$center);
        //倒序，字段score排序
        $sort = array(
            'direction' => 'SORT_DESC',
            'field' => 'score',
        );
        $arrSort = array();
        foreach ($final2 as $k => $v){
            foreach ($v as $key => $value){
                $arrSort[$key][$k] = $value;
            }
        }
        if($sort['direction'] && $arrSort){
            array_multisort($arrSort[$sort['field']],constant($sort['direction']),$final2);
        }

        //最终重组，限制输出20名，获取用户个人信息
        $final = array();
        foreach ($final2 as $key => $value){
            if($key<20){
                $user = WechatUser::where('userid',$value['userid'])->find();
                $value['name'] = $user['name'];
                $final[$key] = $value;
            }
        }
        $this->assign('week',$final);

        //获取季榜信息
        date_default_timezone_set("PRC");        //初始化时区
        $season = ceil((date('n'))/3);//当月是第几季度
        $start = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
        $end = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
        $map = array(
            'create_time' => array('between',[$start,$end]),    //在时间区间内
        );

        //本月浏览
        $browse_m = Browse::where($map)->select();
        $list1_m = array();
        foreach ($browse_m as $value){
            $k = $value['user_id'];
            $list1_m[$k][] = $value;
        }
        $new1_m = array();
        foreach ($list1_m as $u => $val){
            $count = count($list1_m[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new1_m[] = $cen;
        }

        //本月评论
        $comment_m = Comment::where($map)->select();
        $list2_m = array();
        foreach ($comment_m as $value){
            $k = $value['uid'];
            $list2_m[$k][] = $value;
        }
        $new2_m = array();
        foreach ($list2_m as $u => $val){
            $count = count($list2_m[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new2_m[] = $cen;
        }

        //本月点赞
        $like_m = Like::where($map)->select();
        $list3_m = array();
        foreach ($like_m as $value){
            $k = $value['uid'];
            $list3_m[$k][] = $value;
        }
        $new3_m = array();
        foreach ($list3_m as $u => $val){
            $count = count($list3_m[$u])*1;
            $cen['userid'] = $u;
            $cen['score'] = $count;
            $new3_m[] = $cen;
        }

        $center_m = array();
        foreach ($new1_m as $k1 => $v1){
            foreach ($new2_m as $k2 => $v2){
                if($v1['userid'] == $v2['userid']){
                    $cen['userid'] = $v2['userid'];
                    $cen['score'] = $v1['score'] + $v2['score'];
                    $center_m[] = $cen;
                    unset($new2_m[$k2]);
                    unset($new1_m[$k1]);
                }else{

                }
            }
        }
        $center_m = array_merge($center_m,$new2_m,$new1_m);     //unset掉重复值，重组成新数组

        //过渡数组与第三组比较同里，结果存入final2_m
        $final2_m = array();
        foreach ($center_m as $f => $val){
            foreach ($new3_m as $k3 => $v3){
                if($val['userid'] == $v3['userid']){
                    $cen['userid'] = $v3['userid'];
                    $cen['score'] = $val['score'] + $v3['score'];
                    $final2_m[] = $cen;
                    unset($new3_m[$k3]);
                    unset($center_m[$f]);
                }
            }
        }
        $final2_m = array_merge($final2_m,$new3_m,$center_m);

        //倒序，字段score排序
        $sort_m = array(
            'direction' => 'SORT_DESC',
            'field' => 'score',
        );
        $arrSort_m = array();
        foreach ($final2_m as $k => $v){
            foreach ($v as $key => $value){
                $arrSort_m[$key][$k] = $value;
            }
        }
        if($sort_m['direction'] && $arrSort_m){
            array_multisort($arrSort_m[$sort_m['field']],constant($sort_m['direction']),$final2_m);
        }
        //最终重组，限制输出20名，获取用户个人信息
        $final_m = array();
        foreach ($final2_m as $key => $value){
            if($key<20){
                $user = WechatUser::where('userid',$value['userid'])->find();
                $value['name'] = $user['name'];
                $final_m[$key] = $value;
            }
        }
        $this->assign('month',$final_m);

        return $this->fetch();
    }

}