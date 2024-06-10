<?php
class Manager_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function getList()
  {
    $this->db->select('*');
  	$this->db->from('tb_manager');
    $this->db->order_by('seq', 'ASC');
    $result = $this->db->get()->result();

    return $result;
  }
}
