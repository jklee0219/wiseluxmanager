<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/sendsms.css?<?=time()?>">
   <script src="/admn/js/sendsms.js?<?=time()?>"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title">
			<script>document.write($('.nav li .active').text());</script>	
		</div>

		<div class="content_list">
         	
			<div class="sendsms_wrap">
				<input type='text' class='form-control input-sm' autocomplete="off" name='smsphonenumber' id='smsphonenumber' maxlength="13" placeholder="수신번호(-없이 입력하세요)">
				<textarea class='form-control input-sm' autocomplete="off" name='smscontent' id='smscontent' placeholder="문자내용"></textarea>
				<button type="button" onclick="sendsms()" class="smssend_btn btn btn-danger btn-sm"><i class="glyphicon glyphicon-send"></i> 전송</button>
				<a href="https://console.ncloud.com/sens/sms-message" style="margin-top:100px" target="_blank" type="button" class="smssend_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-envelope"></i> 문자전송 목록보기</a>
			</div>
			
			<div class="savesms_wrap">
				<?php for($i=1; $i<=100; $i++){
				    $txt = "";
				    foreach($list as $v){
				        if($v->seq == $i){
				            $txt = $v->txt;
				            break;
				        }
				    }
				?>
				<div class="savesmscontent_wrap">
					<p class="num"><?=$i?></p>
					<textarea class='form-control input-sm savesmscontent' autocomplete="off"><?=$txt?></textarea>
					<p class="bytetxt">[0 / 2000]byte (SMS)</p>
					<button type="button" class="smscopy_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-arrow-left"></i> 사용</button>
					<button type="button" data-seq="<?=$i?>" class="smssave_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-pushpin"></i> 저장</button>
				</div>
				<?php }?>
			</div>

      	</div>
      
   	</div>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
