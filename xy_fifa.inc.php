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
    $user = new User(43985);
    //如果是ajax请求
    $action = $_POST['ajax'];
    //执行请求
    $res = $user->$action($action_id);
    echo json_encode($res);
}
$page_to = $_GET['page_to']?$_GET['page_to']:"fifa_home";
if($page_to=="fifa_home"){
    //获取当前用户对象
    $user = new User(43985);
    if(isset($user->u_id)){
        //获取赛事列表
        $lists = C::t("#xy_fifa#fifa_game")->get_all_list();
    }
}
$u_id = intval($_GET['u_id']);
if($page_to=="fifa_home") {
    $game_id = intval($_GET['game_id']);
    $user = new User($u_id);
    //获取对应比赛详情页内容
    $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    //获取比赛奖池
    $dataholder = new DataHead();
    $count_int = $dataholder->count_integral($game_id);
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