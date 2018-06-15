<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/9
 * Time: 13:41
 */
require_once "class/DataHead.php";
$dataholder = new DataHead();
$action = $_GET['action']?$_GET['action']:'game';
if($action=="game"){
    //赛程展示，管理页面
    $lists = C::t("#xy_fifa#fifa_game")->get_all_list();
    include template("fifa_admin","","source/plugin/xy_fifa/template");
}elseif($action =="add_game"){
    $post = $_POST;
    $jia_name = $dataholder->change_to_gbk($post['jia_name']);
    $jia_img = $post['team_img'][0];
    $yi_name = $dataholder->change_to_gbk($post['yi_name']);
    $yi_img = $post['team_img'][1];
    $start_time = strtotime($post['start_time']);
    $bf_integral = intval($post['bf_integral']);
    $basic_integral = intval($post['basic_integral']);
    $spf_integral = intval($post['spf_integral']);
    //插入的数据
    $data = array(
        'jia_name'=>$jia_name,
        "jia_img"=>$jia_img,
        "yi_name"=>$yi_name,
        'yi_img'=>$yi_img,
        'start_time'=>$start_time,
        'bf_integral'=>$bf_integral,
        'basic_integral'=>$basic_integral,
        'spf_integral'=>$spf_integral
    );
    $res = $dataholder->is_fail_data($data);
    if($res['status']==200){
        //数据通过检测
        $res = C::t("#xy_fifa#fifa_game")->add_data($data);
        if($res>0){
            //添加成功
            header("location:plugin.php?id=xy_fifa:admin");
        }else{
            //添加失败
            return array("status"=>500,"msg"=>"insert data fail");
        }
    }
}elseif($action=="luck_list"){
    //查看中奖列表
    $page = isset($_GET['page'])? intval($_GET['page']):1;
    $pagesize = 15;
    $start = ($page-1)*$pagesize;
    $luck_list = C::t('#xy_fifa#fifa_lucker')->get_all_list($start,$pagesize);
    $luck_lists = array();
    foreach ($luck_list as $key=>$val){
        $user_arr = C::t('#xy_fifa#fifa_user')->get_by_uid($val['u_id']);
        $sai_arr = C::t('#xy_fifa#fifa_game')->get_by_id($val['sai_id']);
        $data = array("user"=>$user_arr,"game"=>$sai_arr,"info"=>$val);
        $luck_lists[]=$data;
    }
    $count = C::t('#xy_fifa#fifa_lucker')->get_cont();
    $showNextPage = 1;
    if(($start + $pagesize) >= $count){
        $showNextPage = 0;
    }
    $allPageNum = ceil($count/$pagesize);
    $prePage = $page - 1;
    $nextPage = $page + 1;
    $prePageUrl = "plugin.php?id=xy_fifa:admin&action=luck_list&page={$prePage}";
    $nextPageUrl = "plugin.php?id=xy_fifa:admin&action=luck_list&page={$nextPage}";
    $firstPageUrl = "plugin.php?id=xy_fifa:admin&action=luck_list&page=1";
    $lastPageUrl = "plugin.php?id=xy_fifa:admin&action=luck_list&page={$allPageNum}";
    include template("fifa_lucker","","source/plugin/xy_fifa/template");
}