<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/goods.css?<?=time()?>">
   <script src="/admn/js/goods.js?<?=time()?>"></script>
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
            				<?php
            				$totnum1 = 0;
            				$totprice1 = 0;
            				$totpprice1 = 0;
            				$totnum2 = 0;
            				$totprice2 = 0;
            				$totpprice2 = 0;
            				foreach($stocktotprice as $v){
            					if($v->stock == 'Y'){
            						$totnum1 = $v->cnt;
									$totprice1 = $v->price;
									$totpprice1 = $v->pprice;
            					}
            					if($v->stock == 'N'){
									$totnum2 = $v->cnt;
									$totprice2 = $v->price;
									$totpprice2 = $v->pprice;
            					}	 
            				}?>
            				<tr>
            					<td class="bg">재고있음</td>
            					<td class="txt"><?=number_format($totnum1)?>건</td>
            					<td class="bg2">재고있음 판매합계</td>
            					<td class="txt"><?=number_format($totprice1)?>원</td>
            					<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            					<td class="bg2">재고있음 매입가합계</td>
            					<td class="txt"><?=number_format($totpprice1)?>원</td>
            					<?php }?>
            				</tr>
            				<tr>
            					<td class="bg">재고없음</td>
            					<td class="txt"><?=number_format($totnum2)?>건</td>
            					<td class="bg2">재고없음 판매합계</td>
            					<td class="txt"><?=number_format($totprice2)?>원</td>
            					<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            					<td class="bg2">재고없음 매입가합계</td>
            					<td class="txt"><?=number_format($totpprice2)?>원</td>
            					<?php }?>
            				</tr>
            			</table>
            		</div>
               		<form name="searchFrm" action="/admn/goods" method="get">
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
                            <select class="form-control input-sm" name="sc24display">
                                <option value="">진열상태(전체)</option>
                                <option value="T" <?=($sc24display=='T') ? "selected='selected'" : ""?>>진열함</option>
                                <option value="F" <?=($sc24display=='F') ? "selected='selected'" : ""?>>진열안함</option>
 
                            </select>
							<select class="form-control input-sm" name="sorder">
								<option value="1" <?=($sorder=='1') ? "selected='selected'" : ""?>>등록순</option>
	                  			<option value="2" <?=($sorder=='2') ? "selected='selected'" : ""?>>낮은가격순</option>
	                  			<option value="3" <?=($sorder=='3') ? "selected='selected'" : ""?>>높은가격순</option>
							</select>
							<select class="form-control input-sm" name="sstock">
								<option value="">재고선택(전체)</option>
	                  			<option value="Y" <?=($sstock=='Y') ? "selected='selected'" : ""?>>보유</option>
	                  			<option value="N" <?=($sstock=='N') ? "selected='selected'" : ""?>>없음</option>
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

						</div>
						<div style="margin-bottom:6px">
						<div class="startdate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="ssrdate" class="form-control input-sm" autocomplete="off" placeholder="등록일 시작" value="<?=$ssrdate?>">
							    <div class="input-group-addon input-sm">
							        <span class="glyphicon glyphicon-calendar"></span>
							    </div>
							</div>
						</div>~
						<div class="enddate_wrap">
	               			<div class="input-group date">
							    <input type="text" name="serdate" class="form-control input-sm" autocomplete="off" placeholder="등록일 끝" value="<?=$serdate?>">
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
						<select class="form-control input-sm" name="stype">
                  			<option value="modelname" <?=($stype=='modelname') ? "selected='selected'" : ""?>>모델명</option>
							<option value="pcode" <?=($stype=='pcode') ? "selected='selected'" : ""?>>상품코드</option>
						</select>
						</div>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
							
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./goods/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
						
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>상품번호</th>
	                     <th>상품코드</th>
	                     <th>사진</th>
						 <th>브랜드</th>
						 <th>매입구분</th>
	                     <th>매입일자</th>
	                     <th>등록일자</th>
	                     <th>종류</th>
	                     <th>지점명(층)</th>
	                     <th>모델명</th>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
	                     <th>매입가격</th>
	                     <?php }?>
	                     <th>판매예정금액</th>
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
	                     $thumb = $v->thumb;
	                     if($thumb == '') $thumb = '/admn/img/noimg_l.jpg';
						 $brandname = $v->brandname;
						 $pdate = $v->pdate;
						 if($pdate){
							 $pdate = strtotime($pdate);
							 $pdate = date('y-m-d H:i', $pdate);
						 }else{
						 	$pdate = '';
						 }
	                     $kind = $v->kind;
	                     $modelname = $v->modelname;
	                     $price = $v->price;
	                     $price = number_format($price);
	                     $pprice = $v->pprice;
	                     $pprice = number_format($pprice);
	                     $stock = $v->stock;
	                     $stock = ($stock=='Y') ? '있음' : '없음';
	                     $note = $v->note;
	                     $purchase_seq = $v->purchase_seq;
	                     $asinfo_seq = $v->asinfo_seq;
	                     $trade_seq = $v->trade_seq;
	                     $rdate = $v->rdate;
	                     if(strpos($rdate, '0000-00-00') !== false){
	                     	$rdate = '';
	                     }
	                     if($rdate){
	                     	$rdate = strtotime($rdate);
							$rdate = date('y-m-d', $rdate);
	                     }
	                     $type = $v->purchase_type;
	                     $type_str = $type;
	                     if($type == '위탁') $type_str = '<font color="#3939ff">'.$type.'</font>';
	                     //$c24_product_no = $v->c24_product_no ? $v->c24_product_no : '';
	                     $c24_origin_place_value = $v->c24_origin_place_value;
	                     $floor = $v->floor;
	                     if(!$floor) $floor = '1F';
	                     $c24_origin_place_str = $c24_origin_place_value.'('.$floor.')';

	                     $classstr = '';
	                     if($stock=='없음'){
	                     	$classstr = ' class="active"';
	                     }
	                     if($type=='위탁'){
	                     	$classstr = ' class="active4"';
	                     }
                         $c24_display = $v->c24_display;
	               ?>
	                  <tr<?=$classstr?>>
	                     <td><?=$seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><div class="thumb"><img src="<?=$thumb?>"></div></td>
	                     <td><?=$brandname?></td>
	                     <td><?=$type_str?></td>
	                     <td><?=$pdate?></td>
	                     <td><?=$rdate?></td>
	                     <td><?=$kind?></td>
	                     <td><?=$c24_origin_place_str?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
	                     <td><?=$pprice?></td>
	                     <?php }?>
	                     <td><?=$price?></td>
	                     <td><?=$stock?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/goods/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 상품수정</button>
	                     	<?php if($trade_seq){ ?>
	                     	<button type="button" onclick="location.href='/admn/trade/modify?seq=<?=$trade_seq?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 거래수정</button>
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
