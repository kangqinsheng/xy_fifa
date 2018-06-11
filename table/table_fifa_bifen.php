<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 20:38
 */

class table_fifa_bifen extends discuz_table
{
    public function __construct(){
        $this->_table = 'fifa_bifen';
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
        $data = DB::fetch_all("select * from %t where `game_id`=%i ORDER by `cai_time` desc limit 0,$limit ",array($this->_table,$game_id));
        return $data;
    }
    //��ȡĳ�������ȷֲ��еļ�¼
    public function is_luck($game_id,$jia_fen,$yi_fen){
        $data = DB::fetch_all("select * from %t where `game_id`=%i and `cai_jia`=%i and `cai_yi`=%i",array($this->_table,$game_id,$jia_fen,$yi_fen));
        return $data;
    }
    //��ȡĳ������Ͷע����
    public function game_all($game_id){
        $res = DB::result_first("select count(*) from %t where `game_id`=%i",array($this->_table,$game_id));
        return $res;
    }
    //�鿴ĳ��ĳ��������Ͷע��
    public function game_tou_times($game_id,$u_id){
        $res = DB::result_first("select count(*) from %t where `game_id`=%i and `u_id`=%i",array($this->_table,$game_id));
        return $res;
    }
}