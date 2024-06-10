<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/member.css?<?=time()?>">
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/member.js?<?=time()?>"></script>
</head>
<body>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">

		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/member/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">

				<input type="hidden" name="seq" value="<?=$view->seq?>">

            <div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="id">아이디</div>
					<div class="col-sm-7 pt2">
						<span class="txt"><?=$view->id?></span>
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password">권한</div>
					<div class="col-sm-4 pt2">
						<select class='form-control input-sm' name="auth">
							<?php foreach($auth as $k => $v){ ?>
							<option value="<?=$k?>"<?=($k==$view->auth) ? ' selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password">패스워드</div>
					<div class="col-sm-4 pt2">
						<input type='password' maxlength="20" class='form-control input-sm' autocomplete="off" name='password' id="password" value="">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password_c">패스워드 확인</div>
					<div class="col-sm-4 pt2">
						<input type='password' maxlength="20" class='form-control input-sm' autocomplete="off" name='password_c' id="password_c" value="">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="name">이름</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='name' id="name" value="<?=$view->name?>">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="class">직책</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='class' id="class" value="<?=$view->class?>">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="phone">연락처</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='phone' id="phone" value="<?=$view->phone?>">
					</div>
				</div>
                <div class="form-group">
                <div class="fwb col-sm-3 control-label" for="ordernum">번호</div>
                    <div class="col-sm-2 pt2">
                        <input type='text' maxlength="3" class='form-control input-sm' autocomplete="off" name='ordernum' id="ordernum" value="<?=$view->ordernum?>">
                    </div>
                </div>

			</form>

			<div class="content_bottom">
         	<div class="content_left">
					<button type="button" class="btn btn-success btn-sm" onclick="dosumbmit2()"><i class="glyphicon glyphicon-floppy-disk"></i> 수정</button>
					<?php if($view->id != 'admin'){ ?>
					<button type="button" class="btn btn-danger btn-sm" onclick="dodelete()"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
					<?php } ?>
					<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/member?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
				</div>
			</div>

		</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
