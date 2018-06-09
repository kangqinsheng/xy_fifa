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
    //插入一条数据
    public function add_data($data){
        $res = DB::insert($this->_table,$data,true);
        return $res;
    }
    //通过id获取数据
    public function get_by_id($id){
        $data = DB::fetch_first("select * from %t where `id`=%i",array($this->_table,$id));
        return $data;
    }
    //获取所有数据
    public function get_all_list(){
        $data = DB::fetch_all("select * from %t",array($this->_table));
        return $data;
    }
    //查看某比赛中奖人员
    public function is_luck($game_id,$type){
        $data = DB::fetch_all("select * from %t where `sai_id`=%i and `cai_spf`=%i",array($this->_table,$game_id,$type));
        return$data;
    }
}