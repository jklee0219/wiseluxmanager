<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/purchase.css?1">
   <script>
   var qs = "<?=$param?>";
   var limit_file_size = <?=LIMIT_FILE_SIZE?>;
   var attch_file_ext = '<?=ATTACH_FILE_EXT?>';
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/purchase_modify.js?<?=time()?>"></script>
</head>
<body>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

   <div id="content">
      <div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

      <div class="content_write">

			<form class="form-horizontal" action="/admn/purchase/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
				<div class="form-group">
               		<label class="col-sm-3 control-label">거래번호</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' readonly="readonly" class='form-control input-sm' name='seq' id="seq" value="<?=$seq?>">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label">상품코드</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' readonly="readonly" class='form-control input-sm' name='pcode' id="pcode" value="<?=$view->pcode?>">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="seller">판매자</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm' autocomplete="off" name='seller' id="seller" maxlength="33" value="<?=$view->seller?>">
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="sellerphone1">판매자연락처</label>
               		<div class="col-sm-6 pt2" style="width:400px">
               			<?php
               			$sellerphone = $view->sellerphone;
               			$sellerphone = explode('-',$sellerphone);
               			$sellerphone1 = '';
               			$sellerphone2 = '';
               			$sellerphone3 = '';
               			if(count($sellerphone) == 3){
	               			$sellerphone1 = $sellerphone[0];
	               			$sellerphone2 = $sellerphone[1];
	               			$sellerphone3 = $sellerphone[2];
               			}
               			?>
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:50px;display:inline-block;" name='sellerphone1' id="sellerphone1" maxlength="4" value="<?=$sellerphone1?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='sellerphone2' id="sellerphone2" maxlength="4" value="<?=$sellerphone2?>"> -
                  		<input type='text' class='form-control input-sm onlynum' autocomplete="off" style="width:60px;display:inline-block;" name='sellerphone3' id="sellerphone3" maxlength="4" value="<?=$sellerphone3?>">
               		</div>
            	</div>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="birth">생년월일</label>
                  <div class="col-sm-3 pt2">
                     <input type='text' class='form-control input-sm' autocomplete="off" name='birth' id="birth" maxlength="6" placeholder="" value="<?=$view->birth?>">
                  </div>
               </div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="onlineyn">온라인등록여부</label>
					<div class="col-sm-2 pt2">
						<select name="onlineyn" id="onlineyn" class="form-control input-sm">
							<option value="N" <?=($view->onlineyn == 'N') ? 'selected="selected"' : ''?>>미등록</option>
							<option value="Y" <?=($view->onlineyn == 'Y') ? 'selected="selected"' : ''?>>등록</option>
							<option value="F" <?=($view->onlineyn == 'F') ? 'selected="selected"' : ''?>>매입실패</option>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label">매입일자</label>
               		<div class="col-sm-3 pt2">
	               		<?php
						$pdate = $view->pdate;
	               		if($pdate){
		               		$pdate = strtotime($pdate);
		               		$pdate = date('Y-m-d', $pdate);
	               		}else{
	               			$pdate = '';
	               		}
	               		?>
                  		<input type='text' class='form-control input-sm' name='pdate' id="pdate" readonly="readonly" value="<?=$pdate?>">
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="type">구분<?=$required_mark?></label>
					<div class="col-sm-3 pt2">
						<select name="type" id="type" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_type as $v) {?>
							<option value="<?=$v?>" <?=($view->type == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="method">매입방법</label>
					<div class="col-sm-3 pt2">
						<select name="method" id="method" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_method as $v) {?>
							<option value="<?=$v?>" <?=($view->method == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="kind">종류</label>
					<div class="col-sm-3 pt2">
						<select name="kind" id="kind" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_kind as $v) {?>
							<option value="<?=$v?>" <?=($view->kind == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="pbrand_seq">브랜드명<?=$required_mark?></label>
					<div class="col-sm-4 pt2">
						<select name="pbrand_seq" id="pbrand_seq" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($brand_list as $v){ ?>
							<option value="<?=$v->seq?>" <?=($view->pbrand_seq == $v->seq) ? 'selected="selected"' : ''?>><?=$v->name?></option>
							<?php } ?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="modelname">모델명</label>
               		<div class="col-sm-9 pt2">
               			<input type='text' class='form-control input-sm' autocomplete="off" name='modelname' id='modelname' maxlength="680" value="<?=$view->modelname?>">
               		</div>
            	</div>
            	<?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="pprice">매입거래가격</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='pprice' id='pprice' maxlength="20" value="<?=number_format($view->pprice)?>">
               		</div>
            	</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="exprice">교환금액</label>
                    <div class="col-sm-3 pt2">
                        <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='exprice' id='exprice' maxlength="20" value="<?=number_format($view->exprice)?>">
                    </div>
                </div>
            	<?php } ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="goods_price">판매예정금액</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='goods_price' id='goods_price' maxlength="20" value="<?=number_format($view->goods_price)?>">
                  		<div class="price_guide">
                  			수수료 : <span class="price_guide1">0</span>원&nbsp;&nbsp;&nbsp;AS수리비 : <span class="price_guide3">0</span>원&nbsp;&nbsp;&nbsp;정산예정금액 : <span class="price_guide2">0</span>원
	                  	</div>
               		</div>
            	</div>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="asprice">AS수리비</label>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='asprice' id='asprice' maxlength="20" value="<?=number_format($view->asprice)?>" readonly="readonly">
               		</div>
            	</div>
               <div class="form-group">
                     <label class="col-sm-3 control-label" for="place">매입지점</label>
                     <div class="col-sm-2 pt2">
                        <select name="place" id="place" class="form-control input-sm">
                        <?php foreach($goods_place as $v){ ?>
                        <option value="<?=$v?>" <?=($view->place == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
                        <?php } ?>
                        </select>
                     </div>
               </div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="class">등급</label>
					<div class="col-sm-3 pt2">
						<select name="class" id="class" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_class as $v) {?>
							<option value="<?=$v?>" <?=($view->class == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group cus">
					<label class="col-sm-3 control-label" for='astype'>AS요청</label>
					<div class="col-sm-9 pt2">
						<?php foreach($goods_astype as $v){ ?>
						<label><input type='checkbox' class='form-control input-sm' name='astype[]' id='astype' value="<?=$v?>" <?=(strpos('|'.$view->astype.'|', '|'.$v.'|') !== false) ? 'checked="checked"' : ''?>><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						<?php } ?>
                        <?php 
                         $temparr = explode('|', $view->astype);
                         $astype_etc_txt = '';
                         foreach($temparr as $v){
                            if(strpos($v, '기타!@#^') !== false){
                               $astype_etc_txt = str_replace('기타!@#^','',$v);
                               break;
                            }
                         }
                         ?>
                        <label><input type='checkbox' class='form-control input-sm' name='astype_etc_chk' value="기타"<?=(strpos('|'.$view->astype.'|', '기타') !== false) ? ' checked="checked"' : ''?>><p class="checkboxtxt">기타</p><input type="text" <?=(strpos('|'.$view->astype.'|', '기타') !== false) ? '' : 'disabled="disabled"'?> name="astype_etc_txt" value="<?=$astype_etc_txt?>" class='form-control input-sm'></label>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="reason">AS신청사유</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name="reason" id='reason'><?=$view->reason?></textarea>
               		</div>
            	</div>
            	<div class="form-group cus">
                  <label class="col-sm-3 control-label" for='note'>참고사항</label>
                  <div class="col-sm-9 pt2">
                     <?php foreach($goods_note as $v){ ?>
                     <label><input type='checkbox' class='form-control input-sm' name='reference[]' id='reference' value="<?=$v?>" <?=(strpos('|'.$view->reference.'|', '|'.$v.'|') !== false) ? 'checked="checked"' : ''?>><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						   <?php } ?>
                     <?php 
                     $temparr = explode('|', $view->reference);
                     $reference_etc_txt = '';
                     foreach($temparr as $v){
                        if(strpos($v, '기타!@#^') !== false){
                           $reference_etc_txt = str_replace('기타!@#^','',$v);
                           break;
                        }
                     }
                     ?>
                     <label><input type='checkbox' class='form-control input-sm' name='reference_etc_chk' value="기타"<?=(strpos('|'.$view->reference.'|', '기타!@#^') !== false) ? ' checked="checked"' : ''?>><p class="checkboxtxt">기타</p><input type="text" <?=(strpos('|'.$view->reference.'|', '기타!@#^') !== false) ? '' : 'disabled="disabled"'?> name="reference_etc_txt" value="<?=$reference_etc_txt?>" class='form-control input-sm'></label>
                  </div>
            	</div>
            	<!-- 
            	<div class="form-group">
					<label class="col-sm-3 control-label" for='guarantee'>보증서</label>
					<div class="col-sm-9 pt2">
						<?php foreach($goods_guarantee as $v){ ?>
						<label><input type='checkbox' class='form-control input-sm' name='guarantee[]' id='guarantee' value="<?=$v?>" <?=(strpos('|'.$view->guarantee.'|', '|'.$v.'|') !== false) ? 'checked="checked"' : ''?>><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						<?php } ?>
	               	</div>
            	</div>
            	 -->
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="paymethod">지급방법</label>
					<div class="col-sm-3 pt2">
						<select name="paymethod" id="paymethod" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_paymethod as $v) {?>
							<option value="<?=$v?>" <?=($view->paymethod == $v) ? 'selected="selected"' : ''?>><?=$v?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="account">입금계좌번호</label>
					<div class="col-sm-7 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name='account' id='account' maxlength="100" value="<?=$view->account?>">
	               	</div>
            	</div>
               <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="account">경리팀확인</label>
                  <div class="col-sm-7 pt2">
                     <label class="account_conf_wrap">아니오 <input name="account_conf" type="radio" value="N"<?=($view->account_conf == 'N') ? ' checked="checked"' : ''?>></label>
                     <label class="account_conf_wrap">예 <input name="account_conf" type="radio" value="Y"<?=($view->account_conf == 'Y') ? ' checked="checked"' : ''?>></label>
                  </div>
               </div>
               <?php }?>
               <?php if($this->session->userdata('ADM_AUTH') != '3'){ ?>
               <div class="form-group">
                  <label class="col-sm-3 control-label" for="goods_stock">재고여부</label>
                  <div class="col-sm-2 pt2">
                     <select name="goods_stock" id="goods_stock" class="form-control input-sm">
                        <option value="Y" <?=($view->goods_stock=='Y') ? 'selected="selected"' :''?>>보유</option>
                        <option value="N" <?=($view->goods_stock=='N') ? 'selected="selected"' :''?>>없음</option>
                     </select>
                  </div>
               </div>
               <?php } ?>
            	<div class="form-group">
               		<label class="col-sm-3 control-label" for="note">비고</label>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:200px" name='note' id='note'><?=$view->note?></textarea>
               		</div>
            	</div>
            	<div class="form-group">
					<label class="col-sm-3 control-label" for="manager">매입담당</label>
					<div class="col-sm-3 pt2">
						<select name="manager" id="manager" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($manager_list as $v) {?>
							<option value="<?=$v->name?>" <?=($view->manager == $v->name) ? 'selected="selected"' : ''?>><?=$v->viewname?></option>
							<?php }?>
	                  	</select>
	               	</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_modify_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" data-seq="<?=$view->seq?>" class="board_copy_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 복사</button>
               		<?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
	                <button type="button" data-seq="<?=$view->seq?>" class="board_delete_btn btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
	                <?php }?>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/purchase?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
