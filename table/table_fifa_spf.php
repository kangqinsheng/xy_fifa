<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 20:38
 */

class table_fifa_spf extends discuz_table
{
    public function __construct(){
        $this->_table = 'fifa_spf';
        $this->_pk = 'id';
        parent::__construct();
    }
    //����һ������
    public function add_data($data){
        $res = DB::insert($this->_table,$data,true);
        return $res;
    }
    //ͨ��id��ȡ����
    public function get_by_id($id){
        $data = DB::fetch_first("select * from %t where `id`=%i",array($this->_table,$id));
        return $data;
    }
    //��ȡ��������
    public function get_all_list(){
        $data = DB::fetch_all("select * from %t",array($this->_table));
        return $data;
    }
    //��ȡĳ������Ͷע��¼
    public function get_some_limit($game_id,$limit){
        $data = DB::fetch_all("select * from %t where `sai_id`=%i ORDER by `cai_time` desc limit 0,$limit ",array($this->_table,$game_id));
        return $data;
    }
    //�鿴ĳ�����н���Ա
    public function is_luck($game_id,$type){
        $data = DB::fetch_all("select * from %t where `sai_id`=%i and `cai_spf`=%i",array($this->_table,$game_id,$type));
        return$data;
    }
    //�鿴ĳ��ĳ��������Ͷע��
    public function game_tou_times($game_id,$u_id){
        $res = DB::result_first("select count(*) from %t where `sai_id`=%i and `u_id`=%i",array($this->_table,$game_id,$u_id));
        return $res;
    }
    //�鿴ĳ����Ͷע��
    public function my_tou_all($u_id){
        $data = DB::fetch_all("select * from %t where `u_id`=%i",array($this->_table,$u_id));
        return $data;
    }
}