<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/member.css?<?=time()?>">
   <script src="/admn/js/member.js?<?=time()?>"></script>
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

		<div class="content_list">

			<div class="content_top">

				<form name="searchFrm" action="/admn/member" method="get">

					<div class="searchareawrap">

						<div class="frmfoot">
							<input type="text" class="form-control input-sm" autocomplete="off" name="sid" value="<?=$sid?>" placeholder="아이디 검색">
							<button type="button" onclick="searchFrm.submit()" class="search_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
							<button type="button" onclick="location.href='./member/write'" class="search_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i> 사용자추가</button>
						</div>

					</div>

				</form>

			</div>
	
			<table class="table">

				<thead class="thead-inverse">

					<tr>
						<th>고유번호</th>
                  <th>아이디</th>
                  <th>권한</th>
						<th>이름</th>
						<th>직책</th>
						<th>연락처</th>
						<th>최종로그인일자</th>
                  <th></th>
					</tr>

				</thead>
				<tbody>
	            
	            <?php foreach($board_list as $v){ ?>
					<tr>
						<td><?=$v->seq?></td>
                  <td class="tal"><strong><?=$v->id?></strong></td>
						<td><?=$auth[$v->auth]?></td>
						<td><?=$v->name?></td>
						<td><?=$v->class?></td>
						<td><?=$v->phone?></td>
						<td><?=($v->last_login != '0000-00-00 00:00:00') ? $v->last_login : ''?></td>
                  <td>
                  	<button type="button" onclick="location.href='/admn/member/modify?seq=<?=$v->seq?>&<?=$param?>'" class="search_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i> 수정</button>
                  </td>
					</tr>
					<?php } ?>

				</tbody>
			</table>
		
			<div class="content_bottom">
				<div class="content_left"></div>
				<div class="content_middle"><?=$paging_html?></div>
			</div>

		</div>
      
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
