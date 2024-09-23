<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/asinfo.css?2">
   <script>
   var qs = "<?=$param?>";
   var limit_file_size = <?=LIMIT_FILE_SIZE?>;
   var attch_file_ext = '<?=ATTACH_FILE_EXT?>';
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/asinfo_modify.js?v=4"></script>
</head>
<body>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

   <div id="content">
      <div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

      <div class="content_write">

			<form class="form-horizontal" action="/admn/asinfo/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
				<input type="hidden" name="seq" value="<?=$view->seq?>">
				<input type="hidden" name="purchase_seq" value="<?=$view->purchase_seq?>">
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="pcode">상품코드 검색</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' style="width:100px;display:inline-block;" maxlength="7" class='form-control input-sm' autocomplete="off" name='pcode' id="pcode" value="<?=$view->pcode?>">
                  		<button type="button" onclick="getPurchaseInfoFromCode()" class="btn btn-success btn-sm" style="vertical-align:top"><i class="glyphicon glyphicon-search"></i> 검색</button>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label">모델명</label>
               		<div class="col-sm-6 pt2">
                  		<p id="purchase_modelname"></p>
               		</div>
            	</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for='guarantee'>구분</label>
                    <div class="col-sm-9 pt2">
                        <p style="margin-top:5px"><?=$view->type?></p>
                    </div>
                </div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label">이미지</label>
               		<div class="col-sm-9 pt2">
                  		<img src="/admn/img/noimg.gif" class="thumb" id="thumb">
                  		<p>※ 이미지는 상품목록에서만 등록/수정이 가능합니다.</p>
               		</div>
            	</div>
            	
            	<!-- 매입정보시작 -->
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
                    <label class="col-sm-3 control-label">구매자</label>
                    <div class="col-sm-3 pt2">
                        <input type='text' class='form-control input-sm' disabled="disabled" id='trade_buyer' name='trade_buyer' value="" maxlength="10">
                    </div>
                </div>
				<!--
            	<div class="form-group">
               		<label class="col-sm-3 control-label">판매자</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm' disabled="disabled" id='purchase_seller' name='purchase_seller' value="" maxlength="10">
               		</div>
            	</div>	
				-->
				<!--
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="purchase_sellerphone1">구매자연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:50px;display:inline-block;" name='purchase_sellerphone1' id="purchase_sellerphone1" maxlength="3" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:60px;display:inline-block;" name='purchase_sellerphone2' id="purchase_sellerphone2" maxlength="4" placeholder=""> -
                  		<input type='text' class='form-control input-sm onlynum' disabled="disabled" autocomplete="off" style="width:60px;display:inline-block;" name='purchase_sellerphone3' id="purchase_sellerphone3" maxlength="4" placeholder="">
               		</div>
            	</div>
				-->
                <div class="form-group cus">
                    <label class="col-sm-3 control-label" for='astype'>AS요청</label>
                    <div class="col-sm-9 pt2">
                        <?php foreach($goods_astype as $v){ ?>
                        <label><input type='checkbox' class='form-control input-sm' name='astype[]' id='astype' value="<?=$v?>"><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
                        <?php } ?>
                        <br/>
                        <label><input type='checkbox' class='form-control input-sm' name='astype_etc_chk' value="기타"><p class="checkboxtxt">기타</p><input type="text" disabled="disabled" name="astype_etc_txt" class='form-control input-sm'></label>
                    </div>
                </div>		
            	<div class="form-group cus">
					<label class="col-sm-3 control-label" for='note'>참고사항</label>
					<div class="col-sm-9 pt2">
						<?php foreach($goods_note as $v){ ?>
						<label><input type='checkbox' class='form-control input-sm' name='reference[]' id='reference' value="<?=$v?>"><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						<?php } ?>
                        <br/>
                        <label><input type='checkbox' class='form-control input-sm' name='reference_etc_chk' value="기타"><p class="checkboxtxt">기타</p><input type="text" disabled="disabled" name="reference_etc_txt" class='form-control input-sm'></label>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for='guarantee'>보증서</label>
					<div class="col-sm-9 pt2">
						<?php foreach($goods_guarantee as $v){ ?>
						<label><input type='checkbox' class='form-control input-sm' name='guarantee[]' id='guarantee' value="<?=$v?>"><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						<?php } ?>
	               	</div>
            	</div>
            	<!-- 매입정보끝 -->

				           	
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="as_yn">처리여부</label>
					<div class="col-sm-3 pt2">
						<select name="as_yn" id="as_yn" class="form-control input-sm">
							<option value="N"<?=($view->as_yn == 'N') ? ' selected="selected"' : ''?>>미처리</option>
							<option value="Y"<?=($view->as_yn == 'Y') ? ' selected="selected"' : ''?>>처리완료</option>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="start_date">신청날짜</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' style="width:170px;display:inline-block" name="start_date" id='start_date' value="<?=$view->start_date?>">
                  		<button type="button" class="start_date_btn btn btn-warning btn-sm" style="display:inline-block;vertical-align:top"><i class="glyphicon glyphicon-calendar"></i> AS신청</button>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="reason">AS신청사유</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name="reason" id="reason"><?=$view->reason?></textarea>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="end_date">마감날짜</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' style="width:170px;display:inline-block" name="end_date" id='end_date' value="<?=$view->end_date?>">
                  		<button type="button" class="end_date_btn btn btn-warning btn-sm" style="display:inline-block;vertical-align:top"><i class="glyphicon glyphicon-calendar"></i> AS마감</button>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' disabled="disabled" autocomplete="off" style="height:200px" name="purchase_note" id="purchase_note"></textarea>
               		</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" data-seq="<?=$view->seq?>" class="board_copy_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 복사</button>
	                <button type="button" data-seq="<?=$view->seq?>" class="board_delete_btn btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/asinfo?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
