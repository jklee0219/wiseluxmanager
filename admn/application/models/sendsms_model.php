<?php
class Sendsms_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	function getList()
	{
	    $this->db->select('*', FALSE);
	    $this->db->from('tb_sms');
	    $this->db->order_by('seq', 'ASC');
	    $result = $this->db->get()->result();
	    
	    return $result;
	}

	function insert($seq, $txt)
	{
		$this->db->query(" insert into tb_sms (`seq`,`txt`) values ('".$seq."', '".$txt."') ON DUPLICATE KEY UPDATE txt='".$txt."' ");
	}
}
