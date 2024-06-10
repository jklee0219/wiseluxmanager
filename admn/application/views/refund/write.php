<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/refund.css?<?=time()?>">
   <script>
   var qs = "<?=$param?>";
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/refund_write.js?<?=time()?>"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/refund/writeproc?<?=$param?>" method="post" role="form" name="boardForm">
			
				<input type="hidden" name="purchase_seq" value="">
				<input type="hidden" name="thumb" value="">
				<input type="hidden" name="modelname" value="">  
				<input type="hidden" name="selltype" value="">
				<input type="hidden" name="paymethod" value="">
				<input type="hidden" name="brand_seq" value="">
				<input type="hidden" name="kind" value="">
				<input type="hidden" name="amount" value="">
				<input type="hidden" name="price" value="">
                <input type="hidden" name="paymentprice" value="">
				
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="pcode">상품코드 검색</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' style="width:100px;display:inline-block;" maxlength="7" class='form-control input-sm' autocomplete="off" name='pcode' id="pcode">
                  		<button type="button" style="vertical-align:top" onclick="getPurchaseCode()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
               		</div>
            	</div>

            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="selldate">판매일자</label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" readonly="readonly" name='selldate' id='selldate' class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="buyer">구매자</label>
               		<div class="col-sm-3 pt2">
	                  	<input type='text' disabled="disabled" class='form-control input-sm' autocomplete="off" name='buyer' id='buyer' maxlength="10">
               		</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='buyerphone1'>구매자연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:50px;display:inline-block;" name='buyerphone1' id="buyerphone1" maxlength="4" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:60px;display:inline-block;" name='buyerphone2' id="buyerphone2" maxlength="4" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:60px;display:inline-block;" name='buyerphone3' id="buyerphone3" maxlength="4" placeholder="">
               		</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="applydate">반품신청일</label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" readonly="readonly" name="applydate" id="applydate" class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="completedate">반품완료일</label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" readonly="readonly" name="completedate" id="completedate" class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
            	
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="process">처리결과</label>
					<div class="col-sm-2 pt2">
                  		<select name="process" id="process" class="form-control input-sm">
							<option value="">선택하세요</option>
							<option value="Y">승인</option>
							<option value="N">거부</option>
	                  	</select>
	               	</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="reason">반품사유</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name="reason" id="reason"></textarea>
               		</div>
            	</div>
            	<?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
               <div class="form-group">
               <label class="col-sm-3 control-label" for="account">경리팀확인</label>
               <div class="col-sm-7 pt2">
                  <label class="account_conf_wrap">아니오 <input name="account_conf" type="radio" value="N" checked="checked"></label>
                  <label class="account_conf_wrap">예 <input name="account_conf" type="radio" value="Y"></label>
                     </div>
               </div>
               <?php }?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name="note" id="note"></textarea>
               		</div>
            	</div>
            	
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/refund?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
