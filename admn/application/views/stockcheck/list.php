<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/stockcheck.css?<?=time()?>">
   <script src="/admn/js/stockcheck.js?<?=time()?>"></script>
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
               		<form name="searchFrm" action="/admn/stockcheck" method="get">
               			<div style="margin-bottom:6px">
               			<!-- 
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
								 -->
							<select class="form-control input-sm" name="sstock">
								<option value="">재고선택(전체)</option>
	                  			<option value="Y" <?=($sstock=='Y') ? "selected='selected'" : ""?>>보유</option>
	                  			<option value="N" <?=($sstock=='N') ? "selected='selected'" : ""?>>없음</option>
							</select>
							<!-- 
							<select class="form-control input-sm" name="spaymethod">
								<option value="">결제방법(전체)</option>
								<?php foreach($trade_paymethod as $v){ ?>
	                  			<option value="<?=$v?>" <?=($spaymethod==$v) ? "selected='selected'" : ""?>><?=$v?></option>
	                  			<?php } ?>
							</select>
							-->
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
                         <th>구분</th>
	                     <th>모델명</th>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
	                     <th>매입거래가격</th>
	                     <?php }?>
	                     <th>매입일자</th>
	                     <th>상품등록일자</th>
                        <th>재고검수등록일자</th>
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
	                     $goods_seq = $v->goods_seq;
	                     $purchase_seq = $v->purchase_seq;
	                     $trade_seq = $v->trade_seq;
	                     $pcode = $v->pcode;
	                     $seller = $v->seller;
	                     $modelname = $v->modelname;
	                     $pprice = $v->pprice;
	                     $pdate = $v->pdate;
	                     if(strpos($pdate, '0000-00') === false){
	                         $pdate_arr = explode(' ', $pdate);
	                         $pdate = $pdate_arr[0];
	                     }
	                     $rdate = $v->rdate;
	                     if(strpos($rdate, '0000-00') === false){
	                         $rdate_arr = explode(' ', $rdate);
                            $rdate = $rdate_arr[0];
	                     }
	                     $price = $v->price;					 
	                     $buyer = $v->buyer;
	                     $stock = $v->stock;
	                     $stock = $stock=='Y' ? '있음' : '없음';
                        $stockcheck_date = $v->stockcheck_date;

                        $c24_display = $v->c24_display;

                        $type = $v->type;
                        $type_str = $type;
                        if($type == '위탁') $type_str = '<font color="#3939ff">'.$type.'</font>';
	               ?>
	                  <tr>
	                     <td><?=$purchase_seq?></td>
	                     <td class="c24_display_<?=$c24_display?>"><?=$pcode?></td>
	                     <td><?=$seller?></td>
                         <td><?=$type_str?></td>
	                     <td class="aleft"><?=$modelname?></td>
	                     <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
	                     <td><?=number_format($pprice)?></td>
	                     <?php }?>
	                     <td><?=$pdate?></td>
	                     <td><?=$rdate?></td>
                        <td><?=$stockcheck_date?></td>
	                     <td><?=number_format($price)?></td>

	                     <td><?=$stock?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/stockcheck/modify?seq=<?=$goods_seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 재고검수수정</button>
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
