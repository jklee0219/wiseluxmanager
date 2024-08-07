<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/productmvdate.css?v=3">
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
            		<div class="amount_info_wrap">
            			<table>
            				<tr>
            					<td class="bg">미처리</td>
            					<td class="txt"><?=number_format($movenCnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg">처리완료</td>
            					<td class="txt"><?=number_format($moveyCnt)?>건</td>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/productmove" method="get">
                        <div style="margin-bottom:6px">
                            <select class="form-control input-sm" name="sshipplace">
                                <option value="">발송지점</option>
                                <?php foreach($goods_place as $v){ ?>
                                <option value="<?=$v?>" <?=($sshipplace==$v) ? "selected='selected'" : ""?>><?=$v?></option>    
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="sreciveplace">
                                <option value="">수령지점</option>
                                <?php foreach($goods_place as $v){ ?>
                                <option value="<?=$v?>" <?=($sreciveplace==$v) ? "selected='selected'" : ""?>><?=$v?></option>    
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="sstype">
                                <option value="">구분(전체)</option>
                                <?php foreach($purchase_type as $v){ ?>
                                <option value="<?=$v?>" <?=($sstype==$v) ? "selected='selected'" : ""?>><?=$v?></option>    
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="sbrand">
                                <option value="">브랜드(전체)</option>
                                <?php foreach($brand_list as $v){ ?>
                                <option value="<?=$v->seq?>" <?=($sbrand==$v->seq) ? "selected='selected'" : ""?>><?=$v->name?></option>
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="skind">
                                <option value="">종류(전체)</option>
                                <?php foreach($purchase_kind as $v){ ?>
                                <option value="<?=$v?>" <?=($skind==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="smoveyn">
                                <option value="">이동결과(전체)</option>
                                <?php foreach($goodsmove_yn as $k => $v){ ?>
                                <option value="<?=$k?>" <?=($smoveyn==$k) ? "selected='selected'" : ""?>><?=$v?></option>
                                <?php } ?>
                            </select>
                        </div>
                  		<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="sshipdate" class="form-control input-sm" autocomplete="off" placeholder="발송일 시작" value="<?=$sshipdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="eshipdate" class="form-control input-sm" autocomplete="off" placeholder="발송일 끝" value="<?=$eshipdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<div class="startdate_wrap">
                            <div class="input-group date">
                                <input type="text" name="srecivedate" class="form-control input-sm" autocomplete="off" placeholder="수령일 시작" value="<?=$srecivedate?>">
                                <div class="input-group-addon input-sm">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>~
                        <div class="enddate_wrap">
                            <div class="input-group date">
                                <input type="text" name="erecivedate" class="form-control input-sm" autocomplete="off" placeholder="수령일 끝" value="<?=$erecivedate?>">
                                <div class="input-group-addon input-sm">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>
						<select class="form-control input-sm" name="stype">
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
                            <option value="seller" <?=($stype=='seller') ? "selected='selected'" : ""?>>판매자</option>
                            <option value="sellerphone" <?=($stype=='sellerphone') ? "selected='selected'" : ""?>>판매자연락처</option>
                            <option value="reciveplace" <?=($stype=='reciveplace') ? "selected='selected'" : ""?>>수령지점</option>
							<option value="tb_purchase.note" <?=($stype=='tb_purchase.note') ? "selected='selected'" : ""?>>비고</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./productmove/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
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
                                <th>발송일자</th>
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
                                $shipdate = $v->shipdate;
                                if($shipdate){
                                    $shipdate = strtotime($shipdate);
                                    $shipdate = date('Y-m-d', $shipdate);
                                }else{
                                   $shipdate = '';
                                }
                                $moveyn = $v->moveyn;
                        ?>
                            <tr<?=($moveyn=='Y') ? ' class="active"' : ''?>>
                                <td><?=$pcode?></td>
                                <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
                                <td><?=$shipdate?></td>
                                <td class="tal"><?=$modelname?></td>
                                <td><?=($v->moveyn == 'N' ? '미처리' : '처리완료')?></td>
                                <td><?=$v->shipplace?></td>
                                <td><?=$v->reciveplace?></td>
                                <td>
                                    <button type="button" onclick="location.href='/admn/productmove/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 수정</button>
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

            var colorMap = {
                '강남본점': '#e09c48',
                '강동점': '#2528c3',
                '잠실점': '#884c4c',
                '부천점': '#009000'
            };

            var calendarEl = document.getElementById('calendar');
            var tooltip = document.getElementById('tooltip');
            var events = [
                <?php foreach($alllist as $item){ ?>
                { title: '<?=$item->shipplace?> -> <?=$item->reciveplace?>', start: '<?=date('Y-m-d', strtotime($item->shipdate))?>', description: '<span style="color:' + colorMap['<?=$item->shipplace?>'] + '"><?=$item->shipplace?></span> -> <span style="color:' + colorMap['<?=$item->reciveplace?>'] + '"><?=$item->reciveplace?></span>' },
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

            var savedMonth = getCookie('calendarMonth');
            var initialDate = savedMonth ? new Date(savedMonth + '-01') : new Date();

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'ko',
                initialView: 'dayGridMonth',
                initialDate: initialDate,
                events: groupedEvents,
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
                    var url = '/admn/productmove?sstype=&sbrand=&skind=&smoveyn=&sshipdate='+date+'&eshipdate='+date+'&srecivedate=&erecivedate=&stype=pcode&skeyword='; // 원하는 URL로 변경
                    window.location.href = url;
                },
                eventClick: function(info) {
                    var date = info.event.startStr;
                    var url = '/admn/productmove?sstype=&sbrand=&skind=&smoveyn=&sshipdate='+date+'&eshipdate='+date+'&srecivedate=&erecivedate=&stype=pcode&skeyword='; // 원하는 URL로 변경
                    window.location.href = url;
                }
            });

            calendar.render();

            document.querySelector('.fc-prev-button').addEventListener('click', updateCookie);
            document.querySelector('.fc-next-button').addEventListener('click', updateCookie);
            document.querySelector('.fc-today-button').addEventListener('click', updateCookie);

            function updateCookie() {
                var view = calendar.view;
                var year = view.currentStart.getFullYear();
                var month = view.currentStart.getMonth() + 1; // getMonth()는 0부터 시작하므로 +1 필요
                if (month < 10) {
                    month = '0' + month; // 월이 한자리 수일 때 0을 추가
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