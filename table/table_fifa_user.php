<?php
/**
 * Created by PhpStorm.
 * User.class: ASUS
 * Date: 2018/6/8
 * Time: 20:37
 */

class table_fifa_user extends discuz_table
{
    public function __construct(){
        $this->_table = 'fifa_user';
        $this->_pk = 'id';
        parent::__construct();
    }
    //����һ������
    public function add_data($data){
        $res = DB::insert($this->_table,$data,true);
        return $res;
    }
    //ͨ��u_id��ȡ����
    public function get_by_uid($id){
        $data = DB::fetch_first("select * from %t where `u_id`=%i",array($this->_table,$id));
        return $data;
    }
    //��ȡ��������
    public function get_all_list(){
        $data = DB::fetch_all("select * from %t",array($this->_table));
        return $data;
    }
	//�����û���
	public function update_data($data,$condition){
		$res = DB::update($this->_table,$data,$condition);
	}
}