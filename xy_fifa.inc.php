<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/11
 * Time: 10:00
 */
require_once "class/User.class.php";
require_once "class/DataHead.php";
session_start();
if($_POST['ajax']){
    $u_id = intval($_POST['u_id']);
    $action_id = intval($_POST['action_id']);
    $user = new User($u_id);
    //�����ajax����
    $action = $_POST['ajax'];
    //ִ������
    $res = $user->$action($action_id);
    echo json_encode($res);
    die();
}
$page_to = $_GET['page_to']?$_GET['page_to']:"fifa_home";//Ĭ����ʾ��ҳ
if($page_to=="fifa_home"){
    //$u_id = $_SESSION['u_id'];
    //��ȡ��ǰ�û�����
    $user = new User();
    if(isset($user->u_id)){
        //��ȡ�����б�
        $lists = C::t("#xy_fifa#fifa_game")->get_all_list();
    }
    $ke_tou = array();//��Ͷע����
    $no_tou = array();//����Ͷע����
    foreach ($lists as $key=>$val){
        if($val['start_time']>time()){
            $ke_tou[] = $val;
        }else{
            $no_tou[] = $val;
        }
    }
}
$u_id = intval($_GET['u_id'])?intval($_GET['u_id']):$_SESSION['u_id'];
if($page_to=="fifa_info"){
    $game_id = intval($_GET['game_id']);
    //ҳ��ˢ��Ĭ��ѡ��
    $label = $_GET['label']?$_GET['label']:"spf";
    $user = new User($u_id);
    //��ȡ��Ӧ��������ҳ����
    $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    //��ȡ��������
    $dataholder = new DataHead();
    $count_int = $dataholder->count_integral($game_id);
    //�������������
    $count_int = $count_int+8000;
    //��ȡ�����û���Ͷע��
    $max_bifen_times = $user->max_bifen_times;
    $max_spf_times = $user->max_spf_times;
    $my_bifen_times = C::t("#xy_fifa#fifa_bifen")->game_tou_times($game_id,$user->u_id);
    $my_spf_times = C::t("#xy_fifa#fifa_spf")->game_tou_times($game_id,$user->u_id);
    //Ͷע�б���ʾ���¼�¼
    $limit = 50;
    //��ȡ�����ȷ�Ͷע���
    $bifen_lists = C::t("#xy_fifa#fifa_bifen")->get_some_limit($game_id,$limit);

    //��ȡ��Ӧ�û��ǳ�
    foreach ($bifen_lists as $key=>$val){
        $user_data = C::t("#xy_fifa#fifa_user")->get_by_uid($val['u_id']);
        $bifen_lists[$key]['u_nickname'] = $user_data['u_nickname'];
    }
    //��ȡ����ʤƽ��Ͷע���

    $spf_lists = C::t("#xy_fifa#fifa_spf")->get_some_limit($game_id,$limit);
    //��ȡ��Ӧ�û��ǳ�
    foreach ($spf_lists as $key=>$val){
        $user_data = C::t("#xy_fifa#fifa_user")->get_by_uid($val['u_id']);
        $spf_lists[$key]['u_nickname'] = $user_data['u_nickname'];
    }
}
if($page_to=="my_luck"){
    $user = new User($u_id);
    $my_luck = C::t("#xy_fifa#fifa_lucker")->get_some_all($user->u_id);
    foreach($my_luck as $key=>$val){
        $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id(intval($val['sai_id']));
        $my_luck[$key]['game_info'] = $gameinfo;
    }
}
if($page_to=="my_tou"){
    $user = new User($u_id);
    $res = $user->my_tou_lists();
    if($res['status']==200){
        $my_lists = $res['res'];
    }
}
//��ת��Ӧҳ��
include template($page_to,"","source/plugin/xy_fifa/template");