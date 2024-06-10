<?php
class Refund_model extends CI_Model
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
    $this->db->select('tb_refund.*, tb_goods.c24_origin_place_value as place, c24_display, tb_goods.floor');
    $this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
    $this->db->join('tb_goods', 'tb_goods.purchase_seq = tb_purchase.seq', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
  	if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
  	if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
  	if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
  	if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
  	if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
    if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
    if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['saccount_conf'])) $this->db->where("account_conf", $condition['saccount_conf']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
    $this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
    $this->db->join('tb_goods', 'tb_goods.purchase_seq = tb_purchase.seq', 'left');
    if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
    if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
    if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['saccount_conf'])) $this->db->where("account_conf", $condition['saccount_conf']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
      $this->db->delete('tb_refund');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_refund', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_refund', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
	    $this->db->select('*');
      	$this->db->from('tb_refund');
      	$this->db->where('seq', $seq);

      	$result = $this->db->get()->row();
      	
      	return $result;
   }
   
   function getSeqRefund($purchase_seq)
   {
   	$this->db->select('seq');
   	$this->db->from('tb_refund');
   	$this->db->where('purchase_seq', $purchase_seq);
   	 
   	$result = $this->db->get()->row();
   	 
   	return $result;
   }
   
   function totalsum_a($condition){
   	$selectcolumn = "sum(price) as totalsum_a";
   	$this->db->select($selectcolumn, FALSE);
   	$this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
   	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
   	if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
   	if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
   	if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
   	if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
   	return $this->db->get()->row();
   }
   
   function totalsum_b($condition){
   	$selectcolumn = "sum(price) as totalsum_b";
   	$this->db->select($selectcolumn, FALSE);
   	$this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
   	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
   	if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
   	if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
   	if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
   	if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
   	$this->db->where('process', 'Y');
   	return $this->db->get()->row();
   }
   
   function totalsum_c($condition){
   	$selectcolumn = "sum(price) as totalsum_c";
   	$this->db->select($selectcolumn, FALSE);
   	$this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
   	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
   	if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
   	if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
   	if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
   	if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
   	$this->db->where('process', 'N');
   	return $this->db->get()->row();
   }

   function totalsum_d($condition){
    $selectcolumn = "sum(paymentprice) as totalsum_d";
    $this->db->select($selectcolumn, FALSE);
    $this->db->from('tb_refund');
    $this->db->join('tb_purchase', 'tb_purchase.pcode = tb_refund.pcode', 'left');
    if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['ssapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') >= '".$condition['ssapplydate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['seapplydate'])) $this->db->where("STR_TO_DATE(applydate,'%Y-%m-%d') <= '".$condition['seapplydate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sscompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') >= '".$condition['sscompletedate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['secompletedate'])) $this->db->where("STR_TO_DATE(completedate,'%Y-%m-%d') <= '".$condition['secompletedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sprocess'])) $this->db->where("process", $condition['sprocess']);
    if(isset($condition['spaymethod'])) $this->db->where("paymethod", $condition['spaymethod']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
      }
    }
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
    $this->db->where('process', 'Y');
    return $this->db->get()->row();
   }
   
   function getPurchaseCode($pcode){
    $qry = "
        select 
        tb_purchase.seq as seq,
        (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
        pcode, 
        tb_trade.selldate as selldate,
        tb_trade.buyer as buyer,
        tb_trade.buyerphone as buyerphone,
        tb_purchase.modelname as modelname,
        tb_trade.selltype as selltype,
        tb_trade.paymethod as paymethod,
        tb_goods.brand_seq as brand_seq,
        tb_purchase.kind as kind,
        tb_trade.sellprice as amount,
        tb_purchase.goods_price as price,
        tb_trade.paymentprice as paymentprice,
        tb_trade.payment_price_1 as payment_price_1,
        tb_trade.payment_price_2 as payment_price_2,
        tb_trade.payment_price_3 as payment_price_3,
        tb_trade.payment_price_4 as payment_price_4,
        tb_trade.payment_price_5 as payment_price_5
        from tb_purchase left join tb_goods on tb_goods.purchase_seq = tb_purchase.seq left join tb_trade on tb_trade.purchase_seq = tb_purchase.seq 
        where pcode = '".$pcode."'
    "; 
    return $this->db->query($qry)->row();
   }
   
   function stockupdate($process, $purchase_seq){
   	$this->db->where('purchase_seq', $purchase_seq);
   	$this->db->update('tb_goods', array('stock' => $process, 'udate' => date('Y-m-d H:i:s')));
   }
}
