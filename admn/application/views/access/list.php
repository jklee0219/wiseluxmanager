<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/access.css?<?=time()?>">
   <script src="/admn/js/access.js?<?=time()?>"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title">
			<script>document.write($('.nav li .active').text());</script>	
		</div>

		<div class="access_list_wrap">
			<p class="legend">접속목록</p>
			<div class="list_wrap"></div>
		</div>
		
		<div class="block_list_wrap">
			<p class="legend">차단IP목록</p>
			<div class="list_wrap"></div>
		</div>
		
		<div class="pwch_wrap">
			<p class="closebtn btn btn-danger btn-sm">X</p>
			<span class="idstr"></span> 계정 비밀번호 변경
			<input type="password" id="pwd1" value="" autocomplete="off" maxlength="20" placeholder="패스워드">
			<input type="password" id="pwd2" value="" autocomplete="off" maxlength="20" placeholder="패스워드확인">
			<button class="btn btn-success btn-sm pwchbtn" onclick="setPassword()">패스워드 변경 적용 및 차단</button>
			<input type="hidden" id="chid" value="">
			<input type="hidden" id="ip" value="">  
		</div>
      
   	</div>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
