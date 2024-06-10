<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/reference.css">
   <script src="/lib/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
   <script src="/admn/js/reference_write.js?<?=time()?>"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/reference/writeproc?<?=$param?>" method="post" role="form" name="boardForm">
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="title">제목<?=$required_mark?></label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' autocomplete="off" name='title' id="title" maxlength="330" placeholder="">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="writer">작성자<?=$required_mark?></label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm' autocomplete="off" name='writer' id="writer" maxlength="50" placeholder="">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="category">카테고리</label>
               		<div class="col-sm-3 pt2">
						<select name="category" id="category" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($reference_category as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-2 control-label" for="content">내용</label>
					<div class="col-sm-10 pt2">
						<textarea class='form-control input-sm textarea' autocomplete="off" name="content" id="content_editor"></textarea>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' autocomplete="off" name='note' id="note" maxlength="200" placeholder="">
               		</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm" onclick="doSubmit()"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/reference?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
