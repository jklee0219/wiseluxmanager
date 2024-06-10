<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/bloggerlist.css?<?=time()?>">
   <script src="/admn/js/bloggerlist.js?<?=time()?>"></script>
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

                <div class="info_wrap">
                    <table>
                        <tbody>
                            <tr>
                                <td class="bg">방문</td>
                                <td class="txt"><?=number_format($total_cnt)?>건</td>
                            </tr>
                            <tr>
                                <td class="bg">정산</td>
                                <td class="txt"><?=number_format($paycnt)?>건</td>
                            </tr>
                            <tr>
                                <td class="bg">정산합계</td>
                                <td class="txt"><?=number_format($totpay)?>원</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
				<form name="searchFrm" action="/admn/bloggerlist" method="get">

					<div class="searchareawrap">

						<div class="frmfoot">
                            <div style="margin-bottom:10px">
                                <div class="startdate_wrap">
                                    <div class="input-group date">
                                        <input type="text" name="svisdate" class="form-control input-sm" autocomplete="off" placeholder="방문날짜 시작" value="<?=$svisdate?>">
                                        <div class="input-group-addon input-sm">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                </div><span class="dash">~</span>
                                <div class="enddate_wrap">
                                    <div class="input-group date">
                                        <input type="text" name="evisdate" class="form-control input-sm" autocomplete="off" placeholder="방문날짜 끝" value="<?=$evisdate?>">
                                        <div class="input-group-addon input-sm">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                </div>&nbsp;&nbsp;
                                <div class="startdate_wrap">
                                    <div class="input-group date">
                                        <input type="text" name="spaydate" class="form-control input-sm" autocomplete="off" placeholder="정산날짜 시작" value="<?=$spaydate?>">
                                        <div class="input-group-addon input-sm">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                </div><span class="dash">~</span>
                                <div class="enddate_wrap">
                                    <div class="input-group date">
                                        <input type="text" name="epaydate" class="form-control input-sm" autocomplete="off" placeholder="정산날짜 끝" value="<?=$epaydate?>">
                                        <div class="input-group-addon input-sm">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <select class="form-control input-sm" name="splace">
                                <option value="">방문지점(전체)</option>
                                <?php foreach($goods_place as $v){ ?>
                                <option value="<?=$v?>" <?=($splace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                                <?php } ?>
                            </select>
                            <select class="form-control input-sm" name="stype">
                                <option value="name"<?=($stype=='name') ? " selected='selected'" : ""?>>성함</option>
                                <option value="phone"<?=($stype=='phone') ? " selected='selected'" : ""?>>연락처</option>
                                <option value="keyword"<?=($stype=='keyword') ? " selected='selected'" : ""?>>키워드</option>
                                <option value="accountnumber"<?=($stype=='accountnumber') ? " selected='selected'" : ""?>>계좌번호</option>
                            </select>
							<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
							<button type="button" onclick="searchFrm.submit()" class="search_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                            <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                            <button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                            <?php }?>
							<button type="button" onclick="location.href='./bloggerlist/write'" class="search_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-plus"></i> 등록</button>
						</div>

					</div>

				</form>

			</div>
	
			<table class="table">

				<thead class="thead-inverse">

					<tr>
						<th>고유번호</th>
                        <th>성함</th>
                        <th>연락처</th>
						<th>키워드</th>
						<th>계좌번호</th>
                        <?php if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){ ?>
                        <th>주민번호</th>
                        <?php } ?>
						<th>방문지점</th>
						<th>방문날짜</th>
                        <th>정산날짜</th>
                        <th>정산금액</th>
                        <th>링크</th>
                        <th></th>
					</tr>

				</thead>
				<tbody>
	            
                    <?php 
                    foreach($board_list as $v){ 
                        $visdate = $v->visdate;
                        if($visdate && $visdate != '0000-00-00 00:00:00'){
                            $visdate = strtotime($visdate);
                            $visdate = date('Y-m-d', $visdate);
                        }else{
                            $visdate = '';
                        }
                        $paydate = $v->paydate;
                        if($paydate && $paydate != '0000-00-00 00:00:00'){
                            $paydate = strtotime($paydate);
                            $paydate = date('Y-m-d', $paydate);
                        }else{
                            $paydate = '';
                        }
                    ?>
					<tr>
						<td><?=$v->seq?></td>
                        <td><?=$v->name?></td>
						<td><?=$v->phone?></td>
						<td><?=$v->keyword?></td>
                        <td><?=$v->accountnumber?></td>
                        <?php if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){ ?>
                        <td><?=$v->ssn?></td>
                        <?php }?>
						<td><?=$v->place?></td>
						<td><?=$visdate?></td>
						<td><?=$paydate?></td>
                        <td><?=number_format($v->payprice)?></td>
                        <td><a href="<?=$v->link?>" target="_blank">링크</a></td>
                        <td>
                            <button type="button" onclick="location.href='/admn/bloggerlist/modify?seq=<?=$v->seq?>&<?=$param?>'" class="search_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-pencil"></i> 수정</button>
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

    <iframe name="hiddenFrm" src=""></iframe>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
