<!DOCTYPE html>
<html lang='ko'>
<head>
	<?php include_once $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
	<link rel="stylesheet" href="/admn/css/login.css?v=2">
	<script src="/admn/js/login.js?v=2"></script>
</head>
<body>

	<div class="bgArea"></div>
	<div class="bgArea2"></div>
	<div class="loginWarning"></div>
	<div class="loginFrm_wrap">
		<div class="loginFrm_border">
			<form name="loginFrm" method="post" onsubmit="validateForm(); return false;">
				<div class="id_wrap">
					<input type="text" name="id" maxlength="20" class="form-control" id="id" placeholder="username" value="">
					<img src="/admn/img/id.png">
				</div>
				<div class="pw_wrap">
					<input type="password" name="pw" maxlength="20" class="form-control" id="pw" placeholder="password" value="">
					<img src="/admn/img/pw.png">
				</div>
				<div class="btn_wrap"><button type="submit" id="login_btn" class="btn btn-primary linear waves-effect">로그인</button></div>
	    	</form>
		</div>
		<div class="logimg"><img src="./img/logo.png"></div>
		<span class="copyright"><?=COPYRIGHT?></span>
    </div>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
