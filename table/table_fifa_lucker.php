<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 20:38
 */

class table_fifa_lucker extends discuz_table
{
    public function __construct(){
        $this->_table = 'fifa_lucker';
        $this->_pk = 'id';
        parent::__construct();
    }
    //插入数据
    public function add_data($data){
        $res = DB::insert($this->_table,$data,true);
        return $res;
    }
    //通过id获取数据
    public function get_by_id($id){
        $data = DB::fetch_first("select * from %t where `id`=%i",array($this->_table,$id));
        return $data;
    }
    //通过u_id获取数据
    public function get_by_uid($uid){
        $data = DB::fetch_all("select * from %t where `u_id`=%i",array($this->_table,$uid));
        return $data;
    }
    //获取总条数
    public function get_cont(){
        $res = DB::result_first("select count(*) from %t",array($this->_table));
        return $res;
    }
    //获取分页数据
    public function get_all_list($start,$size){
        $data = DB::fetch_all("select * from %t order by `sai_id` desc limit $start,$size",array($this->_table));
        return $data;
    }
    //获取某人所有数据
    public function get_some_all($u_id){
        $data = DB::fetch_all("select * from %t where `u_id`=%i order by `sai_id`",array($this->_table,$u_id));
        return $data;
    }
    //更新兑换状态
    public function update_data($data,$condition)
    {
        $res = DB::update($this->_table,$data,$condition);
        return $res;
    }
}