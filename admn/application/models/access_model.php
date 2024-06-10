<?php
class Access_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function gc_ip()
	{
		$this->db->query(" delete from tb_access where updatedate <= date_add(now(), interval -30 second) ");
	}

	function set_ip($id)
	{
		if($id){
			$ip = $_SERVER['REMOTE_ADDR'];
			$this->db->query(" insert into tb_access (`ip`, `id`) values ('".$ip."', '".$id."') ON DUPLICATE KEY UPDATE id='".$id."', updatedate = now() ");
		}
	}
	
	function chkblockip(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$result = $this->db->query(" select count(*) as cnt from tb_blockip where `ip` = '".$ip."' ")->row();
		return $result->cnt;
	}
	
	function get_access_list(){
		return $this->db->query(" select * from tb_access order by logindate ")->result();
	}
	
	function get_block_list(){
		return $this->db->query(" select * from tb_blockip order by ip ")->result();
	}
	
	function setblockip($ip){
		$this->db->query(" insert into tb_blockip (ip) values ('".$ip."') ");
	}
	
	function removeblockip($ip){
		$this->db->query(" delete from tb_blockip where ip = '".$ip."' ");
	}
	
	function removeaccessip($ip){
		$this->db->query(" delete from tb_access where ip = '".$ip."' ");
	}
	
	function chpw($id, $pw){
		$this->db->query(" update tb_member set `password` = password('".$pw."') where id = '".$id."' ");
	}
}
