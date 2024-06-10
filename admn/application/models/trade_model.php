<?php
class Trade_model extends CI_Model
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
  		tb_trade.seq as seq,
  		pcode,
    	selltype,	
  		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.purchase_seq = tb_trade.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq limit 1) as thumb,
  		(select `name` from tb_brand where tb_brand.seq = tb_goods.brand_seq and tb_trade.purchase_seq = tb_goods.purchase_seq limit 1) as brandname,
  		pdate,
    	tb_purchase.type as `type`,
  		kind,
  		modelname,
  		tb_purchase.goods_price as price,
    	sellprice,
    	dc,
    	tb_trade.paymethod as paymethod,
  		pprice,
  		stock,
    	tb_goods.seq as goods_seq,
    	tb_asinfo.seq as asinfo_seq,
    	selldate,
    	buyer,
    	buyerphone, 
      tb_goods.c24_origin_place_value, 
      tb_goods.floor,
      paymentprice,
      tb_trade.account_conf as account_conf,
      tb_trade.payment_price_1 as payment_price_1,
      tb_trade.payment_price_2 as payment_price_2,
      tb_trade.payment_price_3 as payment_price_3,
      tb_trade.payment_price_4 as payment_price_4,
      tb_trade.payment_price_5 as payment_price_5,
      c24_display,
      npay
  	";
    $this->db->select($selectcolumn, FALSE);
    $this->db->from('tb_trade');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
    $this->db->join('tb_goods', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_trade.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['saccount_conf'])) $this->db->where("tb_trade.account_conf", $condition['saccount_conf']);
    if(isset($condition['spayment_price'])) $this->db->where("tb_trade.".$condition['spayment_price']." <> ''", NULL, FALSE);
    if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
      if($pcol == 'pprice') $pcol = 'tb_purchase.pprice';
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
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
    if($scale != "N") $this->db->limit($scale,$first);
    $this->db->where_in('type', array('기타', '매입', '교환', '교환+매입'));
    $this->db->order_by('tb_trade.seq desc');
    $result = $this->db->get()->result();
