<?php
class Stockcheck_model extends CI_Model
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
      //상품목록에는 재고 없음 되어있는데, 판매목록, 위탁판매목록에 아무런 정보가 없는 경우
    $selectcolumn = "
  		tb_purchase.seq as purchase_seq,
        tb_trade.seq as trade_seq,
        tb_purchase.pcode as pcode,
  		tb_purchase.seller as seller,
        tb_purchase.modelname as modelname,
        tb_purchase.pprice as pprice,
        tb_purchase.pdate as pdate,
        tb_goods.rdate as rdate,
        tb_goods.price as price,
        tb_trade.buyer as buyer,
        tb_trade.buyerphone as buyerphone,
        tb_trade.sellprice as sellprice,
        tb_goods.stock as stock,
        tb_purchase.type as type,
        tb_asinfo.seq as asinfo_seq,
        (select process from tb_refund where tb_purchase.pcode = tb_refund.pcode limit 1) as process,
        tb_goods.seq as goods_seq,
        stockcheck_date,
        c24_display
  	";
    $this->db->select($selectcolumn, FALSE);
    $this->db->from('tb_goods');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_stockcheck', 'tb_stockcheck.purchase_seq = tb_goods.purchase_seq', 'left');
    if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("tb_trade.selltype", $condition['sselltype']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
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
    $this->db->where('tb_goods.stock', 'N');
    $this->db->where('tb_purchase.modelname != ', "''", false);
    $this->db->where('tb_trade.seq is null', null, false);
    $this->db->order_by('tb_goods.udate desc');
    $result = $this->db->get()->result();
	//echo $this->db->last_query(); exit();
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
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq', 'left');
    $this->db->join('tb_stockcheck', 'tb_stockcheck.purchase_seq = tb_goods.purchase_seq', 'left');
    if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sselltype'])) $this->db->where("tb_trade.selltype", $condition['sselltype']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
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
    $this->db->where('tb_goods.stock', 'N');
    $this->db->where('tb_purchase.modelname != ', "''", false);
    $this->db->where('tb_trade.seq is null', null, false);
    $result = $this->db->get()->row();
    return $result->cnt;
  }

  /*-------------------------------------------------
   *DB delete (delyn = 'N')
   --------------------------------------------------*/
   function deleteList($seqs)
   {
      $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
      $this->db->delete('tb_stockcheck');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_stockcheck', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_stockcheck', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
       $selectcolumn = "
      		tb_purchase.seq as purchase_seq,
            tb_trade.seq as trade_seq,
            tb_purchase.pcode as pcode,
      		tb_purchase.seller as seller,
            tb_purchase.modelname as modelname,
            tb_purchase.pprice as pprice,
            tb_purchase.pdate as pdate,
            tb_goods.rdate as rdate,
            tb_goods.price as price,
            tb_trade.buyer as buyer,
            tb_trade.buyerphone as buyerphone,
            tb_trade.sellprice as sellprice,
            tb_goods.stock as stock,
            tb_purchase.type as type,
            tb_asinfo.seq as asinfo_seq,
            (select process from tb_refund where tb_purchase.pcode = tb_refund.pcode limit 1) as process,
            tb_stockcheck.note as note,
            stockcheck_date
      	";
        $this->db->select($selectcolumn, FALSE);
        $this->db->from('tb_goods');
        $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_stockcheck', 'tb_stockcheck.purchase_seq = tb_goods.purchase_seq', 'left');
        $this->db->where('tb_goods.seq', $seq);

      	$result = $this->db->get()->row();
      	//echo $this->db->last_query();
      
      	return $result;
   }
   
   function getInfoFromCode($pcode){
        $selectcolumn = "
      		tb_purchase.seq as purchase_seq,
            tb_trade.seq as trade_seq,
            tb_purchase.pcode as pcode,
      		tb_purchase.seller as seller,
            tb_purchase.modelname as modelname,
            tb_purchase.pprice as pprice,
            tb_purchase.pdate as pdate,
            tb_goods.rdate as rdate,
            tb_goods.price as price,
            tb_trade.buyer as buyer,
            tb_trade.buyerphone as buyerphone,
            tb_trade.sellprice as sellprice,
            tb_goods.stock as stock,
            tb_purchase.type as type,
            tb_asinfo.seq as asinfo_seq,
            (select process from tb_refund where tb_purchase.pcode = tb_refund.pcode limit 1) as process
      	";
        $this->db->select($selectcolumn, FALSE);
        $this->db->from('tb_purchase');
        $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_purchase.seq', 'left');
        $this->db->join('tb_goods', 'tb_goods.purchase_seq = tb_purchase.seq', 'left');
        $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_purchase.seq', 'left');
        $this->db->where('tb_purchase.pcode', $pcode);
        
        $result = $this->db->get()->row();
        return $result;
    }
    
    function getSeqStockcheck($purchase_seq)
    {
        $this->db->select('seq');
        $this->db->from('tb_stockcheck');
        $this->db->where('purchase_seq', $purchase_seq);
        
        $result = $this->db->get()->row();
        
        return $result;
    }
    
    function totalsum($condition){
        $selectcolumn = "
      		sum(tb_purchase.pprice) as tot_pprice, sum(tb_goods.price) as tot_price, stock
      	";
        $this->db->select($selectcolumn, FALSE);
        $this->db->from('tb_goods');
        $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq', 'left');
        $this->db->join('tb_stockcheck', 'tb_stockcheck.purchase_seq = tb_goods.purchase_seq', 'left');
        if(isset($condition['ssdate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
        if(isset($condition['sedate'])) $this->db->where("STR_TO_DATE(tb_trade.selldate,'%Y-%m-%d') <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
        if(isset($condition['sselltype'])) $this->db->where("tb_trade.selltype", $condition['sselltype']);
        if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
        if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
        if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
        if(isset($condition['spaymethod'])) $this->db->where("tb_trade.paymethod", $condition['spaymethod']);
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
        //$this->db->where('tb_goods.stock', 'N');
        $this->db->where('tb_trade.seq is null', null, false);
        $this->db->order_by('tb_goods.stock asc');
        $this->db->group_by('tb_goods.stock');
        $result = $this->db->get()->result();
        //echo $this->db->last_query(); exit();
        return $result;
    }
    
    function noteupdate($note, $purchase_seq){
        $this->db->query(" insert into tb_stockcheck (`note`, `purchase_seq`) values ('".$note."', '".$purchase_seq."') ON DUPLICATE KEY UPDATE note = '".$note."' ");
    }
   
}
