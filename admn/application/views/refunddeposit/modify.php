<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/refunddeposit.css">
   <script>
   var qs = "<?=$param?>";
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/refunddeposit_modify.js?<?=time()?>"></script>
</head>
<body>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

   <div id="content">
      <div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

      <div class="content_write">

			<form class="form-horizontal" action="/admn/refunddeposit/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
			
				<input type="hidden" name="seq" value="<?=$view->seq?>">
				
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="deposit_date">입금날짜</label>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" name='deposit_date' id='deposit_date' class="form-control input-sm" autocomplete="off" placeholder="" value="<?=$view->deposit_date?>">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>

            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="deposit_amount">입금액</label>
               		<div class="col-sm-3 pt2">
	                  	<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='deposit_amount' id='deposit_amount' maxlength="20" value="<?=$view->deposit_amount?>">
               		</div>
            	</div>

				<div class="form-group">
               		<label class="col-sm-3 control-label" for="depositor_name">입금자명</label>
               		<div class="col-sm-3 pt2">
	                  	<input type='text' class='form-control input-sm' autocomplete="off" name='depositor_name' id='depositor_name' maxlength="30" value="<?=$view->depositor_name?>">
               		</div>
            	</div>
            	
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for='depositor_contact1'>구매자연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
					   <?php
               			$depositor_contact = $view->depositor_contact;
               			$depositor_contact = explode('-', $depositor_contact);
               			$depositor_contact1 = '';
               			$depositor_contact2 = '';
               			$depositor_contact3 = '';
               			if(count($depositor_contact) == 3){
	               			$depositor_contact1 = $depositor_contact[0];
	               			$depositor_contact2 = $depositor_contact[1];
	               			$depositor_contact3 = $depositor_contact[2];
               			}
               			?>
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:50px;display:inline-block;" name='depositor_contact1' id="depositor_contact1" maxlength="4" placeholder="" value="<?=$depositor_contact1?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='depositor_contact2' id="depositor_contact2" maxlength="4" placeholder="" value="<?=$depositor_contact2?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='depositor_contact3' id="depositor_contact3" maxlength="4" placeholder="" value="<?=$depositor_contact3?>">
               		</div>
            	</div>

            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="remarks">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name="remarks" id="remarks"><?=$view->remarks?></textarea>
               		</div>
            	</div>

				<div class="form-group">
					<label class="col-sm-3 control-label" for="manager">담당자</label>
					<div class="col-sm-3 pt2">
						<select name="manager" id="manager" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($manager_list as $v) {?>
							<option value="<?=$v->name?>" <?=($view->manager == $v->name) ? 'selected="selected"' : ''?>><?=$v->viewname?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
	                <button type="button" data-seq="<?=$view->seq?>" class="board_delete_btn btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/refunddeposit?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>