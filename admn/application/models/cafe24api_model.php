<?php
class Cafe24api_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function getToken()
	{
	    //만료 두시간 이내인 토큰만 조회
	    $this->db->select("*");
	    $this->db->from('tb_cafe24_token');
	    $result = $this->db->get()->row();
	    return $result;
	}
	
	function setToken($access_token, $refresh_token, $expires_at, $refresh_token_expires_at){
	    $this->db->query("insert into tb_cafe24_token (`seq`, `access_token`, `refresh_token`, `expires_at`, `refresh_token_expires_at`) values ('1', '".$access_token."', '".$refresh_token."', '".$expires_at."', '".$refresh_token_expires_at."') ON DUPLICATE KEY UPDATE access_token='".$access_token."', refresh_token='".$refresh_token."', expires_at='".$expires_at."', refresh_token_expires_at='".$refresh_token_expires_at."' ");
	}
	
	function getGoodsInfo($seq){
	    return $result;
	}
	
	function getGoodsImg($seq){
	    $qry  = " select * from tb_goods_img where goods_seq = '".$seq."';";
	    return $this->db->query($qry)->result();
	}
	
	function getProductInfo($seq){
	    $qry  = " select *,(select goods_price from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as price2,(select modelname from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as modelname,(select pcode from tb_purchase where tb_purchase.seq = tb_goods.purchase_seq) as pcode from tb_goods where seq = '".$seq."';";
	    return $this->db->query($qry)->row();
	}
	
	function setCategory($category_no, $parent_category_no, $category_depth, $category_name){
	    $this->db->query("insert into tb_cafe24_category (`category_no`, `parent_category_no`, `category_depth`, `category_name`) values ('".$category_no."', '".$parent_category_no."', '".$category_depth."', '".$category_name."') ON DUPLICATE KEY UPDATE category_depth='".$category_depth."', parent_category_no='".$parent_category_no."', category_name='".$category_name."' ");
	}
	
	function setBrand($brand_code, $brand_name){
	    $this->db->query("insert into tb_cafe24_brand (`brand_code`, `brand_name`) values ('".$brand_code."', '".$brand_name."') ON DUPLICATE KEY UPDATE brand_name='".$brand_name."' ");
	}
	
	function getCategory($depth, $no){
	    $qry  = " select * from tb_cafe24_category where category_depth = '".$depth."' and parent_category_no = '".$no."' ";
	    return $this->db->query($qry)->result();
	}
	
	function setProductNo($goods_seq, $product_no){
	    $qry = "update tb_goods set c24_product_no = '".$product_no."' where seq = '".$goods_seq."' ";
	    $this->db->query($qry);
	}
	
	function getCategoryStr($no){
	    $qry = " select parent_category_no,category_name from tb_cafe24_category where category_no = '".$no."' ";
	    return $this->db->query($qry)->row();
	}
}
