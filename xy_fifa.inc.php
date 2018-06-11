<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/11
 * Time: 10:00
 */
require_once "class/User.class.php";
require_once "class/DataHead.php";
if($_POST['ajax']){
    $u_id = intval($_POST['u_id']);
    $action_id = intval($_POST['action_id']);
    $user = new User($u_id);
    //如果是ajax请求
    $action = $_POST['ajax'];
    //执行请求
    $res = $user->$action($action_id);
    echo json_encode($res);
}
$page_to = $_GET['page_to']?$_GET['page_to']:"fifa_home";//默认显示主页
if($page_to=="fifa_home"){
    //获取当前用户对象
    $user = new User(43985);
    if(isset($user->u_id)){
        //获取赛事列表
        $lists = C::t("#xy_fifa#fifa_game")->get_all_list();
    }
    $ke_tou = array();//可投注数据
    $no_tou = array();//不可投注数据
    foreach ($lists as $key=>$val){
        if($val['start_time']>time){
            $ke_tou[] = $val;
        }else{
            $no_tou[] = $val;
        }
    }
}
$u_id = intval($_GET['u_id']);
if($page_to=="fifa_info") {
    $game_id = intval($_GET['game_id']);
    $user = new User($u_id);
    //获取对应比赛详情页内容
    $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    //获取比赛奖池
    $dataholder = new DataHead();
    $count_int = $dataholder->count_integral($game_id);
    //获取访问用户已投注数
    $max_bifen_times = $user->max_bifen_times;
    $max_spf_times = $user->max_spf_times;
    $my_bifen_times = C::t("#xy_fifa#fifa_bifen")->game_tou_times($game_id,$user->u_id);
    $my_spf_times = C::t("#xy_fifa#fifa_spf")->game_tou_times($game_id,$user->u_id);
    //投注列表显示最新记录
    $limit = 50;
    //获取比赛比分投注情况
    $bifen_lists = C::t("#xy_fifa#fifa_bifen")->get_some_limit($game_id,$limit);

    //获取对应用户昵称
    foreach ($bifen_lists as $key=>$val){
        $u_nickname = C::t("#xy_fifa#fifa_user")->get_by_uid($val['u_id']);
        $bifen_lists[$key]['u_nickname'] = $u_nickname;
    }
    //获取比赛胜平负投注情况

    $spf_lists = C::t("#xy_fifa#fifa_spf")->get_some_limit($game_id,$limit);
    //获取对应用户昵称
    foreach ($spf_lists as $key=>$val){
        $u_nickname = C::t("#xy_fifa#fifa_user")->get_by_uid($val['u_id']);
        $spf_lists[$key]['u_nickname'] = $u_nickname;
    }
}
if($page_to=="my_luck") {
    $user = new User($u_id);
    $my_luck = C::t("#xy_fifa#fifa_lucker")->get_some_all($user->u_id);
    foreach($my_luck as $key=>$val){
        $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id(intval($val['sai_id']));
        $my_luck[$key]['game_info'] = $gameinfo;
    }
}
//跳转对应页面
include template($page_to,"","source/plugin/xy_fifa/template");