<?php
class Login_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  /*-------------------------------------------------
   *로그인 정보 체크
   --------------------------------------------------*/
  function getLoginChk($id,$pw)
  {
    $this->db->select('*');
    $this->db->from('tb_member');
    $this->db->where('id', $id, TRUE);
    $this->db->where("`password` = password('".$pw."')", NULL, TRUE);
    $result = $this->db->get()->row();
    
    return $result;
  }
  
  function getMemberList()
  {
  	$this->db->select('*');
  	$this->db->from('tb_member');
  	$this->db->order_by('name', 'ASC');
    $result = $this->db->get()->result();
  
  	return $result;
  }

  /*-------------------------------------------------
   *마지막 로그인 날짜 업데이트
   --------------------------------------------------*/
  function setLoginData($id,$pw)
  {
    $data = array(
      'last_login' => date('Y-m-d H:i:s')
    );
    $this->db->where('id', $id, TRUE);
    $this->db->where("`password` = password('".$pw."')", NULL, TRUE);

    return $this->db->update('tb_member', $data);
  }
  
  function updatePassword($id,$pw)
  {
  	$this->db->set('password', "password('".$pw."')", FALSE);
  	$this->db->where('id', $id, TRUE);
  
  	return $this->db->update('tb_member');
  }

}
