<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/trade.css?<?=time()?>">
   <script src="/admn/js/trade2.js?<?=time()?>"></script>
   <script>
   var qs = "<?=$param?>";
   </script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title">
			<script>document.write($('.nav li .active').text());</script>	
		</div>

		<div class="content_list">
		
			<div class="amount_info_wrap2">
    			<table>
    				<tr>
    					<th></th>
    					<?php foreach($trade_selltype as $v){ ?>
    					<th><?=$v?></th>
    					<?php }?>
    				</tr>
    				<tr>
    					<th>판매예정금액</th>
    					<?php foreach($totSelltype as $v){ ?>
    					<td><?=number_format($v->total_price)?></td>
    					<?php }?>
    				</tr>
    				<tr>
    					<th>결제금액</th>
    					<?php foreach($totSelltype as $v){ ?>
    					<td><?=number_format($v->total_paymentprice)?></td>
    					<?php }?>
    				</tr>
    				<tr>
    					<th>정산금액</th>
    					<?php foreach($totSelltype as $v){ ?>
    					<td><?=number_format($v->total_sellprice)?></td>
    					<?php }?>
    				</tr>
    			</table>
    		</div>
    		
         	<div class="content_top">
            	<div>
            		<div class="amount_info_wrap">
            			<table>
            				<tr>
            					<td class="bg">자료검색</td>
            					<td class="txt"><?=number_format($total_cnt)?>건</td>
            					<td class="bg"></td>
            					<td class="txt"></td>
            				</tr>
            				<tr>
            					<td class="bg">총판매예정금액</td>
            					<td class="txt"><?=number_format($total_price)?>원</td>
            					<td class="bg">총정산금액</td>
            					<td class="txt"><?=number_format($total_sellprice)?>원</td>
            				</tr>
            				<tr>
            					<td class="bg">총결제금액</td>
            					<td class="txt"><?=number_format($total_paymentprice)?>원</td>
            					<td class="bg">총판매지급액</td>
                                <td class="txt"><?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?><?=number_format($total_pprice)?>원<?php }?></td>
            				</tr>
							
					     <tr>
                           <td class="bg2">현금</td>
                           <td class="txt"><?=number_format($payment_price_1_sum)?>원</td>
                           <td class="bg2">온라인카드</td>
                           <td class="txt"><?=number_format($payment_price_4_sum)?>원</td>
                        </tr>
                        <tr>
                           <td class="bg2">무통장입금</td>
                           <td class="txt"><?=number_format($payment_price_2_sum)?>원</td>
                           <td class="bg2">기타</td>
                           <td class="txt"><?=number_format($payment_price_5_sum)?>원</td> 

                        </tr>
                        <tr>
                           <td class="bg2">카드단말기</td>
                           <td class="txt"><?=number_format($payment_price_3_sum)?>원</td>
						   <!--임시사용중지
                           <td class="bg2">N페이</td>
                           <td class="txt"><?=number_format($npay_sum)?>원</td>
						   -->
                        </tr>
            			</table>
            		</div>

                  <div class="amount_info_wrap3">
                     <table>
 
                     </table>
                  </div>

               		<form name="searchFrm" action="/admn/trade2" method="get">
               			<div style="margin-bottom:6px">
               				<select class="form-control input-sm" name="smmpricecol" id="smmpricecol">
	                  			<option value="price" <?=($smmpricecol=='price') ? "selected='selected'" : ""?>>판매예정금액</option>
	                  			<option value="paymentprice" <?=($smmpricecol=='paymentprice') ? "selected='selected'" : ""?>>결제금액</option>
	                  			<option value="sellprice" <?=($smmpricecol=='sellprice') ? "selected='selected'" : ""?>>정산금액</option>
								<?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
								<option value="pprice" <?=($smmpricecol=='pprice') ? "selected='selected'" : ""?>>위탁지급액</option>
								<?php } ?>
							</select>
							<input type="text" class="form-control input-sm" autocomplete="off" name="sminprice" id="sminprice" value="<?=$sminprice?>" placeholder="20000"> <span>~</span>
							<input type="text" class="form-control input-sm" autocomplete="off" name="smaxprice" id="smaxprice" value="<?=$smaxprice?>" placeholder="300000">
               			</div>
               			<div style="margin-bottom:6px">
                  			<div class="startdate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="판매일 시작" value="<?=$ssdate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>~
							<div class="enddate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="판매일 끝" value="<?=$sedate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>
							<select class="form-control input-sm" name="sselltype">
								<option value="">판매구분(전체)</option>
								<?php foreach($trade_selltype as $v){ ?>
	                  			<option value="<?=$v?>" <?=($sselltype==$v) ? "selected='selected'" : ""?>><?=$v?></option>
	                  			<?php } ?>
							</select>
							<select class="form-control input-sm" name="sstock">
								<option value="">재고선택(전체)</option>
	                  			<option value="Y" <?=($sstock=='Y') ? "selected='selected'" : ""?>>보유</option>
	                  			<option value="N" <?=($sstock=='N') ? "selected='selected'" : ""?>>없음</option>
							</select>
							<select class="form-control input-sm" name="sbrand">
								<option value="">브랜드(전체)</option>
								<?php foreach($brand_list as $v){ ?>
	                  			<option value="<?=$v->seq?>" <?=($sbrand==$v->seq) ? "selected='selected'" : ""?>><?=$v->name?></option>
	                  			<?php } ?>
							</select>
							<select class="form-control input-sm" name="skind">
								<option value="">종류(전체)</option>
								<?php foreach($purchase_kind as $v){ ?>
	                  			<option value="<?=$v?>" <?=($skind==$v) ? "selected='selected'" : ""?>><?=$v?></option>
	                  			<?php } ?>
							</select>
						</div>
						<select class="form-control input-sm" name="spayment_price">
							<option value="">결제수단(전체)</option>
							<option value="payment_price_1"<?=($spayment_price=='payment_price_1') ? " selected='selected'" : ""?>>현금</option>
							<option value="payment_price_2"<?=($spayment_price=='payment_price_2') ? " selected='selected'" : ""?>>무통장입금</option>
							<option value="payment_price_3"<?=($spayment_price=='payment_price_3') ? " selected='selected'" : ""?>>카드단말기</option>
							<option value="payment_price_4"<?=($spayment_price=='payment_price_4') ? " selected='selected'" : ""?>>온라인카드</option>
                            <option value="npay"<?=($spayment_price=='npay') ? " selected='selected'" : ""?>>N페이</option>
							<option value="payment_price_5"<?=($spayment_price=='payment_price_5') ? " selected='selected'" : ""?>>기타</option>
						</select>
						<select class="form-control input-sm" name="saccount_conf">
							<option value="">경리팀확인(전체)</option>
							<option value="N"<?=($saccount_conf=='N') ? " selected='selected'" : ""?>>아니요</option>
							<option value="Y"<?=($saccount_conf=='Y') ? " selected='selected'" : ""?>>예</option>
						</select>
						<select class="form-control input-sm" name="spaymethod2">
							<option value="">지급방법</option>
							<option value="현금" <?=($spaymethod2=='현금') ? "selected='selected'" : ""?>>현금</option>
                  			<option value="입금" <?=($spaymethod2=='입금') ? "selected='selected'" : ""?>>입금</option>
                  			<option value="입금(위탁정산완료)" <?=($spaymethod2=='입금(위탁정산완료)') ? "selected='selected'" : ""?>>입금(위탁정산완료)</option>
                  			<option value="empty" <?=($spaymethod2=='empty') ? "selected='selected'" : ""?>>없음</option>
						</select>
						<select class="form-control input-sm" name="splace">
							<option value="">지점명(전체)</option>
							<?php foreach($goods_place as $v){ ?>
							<option value="<?=$v?>" <?=($splace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
							<?php } ?>
						</select>
						<select class="form-control input-sm" name="stype">
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
							<option value="buyer" <?=($stype=='buyer') ? "selected='selected'" : ""?>>구매자</option>
							<option value="buyerphone" <?=($stype=='buyerphone') ? "selected='selected'" : ""?>>연락처</option>
                            <option value="tb_trade.note" <?=($stype=='tb_trade.note') ? "selected='selected'" : ""?>>비고</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./trade2/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>번호</th>
	                     <th>상품코드</th>
	                     <th>판매구분</th>
						 <th>사진</th>
	                     <th>판매일자</th>
	                     <th>종류</th>
	                     <th>지점명(층)</th>
	                     <th width="15%">모델명</th>
	                     <th>판매예정금액</th>
	                     <th>결제금액</th>
	                     <th>정산금액</th>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
	                     <th>위탁지급액</th>
	                     <?php } ?>
	                     <th>재고</th>
	                     <th>구매자성함</th>
	                     <th>구매자연락처</th>
	                     <th></th>
	                  </tr>
	               </thead>
	               <tbody>
	               <?php
	               if($board_list)
	               {
	                  foreach($board_list as $k => $v)
	                  {
	                     $seq = $v->seq;
	                     $pcode = $v->pcode;
	                     $selltype = $v->selltype;
	                     $type = $v->type;
	                     $thumb = $v->thumb;
	                     $pdate = $v->pdate;
	                     $selldate = $v->selldate;
	                     $kind = $v->kind;
	                     $modelname = $v->modelname;
	                     $price = $v->price;
	                     $price = number_format($price);
	                     $sellprice = $v->sellprice;
	                     $sellprice = number_format($sellprice);
	                     $paymethod = $v->paymethod;
	                     $paymethod2 = $v->paymethod2;
	                     $pprice = $v->pprice;
	                     $pprice = number_format($pprice);
	                     $stock = ($v->stock=='Y') ? '있음' : '없음';
	                     $goods_seq = $v->goods_seq;
	                     $asinfo_seq = $v->asinfo_seq;
	                     $buyer = $v->buyer;
	                     $buyerphone = $v->buyerphone;
	                     $paymentprice = $v->paymentprice;
	                     $paymentprice = number_format($paymentprice);
	                     $account_conf = $v->account_conf;

	                     $c24_origin_place_value = $v->c24_origin_place_value ? $v->c24_origin_place_value : '';
	                     $floor = $v->floor;
	                     if(!$floor) $floor = '1F';
	                     $c24_origin_place_str = '';
	                     if($c24_origin_place_value){
	                     	$c24_origin_place_str = $c24_origin_place_value.'('.$floor.')';
	                     }

	                     $class_str = '';
						 if($stock == '없음'){
	                     	$class_str = ' class="active"';
	                     }

	                     if($stock == '없음' && ($paymethod2 == '현금' || $paymethod2 == '입금')){
	                     	$class_str = ' class="active"';
	                     }

	                     if($stock == '없음' && $paymethod2 == '입금(위탁정산완료)'){
	                     	$class_str = ' class="active4"';
	                     }

	                     if($account_conf == 'Y') $class_str = ' class="active5"';
						 if($account_conf == 'C') $class_str = ' class="active6"';

	                     $payment_price_1 = $v->payment_price_1;
	                     $payment_price_1 = number_format($payment_price_1);
	                     $payment_price_2 = $v->payment_price_2;
	                     $payment_price_2 = number_format($payment_price_2);
	                     $payment_price_3 = $v->payment_price_3;
	                     $payment_price_3 = number_format($payment_price_3);
	                     $payment_price_4 = $v->payment_price_4;
	                     $payment_price_4 = number_format($payment_price_4);
	                     $payment_price_5 = $v->payment_price_5;
	                     $payment_price_5 = number_format($payment_price_5);

	                     //210204 입금(위탁정산완료)인 경우 위탁지급액 마킹
	                     $pprice_mark = $paymethod2=='입금(위탁정산완료)' ? 'class="chking"' : '';

                        $pdate_arr = explode(' ',$pdate);
                        $pdate_str = $pdate_arr[0];

                        $c24_display = $v->c24_display;
	                  ?>
	                  <tr<?=$class_str?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><?=$selltype?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$selldate?><br/><span style="color:#437BB5;font-weight:bold"><?=$pdate_str?></span></td>
	                     <td><?=$kind?></td>
	                     <td><?=$c24_origin_place_str?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <td><?=$price?></td>
	                     <td>
	                     	<?=$paymentprice?>
	                     	<?php if($payment_price_1){ ?><p class="smf">현금 <?=$payment_price_1?></p><?php } ?>
	                     	<?php if($payment_price_2){ ?><p class="smf">무통장입금 <?=$payment_price_2?></p><?php } ?>
	                     	<?php if($payment_price_3){ ?><p class="smf">카드단말기 <?=$payment_price_3?></p><?php } ?>
	                     	<?php if($payment_price_4){ ?><p class="smf">온라인카드 <?=$payment_price_4?></p><?php } ?>
	                     	<?php if($payment_price_5){ ?><p class="smf">기타 <?=$payment_price_5?></p><?php } ?>
	                     </td>
	                     <td><?=$sellprice?></td>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
	                     <td <?=$pprice_mark?>><?=$pprice?></td>
	                     <?php } ?>
	                     <td><?=$stock?></td>
	                     <td><?=$buyer?></td>
	                     <td><?=$buyerphone?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/trade2/modify?seq=<?=$seq?>&<?=$param?>'" style="width:83px" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 거래수정</button><br/>
	                     	<?php if($goods_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/goods/modify?seq=<?=$goods_seq?>'" style="margin-top:2px;width:83px" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 상품수정</button><br/>
	                     	<?php } ?>
	                     	<?php if($asinfo_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/asinfo/modify?seq=<?=$asinfo_seq?>'" style="margin-top:2px;width:83px" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> AS수정</button>
	                     	<?php } ?>
	                     </td>
	            	  </tr>
	               <?php
	                  }
	               }
	               ?>
	               </tbody>
	            </table>
         	</form>
			
		 	<iframe name="hiddenFrm" src=""></iframe>
		
	         <div class="content_bottom">
	            <div class="content_left"></div>
	            <div class="content_middle"><?=$paging_html?></div>
	         </div>

      	</div>
      
   	</div>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
