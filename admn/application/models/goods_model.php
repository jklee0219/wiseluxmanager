<?php
class Goods_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  /*-------------------------------------------------
   *테이블 리스트 조회
   *$condition === array(key,value) == like
   --------------------------------------------------*/
  function getList($condition,$scale="N", $first="0")
  {
  	$selectcolumn = "
  		tb_goods.seq as seq,
  		pcode,	
  		tb_goods.purchase_seq as purchase_seq,
  		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
  		(select `name` from tb_brand where tb_brand.seq = tb_goods.brand_seq limit 1) as brandname,
  		pdate,
  		kind,
  		modelname,
  		goods_price as price,
  		stock,
  		tb_goods.note as note,
  		tb_asinfo.seq as asinfo_seq,
  		tb_trade.seq as trade_seq,
  		pprice,
  		tb_purchase.type as purchase_type,
      c24_product_no,
  		rdate,
      c24_origin_place_value,
      tb_goods.floor as floor,
      c24_display
  	";
    $this->db->select($selectcolumn, FALSE);
    $this->db->from('tb_goods');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_trade', 'tb_trade.purchase_seq = tb_goods.purchase_seq and tb_trade.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_asinfo.purchase_seq = tb_goods.purchase_seq', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['ssrdate'])) $this->db->where("rdate >= '".$condition['ssrdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['serdate'])) $this->db->where("rdate <= '".$condition['serdate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstype'])) $this->db->where("tb_purchase.type", $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    if(isset($condition['sc24display'])) $this->db->where('tb_goods.c24_display', $condition['sc24display']);
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
    if($condition['sorder'] == '2'){
        $this->db->order_by('price', 'ASC');
    }else if($condition['sorder'] == '3'){
        $this->db->order_by('price', 'DESC');
    }else{
        $this->db->order_by('seq', 'DESC');
    }
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
    if(isset($condition['ssrdate'])) $this->db->where("rdate >= '".$condition['ssrdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['serdate'])) $this->db->where("rdate <= '".$condition['serdate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstype'])) $this->db->where("tb_purchase.type", $condition['sstype']);
    if(isset($condition['sc24display'])) $this->db->where('tb_goods.c24_display', $condition['sc24display']);
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
      $this->db->delete('tb_goods');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_goods', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $data['udate'] = date('Y-m-d H:i:s');
       $this->db->update('tb_goods', $data);
    }
    
    function updateList2($data, $purchase_seq)
    {
    	$this->db->where('purchase_seq', $purchase_seq);
    	$data['udate'] = date('Y-m-d H:i:s');
    	$this->db->update('tb_goods', $data);
    }

   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      $selectcolumn = "
	  		tb_goods.*, 
	  		(select kind from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as kind,
	  		(select modelname from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as modelname,
	  		(select pdate from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as pdate,
      		(select pprice from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as pprice,
	  		(select method from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as method,
	  		(select class from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as class,
      		(select pcode from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as pcode,
      		(select seq from tb_goods_img where tb_goods_img.goods_seq = tb_goods.seq and represent = 'Y' limit 1) as represent,
            (select asprice from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as purchase_asprice,
            (select `type` from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as purchase_type,
      (select reason from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as reason
	  ";
	  $this->db->select($selectcolumn, FALSE);
      $this->db->from('tb_goods');
      $this->db->where('seq', $seq);

      $result = $this->db->get()->row();
      
      return $result;
   }
   
   /*-------------------------------------------------
    *상품등록에 대한 고유번호를 가져옴
     --------------------------------------------------*/
   function getSeqGoods($purchase_seq)
   {
		$this->db->select('seq');
	   	$this->db->from('tb_goods');
   		$this->db->where('purchase_seq', $purchase_seq);
   
   		$result = $this->db->get()->row();
   
   		return $result;
   }
   
   function copy($seq){
   	$qry  = "insert into tb_goods (`price`, `brand_seq`, selfcode, asmemo, note, `guarantee`, stock)";
   	$qry .= "(select `price`, `brand_seq`, selfcode, asmemo, note, `guarantee`, stock from tb_goods where seq = '".$seq."' )";
   	return $this->db->query($qry);
   }
   
	function confirmPurchase($purchase_seq)
    {
    $this->db->select('count(*) as cnt');
    $this->db->from('tb_goods');
    $this->db->where('purchase_seq', $purchase_seq);
    $result = $this->db->get()->row();
    return $result->cnt;
  }
  
  function representUpdate($goods_seq, $represent_seq){
  	$this->db->query(" update tb_goods_img set represent = 'N' where goods_seq = '".$goods_seq."' ");
  	$this->db->query(" update tb_goods_img set represent = 'Y' where seq = '".$represent_seq."' and goods_seq = '".$goods_seq."' ");
  }
  
  function brandupdate($purchase_seq, $brand_seq){
  	$this->db->query(" update tb_goods set brand_seq = '".$brand_seq."' where purchase_seq = '".$purchase_seq."' ");
  }
  
  function getstocktotprice($condition){
  	$this->db->select('count(*) as cnt, sum(goods_price) as price, sum(pprice) as pprice, stock');
  	$this->db->from('tb_goods');
  	$this->db->join('tb_purchase', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
  	if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
  	if(isset($condition['ssrdate'])) $this->db->where("rdate >= '".$condition['ssrdate']." 00:00:00'", NULL, FALSE);
  	if(isset($condition['serdate'])) $this->db->where("rdate <= '".$condition['serdate']." 23:59:59'", NULL, FALSE);
  	if(isset($condition['sstock'])) $this->db->where("stock", $condition['sstock']);
  	if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
  	if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
  	if(isset($condition['sstype'])) $this->db->where("tb_purchase.type", $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_goods.c24_origin_place_value", $condition['splace']);
    if(isset($condition['sc24display'])) $this->db->where('tb_goods.c24_display', $condition['sc24display']);
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
  	$this->db->order_by('stock', 'ASC');
  	$this->db->group_by('stock');
  	$result = $this->db->get()->result();
  	// echo $this->db->last_query();
  	return $result;
  }

  function delc24key($seq){
    if($seq){
      $qry = "update tb_goods set c24_product_no = 0 where seq = '".$seq."' ";
      $this->db->query($qry);
    }
  }
}
