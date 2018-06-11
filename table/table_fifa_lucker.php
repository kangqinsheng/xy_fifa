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
    //��������
    public function add_data($data){
        $res = DB::insert($this->_table,$data,true);
        return $res;
    }
    //ͨ��id��ȡ����
    public function get_by_id($id){
        $data = DB::fetch_first("select * from %t where `id`=%i",array($this->_table,$id));
        return $data;
    }
    //ͨ��u_id��ȡ����
    public function get_by_uid($uid){
        $data = DB::fetch_all("select * from %t where `u_id`=%i",array($this->_table,$uid));
        return $data;
    }
    //��ȡ������
    public function get_cont(){
        $res = DB::result_first("select count(*) from %t",array($this->_table));
        return $res;
    }
    //��ȡ��ҳ����
    public function get_all_list($start,$size){
        $data = DB::fetch_all("select * from %t order by `sai_id` desc limit $start,$size",array($this->_table));
        return $data;
    }
    //��ȡĳ����������
    public function get_some_all($u_id){
        $data = DB::fetch_all("select * from %t where `u_id`=%i order by `sai_id`",array($this->_table,$u_id));
        return $data;
    }
    //���¶һ�״̬
    public function update_data($data,$condition)
    {
        $res = DB::update($this->_table,$data,$condition);
        return $res;
    }
}