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
     * 获取比赛信息
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
     * 比分插入记录
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
     * 胜平负插入记录
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

    /**
     * 转GBK
     * @param $data
     * @return string
     */
    public function change_to_gbk($data){
        $encode = mb_detect_encoding($data,array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $recode = iconv($encode,"GBK",$data);
        return $recode;
    }

    /**
     * 转UTF-8
     * @param $data
     * @return string
     */
    public function change_to_utf($data){
        $encode = mb_detect_encoding($data,array("ASCII","UTF-8","GB2312","GBK","BIG5"));
        $recode = iconv($encode,"UTF-8",$data);
        return $recode;
    }

    /**
     * 检测数据不能有空
     * @param array $datas
     * @return array
     */
    public function is_fail_data(array $datas){
        foreach($datas as $key=>$val){
            if(empty($val)){
                return array("status"=>500,'msg'=>$key." is empty");
            }
        }
        return array("status"=>200,'msg'=>"success data");
    }

    /**
     * 查看当场比赛比分奖池
     * @param $game_id
     * @return int
     */
    public function count_integral($game_id){
        //查看比赛结果
        $res =C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
        //比分购买总人数
        $game_all = C::t("#xy_fifa#fifa_bifen")->game_all($game_id);
        $cot_int = intval($res['basic_integral'])+$game_all*intval($res['bf_integral']);
        return $cot_int;
    }
}