<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/9
 * Time: 16:13
 */
require_once "class/DataHead.php";
$dataholder = new DataHead();
$action = $_POST['action'];
if($action == "delete_game"){
    $game_id = intval($_POST['game_id']);
    $res =C::t("#xy_fifa#fifa_game")->delete_data($game_id);
    if($res){
        echo json_encode(array("status"=>200,"msg"=>"success"));
    }else{
        echo json_encode(array("status"=>500,"msg"=>"no data delete"));
    }
}elseif($action == "update_end"){
    $game_id = intval($_POST['game_id']);
    $jia_fen = intval($_POST['jia_fen']);
    $yi_fen = intval($_POST['yi_fen']);
    //查看比赛结果
    $res =C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    if(time()>intval($res["start_time"])){
        $data = array("jia_fen"=>$jia_fen,"yi_fen"=>$yi_fen,"is_end"=>1);
        $res =C::t("#xy_fifa#fifa_game")->update_data($data,array("id"=>$game_id));
        if($res){
            echo json_encode(array("status"=>200,"msg"=>"success"));
        }else{
            echo json_encode(array("status"=>500,"msg"=>"no data update"));
        }
    }else{
        echo json_encode(array("status"=>500,"msg"=>"not end time"));
    }
}elseif($action=="bj"){
    $game_id = intval($_POST['game_id']);
    //查看比赛结果
    $res =C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
    if($res){
        $jia_fen = intval($res['jia_fen']);
        $yi_fen = intval($res['yi_fen']);
        $luckers = array();
        //查找比分中奖记录
        $bf_lucks = C::t("#xy_fifa#fifa_bifen")->is_luck($game_id,$jia_fen,$yi_fen);
        if(count($bf_lucks)>0){
            //计算每人赢得积分
            $cont_int = $dataholder->count_integral($game_id);
            $one_int = intval($cont_int/count($bf_lucks));
            foreach($bf_lucks as $key=>$val){
                $arr = array("u_id"=>$val['u_id'],"sai_id"=>$val['game_id'],"luck_integral"=>$one_int);
                $luckers[] = $arr;
            }
        }
        //查询胜平负中奖记录
        $type=0;
        if($jia_fen>$yi_fen){
            $type=1;
        }elseif($jia_fen==$yi_fen){
            $type=2;
        }else{
            $type=3;
        }
        $spf_lucks = C::t("#xy_fifa#fifa_spf")->is_luck($game_id,$type);
        //每人获得积分
        //修改，获得积分是投入的两倍
        $one_int1 = intval($res['spf_integral']);
        $one_int1 = $one_int1*2;
        foreach($spf_lucks as $key=>$val){
            $arr = array("u_id"=>$val['u_id'],"sai_id"=>$val['sai_id'],"luck_integral"=>$one_int1);
            $luckers[] = $arr;
        }
        if(count($luckers)>0){
            //将所有数据插入数据库
            $num = 0;
            foreach ($luckers as $val){
                $res = C::t("#xy_fifa#fifa_lucker")->add_data($val);
                if($res){
                    $num++;
                }
            }
            if($num>0){
                //修改颁奖状态
                $res = C::t("#xy_fifa#fifa_game")->update_data(array("fj_status"=>1),array('id'=>$game_id));
                if($res){
                    echo json_encode(array("status"=>200,"msg"=>"{$num} person luck"));
                }else{
                    echo json_encode(array("status"=>500,"msg"=>"update fj_status fail"));
                }
            }else{
                echo json_encode(array("status"=>500,"msg"=>"lucker insert fail"));
            }
        }else{
            //修改颁奖状态
            $res = C::t("#xy_fifa#fifa_game")->update_data(array("fj_status"=>1),array('id'=>$game_id));
            if($res) {
                echo json_encode(array("status" => 200, "msg" => "no person luck"));
            }else{
                echo json_encode(array("status"=>500,"msg"=>"update fj_status fail"));
            }
        }
    }else{
        echo json_encode(array("status"=>500,"msg"=>"not found game"));
    }
}