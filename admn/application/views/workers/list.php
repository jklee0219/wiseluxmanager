<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/workers.css?<?=time()?>">
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
	
			<table class="table">

				<thead class="thead-inverse">

					<tr>
						<th>이름</th>
						<th>직책</th>
						<th>입사날짜</th>
                        <th>아이디</th>
					</tr>

				</thead>
				<tbody>
	            
	            <?php foreach($board_list as $v){ ?>
					<tr>
						<td><?=$v->name?></td>
						<td><?=$v->class?></td>
						<td><?=$v->joindate?></td>
                        <td><?=$v->id?></td>
					</tr>
					<?php } ?>

				</tbody>
			</table>

		</div>
      
	</div>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
