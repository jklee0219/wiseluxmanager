<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/overtime.css?<?=time()?>">
   <script src="/admn/js/overtime.js?<?=time()?>"></script>
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
            					<td class="txt"><?=number_format($unprocessed_cnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg">미처리매입가격</td>
            					<td class="txt"><?=number_format($unprocessed_price)?>원</td>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/overtime" method="get">
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
                      <select class="form-control input-sm" name="sbrand">
                         <option value="">브랜드(전체)</option>
                         <?php foreach($brand_list as $v){ ?>
                         <option value="<?=$v->seq?>" <?=($sbrand==$v->seq) ? "selected='selected'" : ""?>><?=$v->name?></option>
                         <?php } ?>
                      </select>
						<select class="form-control input-sm" name="stype">
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
            			<option value="seller" <?=($stype=='seller') ? "selected='selected'" : ""?>>판매자</option>
            			<option value="sellerphone" <?=($stype=='sellerphone') ? "selected='selected'" : ""?>>판매자연락처</option>
            			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
            			<option value="manager" <?=($stype=='manager') ? "selected='selected'" : ""?>>매입담당</option>
            			<option value="tb_purchase.note" <?=($stype=='tb_purchase.note') ? "selected='selected'" : ""?>>비고</option>
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
	                     <th>경과일자</th>
	                     <th>경과일수</th>
	                     <th>판매자</th>
	                     <th>판매자연락처</th>
                         <th>지점명</th>
	                     <th>모델명</th>
	                     <th>위탁판매금액</th>
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
	                     $pcode = $v->pcode;
                        $thumb = $v->thumb;
                        if($thumb == '') $thumb = '/admn/img/noimg_l.jpg';
                        $pdate = $v->pdate;
                        if(strpos($pdate, '0000-00-00') !== false){
                           $pdate = '';
                        }
                        $adddate = ''; //경과일자
                        if($pdate){
                           $pdate = strtotime($pdate);
                           $adddate = strtotime('+90 Days',$pdate);
                           $pdate = date('Y-m-d', $pdate);
                        }
                        $adddate = date('Y-m-d', $adddate);
                        $addday = date_diff(new DateTime($pdate), new DateTime(date('Y-m-d')))->days; //경과일자
                        $seller = $v->seller;
                        $sellerphone = $v->sellerphone;
                        $modelname = $v->modelname;
                        $goods_price = $v->goods_price;
                        $goods_price = number_format($goods_price);
                        $note = $v->note;
                        if(mb_strlen($note) > 40){
                           $note = mb_substr($note, 0, 40, 'utf-8').'..';
                        }
                        $goods_seq = $v->goods_seq;

                        $trclass = '';
                        if($v->onlineyn=='Y'){
                           $trclass = ' class="active"';
                        }else if($v->onlineyn=='F'){
                           $trclass = ' class="active2"';
                        }
                        if($v->type == '위탁' && $v->onlineyn=='Y' && ($v->paymethod == '입금' || $v->paymethod == '현금')){
                           $trclass = ' class="active4"';   
                        }
                        if($v->type == '위탁' && $v->paymethod == '입금(위탁정산완료)' && $v->onlineyn=='Y'){
                           $trclass = ' class="active3"';   
                        }
                        if($v->account_conf == 'Y') $trclass = ' class="active5"';

                        $c24_display = $v->c24_display;
	               ?>
	                  <tr<?=$trclass?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$pdate?></td>
	                     <td class="rb"><?=$adddate?></td>
	                     <td class="rb"><?=$addday?>일</td>
	                     <td><?=$seller?></td>
	                     <td><?=$sellerphone?></td>
                         <td><?=$v->place?></td>
                        <td class="aleft"><?=$modelname?></td>
	                     <td><?=$goods_price?></td>
	                     <td class="aleft"><?=$note?></td>
	                     <td>
	                     	<?php if($goods_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/goods/modify?seq=<?=$goods_seq?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 상품수정</button>
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
