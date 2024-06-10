<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/asinfo.css">
   <script src="/admn/js/asinfo.js?<?=time()?>"></script>
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
            					<td class="txt"><?=number_format($asnCnt)?>건</td>
            					<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            					<td class="bg">미처리매입가격</td>
            					<td class="txt"><?=number_format($totPprise2)?>원</td>
            					<?php }?>
            				</tr>
            				<tr>
            					<td class="bg">처리완료</td>
            					<td class="txt"><?=number_format($asyCnt)?>건</td>
            					<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            					<td class="bg">처리완료매입가격</td>
            					<td class="txt"><?=number_format($totPprise1)?>원</td>
            					<?php }?>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/asinfo" method="get">
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
						<select class="form-control input-sm" name="sasyn">
							<option value="">처리여부(전체)</option>
							<option value="Y" <?=($sasyn=='Y') ? "selected='selected'" : ""?>>처리완료</option>
                  			<option value="N" <?=($sasyn=='N') ? "selected='selected'" : ""?>>미처리</option>
						</select>
						<select class="form-control input-sm" name="stype">
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
                  <!--   	<option value="seller" <?=($stype=='seller') ? "selected='selected'" : ""?>>판매자</option> -->
                  			<option value="buyer" <?=($stype=='buyer') ? "selected='selected'" : ""?>>구매자</option>
                  <!--		<option value="sellerphone" <?=($stype=='sellerphone') ? "selected='selected'" : ""?>>판매자연락처</option> -->
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
                  <!--		<option value="manager" <?=($stype=='manager') ? "selected='selected'" : ""?>>매입담당</option> -->
                  			<option value="reason" <?=($stype=='reason') ? "selected='selected'" : ""?>>AS신청사유</option>
							<option value="tb_purchase.note" <?=($stype=='tb_purchase.note') ? "selected='selected'" : ""?>>비고</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./asinfo/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
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
	                     <th style="min-width:80px">구매자</th>
                         <th style="min-width:80px">구분</th>
	                <!--     <th>구매자연락처</th> -->
	                     <th>모델명</th>
	                     <th>AS신청사유</th>
	                     <th>신청날짜</th>
	                     <th>마감날짜</th>
	                     <th>AS신청결과</th>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
	                     <th>매입가격</th>
	                     <?php }?>
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
						 $seller = $v->seller;
						 $sellerphone = $v->sellerphone;
                         $buyerphone = $v->buyerphone;
						 
	                     $type = $v->type;
	                     $type_str = $type;
	                     if($type == '위탁') $type_str = '<font color="#3939ff">'.$type.'</font>';
						 
						 $modelname = $v->modelname;
	                     $reason = $v->reason;
	                     $start_date = $v->start_date;
	                     if($start_date){
	                     	$start_date = strtotime($start_date);
	                     	$start_date = date('Y-m-d', $start_date);
	                     }else{
	                     	$start_date = '';
	                     }
	                     $end_date = $v->end_date;
	                     if($end_date){
	                     	$end_date = strtotime($end_date);
	                     	$end_date = date('Y-m-d', $end_date);
	                     }else{
	                     	$end_date = '';
	                     }
	                     $result = $v->as_yn;
	                     $resul_str = $result == 'Y' ? '처리완료' : '미처리';
	                     //$note = $v->note;
	                     $buyer = $v->buyer;
						 // $buyer = $buyer ? '('.$buyer.')' : '';
	                     $buyer = $buyer ? ''.$buyer.'' : '';
	                     $pprice = $v->pprice;

                         $c24_display = $v->c24_display;
	               ?>
	                  <tr <?=($result=='Y') ? 'class="active"' : ''?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$pdate?></td>
	                     <td><?=$buyer?></td>
	                     <td><?=$type_str?></td>
	                <!--     <td><?=$buyerphone?></td> -->
	                     <td><?=$modelname?></td>
	                     <td><?=$reason?></td>
	                     <td><?=$start_date?></td>
	                     <td><?=$end_date?></td>
	                     <td><?=$resul_str?></td>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
	                     <td><?=number_format($pprice)?></td>
	                     <?php }?>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/asinfo/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> AS수정</button>
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
