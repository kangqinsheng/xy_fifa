<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 20:37
 */

class table_fifa_game extends discuz_table
{
    public function __construct(){
        $this->_table = 'fifa_game';
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
    //删除数据
    public function delete_data($game_id)
    {
        $res = DB::delete($this->_table,array("id"=>$game_id));
        return $res;
    }
    //更新数据

    /**
     * @param $data 修改的数据
     * @param $condition 修改条件
     * @return bool
     */
    public function update_data($data,$condition)
    {
        $res = DB::update($this->_table,$data,$condition);
        return $res;
    }
}