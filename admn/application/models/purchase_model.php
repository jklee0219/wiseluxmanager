<?php
class Purchase_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }
  
  function getLastCode(){
    $this->db->select('pcode');
    $this->db->from('tb_purchase');
    $this->db->limit('1');
    $this->db->order_by('seq', 'DESC');
    $result = $this->db->get()->row();
    return isset($result->pcode) ? $result->pcode : '';
  }

  /*-------------------------------------------------
   *테이블 리스트 조회
   *$condition === array(key,value) == like
   --------------------------------------------------*/
  function getList($condition,$scale="N",$first="0")
  {
    $this->db->select('tb_purchase.*, tb_goods.seq as goods_seq, tb_asinfo.seq as asinfo_seq, tb_goods.price as price, tb_goods.stock as stock, tb_goods.c24_origin_place_value, tb_goods.floor, c24_display');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype'],true);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['saccount_conf'])) $this->db->where("tb_purchase.account_conf", $condition['saccount_conf']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
    $this->db->order_by('seq', 'DESC');
    $result = $this->db->get()->result();
    // echo $this->db->last_query(); exit;
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
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['saccount_conf'])) $this->db->where("tb_purchase.account_conf", $condition['saccount_conf']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
    $result = $this->db->get()->row();
    return $result->cnt;
  }
  
  function copy($seq, $pcode){
    $qry  = "insert into tb_purchase (`pcode`, `onlineyn`, seller, sellerphone, pdate, `type`, method, kind, modelname, pprice, `class`, paymethod, account, `note`, manager, astype, `pbrand_seq`) ";
    $qry .= "(select '".$pcode."', `onlineyn`, seller, sellerphone, pdate, `type`, method, kind, modelname, pprice, `class`, paymethod, account, `note`, manager, astype, `pbrand_seq` from tb_purchase where seq = '".$seq."' )";
    return $this->db->query($qry);
  }

  /*-------------------------------------------------
   *DB delete (delyn = 'N')
   --------------------------------------------------*/
   function deleteList($seqs)
   {
      $this->db->where('seq in ('.$seqs.')', NULL, FALSE);
      $this->db->delete('tb_purchase');
   }

  /*-------------------------------------------------
   *테이블 insert
   --------------------------------------------------*/
   function insertList($data)
   {
      $this->db->insert('tb_purchase', $data);
   }

   /*-------------------------------------------------
    *테이블 update
    --------------------------------------------------*/
    function updateList($data, $seq)
    {
       $this->db->where('seq', $seq);
       $this->db->update('tb_purchase', $data);
    }


   /*-------------------------------------------------
   *게시물 내용 조회
   --------------------------------------------------*/
   function getView($seq)
   {
      $this->db->select('*');
      $this->db->from('tb_purchase');
      $this->db->where('seq', $seq);

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
   
   function getInfoFromCode2($pcode)
   {
    //tb_goods 조인
    $this->db->select('tb_purchase.seq as seq, tb_purchase.pdate as pdate, kind, modelname, pprice, goods_price as price, method, `class`, selfcode, stock');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->where('pcode', $pcode);
     
    $result = $this->db->get()->row();
     
    return $result;
   }
   
   function getInfoFromCode3($pcode)
   {
    //tb_goods 조인
    $this->db->select('tb_purchase.astype as astype, tb_purchase.modelname as modelname, tb_purchase.seq as seq, (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb, tb_purchase.pdate as pdate, tb_purchase.pdate as pdate, seller, sellerphone, tb_purchase.reference, tb_purchase.guarantee, tb_purchase.note, (select buyer from tb_trade where tb_trade.purchase_seq = tb_purchase.seq) as trade_buyer', false);
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->where('pcode', $pcode);
     
    $result = $this->db->get()->row();
     
    return $result;
   }

   function getInfoFromCode4($pcode)
   {
    //tb_goods 조인
    $this->db->select('reason, tb_purchase.astype as astype, tb_purchase.modelname as modelname, tb_purchase.seq as seq, (select concat(`filepath`,`realfilename`) as thumb from tb_goods_img where tb_goods.seq = tb_goods_img.goods_seq order by represent limit 1) as thumb, tb_purchase.pdate as pdate, tb_purchase.pdate as pdate, seller, sellerphone, tb_purchase.reference, tb_purchase.guarantee, tb_purchase.note, (select buyer from tb_trade where tb_trade.purchase_seq = tb_purchase.seq) as trade_buyer,buyerphone', false);
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_trade', 'tb_purchase.seq = tb_trade.purchase_seq', 'left');
    $this->db->where('pcode', $pcode);
     
    $result = $this->db->get()->row();
     
    return $result;
   }
   
   function getPurchaseprice1($condition){
    $this->db->select('sum(tb_purchase.pprice) as pprice');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
        if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
        $skeyword_cond .= $stype." like '%".$v."%'";
      }
      $this->db->where($skeyword_cond, NULL, FALSE);
    }
    $this->db->where(" (type = '매입' or type = '교환+매입') ", NULL, FALSE);
    //echo $this->db->last_query();
    return $this->db->get()->row();
   }
   
   function getPurchaseprice2($condition){
    $this->db->select('sum(tb_purchase.pprice) as pprice');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
        if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
        $skeyword_cond .= $stype." like '%".$v."%'";
      }
      $this->db->where($skeyword_cond, NULL, FALSE);
    }
    $this->db->where('type', '위탁');
    //echo $this->db->last_query();
    return $this->db->get()->row();
   }
   
   function brandupdate($purchase_seq, $pbrand_seq){
    $this->db->query(" update tb_purchase set pbrand_seq = '".$pbrand_seq."' where seq = '".$purchase_seq."' ");
   }
   
   //등록수량
   function getOnlineyData1($condition){
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'Y');
       return $this->db->get()->row();
   }
   
   //등록된매입금액
   function getOnlineyData2($condition){
       $this->db->select('sum(pprice) as pprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'Y');
       $this->db->where(" (type = '매입' or type = '교환+매입') ", NULL, FALSE);
       return $this->db->get()->row();
   }
   
   //등록된위탁금액
   function getOnlineyData3($condition){
       $this->db->select('sum(pprice) as pprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'Y');
       $this->db->where('type', '위탁');
       return $this->db->get()->row();
   }
   
   //미등록수량
   function getOnlinenData1($condition){
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'N');
       return $this->db->get()->row();
   }
   
   //미등록된매입금액
   function getOnlinenData2($condition){
       $this->db->select('sum(pprice) as pprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'N');
       $this->db->where(" (type = '매입' or type = '교환+매입') ", NULL, FALSE);
       return $this->db->get()->row();
   }
   
   //미등록된위탁금액
   function getOnlinenData3($condition){
       $this->db->select('sum(pprice) as pprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'N');
       $this->db->where('type', '위탁');
       return $this->db->get()->row();
   }
   
   //매입, 위탁, 기타 갯수
   function getTypeCnt($condition, $type){
       $this->db->select('count(*) as cnt');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
       $this->db->where('type', $type);
       $result = $this->db->get()->row();
       return $result->cnt;
   }

   function getPurchasetotsellprise($condition){
    $this->db->select('sum(tb_goods.price) as price');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['sstock'])) $this->db->where("tb_goods.stock", $condition['sstock']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
    //echo $this->db->last_query();
    return $this->db->get()->row();
   }

   function getstocktotpriceY($condition){
    $this->db->select('sum(goods_price) as price');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    $this->db->where("tb_goods.stock", 'Y');
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
    // $this->db->get()->row();
    // echo $this->db->last_query();
    return $this->db->get()->row();
   }

   function getstocktotpriceN($condition){
    $this->db->select('sum(goods_price) as price');
    $this->db->from('tb_purchase');
    $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
    $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
    if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
    if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
    if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
    if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
    if(isset($condition['sbrand'])) $this->db->where("tb_purchase.pbrand_seq", $condition['sbrand']);
    if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
    if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
    if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
    if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
    $this->db->where("tb_goods.stock", 'N');
    if(isset($condition['smmpricecol'])) {
      $pcol = $condition['smmpricecol'];
      if($pcol == 'price') $pcol = 'tb_goods.price';
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
    //echo $this->db->last_query();
    return $this->db->get()->row();
   }

   function updateGoodsprice($goods_price, $seq){
    $data = array('price'=>$goods_price);
    $this->db->where('purchase_seq', $seq);
    $this->db->update('tb_goods', $data);
   }

   function updateGoodsstock($goods_stock, $seq){
    $data = array('stock'=>$goods_stock);
    $this->db->where('purchase_seq', $seq);
    $this->db->update('tb_goods', $data);
   }

   function updatePurchaseGoodsprice($price,$purchase_seq){
    $data = array('goods_price'=>$price);
    $this->db->where('seq', $purchase_seq);
    $this->db->update('tb_purchase', $data);
   }

   function updatePurchaseGoodsstock($stock,$purchase_seq){
    $data = array('goods_stock'=>$stock);
    $this->db->where('seq', $purchase_seq);
    $this->db->update('tb_purchase', $data);
   }

   //총교환금액
    function getType3Data($condition){
       $this->db->select('sum(exprice) as exprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where(" (type = '교환' or type = '교환+매입') ", NULL, FALSE);
       return $this->db->get()->row();
    }

    //등록교환금액
    function getType4Data($condition){
       $this->db->select('sum(exprice) as exprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'Y');
       $this->db->where(" (type = '교환' or type = '교환+매입') ", NULL, FALSE);
       return $this->db->get()->row();
    }

    //미등록교환금액
    function getType5Data($condition){
       $this->db->select('sum(exprice) as exprice');
       $this->db->from('tb_purchase');
       $this->db->join('tb_goods', 'tb_purchase.seq = tb_goods.purchase_seq and tb_goods.purchase_seq <> 0', 'left');
       $this->db->join('tb_asinfo', 'tb_purchase.seq = tb_asinfo.purchase_seq and tb_asinfo.purchase_seq <> 0', 'left');
       if(isset($condition['ssdate'])) $this->db->where("pdate >= '".$condition['ssdate']." 00:00:00'", NULL, FALSE);
       if(isset($condition['sedate'])) $this->db->where("pdate <= '".$condition['sedate']." 23:59:59'", NULL, FALSE);
       if(isset($condition['sstype'])) $this->db->where('type', $condition['sstype']);
       if(isset($condition['splace'])) $this->db->where("tb_purchase.place", $condition['splace']);
       if(isset($condition['smanager'])) $this->db->where("tb_purchase.manager", $condition['smanager']);
       if(isset($condition['sonlineyn'])) $this->db->where('onlineyn', $condition['sonlineyn']);
       if(isset($condition['sbrand'])) $this->db->where("tb_goods.brand_seq", $condition['sbrand']);
       if(isset($condition['skind'])) $this->db->where("tb_purchase.kind", $condition['skind']);
       if(isset($condition['spurchase_method'])) $this->db->where("tb_purchase.method", $condition['spurchase_method']);
       if(isset($condition['spaymethod'])) $this->db->where("tb_purchase.paymethod", $condition['spaymethod']=='empty' ? '' : $condition['spaymethod']);
       if(isset($condition['smmpricecol'])) {
          $pcol = $condition['smmpricecol'];
          if($pcol == 'price') $pcol = 'tb_goods.price';
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
               if($skeyword_cond != '') $skeyword_cond = $skeyword_cond.' or ';
               $skeyword_cond .= $stype." like '%".$v."%'";
           }
           $this->db->where($skeyword_cond, NULL, FALSE);
       }
       $this->db->where('onlineyn', 'N');
       $this->db->where(" (type = '교환' or type = '교환+매입') ", NULL, FALSE);
       return $this->db->get()->row();
    }

    function updateReason($purchase_seq, $reason)
    {
      $data = ['reason' => $reason];
      $this->db->where('seq', $purchase_seq);
      $this->db->update('tb_purchase', $data);
    }
}
