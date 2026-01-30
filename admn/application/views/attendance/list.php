<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/attendance.css?v=<?=time()?>">
   <script src="/admn/js/attendance.js?<?=time()?>"></script>
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
            		<div class="amount_info_wrap">
            			<table>
            				<tr>
            					<td class="bg">연차</td>
            					<td class="txt"><?=number_format($annualCnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg">조퇴</td>
            					<td class="txt"><?=number_format($earlyCnt)?>건</td>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/attendance" method="get">
                        <div style="margin-bottom:6px">
                            <select class="form-control input-sm" name="att_type">
                                <option value="">근무유형(전체)</option>
                                <option value="근무" <?=($att_type=='근무') ? "selected='selected'" : ""?>>근무</option>
                                <option value="연차" <?=($att_type=='연차') ? "selected='selected'" : ""?>>연차</option>
                                <option value="조퇴" <?=($att_type=='조퇴') ? "selected='selected'" : ""?>>조퇴</option>
                            </select>
                        </div>
                  		<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="sdate" class="form-control input-sm" autocomplete="off" placeholder="시작일" value="<?=$sdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="edate" class="form-control input-sm" autocomplete="off" placeholder="종료일" value="<?=$edate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<select class="form-control input-sm" name="stype">
							<option value="worker_name" <?=($stype=='worker_name') ? "selected='selected'" : ""?>>직원명</option>
                            <option value="worker_id" <?=($stype=='worker_id') ? "selected='selected'" : ""?>>직원ID</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./attendance/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
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
                                <th>날짜</th>
                                <th>직원명</th>
                                <th>직책</th>
                                <th>근무유형</th>
                                <th>비고</th>
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
                                $att_date = $v->att_date;
                                $worker_name = $v->worker_name;
                                $worker_class = $v->worker_class;
                                $att_type_label = $v->att_type;
                                $note = $v->note;
                                
                                // 근무유형별 색상
                                $type_color = '';
                                if($att_type_label == '연차') $type_color = 'color:#e74c3c;';
                                else if($att_type_label == '조퇴') $type_color = 'color:#f39c12;';
                                else $type_color = 'color:#27ae60;';
                        ?>
                        <tr>
                            <td><?=$att_date?></td>
                            <td><?=$worker_name?></td>
                            <td><?=$worker_class?></td>
                            <td style="<?=$type_color?> font-weight:bold;"><?=$att_type_label?></td>
                            <td><?=$note?></td>
                            <td>
                                <button type="button" onclick="location.href='./attendance/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-info btn-xs">수정</button>
                                <button type="button" onclick="if(confirm('삭제하시겠습니까?')) location.href='./attendance/delproc?seq=<?=$seq?>&<?=$param?>'" class="btn btn-danger btn-xs">삭제</button>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                        ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">등록된 데이터가 없습니다.</td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                    
                    <div class="pagination_wrap">
                        <?=$paging_html?>
                    </div>
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
                { 
                    title: '연차:<?=$item->annual_cnt?> 조퇴:<?=$item->early_cnt?>', 
                    start: '<?=$item->att_date?>', 
                    description: '연차: <?=$item->annual_cnt?>명<br>조퇴: <?=$item->early_cnt?>명',
                    annualCnt: <?=$item->annual_cnt?>,
                    earlyCnt: <?=$item->early_cnt?>
                },
                <?php } ?>
            ];

            var savedMonth = getCookie('calendarMonth');
            var initialDate = savedMonth ? new Date(savedMonth + '-01') : new Date();

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ko',
                initialView: 'dayGridMonth',
                initialDate: initialDate,
                events: events,
                eventMouseEnter: function(info) {
                    tooltip.innerHTML = info.event.extendedProps.description;
                    tooltip.style.display = 'block';
                    tooltip.style.left = info.jsEvent.pageX + 10 + 'px';
                    tooltip.style.top = info.jsEvent.pageY + 10 + 'px';
                },
                eventMouseLeave: function(info) {
                    tooltip.style.display = 'none';
                },
                dateClick: function(info) {
                    var date = info.dateStr;
                    var url = '/admn/attendance?sdate='+date+'&edate='+date+'&att_type=&stype=worker_name&skeyword=';
                    window.location.href = url;
                },
                eventClick: function(info) {
                    var date = info.event.startStr;
                    var url = '/admn/attendance?sdate='+date+'&edate='+date+'&att_type=&stype=worker_name&skeyword=';
                    window.location.href = url;
                },
                eventContent: function(arg) {
                    let annualCnt = arg.event.extendedProps.annualCnt;
                    let earlyCnt = arg.event.extendedProps.earlyCnt;
                    
                    let html = '<div style="font-size:11px; padding:2px;">';
                    if(annualCnt > 0) {
                        html += '<div style="color:#e74c3c;">연차:' + annualCnt + '</div>';
                    }
                    if(earlyCnt > 0) {
                        html += '<div style="color:#f39c12;">조퇴:' + earlyCnt + '</div>';
                    }
                    html += '</div>';
                    
                    return { html: html };
                }
            });

            calendar.render();

            document.querySelector('.fc-prev-button').addEventListener('click', updateCookie);
            document.querySelector('.fc-next-button').addEventListener('click', updateCookie);
            document.querySelector('.fc-today-button').addEventListener('click', updateCookie);

            function updateCookie() {
                var view = calendar.view;
                var year = view.currentStart.getFullYear();
                var month = view.currentStart.getMonth() + 1;
                if (month < 10) {
                    month = '0' + month;
                }
                var yearMonth = year + '-' + month;
                setCookie('calendarMonth', yearMonth, 30);
            }

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
