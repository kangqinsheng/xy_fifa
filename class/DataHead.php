<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 2018/6/8
 * Time: 22:15
 */

class DataHead
{
    /**
     * ��ȡ��Ϸ��Ϣ
     */
    public function get_one_game($game_id){
        $data = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
        if($data){
            return $data;
        }else{
            return array("status"=>500,"msg"=>"no game dat");
        }
    }

    /**
     * �ȷֲ����¼
     * @param $data
     * @return array
     */
    public function add_touzhu_bifen($data){
        $res = C::t("#xy_fifa#fifa_bifen")->add_data($data);
        if($res>0){
            return $res;
        }else{
            return array("status"=>500,"msg"=>"fail add data");
        }
    }

    /**
     * ʤƽ�������¼
     * @param $data
     * @return array
     */
    public function add_touzhu_spf($data){
        $res = C::t("#xy_fifa#fifa_spf")->add_data($data);
        if($res>0){
            return $res;
        }else{
            return array("status"=>500,"msg"=>"fail add data");
        }
    }
}