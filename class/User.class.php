<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 21:37
 */
require_once "Poster.php";
require_once "OpenSSLEncryptDecrypt.php";
require_once "DataHead.php";
class User
{
    public $key = "7bef64952807359ccc94ac5d1b864a4e";
    public $poster;
    public $token;
    public $u_id;
    public $u_nickname;
    public $u_phone;
    public $data_head;
    public $max_bifen_times = 5;
    public $max_spf_times = 5;
    public function __construct($u_id)
    {
        $this->poster = new Poster();
        $this->token = new OpenSSLEncryptDecrypt();
        $this->data_head = new DataHead();
        session_start();
        if(!isset($u_id)){
            //不知道用户UID，需获取
            $this->u_id =$this->get_user_id();
        }else{
            $this->u_id = $u_id;
        }
        $_SESSION['u_id'] = $this->u_id;
        //赋值基本信息
        $res = $this->get_user_info();
        if(!isset($_SESSION['u_id'])||!$res){
            header("Location:http://www.cqdsrb.com.cn/app");
            exit;
        }
    }
    public function get_user_id(){
        $token = $_GET['voucher'];
        $this->token->setCipherText($token);
        $deText = $this->token->decrypt();
        //获取用户id
        $url = "http://api.dsrb.cq.cn/member/my/index";
        $data = array("request"=>json_encode(array("user_token"=>$deText,"device_type"=>"Android","post_body"=>"")));
        $all = json_decode($this->poster->getHttpContent($url, "POST", $data),true);
        $u_id = $all["result"]['member_id'];
        return $u_id;
    }
    public function get_user_info(){
        //获取用户信息
        $url = "http://api.dsrb.cq.cn/member/my/info";
        $data = array("member_id" =>$this->u_id);
        $res = json_decode($this->poster->getHttpContent($url, "POST", $data), true);
        if($res){
            //获取用户资料成功
            $info = $res['result'];
            $this->u_nickname = iconv("UTF-8","GBK",$info['member_nick_name']);
            $this->u_phone = $info['member_phone'];
            //检测数据库
            $res = C::t("#xy_fifa#fifa_user")->get_by_uid($this->u_id);
            if(!isset($res['id'])){
                //插入数据
                $data = array("u_id"=>$this->u_id,"u_nickname"=>$this->u_nickname,"u_phone"=>$this->u_phone);
                $res = C::t("#xy_fifa#fifa_user")->add_data($data);
                if($res){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }
    }

    /**
     * 暂时没使用，分开两种方法写
     * @param $rule 投注方式 1比分,2胜平负
     * @return 返回积分状态
     */
    public function cathectic($rule){
        if(isset($rule)){
            $game_id = intval($_POST['game_id']);
            $game = $this->data_head->get_one_game($game_id);
            if(isset($game)){
                $bf_integral = intval($game['bf_integral']);
                $spf_integral = intval($game['spf_integral']);
            }else{
                return array("status"=>$game['status'],'msg'=>$game['msg']);
            }
            if($rule==1){
                //比分投注
                $cai_jia = intval($_POST['cai_jia']);
                $cai_yi = intval($_POST['cai_yi']);
                //使用积分投注
                $res = $this->change_integral(2,$bf_integral);
                if($res['status']==200) {
                    //扣除积分插入数据
                    $add_data = array("u_id" => $this->u_id, "game_id" => $game_id, "cai_jia" => $cai_jia, "cai_yi" => $cai_yi, "cai_time" => time());
                    $res = $this->data_head->add_touzhu_bifen($add_data);
                    if($res['status']==500){
                        return array("status"=>$res['status'],'msg'=>$res['msg']);
                    }
                }else{
                    return array("status"=>$res['status'],'msg'=>$res['msg']);
                }
            }else{
                //胜平负投注
                $cai_spf = intval($_POST['cai_spf']);
                //使用积分投注
                $res = $this->change_integral(2,$spf_integral);
                if($res['status']==200) {
                    //扣除积分插入数据
                    $add_data = array("u_id" => $this->u_id, "game_id" => $game_id, "cai_spf" => $cai_spf,"cai_time" => time());
                    $res = $this->data_head->add_touzhu_spf($add_data);
                    if($res['status']==500){
                        return array("status"=>$res['status'],'msg'=>$res['msg']);
                    }
                }else{
                    return array("status"=>$res['status'],'msg'=>$res['msg']);
                }
            }
        }else{
            return array("status"=>400,'msg'=>"no rule");
        }
    }

    /**
     * @param $type 增减1+ 2-
     * @param $integral 积分
     * @return 状态
     */
    public function change_integral($type,$integral){
        //添加积分
        $data = array("type"=>$type,"integral"=>$integral,"id"=>$this->u_id);
        $url = "http://api.dsrb.cq.cn/member/my/addfen";
        $res = json_decode($this->poster->getHttpContent($url, "POST", $data),true);
        if($res['status']!=200){
            return array("status"=>500,"msg"=>"fail change integral");
        }else{
            return array("status"=>200,"msg"=>"success change integral");
        }
    }

    /**
     * @param $game_id
     * @return array
     */
    public function tou_bifen($game_id){
        $cai_jia = intval($_POST["cai_jia"]);
        $cai_yi = intval($_POST['cai_yi']);
        //查看比赛可投状态
        $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
        $start_time = intval($gameinfo['start_time']);
        $intergal = intval($gameinfo['bf_integral']);
        if(time()<$start_time){
            //查看个人投注状态
            $cont = C::t("#xy_fifa#fifa_bifen")->game_tou_times($game_id,$this->u_id);
            if($cont<$this->max_bifen_times){
                //可投注，加入投注记录
                $data = array("u_id"=>$this->u_id,"game_id"=>$game_id,"cai_jia"=>$cai_jia,"cai_yi"=>$cai_yi,"cai_time"=>time());
                $res = C::t("#xy_fifa#fifa_bifen")->add_data($data);
                //扣除积分
                $res1 = $this->change_integral(2,$intergal);
                if($res&&$res1['status']==200){
                    return array("status"=>200,"msg"=>"success");
                }else{
                    return array("status"=>500,"msg"=>"database insert fail".$res1['msg']);
                }
            }else{
                return array("status"=>500,"msg"=>"times is beyond");
            }
        }else{
            return array("status"=>500,"msg"=>"time is last");
        }
    }


    /**
     * @param $game_id
     * @return array
     */
    public function tou_spf($game_id){
        $cai_spf = intval($_POST["cai_spf"]);
        //查看比赛可投状态
        $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id($game_id);
        $start_time = intval($gameinfo['start_time']);
        $intergal = intval($gameinfo['spf_integral']);
        if(time()<$start_time){
            //查看个人投注状态
            $cont = C::t("#xy_fifa#fifa_spf")->game_tou_times($game_id,$this->u_id);
            if($cont<$this->max_spf_times){
                //可投注，加入投注记录
                $data = array("u_id"=>$this->u_id,"sai_id"=>$game_id,"cai_spf"=>$cai_spf,"cai_time"=>time());
                $res = C::t("#xy_fifa#fifa_spf")->add_data($data);
                //扣除积分
                $res1 = $this->change_integral(2,$intergal);
                if($res&&$res1['status']==200){
                    return array("status"=>200,"msg"=>"success");
                }else{
                    return array("status"=>500,"msg"=>"database insert fail".$res1['msg']);
                }
            }else{
                return array("status"=>500,"msg"=>"times is beyond");
            }
        }else{
            return array("status"=>500,"msg"=>"time is last");
        }
    }

    /**
     * @param $lucker_id
     * @return array
     */
    public function lucker_dh($lucker_id){
        //查看对应用户记录
        $luck = C::t("#xy_fifa#fifa_lucker")->get_by_id($lucker_id);
        $u_id = intval($luck['u_id']);
        $luck_integral = intval($luck['luck_integral']);
        $this->u_id = $u_id;
        if($luck['dh_status']==0) {
            $res = $this->change_integral(1, $luck_integral);
            if($res['status']==200){
                $res = C::t("#xy_fifa#fifa_lucker")->update_data(array("dh_status"=>1),array('id'=>$lucker_id));
                if($res){
                    return array("status"=>200,"msg"=>"success");
                }else{
                    return array("status"=>500,"msg"=>"update dh_status fail");
                }
            }else{
                return array("status"=>500,"msg"=>$res['msg']);
            }
        }else{
            return array("status"=>500,"msg"=>"no tow time dh");
        }
    }

    public function my_tou_lists(){
        //获取我的比分投注
        $my_bifen=C::t("#xy_fifa#fifa_bifen")->my_tou_all($this->u_id);
        foreach($my_bifen as $key=>$val){
            $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id(intval($val['game_id']));
            $my_bifen[$key]['game_info'] = $gameinfo;
        }
        //获取我的胜平负投注
        $my_spf=C::t("#xy_fifa#fifa_spf")->my_tou_all($this->u_id);
        foreach($my_spf as $key=>$val){
            $gameinfo = C::t("#xy_fifa#fifa_game")->get_by_id(intval($val['sai_id']));
            $my_spf[$key]['game_info'] = $gameinfo;
        }
        $res = array("bifen"=>$my_bifen,"spf"=>$my_spf);
        return array("status"=>200,"res"=>$res);
    }
}