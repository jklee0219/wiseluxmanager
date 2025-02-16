<?php
class Refunddeposit_model extends CI_Model
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
		$this->db->from('tb_refunddeposit');
		if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(deposit_date,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
		if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(deposit_date,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
		if(isset($condition['stype']) && isset($condition['skeyword'])) {
			$stype = $condition['stype'];
				if(in_array($stype,array('modelname','pcode'))) $stype = 'tb_purchase.'.$stype;
			$skeyword = $condition['skeyword'];
			$skeyword_arr = explode(' ', $skeyword);
			$skeyword_cond = "";
			foreach($skeyword_arr as $v){
				if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
				$skeyword_cond .= $stype." like '%".$v."%'";
			}
			$this->db->where($skeyword_cond, NULL, FALSE);
		}
		if($scale != "N") $this->db->limit($scale,$first);
		$this->db->order_by('seq desc');
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
		$this->db->from('tb_refunddeposit');
		if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(deposit_date,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
		if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(deposit_date,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
		if(isset($condition['stype']) && isset($condition['skeyword'])) {
			$stype = $condition['stype'];
				if(in_array($stype,array('modelname','pcode'))) $stype = 'tb_purchase.'.$stype;
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
        $this->db->delete('tb_refunddeposit');
    }

	/*-------------------------------------------------
	 *테이블 insert
	 --------------------------------------------------*/
    function insertList($data)
    {
        $this->db->insert('tb_refunddeposit', $data);
    }

    /*-------------------------------------------------
     *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
        $this->db->where('seq', $seq);
        $this->db->update('tb_refunddeposit', $data);
    }

	/*-------------------------------------------------
	 *게시물 내용 조회
	--------------------------------------------------*/
	function getView($seq)
	{
        $this->db->select('*');
        $this->db->from('tb_refunddeposit');
        $this->db->where('seq', $seq);
		return $this->db->get()->row();
	}
}
