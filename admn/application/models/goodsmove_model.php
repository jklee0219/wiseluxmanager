<?php
class Goodsmove_model extends CI_Model
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
  		(select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goodsmove.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
  		tb_goodsmove.*,
        c24_display
  	";
    $this->db->select($selectcolumn, FALSE);
  	$this->db->from('tb_goodsmove');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goodsmove.purchase_seq', 'left');
    $this->db->join('tb_goods', 'tb_goodsmove.purchase_seq = tb_goods.purchase_seq', 'left');
    if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['sstype'])) $this->db->where("type", $condition['sstype']);
  	if(isset($condition['sshipdate'])) $this->db->where("shipdate >= '".$condition['sshipdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['eshipdate'])) $this->db->where("shipdate <= '".$condition['eshipdate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['srecivedate'])) $this->db->where("recivedate >= '".$condition['srecivedate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['erecivedate'])) $this->db->where("recivedate <= '".$condition['erecivedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sshipplace'])) $this->db->where("shipplace", $condition['sshipplace']);
    if(isset($condition['sreciveplace'])) $this->db->where("reciveplace", $condition['sreciveplace']);
    if(isset($condition['smoveyn'])) $this->db->where("moveyn", $condition['smoveyn']);
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
    $this->db->order_by('tb_goodsmove.seq', 'DESC');
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
    $this->db->from('tb_goodsmove');
    $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goodsmove.purchase_seq', 'left');
    $this->db->join('tb_goods', 'tb_goodsmove.purchase_seq = tb_goods.purchase_seq', 'left');
  	if(isset($condition['sbrand'])) $this->db->where("brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("kind", $condition['skind']);
    if(isset($condition['sstype'])) $this->db->where("type", $condition['sstype']);
    if(isset($condition['sshipdate'])) $this->db->where("shipdate >= '".$condition['sshipdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['eshipdate'])) $this->db->where("shipdate <= '".$condition['eshipdate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['srecivedate'])) $this->db->where("recivedate >= '".$condition['srecivedate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['erecivedate'])) $this->db->where("recivedate <= '".$condition['erecivedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sshipplace'])) $this->db->where("shipplace", $condition['sshipplace']);
    if(isset($condition['sreciveplace'])) $this->db->where("reciveplace", $condition['sreciveplace']);
    if(isset($condition['smoveyn'])) $this->db->where("moveyn", $condition['smoveyn']);
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
      $this->db->delete('tb_goodsmove');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_goodsmove', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_goodsmove', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      	$selectcolumn = "
            tb_purchase.*,
            (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goodsmove.purchase_seq = tb_goods.purchase_seq and tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb,
            tb_goodsmove.*
        ";
	    $this->db->select($selectcolumn, FALSE);
      	$this->db->from('tb_goodsmove');
        $this->db->join('tb_purchase', 'tb_purchase.seq = tb_goodsmove.purchase_seq', 'left');
        $this->db->join('tb_goods', 'tb_goodsmove.purchase_seq = tb_goods.purchase_seq', 'left');
      	$this->db->where('tb_goodsmove.seq', $seq);

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
   
   //미처리
   function movenCnt()
   {
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_goodsmove');
       $this->db->where("moveyn", 'N');
       $result = $this->db->get()->row();
       return $result->cnt;
   }
   
   //처리완료
   function moveyCnt()
   {
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_goodsmove');
       $this->db->where("moveyn", 'Y');
       $result = $this->db->get()->row();
       return $result->cnt;
   }

    function getPurchaseInfoFromCode($pcode){

        $qry = " 
                select 
                    seq, 
                    (select concat(`filepath`,`realfilename`) from tb_goods_img where goods_seq = (select seq from tb_goods where tb_goods.purchase_seq = tb_purchase.seq) limit 1) as thumb,
                    pdate,
                    modelname,
                    ifnull((select seq from tb_goodsmove where purchase_seq = tb_purchase.seq),0) as tb_goodsmove_seq
                from tb_purchase
                where pcode = '".$pcode."'
               ";
        return $this->db->query($qry)->row();

    }

    function getAllList()
    {
        $this->db->select('*', FALSE);
        $this->db->from('tb_goodsmove');
        $this->db->order_by('shipdate', 'ASC');
        $result = $this->db->get()->result();

        return $result;
    }
}
