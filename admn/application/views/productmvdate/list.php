<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/productmvdate.css">
   <script src="/admn/js/productmvdate.js?<?=time()?>"></script>
   <script>
   var qs = "<?=$param?>";
   </script>
   <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
   <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/ko.js'></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title">
			<script>document.write($('.nav li .active').text());</script>	
		</div>

		<div class="content_list">
         	<div class="content_top">
            	<div>
               		<form name="searchFrm" action="/admn/productmvdate" method="get">
                  		<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="이동일 시작" value="<?=$ssdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="이동일 끝" value="<?=$sedate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<select class="form-control input-sm" name="stype">
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
							<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
                            <option value="tb_productmovedate.shipplace" <?=($stype=='tb_productmovedate.shipplace') ? "selected='selected'" : ""?>>발송지점</option>
                            <option value="tb_productmovedate.reciveplace" <?=($stype=='tb_productmovedate.reciveplace') ? "selected='selected'" : ""?>>수령지점</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                  		<button type="button" onclick="location.href='./productmvdate/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
			<div class="calendar_wrap">
				<div>
					<div id='calendar'></div>
					<div id="tooltip"></div>
				</div>
				<div>
					<table class="table">
						<thead class="thead-inverse">
							<tr>
								<th>상품코드</th>
								<th>사진</th>
								<th>이동일자</th>
								<th>모델명</th>
								<th>이동결과</th>
								<th>발송지점</th>
								<th>수령지점</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						<?php
						if($board_list)
						{
							foreach($board_list as $k => $v)
							{
								$seq = $v->seq;
								$pcode = $v->pcode;
								$thumb = $v->thumb;
								if(!$thumb) $thumb = '/admn/img/noimg_l.jpg';
								$pdate = $v->pdate;
								if($pdate){
									$pdate = strtotime($pdate);
									$pdate = date('Y-m-d', $pdate);
								}else{
									$pdate = '';
								}
								$modelname = $v->modelname;
								$movedate = $v->movedate;
								$moveyn = $v->moveyn;
						?>
							<tr<?=($moveyn=='Y') ? ' class="active"' : ''?>>
								<td><?=$pcode?></td>
								<td><div class="thumb"><img src="<?=$thumb?>"></div></td>
								<td><?=$v->movedate?></td>
								<td class="tal"><?=$modelname?></td>
								<td><?=($v->moveyn == 'N' ? '미처리' : '처리완료')?></td>
								<td><?=$v->shipplace?></td>
								<td><?=$v->reciveplace?></td>
								<td>
									<button type="button" onclick="location.href='/admn/productmvdate/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 수정</button>
								</td>
							</tr>
						<?php
							}
						}
						?>
						</tbody>
					</table>	
				</div>
			</div>
			
		 	<iframe name="hiddenFrm" src=""></iframe>
		
	         <div class="content_bottom">
	            <div class="content_left"></div>
	            <div class="content_middle"><?=$paging_html?></div>
	         </div>

      	</div>
      
   	</div>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

	<script>
		$(document).ready(function(){

			var calendarEl = document.getElementById('calendar');
			var tooltip = document.getElementById('tooltip');
			var events = [
				<?php foreach($alllist as $item){ ?>
				{ title: '<?=$item->shipplace?> -> <?=$item->reciveplace?>', start: '<?=$item->movedate?>', description: '<?=$item->shipplace?> -> <?=$item->reciveplace?>' },
				<?php } ?>
			];

			var eventCounts = {};

			events.forEach(function(event) {
				var date = event.start;
				if (!eventCounts[date]) {
					eventCounts[date] = { count: 0, description: [] };
				}
				eventCounts[date].count++;
				eventCounts[date].description.push(event.description);
			});

			var groupedEvents = Object.keys(eventCounts).map(function(date) {
				return {
					title: eventCounts[date].count + '개의 이동',
					start: date,
					description: eventCounts[date].description.join('<br>')
				};
			});

			var calendar = new FullCalendar.Calendar(calendarEl, {
				locale: 'ko',
				initialView: 'dayGridMonth',
				events: groupedEvents,
				eventMouseEnter: function(info) {
					tooltip.innerHTML = info.event.extendedProps.description;
					tooltip.style.display = 'block';
					tooltip.style.left = info.jsEvent.pageX + 10 + 'px';
					tooltip.style.top = info.jsEvent.pageY + 10 + 'px';
				},
				eventMouseLeave: function(info) {
					tooltip.style.display = 'none';
				}
			});

			calendar.render();

			document.addEventListener('mousemove', function(e) {
				if (tooltip.style.display === 'block') {
					tooltip.style.left = e.pageX + 10 + 'px';
					tooltip.style.top = e.pageY + 10 + 'px';
				}
			});
		});
	</script>
</body>
</html>
