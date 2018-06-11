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
    //�����ajax����
    $action = $_POST['ajax'];
    //ִ������
    $res = $user->$action($action_id);
    echo json_encode($res);
}
$page_to = $_GET['page_to']?$_GET['page_to']:"fifa_home";
if($page_to=="fifa_home"){
    //��ȡ��ǰ�û�����
    $user = new User(43985);
    if(isset($user->u_id)){
        //��ȡ�����б�
        $lists = C::t("#xy_fifa#fifa_game")->get_all_list();
    }
}
$u_id = intval($_GET['u_id']);
if($page_to=="fifa_home") {
    $game_id = intval($_GET['game_id']);
    $user = new User($u_id);
    //��ȡ��Ӧ��������ҳ����
    $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    //��ȡ��������
    $dataholder = new DataHead();
    $count_int = $dataholder->count_integral($game_id);
    $limit = 50;
    //��ȡ�����ȷ�Ͷע���
    $bifen_lists = C::t("#xy_fifa#fifa_bifen")->get_some_limit($game_id,$limit);

    //��ȡ��Ӧ�û��ǳ�
    foreach ($bifen_lists as $key=>$val){
        $u_nickname = C::t("#xy_fifa#fifa_user")->get_by_uid($val['u_id']);
        $bifen_lists[$key]['u_nickname'] = $u_nickname;
    }
    //��ȡ����ʤƽ��Ͷע���

    $spf_lists = C::t("#xy_fifa#fifa_spf")->get_some_limit($game_id,$limit);
    //��ȡ��Ӧ�û��ǳ�
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
//��ת��Ӧҳ��
include template($page_to,"","source/plugin/xy_fifa/template");