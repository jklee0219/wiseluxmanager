<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/goodsmove.css">
   <script src="/admn/js/goodsmove.js?<?=time()?>"></script>
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
                                <?php foreach($goodsmove_yn as $v){ ?>
                                <option value="<?=$v?>" <?=($smoveyn==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                                <?php } ?>
                            </select>
                        </div>
                  		<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="발송일 시작" value="<?=$sshipdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="발송일 끝" value="<?=$eshipdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>
						<div class="startdate_wrap">
                            <div class="input-group date">
                                <input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="수령일 시작" value="<?=$srecivedate?>">
                                <div class="input-group-addon input-sm">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>~
                        <div class="enddate_wrap">
                            <div class="input-group date">
                                <input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="수령일 끝" value="<?=$erecivedate?>">
                                <div class="input-group-addon input-sm">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>
						<select class="form-control input-sm" name="stype">
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
                            <option value="seller" <?=($stype=='seller') ? "selected='selected'" : ""?>>판매자</option>
                            <option value="sellerphone" <?=($stype=='sellerphone') ? "selected='selected'" : ""?>>판매자연락처</option>
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
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
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>번호</th>
	                     <th>상품코드</th>
						 <th>사진</th>
	                     <th>매입일자</th>
	                     <th>구분</th>
	                     <th>종류</th>
	                     <th>매입지점</th>
	                     <th>모델명</th>
	                     <th>발송일자</th>
                         <th>수령일자</th>
                         <th>이동결과</th>
                         <th>발송지점</th>
                         <th>수령지점</th>
                         <th>수령인</th>
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
                         $recivedate = $v->recivedate;
                         if($recivedate){
                             $recivedate = strtotime($recivedate);
                             $recivedate = date('Y-m-d', $recivedate);
                         }else{
                            $recivedate = '';
                         }
                         $c24_display = $v->c24_display;
	               ?>
	                  <tr<?=($v->moveyn=='Y') ? ' class="active"' : ''?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$pdate?></td>
	                     <td><?=$v->type?></td>
                         <td><?=$v->kind?></td>
                         <td><?=$v->place?></td>
	                     <td><?=$modelname?></td>
	                     <td><?=$shipdate?></td>
	                     <td><?=$recivedate?></td>
	                     <td><?=$goodsmove_yn[$v->moveyn]?></td>
	                     <td><?=$v->shipplace?></td>
                         <td><?=$v->reciveplace?></td>
                         <td><?=$v->reciveman?></td>
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
