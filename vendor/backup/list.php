<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
include 'loginchk.php';
include 'dbconn.php';

$qry = " select tb_purchase.*, tb_goods.price as price, tb_goods.req_price as req_price, tb_goods.seq as seq, tb_goods.c24_product_no as c24_product_no from tb_purchase left join tb_goods on tb_purchase.seq = tb_goods.purchase_seq where seller = '".$s_seller."' and sellerphone = '".$s_sellerphone."' and birth = '".$s_birth."' ";
$list = getQueryResult($qry);

?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>와이즈럭스 중고명품-위탁판매상품확인</title>
<meta charset="UTF-8">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./vendor.css?<?=time?>">
<script src="/lib/jquery/jquery-1.12.0.min.js"></script>
<script src="./jquery.number.min.js"></script>
<script src="./comma.js?v=1"></script>
<script src="./vendor.js?<?=time()?>"></script>
<body>
    <div class="list_wrapper">
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th>신청일자</th>
                    <th>지점명</th>
                    <th>모델명</th>
                    <th>위탁판매금액</th>
                    <th>판매수정금액</th>
                    <th>수수료</th>
                    <th>정산예정금액</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach($list as $v){
                	$pdate = $v['pdate'];
                    if($pdate){
						$pdate = strtotime($pdate);
						$pdate = date('Y-m-d', $pdate);
					}else{
					 	$pdate = '';
					}
					$modelname = $v['modelname'];
					$place = $v['place'];
					$price = $v['price'];
                    $comv = $price;
					$req_price = $v['req_price'];
					$price = number_format($v['price']);
					$req_price = number_format($v['req_price']);
					$c24_product_no = $v['c24_product_no'];
					$goods_link = $c24_product_no > 0 ? 'http://wiselux.co.kr/product/'.$modelname.'/'.$v['c24_product_no'] : '';
                ?>
                <tr class="fees_calc">
                    <td><?=$pdate?></td>
                    <td><?=$place?></td>
                    <td class="tal">
                    	<?php if($goods_link){ ?>
                    	<a href="<?=$goods_link?>" target="_blank"><?=$modelname?></a>
                    	<?php } else {?>
                    	<?=$modelname?>
                    	<?php } ?>
                    </td>
                    <td><?=$price?></td>
                    <td><input type="text" value="<?=$req_price?>" data-seq="<?=$v['seq']?>" data-kind="<?=$v['kind']?>" data-comv="<?=$comv?>" class="req_price numcomma"></td>
                    <td class="fees">0</td>
                    <td class="expprice">0</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="btn_wrap">
        	<button id="submit_btn" class="btn btn-primary">수정요청</button>
        	<button id="logout_btn" class="btn btn-danger">로그아웃</button>
       	</div>
    </div>
</body>