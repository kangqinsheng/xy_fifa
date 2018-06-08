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
    public function __construct($u_id)
    {
        $this->poster = new Poster();
        $this->token = new OpenSSLEncryptDecrypt();
        $this->data_head = new DataHead();
        session_start();
        if(!isset($u_id)){
            //��֪���û�UID�����ȡ
            $this->u_id =$this->get_user_id();
        }else{
            $this->u_id = $u_id;
        }
        $_SESSION['u_id'] = $this->u_id;
        //��ֵ������Ϣ
        $this->get_user_info();
        if(!isset($_SESSION['u_id'])){
            header("Location:http://www.cqdsrb.com.cn/app");
            exit;
        }
    }
    public function get_user_id(){
        $token = $_GET['voucher'];
        $this->token->setCipherText($token);
        $deText = $this->token->decrypt();
        //��ȡ�û�id
        $url = "http://api.dsrb.cq.cn/member/my/index";
        $data = array("request"=>json_encode(array("user_token"=>$deText,"device_type"=>"Android","post_body"=>"")));
        $all = json_decode($this->poster->getHttpContent($url, "POST", $data),true);
        $u_id = $all["result"]['member_id'];
        return $u_id;
    }
    public function get_user_info(){
        //��ȡ�û���Ϣ
        $url = "http://api.dsrb.cq.cn/member/my/info";
        $data = array("member_id" =>$this->u_id);
        $res = json_decode($this->poster->getHttpContent($url, "POST", $data), true);
        if($res){
            //��ȡ�û����ϳɹ�
            $info = $res['result'];
            $this->u_nickname = iconv("UTF-8","GBK",$info['member_nick_name']);
            $this->u_phone = $info['member_phone'];
        }
    }

    /**
     * @param $rule Ͷע��ʽ 1�ȷ�,2ʤƽ��
     * @return ���ػ���״̬
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
                //�ȷ�Ͷע
                $cai_jia = intval($_POST['cai_jia']);
                $cai_yi = intval($_POST['cai_yi']);
                //ʹ�û���Ͷע
                $res = $this->change_integral(2,$bf_integral);
                if($res['status']==200) {
                    //�۳����ֲ�������
                    $add_data = array("u_id" => $this->u_id, "game_id" => $game_id, "cai_jia" => $cai_jia, "cai_yi" => $cai_yi, "cai_time" => time());
                    $res = $this->data_head->add_touzhu_bifen($add_data);
                    if($res['status']==500){
                        return array("status"=>$res['status'],'msg'=>$res['msg']);
                    }
                }else{
                    return array("status"=>$res['status'],'msg'=>$res['msg']);
                }
            }else{
                //ʤƽ��Ͷע
                $cai_spf = intval($_POST['cai_spf']);
                //ʹ�û���Ͷע
                $res = $this->change_integral(2,$spf_integral);
                if($res['status']==200) {
                    //�۳����ֲ�������
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
     * @param $type ����1+ 2-
     * @param $integral ����
     * @return ״̬
     */
    public function change_integral($type,$integral){
        //��ӻ���
        $data = array("type"=>$type,"integral"=>$integral,"id"=>$this->u_id);
        $url = "http://api.dsrb.cq.cn/member/my/addfen";
        $res = json_decode($this->poster->getHttpContent($url, "POST", $data),true);
        if($res['status']!=200){
            return array("status"=>500,"msg"=>"fail change integral");
        }else{
            return array("status"=>200,"msg"=>"success change integral");
        }
    }
}