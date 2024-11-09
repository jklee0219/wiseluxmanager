<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/purchase.css?<?=time()?>">
   <script src="/admn/js/purchase.js?<?=time()?>"></script>
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
            		<?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9)) || in_array($this->session->userdata('ADM_ID'),array('lagerfeld'))){ ?>
            		<div class="amount_info_wrap">
            			<table>
                            <?php
                            $totpprice1 = 0;
                            $totpprice2 = 0;
                            foreach($stocktotprice as $v){
                                if($v->stock == 'Y'){
                                    $totpprice1 = $v->pprice;
                                }
                                if($v->stock == 'N'){
                                    $totpprice2 = $v->pprice;
                                }    
                            }?>
            				<tr>
            					<td class="bg">자료검색</td>
            					<td class="txt"><?=number_format($total_cnt)?>건</td>
            					<td class="bg">총매입금액</td>
            					<td class="txt"><?=number_format($total_purchaseprice1)?>원</td>
								<td class="bg">총위탁금액</td>
            					<td class="txt"><?=number_format($total_purchaseprice2)?>원</td>
                                <td class="bg">총교환금액</td>
                                <td class="txt"><?=number_format($getType3Data)?>원</td>
            					<td class="bg">총판매예정금액</td>
            					<td class="txt"><?=number_format($total_purchaseprice)?>원</td>

								<td class="bg">매입</td>
            					<td class="txt"><?=number_format($getType1Cnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg">등록수량</td>
            					<td class="txt"><?=number_format($getOnlineyData1)?>건</td>
            					<td class="bg">등록된매입금액</td>
            					<td class="txt"><?=number_format($getOnlineyData2)?>원</td>
            					<td class="bg">등록된위탁금액</td>
            					<td class="txt"><?=number_format($getOnlineyData3)?>원</td>
                                <td class="bg">등록교환금액</td>
                                <td class="txt"><?=number_format($getType4Data)?>원</td>
            					<td class="bg">재고있음 판매합계</td>
            					<td class="txt"><?=number_format($stocktotpriceY)?>원</td>
            					<td class="bg">위탁</td>
            					<td class="txt"><?=number_format($getType2Cnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg">미등록수량</td>
            					<td class="txt"><?=number_format($getOnlinenData1)?>건</td>
            					<td class="bg">미등록매입금액</td>
            					<td class="txt"><?=number_format($getOnlinenData2)?>원</td>
            					<td class="bg">미등록위탁금액</td>
            					<td class="txt"><?=number_format($getOnlinenData3)?>원</td>
                                <td class="bg">미등록교환금액</td>
                                <td class="txt"><?=number_format($getType5Data)?>원</td>
            					<td class="bg">재고없음 판매합계</td>
            					<td class="txt"><?=number_format($stocktotpriceN)?>원</td>
								<td class="bg">기타</td>
            					<td class="txt"><?=number_format($getType3Cnt)?>건</td>
            				</tr>
            				<tr>
            					<td class="bg"></td>
            					<td class="txt"></td>
            					<td class="bg"></td>
            					<td class="txt"></td>
                                <td class="bg"></td>
                                <td class="txt"></td>
            					<td class="bg"></td>
            					<td class="txt"></td>
                                <td class="bg">재고있음 매입가합계</td>
                                <td class="txt"><?=number_format($totpprice1)?>원</td>
            					<td class="bg">교환</td>
            					<td class="txt"><?=number_format($getType4Cnt)?>건</td>
            				</tr>
                            <tr>
                                <td class="bg"></td>
                                <td class="txt"></td>
                                <td class="bg"></td>
                                <td class="txt"></td>
                                <td class="bg"></td>
                                <td class="txt"></td>
                                <td class="bg"></td>
                                <td class="txt"></td>
		                         <td class="bg">재고없음 매입가합계</td>
                                <td class="txt"><?=number_format($totpprice2)?>원</td>
								<td class="bg">반환</td>
                                <td class="txt"><?=number_format($getType5Cnt)?>건</td>
                            </tr>
            			</table>
            		</div>
            		<?php } ?>
               		<form name="searchFrm" action="/admn/purchase" method="get">
               			<div style="margin-bottom:6px">
               				<select class="form-control input-sm" name="smmpricecol" id="smmpricecol">
								<?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
								<option value="pprice" <?=($smmpricecol=='pprice') ? "selected='selected'" : ""?>>매입거래가</option>
								<?php } ?>
	                  			<option value="price" <?=($smmpricecol=='price') ? "selected='selected'" : ""?>>판매예정금액</option>
							</select>
							<input type="text" class="form-control input-sm" autocomplete="off" name="sminprice" id="sminprice" value="<?=$sminprice?>" placeholder="20000"> <span>~</span>
							<input type="text" class="form-control input-sm" autocomplete="off" name="smaxprice" id="smaxprice" value="<?=$smaxprice?>" placeholder="300000">
               			</div>
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
							<select class="form-control input-sm" name="sonlineyn">
								<option value="">온라인등록(전체)</option>
								<option value="Y" <?=($sonlineyn=='Y') ? "selected='selected'" : ""?>>등록</option>
	                  			<option value="N" <?=($sonlineyn=='N') ? "selected='selected'" : ""?>>미등록</option>
	                  			<option value="F" <?=($sonlineyn=='F') ? "selected='selected'" : ""?>>매입실패</option>
							</select>
							<select class="form-control input-sm" name="sstype">
								<option value="">구분(전체)</option>
								<?php foreach($purchase_type as $v){ ?>
								<option value="<?=$v?>" <?=($sstype==$v) ? "selected='selected'" : ""?>><?=$v?></option>	
								<?php } ?>
							</select>
							<select class="form-control input-sm" name="spaymethod">
								<option value="">지급방법</option>
								<?php foreach($purchase_paymethod as $v){ ?>
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
						</div>
						<select class="form-control input-sm" name="saccount_conf">
							<option value="">경리팀확인(전체)</option>
							<option value="N"<?=($saccount_conf=='N') ? " selected='selected'" : ""?>>아니요</option>
							<option value="Y"<?=($saccount_conf=='Y') ? " selected='selected'" : ""?>>예</option>
						</select>
						<select class="form-control input-sm" name="splace">
							<option value="">매입지점(전체)</option>
							<?php foreach($goods_place as $v){ ?>
							<option value="<?=$v?>" <?=($splace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
							<?php } ?>
						</select>
                        <select class="form-control input-sm" name="smanager">
                            <option value="">매입담당(전체)</option>
                            <?php foreach($manager_list as $v){ ?>
                            <option value="<?=$v->name?>" <?=($smanager==$v->name) ? "selected='selected'" : ""?>><?=$v->viewname?></option>
                            <?php } ?>
                        </select>
						<select class="form-control input-sm" name="sstock">
							<option value="">재고(전체)</option>
							<option value="Y" <?=($sstock==$v) ? "selected='selected'" : ""?>>재고있음</option>
							<option value="N" <?=($sstock==$v) ? "selected='selected'" : ""?>>재고없음</option>
						</select>
						<select class="form-control input-sm" name="spurchase_method">
							<option value="">매입방법(전체)</option>
							<?php foreach($purchase_method as $v){ ?>
                  			<option value="<?=$v?>" <?=($spurchase_method==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                  			<?php } ?>
						</select>
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
                  		<button type="button" onclick="location.href='./purchase/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>거래번호</th>
	                     <th>상품코드</th>
	                     <th>판매자</th>
						      <th>판매자연락처</th>
	                     <th>매입일자</th>
	                     <th>구분</th>
	                     <th>매입방법</th>
	                     <th>종류</th>
	                     <th>매입지점</th>
	                     <th>모델명</th>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>

	                     <th>판매예정금액</th>
                        <th>매입거래가격</th>
	                     <?php } ?>
	                     <th>등급</th>
	                     <th>지급방법</th>
	                     <th>비고</th>
	                     <th>재고</th>
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
	                     $onlineyn = $v->onlineyn;
	                     $seller = $v->seller;
	                     $sellerphone = $v->sellerphone;
	                  	 $pdate = $v->pdate;
						 if($pdate){
							 $pdate = strtotime($pdate);
							 $pdate = date('y-m-d H:i', $pdate);
						 }else{
						 	$pdate = '';
						 }
	                     $type = $v->type;
	                     $type_str = $type;
	                     if($type == '위탁') $type_str = '<font color="#3939ff">'.$type.'</font>';
	                     $method = $v->method;
	                     $kind = $v->kind;
	                     $modelname = $v->modelname;
	                     $price = $v->price;
	                     $goods_price = $v->goods_price;
	                     $pprice = $v->pprice;
	                     $pprice = number_format($pprice);
	                     $class = $v->class;
	                     $paymethod = $v->paymethod;
	                     $note = $v->note;
	                     if(mb_strlen($note) > 20){
	                     	$note = mb_substr($note, 0, 20, 'utf-8').'..';
	                     }
	                     $manager = $v->manager;
	                     $goods_seq = $v->goods_seq;
	                     $asinfo_seq = $v->asinfo_seq;
	                     $account_conf = $v->account_conf;
	                     $stock = '';
	                     if($v->goods_stock=='Y') $stock = '있음';
	                     if($v->goods_stock=='N') $stock = '없음';

	                     $place = $v->place ? $v->place : '';

	                     $trclass = '';
	                     if($onlineyn=='Y'){
	                     	$trclass = ' class="active"';
	                     }else if($onlineyn=='F'){
	                     	$trclass = ' class="active2"';
	                     }

	                     if($type == '위탁' && $onlineyn=='Y' && ($paymethod == '입금' || $paymethod == '현금')){
	                     	$trclass = ' class="active4"';	
	                     }

	                     if($type == '위탁' && $paymethod == '입금(위탁정산완료)' && $onlineyn=='Y'){
	                     	$trclass = ' class="active3"';	
	                     }

	                     if($account_conf == 'Y') $trclass = ' class="active5"';

                         $c24_display = $v->c24_display;
	               ?>
	                  <tr<?=$trclass?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><?=$seller?></td>
	                     <td><?=$sellerphone?></td>
	                     <td><?=$pdate?></td>
	                     <td><?=$type_str?></td>
	                     <td><?=$method?></td>
	                     <td><?=$kind?></td>
	                     <td><?=$place?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
	                     <td><?=$pprice?></td>
	                     <?php }?>
	                     <td><?=number_format($goods_price)?></td>
	                     <td><?=$class?></td>
	                     <td><?=$paymethod?></td>
	                     <td class="aleft"><?=$note?></td>
	                     <td><?=$stock?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/purchase/modify?seq=<?=$seq?>&<?=$param?>'" class="board_modify_btn btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 매입수정</button>
	                     	<?php if($goods_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/goods/modify?seq=<?=$goods_seq?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 상품수정</button>
	                     	<?php } ?>
	                     	<?php if($asinfo_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/asinfo/modify?seq=<?=$asinfo_seq?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> AS수정</button>
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
