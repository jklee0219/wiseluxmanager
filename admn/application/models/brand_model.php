<?php
class Brand_model extends CI_Model
{
	public function __construct()
  	{
		parent::__construct();
	}
  
	function getList()
	{
		$this->db->select('*');
  		$this->db->from('tb_brand');
  		$this->db->order_by('name', 'ASC');
    	$result = $this->db->get()->result();
  
  		return $result;
  	}
}
