<?php
class Request_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

  	/*-------------------------------------------------
   	*테이블 리스트 조회
   	*$condition === array(key,value) == like
   	--------------------------------------------------*/
  	function getList($condition, $scale="N", $first="0")
	{
		$selectcolumn = "
			tb_goods.seq as seq,
			tb_goods.purchase_seq as purchase_seq,
			tb_goods.seq as seq,
			pcode,	
			(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
			tb_purchase.pdate as pdate,
			tb_purchase.seller as seller,
			tb_purchase.sellerphone as sellerphone,
			modelname,
			goods_price as price,
			req_price,
			confirmdate,
			request_cnt,
			request_yn,
            c24_display,
            stock
  		";
    	$this->db->select($selectcolumn, FALSE);
    	$this->db->from('tb_goods');
    	$this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    	$this->db->join('tb_brand', 'tb_brand.seq = tb_goods.brand_seq', 'left');
    	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    	if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    	if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    	if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    	if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
    	if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    	if(isset($condition['confirmyn'])){
    		if($condition['confirmyn'] == 'Y'){
    			$this->db->where(" not confirmdate is null ", NULL, FALSE);
    		}else{
    			$this->db->where(" confirmdate is null ", NULL, FALSE);
    		}
    	}
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
	    $this->db->where("req_price > 0", NULL, FALSE);
    	if($scale != "N") $this->db->limit($scale,$first);
    	$this->db->order_by('request_date', 'DESC');
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
	    $this->db->from('tb_goods');
    	$this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    	if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    	if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    	if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    	if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
    	if(isset($condition['sorder'])) $this->db->where("stock", $condition['sorder']);
    	if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    	if(isset($condition['confirmyn'])){
    		if($condition['confirmyn'] == 'Y'){
    			$this->db->where(" not confirmdate is null ", NULL, FALSE);
    		}else{
    			$this->db->where(" confirmdate is null ", NULL, FALSE);
    		}
    	}
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
	    $this->db->where("req_price > 0", NULL, FALSE);
	    $result = $this->db->get()->row();
	    return $result->cnt;
 	}

 	function confirmProc($seq)
  	{
	    $this->db->where('seq', $seq);
        $this->db->update('tb_goods', array('confirmdate' => date('Y-m-d H:i:s'), 'request_yn' => 'N'));
 	}

 	function getConfirmData($condition, $type)
	{
		$selectcolumn = "
			count(*) as cnt,
			sum(tb_purchase.pprice) as sum
  		";
    	$this->db->select($selectcolumn, FALSE);
    	$this->db->from('tb_goods');
    	$this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    	if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    	if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    	if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    	if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
    	if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    	if(isset($condition['confirmyn'])){
    		if($condition['confirmyn'] == 'Y'){
    			$this->db->where(" not confirmdate is null ", NULL, FALSE);
    		}else{
    			$this->db->where(" confirmdate is null ", NULL, FALSE);
    		}
    	}
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
	    if($type == '1'){ //미처리
			$this->db->where(" confirmdate is null ", NULL, FALSE);
	    }else{ //처리완료
			$this->db->where(" not confirmdate is null ", NULL, FALSE);
	    }
	    $this->db->where("req_price > 0", NULL, FALSE);
	    $result = $this->db->get()->row();

    	return $result;
	}

}