// 	echo $this->db->last_query(); exit();
    return $result;
  }

  /*-------------------------------------------------
   *테이블 리스트 갯수 조회
   *$condition === array(key,value) == like
   --------------------------------------------------*/
  function getListCnt($condition)
  {
    $this->db->select('count(*) as cnt');
    $this->db->from('tb_trade');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
    $this->db->join('tb_goods', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['saccount_conf'])) $this->db->where("tb_trade.account_conf", $condition['saccount_conf']);
    if(isset($condition['spayment_price'])) $this->db->where("tb_trade.".$condition['spayment_price']." <> ''", NULL, FALSE);
    if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
      if($pcol == 'pprice') $pcol = 'tb_purchase.pprice';
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
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
    $this->db->where_in('type', array('기타', '매입', '교환', '교환+매입'));
    $result = $this->db->get()->row();
    return $result->cnt;
  }

  /*-------------------------------------------------
   *DB delete (delyn = 'N')
   --------------------------------------------------*/
   function deleteList($seqs)
   {
      $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
      $this->db->delete('tb_trade');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_trade', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_trade', $data);
    }
    
    function updateList2($data, $seq)
    {
        $this->db->where('purchase_seq', $seq);
        $this->db->update('tb_trade', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      $selectcolumn = "
	  		  tb_trade.seq as seq,
      		tb_trade.purchase_seq as purchase_seq,
	  		  pdate,
	    	  kind,	
	  		  modelname,
      		pprice,
	  		  tb_goods.price as price,
      		tb_purchase.method as method,
      		`class`,	
	  		  tb_goods.selfcode as selfcode,
      		selltype,
      		sellprice,
      		dc,
      		tb_trade.paymethod as paymethod,
      		tb_trade.note as note,
      		buyer,
      		selldate,
      		buyerphone,
      		amount,
      		sellerinfo,
      		pcode,
          paymentprice,
          senddate,
          tb_trade.account_conf,
          tb_trade.payment_price_1 as payment_price_1,
          tb_trade.payment_price_2 as payment_price_2,
          tb_trade.payment_price_3 as payment_price_3,
          tb_trade.payment_price_4 as payment_price_4,
          tb_trade.payment_price_5 as payment_price_5,
          npay
	  	";
	    $this->db->select($selectcolumn, FALSE);
      $this->db->from('tb_trade');
	    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
	    $this->db->join('tb_goods', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
      $this->db->where('tb_trade.seq', $seq);

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
   
   function getSeqTrade($purchase_seq)
   {
   	$this->db->select('seq');
   	$this->db->from('tb_trade');
   	$this->db->where('purchase_seq', $purchase_seq);
   	 
   	$result = $this->db->get()->row();
   	 
   	return $result;
   }
   
   function totalsum($condition){
   	$selectcolumn = "
      sum(tb_purchase.pprice) as total_pprice, sum(tb_trade.sellprice) as total_sellprice, sum(tb_purchase.goods_price) as total_price, sum(paymentprice) as total_paymentprice,
      sum(payment_price_1) as payment_price_1_sum, sum(payment_price_2) as payment_price_2_sum, sum(payment_price_3) as payment_price_3_sum, sum(payment_price_4) as payment_price_4_sum, sum(payment_price_5) as payment_price_5_sum, sum(npay) as npay_sum,
      ";
   	$this->db->select($selectcolumn, FALSE);
   	$this->db->from('tb_trade');
   	$this->db->join('tb_purchase', 'tb_purchase.seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
   	$this->db->join('tb_goods', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
   	$this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_trade.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
   	if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
   	if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
   	if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['spayment_price'])) $this->db->where("tb_trade.".$condition['spayment_price']." <> ''", NULL, FALSE);
    if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
      if($pcol == 'pprice') $pcol = 'tb_purchase.pprice';
      if(!empty($condition['sminprice'])) {
        $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
      }
      if(!empty($condition['smaxprice'])) {
        $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
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
   	$this->db->where_in('type', array('기타', '매입', '교환', '교환+매입'));
   	//$qry = "select (select sum(dc) from tb_trade) as total_dc, (select sum(amount) from tb_trade) as total_amount, (select sum(sellprice) from tb_trade) as total_sellprice";
   	$result = $this->db->get()->row();
   	return $result;
   }
   
   function copy($seq){
   	$qry  = "insert into tb_trade (`selltype`, `sellprice`, dc, paymethod, buyer, `buyerphone`, amount, selldate, sellerinfo)";
   	$qry .= "(select `selltype`, `sellprice`, dc, paymethod, buyer, `buyerphone`, amount, selldate, sellerinfo from tb_trade where seq = '".$seq."' )";
   	return $this->db->query($qry);
   }
   
   function getTotSelltype($condition, $selltype)
   {
       $this->db->select('sum(tb_goods.price) as total_price, sum(tb_trade.sellprice) as total_sellprice, sum(tb_trade.paymentprice) as total_paymentprice');
       $this->db->from('tb_trade');
       $this->db->join('tb_purchase', 'tb_purchase.seq = tb_trade.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
       $this->db->join('tb_goods', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sselltype'])) $this->db->where("selltype", $condition['sselltype']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
       if(isset($condition['spayment_price'])) $this->db->where("tb_trade.".$condition['spayment_price']." <> ''", NULL, FALSE);
       if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
          if($pcol == 'pprice') $pcol = 'tb_purchase.pprice';
          if(!empty($condition['sminprice'])) {
            $this->db->where($pcol." >= ".$condition['sminprice'], NULL, FALSE);
          }
          if(!empty($condition['smaxprice'])) {
            $this->db->where($pcol." <= ".$condition['smaxprice'], NULL, FALSE);
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
       $this->db->where_in('type', array('기타', '매입', '교환', '교환+매입'));
       $this->db->where('selltype', $selltype);
       $result = $this->db->get()->row();
       return $result;
   }
}
