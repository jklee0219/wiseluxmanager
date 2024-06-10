<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/stockcheck.css">
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/stockcheck_modify.js?<?=time()?>"></script>
</head>
<body>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

   <div id="content">
      <div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

      <div class="content_write">

			<form class="form-horizontal" action="/admn/stockcheck/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
				<input type="hidden" name="seq" value="<?=$seq?>">
				<input type="hidden" name="purchase_seq" value="<?=$view->purchase_seq?>">
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="pcode">상품코드 검색</label>
               		<div class="col-sm-9 pt2">
                  		<p class="distxt"><?=$view->pcode?></p>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label">거래번호</label>
               		<div class="col-sm-3 pt2">
               			<p class="distxt"><?=$view->purchase_seq?></p>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_seller">판매자</label>
               		<div class="col-sm-3 pt2">
               			<input type='text' class='form-control input-sm' name='purchase_seller' value="<?=$view->seller?>" maxlength="10">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_modelname">모델명</label>
               		<div class="col-sm-9 pt2">
               			<input type='text' class='form-control input-sm' name='purchase_modelname' value="<?=$view->modelname?>" maxlength="680">
               		</div>
            	</div>
            	<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_pprice">매입거래가격</label>
               		<div class="col-sm-3 pt2">
               			<input type='text' class='form-control input-sm numcomma' name='purchase_pprice' value="<?=$view->pprice?>" maxlength="20">
               		</div>
            	</div>
            	<?php } ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_pdate">매입일자</label>
               		<div class="col-sm-3 pt2">
               			<?php
						$pdate = $view->pdate;
	               		if($pdate){
		               		$pdate = strtotime($pdate);
		               		$pdate = date('Y-m-d', $pdate);
	               		}else{
	               			$pdate = '';
	               		}
	               		?>
               			<input type='text' class='form-control input-sm' id="purchase_pdate" name='purchase_pdate' value="<?=$pdate?>" maxlength="10">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="goods_rdate">상품등록일자</label>
               		<div class="col-sm-3 pt2">
               			<?php
						$rdate = $view->rdate;
						if($rdate && strpos($rdate, '0000-00') === false){
						    $rdate = strtotime($rdate);
						    $rdate = date('Y-m-d', $rdate);
	               		}else{
	               		    $rdate = '';
	               		}
	               		?>
               			<p class="distxt" id="goods_rdate"><?=$rdate?></p>
               		</div>
            	</div>
               <div class="form-group">
                     <label class="col-sm-3 control-label" for="goods_rdate">재고검수등록일자</label>
                     <div class="col-sm-3 pt2">
                        <?php
                     $stockcheck_date = $view->stockcheck_date;
                     if($stockcheck_date && strpos($rdate, '0000-00') !== false){
                               $stockcheck_date = '';
                           }
                        ?>
                        <p class="distxt"><?=$stockcheck_date?></p>
                     </div>
               </div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="goods_price">판매예정금액</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='goods_price' maxlength="20" value="<?=$view->price?>">
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="trade_buyer">구매자</label>
					<div class="col-sm-3 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name='trade_buyer' maxlength="10" value="<?=$view->buyer?>">
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="trade_buyerphone1">연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
               			<?php
               			$buyerphone = $view->buyerphone;
               			$buyerphone = explode('-',$buyerphone);
               			$buyerphone1 = '';
               			$buyerphone2 = '';
               			$buyerphone3 = '';
               			if(count($buyerphone) == 3){
	               			$buyerphone1 = $buyerphone[0];
	               			$buyerphone2 = $buyerphone[1];
	               			$buyerphone3 = $buyerphone[2];
               			}
               			?>
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:50px;display:inline-block;" name='trade_buyerphone1' maxlength="4" value="<?=$buyerphone1?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='trade_buyerphone2' maxlength="4" value="<?=$buyerphone2?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='trade_buyerphone3' maxlength="4" value="<?=$buyerphone3?>">
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="trade_sellprice">정산금액</label>
					<div class="col-sm-4 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='trade_sellprice' value='<?=$view->sellprice?>' maxlength="20">
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="goods_stock">재고여부</label>
					<div class="col-sm-3 pt2">
						<select name="goods_stock" class="form-control input-sm">
							<option value="Y" <?=($view->stock=='Y') ? 'selected="selected"' : ''?>>보유</option>
							<option value="N" <?=($view->stock=='N') ? 'selected="selected"' : ''?>>없음</option>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:100px" name="note"><?=$view->note?></textarea>
               		</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/stockcheck?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
