<?php
class Goods_img_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  function getList($goods_seq)
  {
  	$this->db->select('*');
  	$this->db->from('tb_goods_img');
  	$this->db->where('goods_seq', $goods_seq);
  	$this->db->order_by('order', 'ASC');
  
  	$result = $this->db->get()->result();
  
  	return $result;
  }

   /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_goods_img', $data);
      //echo $this->db->last_query().'<br/>';
//       $fn = $_SERVER['DOCUMENT_ROOT'].'/querylog';
//       $fp = fopen($fn, "a");
//       fwrite($fp, date('Y-m-d H:i:s').' | '.$this->db->last_query().PHP_EOL);
//       fclose($fp);
   }
   
   function getView($seq)
   {
   	$this->db->select('*');
   	$this->db->from('tb_goods_img');
   	$this->db->where('seq', $seq);
   
   	$result = $this->db->get()->row();
   
   	return $result;
   }
   
   function deleteimg($seq){
	   	$this->db->where('seq',$seq);
	   	$this->db->delete('tb_goods_img');
   }
   
   function orderUpdate($seq, $order){
   	$this->db->where('seq', $seq);
   	$this->db->update('tb_goods_img', array('order' => $order));
   }
   
   function getimgcnt($goods_seq){
   	$this->db->select('count(*) as cnt');
   	$this->db->from('tb_goods_img');
   	$this->db->where('goods_seq', $goods_seq);
   	$result = $this->db->get()->row();
   	return $result->cnt;
   }
}
