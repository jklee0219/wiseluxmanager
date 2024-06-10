<?php
class Asinfo_model extends CI_Model
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
    $selectcolumn = "
  		tb_asinfo.seq as seq,
  		pcode,	
  		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
  		pdate,
    	seller,
  		sellerphone,
  		modelname,
  		result,
    	start_date,
    	end_date,
    	reason,
    	tb_asinfo.note as note,
    	tb_trade.buyer as buyer,
        as_yn,
        tb_purchase.pprice as pprice,
        tb_trade.buyerphone as buyerphone,
        c24_display,
        type
  	";
    $this->db->select($selectcolumn, FALSE);
  	$this->db->from('tb_asinfo');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    $this->db->join('tb_goods', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_trade', 'tb_asinfo.purchase_seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sasyn'])) $this->db->where("as_yn", $condition['sasyn']);
  	if(isset($condition['stype']) && isset($condition['skeyword'])) {
  		$stype = $condition['stype'];
      if($stype == 'note') $stype = 'tb_asinfo.note';
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
    $this->db->order_by('tb_asinfo.udate', 'DESC');
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
    $this->db->from('tb_asinfo');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    $this->db->join('tb_goods', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_trade', 'tb_asinfo.purchase_seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sasyn'])) $this->db->where("as_yn", $condition['sasyn']);
  	if(isset($condition['stype']) && isset($condition['skeyword'])) {
  		$stype = $condition['stype'];
      if($stype == 'note') $stype = 'tb_asinfo.note';
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
      $this->db->delete('tb_asinfo');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_asinfo', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_asinfo', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      	$selectcolumn = "
	  		tb_asinfo.seq as seq,
      		tb_asinfo.purchase_seq as purchase_seq,
      		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq limit 1) as thumb,	
      		pdate,
      		seller,
      		sellerphone,
      		start_date,
      		end_date,
      		result,
      		reason,
      		tb_asinfo.note as note,
      		pcode,
            as_yn,
            type
	  	";
	    $this->db->select($selectcolumn, FALSE);
      	$this->db->from('tb_asinfo');
	    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
	    $this->db->join('tb_goods', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
      	$this->db->where('tb_asinfo.seq', $seq);

      	$result = $this->db->get()->row();
      
      	return $result;
   }
   
   function getInfoFromCode($pcode)
   {
		$this->db->select('*');
		$this->db->from('tb_trade');
   		$this->db->where('pcode', $pcode);
   
   		$result = $this->db->get()->row();
   
   		return $result;
   }
   
   function getSeqAsinfo($purchase_seq)
   {
   	$this->db->select('seq');
   	$this->db->from('tb_asinfo');
   	$this->db->where('purchase_seq', $purchase_seq);
   	 
   	$result = $this->db->get()->row();
   	 
   	return $result;
   }
   
   function copy($seq){
   	$qry  = "insert into tb_asinfo (`start_date`, `end_date`, `reason`, `result`, `note`)";
   	$qry .= "(select `start_date`, `end_date`, `reason`, `result`, `note` from tb_asinfo where seq = '".$seq."' )";
   	return $this->db->query($qry);
   }
   
   function confirmPurchase($purchase_seq)
   {
   	$this->db->select('count(*) as cnt');
   	$this->db->from('tb_asinfo');
   	$this->db->where('purchase_seq', $purchase_seq);
   	$result = $this->db->get()->row();
   	return $result->cnt;
   }
   
   //미처리
   function asnCnt()
   {
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_asinfo');
       $this->db->where("as_yn", 'N');
       $result = $this->db->get()->row();
       return $result->cnt;
   }
   
   //처리완료
   function asyCnt()
   {
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_asinfo');
       $this->db->where("as_yn", 'Y');
       $result = $this->db->get()->row();
       return $result->cnt;
   }
   
   function getTotPprise($condition,$asyn)
   {
       $this->db->select("sum(pprice) as pprice", FALSE);
       $this->db->from('tb_asinfo');
       $this->db->join('tb_purchase', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       $this->db->join('tb_goods', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_trade', 'tb_asinfo.purchase_seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
       if(isset($condition['sasyn'])) $this->db->where("as_yn", $condition['sasyn']);
       if(isset($condition['stype']) && isset($condition['skeyword'])) {
           $stype = $condition['stype'];
           if($stype == 'note') $stype = 'tb_asinfo.note';
           $skeyword = $condition['skeyword'];
           $skeyword_arr = explode(' ', $skeyword);
           $skeyword_cond = "";
           foreach($skeyword_arr as $v){
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' and ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('tb_asinfo.as_yn', $asyn);
       $result = $this->db->get()->row();
       
       return $result->pprice;
   }
}
