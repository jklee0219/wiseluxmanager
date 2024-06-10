<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/refund.css?<?=time()?>">
   <script src="/admn/js/refund.js?<?=time()?>"></script>
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
            					<td class="bg">자료검색</td>
            					<td class="txt"><?=number_format($total_cnt)?>건</td>
            					<td class="bg"></td>
            					<td class="txt"></td>
            				</tr>
            				<tr>
            					<td class="bg">총반품금액</td>
            					<td class="txt"><?=number_format($totalsum_a)?>원</td>
            					<td class="bg">총환불금액</td>
            					<td class="txt"><?=number_format($totalsum_b)?>원</td>
            				</tr>
            				<tr>
            					<td class="bg">총결제금액</td>
            					<td class="txt"><?=number_format($totalsum_d)?>원</td>
            					<td class="bg">총거부금액</td>
            					<td class="txt"><?=number_format($totalsum_c)?>원</td>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/refund" method="get">
               			<div style="margin-bottom:6px">
               				<select class="form-control input-sm" name="smmpricecol" id="smmpricecol">
	                  			<option value="price" <?=($smmpricecol=='price') ? "selected='selected'" : ""?>>판매예정금액</option>
	                  			<option value="paymentprice" <?=($smmpricecol=='paymentprice') ? "selected='selected'" : ""?>>결제금액</option>
							</select>
							<input type="text" class="form-control input-sm" autocomplete="off" name="sminprice" id="sminprice" value="<?=$sminprice?>" placeholder="20000"> <span>~</span>
							<input type="text" class="form-control input-sm" autocomplete="off" name="smaxprice" id="smaxprice" value="<?=$smaxprice?>" placeholder="300000">
               			</div>
               			<div style="margin-bottom:6px">
               				<select class="form-control input-sm" name="saccount_conf">
								<option value="">경리팀확인(전체)</option>
								<option value="N"<?=($saccount_conf=='N') ? " selected='selected'" : ""?>>아니요</option>
								<option value="Y"<?=($saccount_conf=='Y') ? " selected='selected'" : ""?>>예</option>
							</select>
                  			<div class="startdate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="판매일 시작" value="<?=$ssdate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>~
							<div class="enddate_wrap">
		               			<div class="input-group date">
								    <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="판매일 끝" value="<?=$sedate?>">
								    <div class="input-group-addon input-sm">
								        <span class="glyphicon glyphicon-calendar"></span>
								    </div>
								</div>
							</div>
							
							<select class="form-control input-sm" name="sselltype">
								<option value="">판매구분(전체)</option>
								<?php foreach($trade_selltype as $v){ ?>
	                  			<option value="<?=$v?>" <?=($sselltype==$v) ? "selected='selected'" : ""?>><?=$v?></option>
	                  			<?php } ?>
							</select>
							<select class="form-control input-sm" name="sprocess">
								<option value="">처리결과(전체)</option>
	                  			<option value="Y" <?=($sprocess=='Y') ? "selected='selected'" : ""?>>승인</option>
	                  			<option value="N" <?=($sprocess=='N') ? "selected='selected'" : ""?>>거부</option>
							</select>
							<select class="form-control input-sm" name="spaymethod">
								<option value="">결제방법(전체)</option>
								<?php foreach($trade_paymethod as $v){ ?>
	                  			<option value="<?=$v?>" <?=($spaymethod==$v) ? "selected='selected'" : ""?>><?=$v?></option>
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
                            <select class="form-control input-sm" name="splace">
                                <option value="">매입지점(전체)</option>
                                <?php foreach($goods_place as $v){ ?>
                                <option value="<?=$v?>" <?=($splace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                                <?php } ?>
                             </select>
						</div>
						<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="ssapplydate" class="form-control input-sm" autocomplete="off" placeholder="신청일 시작" value="<?=$ssapplydate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="seapplydate" class="form-control input-sm" autocomplete="off" placeholder="신청일 끝" value="<?=$seapplydate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="sscompletedate" class="form-control input-sm" autocomplete="off" placeholder="완료일 시작" value="<?=$sscompletedate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="secompletedate" class="form-control input-sm" autocomplete="off" placeholder="완료일 끝" value="<?=$secompletedate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<select class="form-control input-sm" name="stype">
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
							<option value="buyer" <?=($stype=='buyer') ? "selected='selected'" : ""?>>구매자</option>
							<option value="buyerphone" <?=($stype=='buyerphone') ? "selected='selected'" : ""?>>연락처</option>
						</select>

						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./refund/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
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
	                     <th>판매구분</th>
						 <th>사진</th>
	                     <th>판매일자</th>
	                     <th>신청일자</th>
	                     <th>완료일자</th>
                         <th>지점명(층)</th>
	                     <th>모델명</th>
	                     <th>판매예정금액</th>
	                     <th>결제취소금액</th>
	                     <th>결제방법</th>
	                     <th>구매자</th>
	                     <th>구매자연락처</th>
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
	                     $selltype = $v->selltype;
	                     $thumb = $v->thumb;
	                     $selldate = $v->selldate;
	                     $applydate = $v->applydate;
	                     $completedate = $v->completedate;
	                     $modelname = $v->modelname;
	                     $price = $v->price;
	                     $paymethod = $v->paymethod;
	                     $buyer = $v->buyer;
	                     $buyerphone = $v->buyerphone;
	                     $process = $v->process;
	                     $paymentprice = $v->paymentprice;
	                     $paymentprice = number_format($paymentprice);
	                     $account_conf = $v->account_conf;

	                     $trclass = '';
	                     if($process == 'Y') $trclass = ' class="active"';
	                     if($account_conf == 'Y') $trclass = ' class="active5"';
                         if($process == 'N') $trclass = ' class="reject"';

                         $place = $v->place;
                         $floor = $v->floor;
                         if(!$floor) $floor = '1F';
                         $place = $place.'('.$floor.')';

                         $c24_display = $v->c24_display;
	               ?>
	                  <tr<?=$trclass?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><?=$selltype?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$selldate?></td>
	                     <td><?=$applydate?></td>
	                     <td><?=$completedate?></td>
                         <td><?=$place?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <td><?=number_format($price)?></td>
	                     <td><?=$paymentprice?></td>
	                     <td><?=$paymethod?></td>
	                     <td><?=$buyer?></td>
	                     <td><?=$buyerphone?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/refund/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 반품수정</button>
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
