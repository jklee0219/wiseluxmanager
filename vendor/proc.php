<?php 
session_start();
include 'dbconn.php';

$type = isset($_POST['type']) ? trim($_POST['type']) : '';

if($type == 'confirm'){

   $nm = isset($_POST['nm']) ? trim($_POST['nm']) : '';
   $ph1 = isset($_POST['ph1']) ? trim($_POST['ph1']) : '';
   $ph2 = isset($_POST['ph2']) ? trim($_POST['ph2']) : '';
   $ph3 = isset($_POST['ph3']) ? trim($_POST['ph3']) : '';
   $pw = isset($_POST['pw']) ? trim($_POST['pw']) : '';

   $nm = addslashes($nm);
   $ph1 = addslashes($ph1);
   $ph2 = addslashes($ph2);
   $ph3 = addslashes($ph3);
   $pw = addslashes($pw);

   if($nm != '' && $ph1 != '' && $ph2 != '' && $ph3 != '' && $pw != ''){

      $ph = $ph1.'-'.$ph2.'-'.$ph3;
      $qry = " select count(*) from tb_purchase where seller = '".$nm."' and sellerphone = '".$ph."' and birth = '".$pw."'; ";
      $chkcnt = getQueryCount($qry);

      if($chkcnt > 0){

         $_SESSION['seller'] = $nm;
         $_SESSION['sellerphone'] = $ph;
         $_SESSION['birth'] = $pw;

         header("Location: /vendor/list.php");

      }else{

         echo '<script> alert("올바르지 않은 정보 입니다."); history.back(-1) </script>';

      }
   }

}else if($type == 'list'){

   $data = isset($_POST['data']) ? trim($_POST['data']) : '';

   $data_arr = explode('@', $data);
   foreach($data_arr as $v){

      $val_arr = explode('|', $v);

      if(count($val_arr) == 2){
         $req_price = str_replace(',', '', $val_arr[1]);
         $qry = " update tb_goods set req_price = '".$req_price."', request_cnt = request_cnt+1, request_yn = 'Y', request_date = '".date('Y-m-d H:i:s')."' where seq = '".$val_arr[0]."' ";
         
         mysqli_query($dbconn, $qry);
      }

   }

}else if($type == 'logout'){

   $_SESSION['seller'] = '';
   $_SESSION['sellerphone'] = '';
   $_SESSION['birth'] = '';

}
?>