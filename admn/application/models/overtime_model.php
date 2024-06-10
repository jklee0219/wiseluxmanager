<?php
class Overtime_model extends CI_Model
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
    $this->db->select('tb_purchase.*, (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb, tb_goods.seq as goods_seq, goods_price, c24_display',false);
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
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
    $this->db->where("now() >= DATE_ADD(pdate, INTERVAL 90 DAY)", NULL, FALSE);
    $this->db->where("tb_goods.stock = 'Y'", NULL, FALSE);
    $this->db->where("tb_purchase.type = '위탁'", NULL, FALSE);
    if($scale != "N") $this->db->limit($scale,$first);
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
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
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
   $this->db->where("now() >= DATE_ADD(pdate, INTERVAL 90 DAY)", NULL, FALSE);
   $this->db->where("tb_goods.stock = 'Y'", NULL, FALSE);
   $this->db->where("tb_purchase.type = '위탁'", NULL, FALSE);
    $result = $this->db->get()->row();
    return $result->cnt;
  }

  function getUnprocessedPrice($condition){
    $this->db->select('sum(pprice) as tot_pprice');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
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
    $this->db->where("now() >= DATE_ADD(pdate, INTERVAL 90 DAY)", NULL, FALSE);
    $this->db->where("tb_goods.stock = 'Y'", NULL, FALSE);
    $this->db->where("tb_purchase.type = '위탁'", NULL, FALSE);
    $result = $this->db->get()->row();
    return $result;
  }
}
