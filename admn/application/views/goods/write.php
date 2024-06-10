<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/goods.css?<?=time()?>">
   <script>
   var limit_file_size = <?=LIMIT_FILE_SIZE?>;
   var attch_file_ext = '<?=IMAGE_FILE_EXT?>';
   </script>
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/lib/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
   <script src="/admn/js/goods_write.js?v=3"></script>
</head>
<body>

   	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">
		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/goods/writeproc?<?=$param?>" method="post" role="form" name="boardForm" enctype="multipart/form-data">
				<input type="hidden" name="purchase_seq" value="">
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="pcode">상품코드 검색</div>
               		<div class="col-sm-9 pt2">
                  		<input type='text' style="width:100px;display:inline-block;" maxlength="7" class='form-control input-sm' autocomplete="off" name='pcode' id="pcode">
                  		<button type="button" onclick="getPurchaseInfoFromCode()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
               		</div>
            	</div>
            	
            	
            	<!-- 매입정보 시작 -->
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="purchase_kind">종류</div>
               		<div class="col-sm-3 pt2">
               			<select disabled="disabled" name="purchase_kind" id="purchase_kind" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_kind as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for='purchase_modelname'>모델명</div>
               		<div class="col-sm-9 pt2">
                  		<input type='text' class='form-control input-sm' disabled="disabled" name='purchase_modelname' id='purchase_modelname' value="" maxlength="99">
               		</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="purchase_pdate">매입날짜</div>
               		<div class="col-sm-3 pt2">
               			<div class="input-group date">
						    <input type="text" disabled="disabled" name='purchase_pdate' id='purchase_pdate' class="form-control input-sm" autocomplete="off" placeholder="" value="">
						    <div class="input-group-addon input-sm">
						        <span class="glyphicon glyphicon-calendar"></span>
						    </div>
						</div>
               		</div>
            	</div>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label" for="purchase_place">매입지점</div>
                    <div class="col-sm-2 pt2">
                        <select name="purchase_place" disabled="disabled" id="purchase_place" class="form-control input-sm">
                         <?php foreach($goods_place as $v){ ?>
                         <option value="<?=$v?>"><?=$v?></option>
                         <?php } ?>
                        </select>
                    </div>
                </div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for='purchase_method'>매입방법</div>
               		<div class="col-sm-3 pt2">
               			<select name="purchase_method" id="purchase_method" disabled="disabled" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_method as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="purchase_class">매입등급</div>
               		<div class="col-sm-3 pt2">
               			<select name="purchase_class" id="purchase_class" disabled="disabled" class="form-control input-sm">
							<option value="">선택하세요</option>
							<?php foreach($purchase_class as $v) {?>
							<option value="<?=$v?>"><?=$v?></option>
							<?php }?>
	                  	</select>
               		</div>
            	</div>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label" for='astype'>AS요청</div>
                    <div class="col-sm-9 pt2">
                        <?php foreach($goods_astype as $v){ ?>
                        <label><input type='checkbox' class='form-control input-sm' disabled="disabled" name='astype[]' id='astype' value="<?=$v?>"><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
                        <?php } ?>
                        <br/>
                        <label><input type='checkbox' class='form-control input-sm' disabled="disabled" name='purchase_astype_etc_chk' value="기타"><p class="checkboxtxt">기타</p><input type="text" disabled="disabled" name="purchase_astype_etc_txt" class='form-control input-sm'></label>
                    </div>
                </div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for='note'>참고사항</div>
					<div class="col-sm-10 pt2">
						<?php foreach($goods_note as $v){ ?>
						<label><input type='checkbox' class='form-control input-sm' disabled="disabled" name='purchase_reference[]' value="<?=$v?>"><p class="checkboxtxt"><?=$v?></p>&nbsp;&nbsp;</label>
						<?php } ?>
                        <br/>
                        <label><input type='checkbox' class='form-control input-sm' disabled="disabled" name='purchase_reference_etc_chk' value="기타"><p class="checkboxtxt">기타</p><input type="text" disabled="disabled" name="purchase_reference_etc_txt" class='form-control input-sm'></label>
	               	</div>
            	</div>
            	<!-- 매입정보 끝 -->
            	
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="c24_display">진열상태</div>
               		<div class="col-sm-3 pt2">
                  		<select name="c24_display" id="c24_display" class="form-control input-sm">
                  			<option value="F">진열안함</option>
                  			<option value="T">진열함</option>
                  		</select>
               		</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label">상품분류 선택<br/><button type="button" id="cafe24_category_refresh_btn" title="카페24에서 상품분류 새로 가져오기"><i class="glyphicon glyphicon-refresh"></i></button></div>
               		<div class="col-sm-8 pt2">
                  		<table class="cafe24_category_tab">
                  			<tr>
                  				<th>대분류</th>
                  				<th>중분류</th>
                  				<th>소분류</th>
                  			</tr>
                  			<tr>
                  				<td>
                  					<div id="cafe24_category1">
                  						<ul></ul>
                  					</div>
                  				</td>
                  				<td>
                  					<div id="cafe24_category2">
                  						<ul></ul>
                  					</div>
                  				</td>
                  				<td>
                  					<div id="cafe24_category3">
                  						<ul></ul>
                  					</div>
                  				</td>
                  			</tr>
                  		</table>
                  		<div class="category_sel_txt"><span></span><button id="add_cafe24_category" class="btn btn-sm btn-success" onclick="addcategory()" type="button"><i class="glyphicon glyphicon-plus"></i> 추가</button></div>
                  		<div class="category_add_txt"></div>
                  		<input type="hidden" name="c24_category" id="c24_category" value=''>
               		</div>
            	</div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_main">메인진열</div>
					<div class="col-sm-9 pt2">
						<label><input type="checkbox" value="2" name="c24_main[]" id="c24_main"> <span class="t2">추천상품</span></label>
						<label><input type="checkbox" value="3" name="c24_main[]"> <span class="t2">신상품</span></label>
						<label><input type="checkbox" value="4" name="c24_main[]"> <span class="t2">스페셜</span></label>
						<label><input type="checkbox" value="5" name="c24_main[]"> <span class="t2">일반상품</span></label>
						<label><input type="checkbox" value="6" name="c24_main[]"> <span class="t2">HOT상품</span></label>
	               	</div>
            	</div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_summary_description">상품 요약설명</div>
					<div class="col-sm-9 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name='c24_summary_description' id='c24_summary_description' maxlength="255">
	               	</div>
            	</div>
				<!-- 미사용 2024.2.28
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_simple_description">상품 간략설명</div>
					<div class="col-sm-9 pt2">
						<textarea class='form-control input-sm textarea' autocomplete="off" name="c24_simple_description" id="c24_simple_description"></textarea>
	               	</div>
            	</div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_description">상품 상세설명</div>
					<div class="col-sm-9 pt2">
						<textarea class='form-control input-sm textarea' autocomplete="off" name="c24_description" id="content_editor"></textarea>
	               	</div>
            	</div>
				-->
                <?php if(in_array($this->session->userdata('ADM_AUTH'), array(2,3,9))){ ?>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label">매입거래가격</div>
                    <div class="col-sm-3 pt2">
                        <input type='text' class='form-control input-sm numcomma' disabled="disabled" id='purchase_pprice' name='purchase_pprice' value="" maxlength="20">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label" for="purchase_exprice">교환금액</div>
                    <div class="col-sm-3 pt2">
                        <input type='text' class='form-control input-sm numcomma' disabled="disabled" autocomplete="off" name='purchase_exprice' id='purchase_exprice' maxlength="20">
                    </div>
                </div>
                <?php } ?>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label" for="purchase_asprice">AS수리비</div>
                    <div class="col-sm-2 pt2">
                        <input type='text' class='form-control input-sm numcomma' autocomplete="off" name='purchase_asprice' id='purchase_asprice' maxlength="20" value="" readonly="readonly">
                    </div>
                </div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="c24_supply_price">소비자가</div>
               		<div class="col-sm-3 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='c24_supply_price' id='c24_supply_price' maxlength="20">
               		</div>
            	</div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_origin_place_value">지점명</div>
					<div class="col-sm-2 pt2">
						<select name="c24_origin_place_value" id="c24_origin_place_value" class="form-control input-sm">
                     <?php foreach($goods_place as $v){ ?>
                     <option value="<?=$v?>"><?=$v?></option>
                     <?php } ?>
	                  	</select>
	               	</div>
            	</div>
               <div class="form-group">
                  <div class="fwb col-sm-2 control-label" for="floor">층수</div>
                  <div class="col-sm-1 pt2">
                     <select name="floor" id="floor" class="form-control input-sm">
                        <option value="1F">1F</option>
                        <option value="2F">2F</option>
                        <option value="5F">5F</option>
                     </select>
                  </div>
               </div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="c24_tax_type">과세구분 </div>
					<div class="col-sm-3 pt2">
						<select name="c24_tax_type" id="c24_tax_type" class="form-control input-sm">
							<option value="A">과세상품</option>
							<option value="B">영세상품</option>
							<option value="C">면세상품</option>
	                  	</select>
	               	</div>
	               	<div class="col-sm-3 pt2 tax_area">
	               		과세율 : <input type='text' style="width:50px;display:inline-block" class='form-control input-sm' autocomplete="off" name='c24_tax_amount' id='c24_tax_amount' maxlength="3" value="10"> %
	               	</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="price">판매예정금액</div>
               		<div class="col-sm-2 pt2">
                  		<input type='text' class='form-control input-sm numcomma' autocomplete="off" name='price' id='price' maxlength="20">
                  		<div class="price_guide">
                  			수수료 : <span class="price_guide1">0</span>원&nbsp;&nbsp;&nbsp;AS수리비 : <span class="price_guide3">0</span>원&nbsp;&nbsp;&nbsp;정산예정금액 : <span class="price_guide2">0</span>원
	                  	</div>
               		</div>
            	</div>

            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="brand_seq">브랜드명</div>
					<div class="col-sm-4 pt2">
						<select name="brand_seq" id="brand_seq" class="form-control input-sm">
							<option value="0">선택하세요</option>
							<?php foreach($brand_list as $v){ ?>
							<option value="<?=$v->seq?>"><?=$v->name?></option>
							<?php } ?>
	                  	</select>
	               	</div>
            	</div>

               <?php if($this->session->userdata('ADM_AUTH') != '3'){ ?>
               <div class="form-group">
               <div class="fwb col-sm-2 control-label" for="stock">재고여부</div>
               <div class="col-sm-2 pt2">
                  <select name="stock" id="stock" class="form-control input-sm">
                     <option value="Y">보유</option>
                     <option value="N">없음</option>
                        </select>
                     </div>
               </div>
               <?php } ?>
                <div class="form-group">
                    <div class="fwb col-sm-2 control-label" for="purchase_type">구분</div>
                    <div class="col-sm-2 pt2">
                        <select name="purchase_type" id="purchase_type" class="form-control input-sm">
                            <option value="">선택하세요</option>
                            <?php foreach($purchase_type as $v) {?>
                            <option value="<?=$v?>"><?=$v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for="selfcode">자체코드</div>
					<div class="col-sm-3 pt2">
						<input type='text' class='form-control input-sm' autocomplete="off" name='selfcode' id='selfcode' maxlength="10">
	               	</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="asmemo">AS메모</div>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:100px" name='asmemo' id='asmemo'></textarea>
               		</div>
            	</div>
            	<div class="form-group">
               		<div class="fwb col-sm-2 control-label" for="note">비고</div>
               		<div class="col-sm-9 pt2">
                  		<textarea class='form-control input-sm' autocomplete="off" style="height:400px" name='note' id='note'></textarea>
               		</div>
            	</div>
            	<div class="form-group">
					<div class="fwb col-sm-2 control-label" for='astype'>사진</div>
					<div class="col-sm-9 pt2">
						<input type="file" class="mt3" multiple name="image[]" size="10" accept="image/x-png,image/gif,image/jpeg">
	               	</div>
            	</div>
         	</form>

         	<div class="content_bottom">
            	<div class="content_left">
               		<button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 저장</button>
               		<button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/goods?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
            	</div>
         	</div>

      	</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
