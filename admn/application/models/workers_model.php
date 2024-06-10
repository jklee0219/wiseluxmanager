<?php
class Workers_model extends CI_Model
{
   public function __construct()
   {
      parent::__construct();
   }

   function getList($condition,$scale="N",$first="0")
   {
      $this->db->select('*');
      $this->db->from('tb_member');
      if(isset($condition['sid'])){
         $this->db->where('id', $condition['sid']); 
      }
      if($scale != "N") $this->db->limit($scale, $first);
      $this->db->order_by('ordernum', 'desc');
      $result = $this->db->get()->result();
      //echo $this->db->last_query();
      return $result;
   }

   function getListCnt($condition)
   {
      $this->db->select('count(*) as cnt');
      $this->db->from('tb_member');
      if(isset($condition['sid'])){
         $this->db->where('id', $condition['sid']); 
      }
      $result = $this->db->get()->row();
      return $result->cnt;
   }

}
