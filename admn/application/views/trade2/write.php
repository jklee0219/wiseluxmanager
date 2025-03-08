<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/trade.css?<?=time()?>">
   <script>
   var limit_file_size = <?=LIMIT_FILE_SIZE?>;
   var attch_file_ext = '<?=IMAGE_FILE_EXT?>';
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/trade_write.js?<?=time()?>"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/trade2/writeproc?<?=$param?>" method="post" role="form" name="boardForm">
				<input type="hidden" name="purchase_seq" value="">
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="pcode">상품코드 검색</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' style="width:100px;display:inline-block;" maxlength="7" class='form-control input-sm' autocomplete="off" name='pcode' id="pcode">
                  		<button type="button" onclick="getPurchaseInfoFromCode()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
               		</div>
            	</div>
            	
            	<!-- 매입 및 상품 정보 시작 -->
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_pdate">매입날짜</label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" disabled="disabled" name='purchase_pdate' id='purchase_pdate' class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_kind">종류</label>
               		<div class="col-sm-3 pt2">
               			<select disabled="disabled" name="purchase_kind" id="purchase_kind" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_kind as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='purchase_modelname'>모델명</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' disabled="disabled" id='purchase_modelname' name='purchase_modelname' value="" maxlength="680">
               		</div>
            	</div>
            	<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='purchase_pprice'>위탁지급액</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' disabled="disabled" id='purchase_pprice' name='purchase_pprice' value="" maxlength="20">
               		</div>
            	</div>
            	<?php }?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='goods_price'>판매예정금액</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' disabled="disabled" id='goods_price' name='goods_price' value="" maxlength="20">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='purchase_method'>매입방법</label>
               		<div class="col-sm-3 pt2">
               			<select name="purchase_method" id="purchase_method" disabled="disabled" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_method as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_class">매입등급</label>
               		<div class="col-sm-3 pt2">
               			<select name="purchase_class" id="purchase_class" disabled="disabled" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_class as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='goods_selfcode'>제품코드</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm' disabled="disabled" id='goods_selfcode' name='goods_selfcode' value="" maxlength="10">
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="stock">재고여부</label>
					<div class="col-sm-3 pt2">
						<select name="goods_stock" id="goods_stock" disabled="disabled" class="form-control input-sm">
							<option value="Y">보유</option>
							<option value="N">없음</option>
	                  	</select>
	               	</div>
            	</div>
            	<!-- 매입 및 상품 정보 종료 -->
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="selldate">판매일자<?=$required_mark?></label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" name="selldate" id="selldate" class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="selltype">판매구분<?=$required_mark?></label>
               		<div class="col-sm-3 pt2">
               			<select name="selltype" id="selltype" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($trade_selltype as $v){ ?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php } ?>
	                  	</select>
               		</div>
            	</div>
            	<!-- 
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="amount">총결제금액</label>
               		<div class="col-sm-4 pt2">
                  		<input type='text' class='form-control input-sm numcomma' name="amount" id='amount' value="">
               		</div>
            	</div>
            	 -->
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="paymentprice">결제금액</label>
                  <div class="col-sm-4 pt2">
                        <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='paymentprice' id='paymentprice' maxlength="20">
                  </div>
               </div>

               <div class="form-group">
                  <label class="col-sm-3 control-label" for="payment_price_1">현금</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='payment_price_1' id='payment_price_1' maxlength="20">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="payment_price_2">무통장입금</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='payment_price_2' id='payment_price_2' maxlength="20">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="payment_price_3">카드단말기</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='payment_price_3' id='payment_price_3' maxlength="20">
                  </div>
               </div>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="payment_price_4">온라인카드</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='payment_price_4' id='payment_price_4' maxlength="20">
                  </div>
               </div>
			   <!--
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="npay">N페이</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='npay' id='npay' maxlength="20">
                  </div>
               </div>
			   -->
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="payment_price_5">기타</label>
                  <div class="col-sm-4 pt2">
                     <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='payment_price_5' id='payment_price_5' maxlength="20">
                  </div>
               </div>
               
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="sellprice">정산금액<?=$required_mark?></label>
					<div class="col-sm-4 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='sellprice' id='sellprice' maxlength="20">
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="buyer">구매자</label>
					<div class="col-sm-3 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name='buyer' id='buyer' maxlength="33">
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="buyerphone1">연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:50px;display:inline-block;" name='buyerphone1' id="buyerphone1" maxlength="4" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='buyerphone2' id="buyerphone2" maxlength="4" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='buyerphone3' id="buyerphone3" maxlength="4" placeholder="">
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="dc">적립금사용</label>
					<div class="col-sm-3 pt2">
						<input type='text' class='form-control input-sm onlynum' autocomplete="off" name='dc' id='dc' maxlength="10">
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="sellerinfo">구매자정보</label>
					<div class="col-sm-7 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name=sellerinfo id='sellerinfo' maxlength="50">
	               	</div>
            	</div>

               <div class="form-group">
                     <label class="col-sm-3 control-label" for="senddate">발송일자</label>
                     <div class="col-sm-3 pt2">
                        <div class="input-group date">
                      <input type="text" name="senddate" class="form-control input-sm" autocomplete="off" placeholder="" value="">
                      <div class="input-group-addon input-sm">
                          <span class="glyphicon glyphicon-calendar"></span>
                      </div>
                  </div>
                     </div>
               </div>
               
               <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
               <div class="form-group">
               <label class="col-sm-3 control-label" for="account">경리팀확인</label>
               <div class="col-sm-7 pt2">
                  <label class="account_conf_wrap">아니오 <input name="account_conf" type="radio" value="N" checked="checked"></label>
                  <label class="account_conf_wrap">예 <input name="account_conf" type="radio" value="Y"></label>
				  <label class="account_conf_wrap">위탁확인 <input name="account_conf" type="radio" value="C"></label>
               </div>
               </div>
               <?php }?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:100px" name="note" id="note"></textarea>
               		</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/trade2?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
