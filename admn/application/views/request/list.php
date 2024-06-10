<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/request.css?<?=time()?>">
   <script src="/admn/js/request.js?<?=time()?>"></script>
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
            	<div>
            		<div class="amount_info_wrap">
            			<table>
            				<tr>
            					<td class="bg">미처리</td>
            					<td class="txt"><?=number_format($confirmdata1->cnt)?>건</td>
            					<td class="bg2">미처리매입가격</td>
            					<td class="txt"><?=number_format($confirmdata1->sum)?>원</td>
            				</tr>
            				<tr>
            					<td class="bg">처리완료</td>
            					<td class="txt"><?=number_format($confirmdata2->cnt)?>건</td>
            					<td class="bg2">처리완료매입가격</td>
            					<td class="txt"><?=number_format($confirmdata2->sum)?>원</td>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/request" method="get">
               			<div style="margin-bottom:6px">
							<div class="startdate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="매입일 시작" value="<?=$ssdate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>~
							<div class="enddate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="매입일 끝" value="<?=$sedate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>
							<select class="form-control input-sm" name="skind">
								<option value="">종류(전체)</option>
								<?php foreach($purchase_kind as $v){ ?>
								<option value="<?=$v?>" <?=($skind==$v) ? "selected='selected'" : ""?>><?=$v?></option>
								<?php } ?>
							</select>
							<select class="form-control input-sm" name="splace">
								<option value="">지점명(전체)</option>
								<?php foreach($goods_place as $v){ ?>
								<option value="<?=$v?>" <?=($splace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
								<?php } ?>
							</select>
							<select class="form-control input-sm" name="sstock">
								<option value="">재고(전체)</option>
								<option value="Y" <?=($sstock==$v) ? "selected='selected'" : ""?>>재고있음</option>
								<option value="N" <?=($sstock==$v) ? "selected='selected'" : ""?>>재고없음</option>
							</select>
						</div>
						<select class="form-control input-sm" name="confirmyn">
							<option value="">처리여부(전체)</option>
                  			<option value="Y" <?=($confirmyn=='Y') ? "selected='selected'" : ""?>>처리완료</option>
                  			<option value="N" <?=($confirmyn=='N') ? "selected='selected'" : ""?>>미처리</option>
						</select>
						<select class="form-control input-sm" name="sbrand">
							<option value="">브랜드(전체)</option>
							<?php foreach($brand_list as $v){ ?>
                  			<option value="<?=$v->seq?>" <?=($sbrand==$v->seq) ? "selected='selected'" : ""?>><?=$v->name?></option>
                  			<?php } ?>
						</select>
						<select class="form-control input-sm" name="stype">
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>판매자</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>판매자연락처</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>번호</th>
	                     <th>상품코드</th>
	                     <th>사진</th>
						 <th>매입일자</th>
						 <th style="width:100px">판매자(구매자)</th>
	                     <th>판매자연락처</th>
	                     <th>모델명</th>
	                     <th style="width:90px">위탁판매금액</th>
	                     <th style="width:90px">판매수정금액</th>
	                     <th style="width:90px">수정요청횟수</th>
                         <th style="width:55px">재고</th>
	                     <th style="width:122px">최종수정확인날짜</th>
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
	                     if($thumb == '') $thumb = '/admn/img/noimg_l.jpg';
	                     $pdate = $v->pdate;
	                     if($pdate){
							 $pdate = strtotime($pdate);
							 $pdate = date('Y-m-d', $pdate);
						 }else{
						 	$pdate = '';
						 }
	                     $seller = $v->seller;
	                     $sellerphone = $v->sellerphone;
						 $modelname = $v->modelname;
						 $price = $v->price;
	                     $price = number_format($price);
	                     $req_price = $v->req_price;
	                     $req_price = number_format($req_price);
	                     $confirmdate = $v->confirmdate;
	                     if($confirmdate){
							 $confirmdate = strtotime($confirmdate);
							 $confirmdate = date('Y-m-d', $confirmdate);
						 }else{
						 	$confirmdate = '';
						 }
	                     $request_cnt = $v->request_cnt;
	                     $request_yn = $v->request_yn;

	                     $classstr = '';
	                     if($request_yn=='N'){
	                     	$classstr = ' class="active"';
	                     }

                         $c24_display = $v->c24_display;
                         $stock = '';
                         if($v->stock=='Y') $stock = '있음';
                         if($v->stock=='N') $stock = '없음';
	               ?>
	                  <tr<?=$classstr?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$pdate?></td>
	                     <td><?=$seller?></td>
	                     <td><?=$sellerphone?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <td><?=$price?></td>
	                     <td><?=$req_price?></td>
	                     <td><?=$request_cnt?></td>
                         <td><?=$stock?></td>
	                     <td><?=$confirmdate?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/goods/modify?seq=<?=$seq?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 상품수정</button>
	                     	<?php if($request_yn=='Y'){ ?>
	                     	<button type="button" onclick="confirmproc('<?=$seq?>')" class="btn btn-info btn-sm"><i class="glyphicon glyphicon-pencil"></i> 수정확인</button>
	                     	<?php } ?>
	                     </td>
	            	  </tr>
	               <?php
	                  }
	               }
	               ?>
	               </tbody>
	            </table>
         	</form>
			
		 	<iframe name="hiddenFrm" src=""></iframe>
		
	         <div class="content_bottom">
	            <div class="content_left"></div>
	            <div class="content_middle"><?=$paging_html?></div>
	         </div>

      	</div>
      
   	</div>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
