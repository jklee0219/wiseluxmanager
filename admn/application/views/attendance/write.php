<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/attendance.css?v=<?=time()?>">
   <script src="/admn/js/attendance_write.js?<?=time()?>"></script>
   <script>
   var qs = "<?=$param?>";
   </script>
</head>
<body>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title">
			<script>document.write($('.nav li .active').text());</script>	
		</div>

		<div class="content_write">
			
			<form name="writeFrm" id="writeFrm" method="post" action="/admn/attendance/writeproc">
				<input type="hidden" name="page" value="<?=$this->input->get('page', TRUE)?>">
				<input type="hidden" name="sdate" value="<?=$this->input->get('sdate', TRUE)?>">
				<input type="hidden" name="edate" value="<?=$this->input->get('edate', TRUE)?>">
				<input type="hidden" name="att_type_filter" value="<?=$this->input->get('att_type', TRUE)?>">
				<input type="hidden" name="stype" value="<?=$this->input->get('stype', TRUE)?>">
				<input type="hidden" name="skeyword" value="<?=$this->input->get('skeyword', TRUE)?>">
				
				<table class="table table-bordered">
					<colgroup>
						<col width="20%">
						<col width="80%">
					</colgroup>
					<tbody>
						<tr>
							<th><span class="essential">*</span> 직원</th>
							<td>
								<select class="form-control input-sm" name="worker_id" required>
									<option value="">직원 선택</option>
									<?php foreach($worker_list as $worker){ ?>
									<option value="<?=$worker->id?>"><?=$worker->name?> (<?=$worker->class?>)</option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<tr>
							<th><span class="essential">*</span> 날짜</th>
							<td>
								<div class="input-group date" style="width:200px;">
								    <input type="text" name="att_date" class="form-control input-sm" autocomplete="off" placeholder="날짜" required>
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</td>
						</tr>
						<tr>
							<th><span class="essential">*</span> 근무유형</th>
							<td>
								<select class="form-control input-sm" name="att_type" style="width:200px;" required>
									<option value="">선택</option>
									<option value="연차">연차</option>
									<option value="반차">반차</option>
									<option value="병가">병가</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>비고</th>
							<td>
								<textarea name="note" class="form-control" rows="3" placeholder="비고사항을 입력하세요"></textarea>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			
			<div class="btn_wrap">
				<button type="button" onclick="writeFrm.submit();" class="btn btn-success">저장</button>
				<button type="button" onclick="location.href='/admn/attendance?<?=$param?>'" class="btn btn-default">목록</button>
			</div>
		</div>
   
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
