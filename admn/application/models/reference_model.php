<?php
class Reference_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  /*-------------------------------------------------
   *테이블 리스트 조회
   *$condition === array(key,value) == like
   --------------------------------------------------*/
  function getList($condition,$scale="N",$first="0")
  {
    $this->db->select('*');
    $this->db->from('tb_reference');
    if(isset($condition['sreference_category'])) $this->db->where("category", $condition['sreference_category']);
    if(isset($condition['stype']) && isset($condition['skeyword'])) {
    	$stype = $condition['stype'];
    	$skeyword = $condition['skeyword'];
    	$skeyword_arr = explode(' ', $skeyword);
    	$skeyword_cond = "";
    	foreach($skeyword_arr as $v){
    		if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
    		$skeyword_cond .= $stype." like '%".$v."%'";
    	}
    	$this->db->where($skeyword_cond, NULL, FALSE);
    }
    if($scale != "N") $this->db->limit($scale, $first);
    $this->db->order_by('seq', 'DESC');
    $result = $this->db->get()->result();
    return $result;
  }

  /*-------------------------------------------------
   *테이블 리스트 갯수 조회
   *$condition === array(key,value) == like
   --------------------------------------------------*/
  function getListCnt($condition)
  {
    $this->db->select('count(*) as cnt');
    $this->db->from('tb_reference');
    if(isset($condition['sreference_category'])) $this->db->where("category", $condition['sreference_category']);
  	if(isset($condition['stype']) && isset($condition['skeyword'])) {
    	$stype = $condition['stype'];
    	$skeyword = $condition['skeyword'];
    	$skeyword_arr = explode(' ', $skeyword);
    	$skeyword_cond = "";
    	foreach($skeyword_arr as $v){
    		if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
    		$skeyword_cond .= $stype." like '%".$v."%'";
    	}
    	$this->db->where($skeyword_cond, NULL, FALSE);
    }
    $result = $this->db->get()->row();
    return $result->cnt;
  }
  
  /*-------------------------------------------------
   *DB delete (delyn = 'N')
   --------------------------------------------------*/
   function deleteList($seqs)
   {
      $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
      $this->db->delete('tb_reference');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_reference', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_reference', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      $this->db->select('*');
      $this->db->from('tb_reference');
      $this->db->where('seq', $seq);

      $result = $this->db->get()->row();
      
      return $result;
   }

    function getTopCnt($condition, $category)
    {
        $this->db->select('count(*) as cnt');
        $this->db->from('tb_reference');
        if(isset($condition['sreference_category'])) $this->db->where("category", $condition['sreference_category']);
        if(isset($condition['stype']) && isset($condition['skeyword'])) {
            $stype = $condition['stype'];
            $skeyword = $condition['skeyword'];
            $skeyword_arr = explode(' ', $skeyword);
            $skeyword_cond = "";
            foreach($skeyword_arr as $v){
                if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
                $skeyword_cond .= $stype." like '%".$v."%'";
            }
            $this->db->where($skeyword_cond, NULL, FALSE);
        }
        $this->db->where('category', $category);
        $result = $this->db->get()->row();
        return $result->cnt;
    }
}
