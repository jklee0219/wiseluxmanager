<?php
class Productmovedate_model extends CI_Model
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
  		tb_purchase.*,
  		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_productmovedate.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
  		tb_productmovedate.*,
        tb_goodsmove.moveyn as moveyn
  	";
    $this->db->select($selectcolumn, FALSE);
  	$this->db->from('tb_productmovedate');
    $this->db->join('tb_goodsmove', 'tb_productmovedate.purchase_seq = tb_goodsmove.purchase_seq', 'left');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_productmovedate.purchase_seq', 'left');
    $this->db->join('tb_goods', 'tb_productmovedate.purchase_seq = tb_goods.purchase_seq', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("movedate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("movedate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
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
    $this->db->order_by('tb_productmovedate.seq', 'DESC');
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
    $this->db->from('tb_productmovedate');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_productmovedate.purchase_seq', 'left');
    $this->db->join('tb_goods', 'tb_productmovedate.purchase_seq = tb_goods.purchase_seq', 'left');
  	if(isset($condition['ssdate'])) $this->db->where("movedate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("movedate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
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
      $this->db->delete('tb_productmovedate');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_productmovedate', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_productmovedate', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      	$selectcolumn = "
            tb_purchase.*,
            (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_productmovedate.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
            tb_productmovedate.*,
            tb_goodsmove.moveyn as moveyn
        ";
	    $this->db->select($selectcolumn, FALSE);
      	$this->db->from('tb_productmovedate');
        $this->db->join('tb_goodsmove', 'tb_productmovedate.purchase_seq = tb_goodsmove.purchase_seq', 'left');
        $this->db->join('tb_purchase', 'tb_purchase.seq = tb_productmovedate.purchase_seq', 'left');
        $this->db->join('tb_goods', 'tb_productmovedate.purchase_seq = tb_goods.purchase_seq', 'left');
      	$this->db->where('tb_productmovedate.seq', $seq);

      	$result = $this->db->get()->row();
      
      	return $result;
   }
   
   function getInfoFromCode($pcode)
   {
		$this->db->select('*');
		$this->db->from('tb_purchase');
   		$this->db->where('pcode', $pcode);
   
   		$result = $this->db->get()->row();
   
   		return $result;
   }
   
    function moveyn($purchase_seq, $flag)
    {
       $this->db->where('purchase_seq', $purchase_seq);
       $this->db->update('tb_goodsmove', ['moveyn', $flag]);
    }

    function getPurchaseInfoFromCode($pcode){

        $qry = " 
                select 
                    seq, 
                    (select concat(`filepath`,`realfilename`) from tb_goods_img where goods_seq = (select seq from tb_goods where tb_goods.purchase_seq = tb_purchase.seq) limit 1) as thumb,
                    pdate,
                    modelname,
                    ifnull((select seq from tb_productmovedate where purchase_seq = tb_purchase.seq),0) as tb_productmovedate_seq
                from tb_purchase
                where pcode = '".$pcode."'
               ";
        return $this->db->query($qry)->row();

    }

    function getAllList()
    {
        $this->db->select('*', FALSE);
        $this->db->from('tb_productmovedate');
        $this->db->order_by('tb_productmovedate.seq', 'DESC');
        $result = $this->db->get()->result();

        return $result;
    }
}
