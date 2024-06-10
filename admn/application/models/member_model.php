<?php
class Member_model extends CI_Model
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
      $this->db->order_by('auth', 'ASC');
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

   function deleteList($seqs)
   {
      $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
      $this->db->delete('tb_member');
   }

   /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $password = $data['password'];
      unset($data['password']);
      $this->db->set('password', "password('".$password."')", FALSE);
      $this->db->insert('tb_member', $data);
   }

   /*-------------------------------------------------
   *테이블 update
   --------------------------------------------------*/
   function updateList($data, $seq)
   {
      if(isset($data['password'])){
         $password = $data['password'];
         unset($data['password']);
         $this->db->set('password', "password('".$password."')", FALSE);
      }
      $this->db->where('seq', $seq);
      $this->db->update('tb_member', $data);
   }

   function idchk($chkstr){
      $this->db->select('count(*) as cnt');
      $this->db->from('tb_member');
      $this->db->where('id', $chkstr);
      $result = $this->db->get()->row();
      return $result->cnt;
   }

   function getView($seq)
   {
      $this->db->select('*', FALSE);
      $this->db->from('tb_member');
      $this->db->where('seq', $seq);
      $result = $this->db->get()->row();
      return $result;
   }

}
