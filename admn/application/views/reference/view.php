<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <script>
   var qs = "<?=$param?>";
   </script>
   <link rel="stylesheet" href="/admn/css/reference.css">
</head>
<body>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

   <div id="content">
		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 상세보기</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/reference/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
				<input type="hidden" name="seq" value="<?=$view->seq?>">
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="title">제목</label>
               		<div class="col-sm-9 pt7">
                  		<?=$view->title?>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="writer">작성자</label>
               		<div class="col-sm-3 pt7">
                  		<?=$view->writer?>
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-2 control-label" for="content">내용</label>
					<div class="col-sm-10 pt7">
						<?=$view->content?>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-2 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt7">
                  		<?=$view->note?>
               		</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/reference?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 목록</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
