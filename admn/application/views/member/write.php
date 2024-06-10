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

		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/member/writeproc?<?=$param?>" method="post" role="form" name="boardForm">

				<input type="hidden" id="idchk" value="0">
            <div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="id">아이디</div>
					<div class="col-sm-7 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='id' id="id">
						<p class="useid">이미 사용중인 아이디 입니다.</p>
						<p class="notuseid">사용 가능한 아이디 입니다.</p>
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password">권한</div>
					<div class="col-sm-4 pt2">
						<select class='form-control input-sm' name="auth">
							<?php foreach($auth as $k => $v){ if($k==9) continue; ?>
							<option value="<?=$k?>"><?=$v?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password">패스워드</div>
					<div class="col-sm-4 pt2">
						<input type='password' maxlength="20" class='form-control input-sm' autocomplete="off" name='password' id="password">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="password_c">패스워드 확인</div>
					<div class="col-sm-4 pt2">
						<input type='password' maxlength="20" class='form-control input-sm' autocomplete="off" name='password_c' id="password_c">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="name">이름</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='name' id="name">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="class">직책</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='class' id="class">
					</div>
				</div>
				<div class="form-group">
            	<div class="fwb col-sm-3 control-label" for="phone">연락처</div>
					<div class="col-sm-4 pt2">
						<input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='phone' id="phone" value="">
					</div>
				</div>
                <div class="form-group">
                <div class="fwb col-sm-3 control-label" for="ordernum">번호</div>
                    <div class="col-sm-2 pt2">
                        <input type='text' maxlength="3" class='form-control input-sm' autocomplete="off" name='ordernum' id="ordernum" value="">
                    </div>
                </div>

			</form>

			<div class="content_bottom">
         	<div class="content_left">
					<button type="button" class="btn btn-success btn-sm" onclick="dosumbmit()"><i class="glyphicon glyphicon-floppy-disk"></i> 등록</button>
					<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/member?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
				</div>
			</div>

		</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
