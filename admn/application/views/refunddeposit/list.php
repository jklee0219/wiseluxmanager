<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/refunddeposit.css?<?=time()?>">
   <script src="/admn/js/refunddeposit.js?<?=time()?>"></script>
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
               		<form name="searchFrm" action="/admn/refunddeposit" method="get">
					   <div class="startdate_wrap">
							<div class="input-group date">
								<input type="text" name="ssdate" class="form-control input-sm" autocomplete="off" placeholder="입금일 시작" value="<?=$ssdate?>">
								<div class="input-group-addon input-sm">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>~
						<div class="enddate_wrap">
							<div class="input-group date">
								<input type="text" name="sedate" class="form-control input-sm" autocomplete="off" placeholder="입금일 끝" value="<?=$sedate?>">
								<div class="input-group-addon input-sm">
									<span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
						<select class="form-control input-sm" name="stype">
                  			<option value="depositor_name" <?=($stype=='depositor_name') ? "selected='selected'" : ""?>>입금자명</option>
							<option value="depositor_contact" <?=($stype=='depositor_contact') ? "selected='selected'" : ""?>>입금자연락처</option>
							<option value="remarks" <?=($stype=='remarks') ? "selected='selected'" : ""?>>비고</option>
						</select>

						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                        <?php if(in_array($this->session->userdata('ADM_AUTH'), array(3,9))){ ?>
                  		<button type="button" id="excel_btn" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-save-file"></i> 엑셀</button>
                        <?php }?>
                  		<button type="button" onclick="location.href='./refunddeposit/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	                <thead class="thead-inverse">
	                    <tr>
	                     	<th>번호</th>
	                     	<th>입금일자</th>
	                     	<th>입금액</th>
						 	<th>입금자명</th>
	                     	<th>입금자연락처</th>
	                     	<th>비고</th>
	                     	<th>등록일자</th>
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
	                    $deposit_date = $v->deposit_date;
						$deposit_date = date('Y-m-d', strtotime($deposit_date));
	                    $deposit_amount = $v->deposit_amount;
						$deposit_amount = number_format($deposit_amount);
	                    $depositor_name = $v->depositor_name;
	                    $depositor_contact = $v->depositor_contact;
	                    $remarks = $v->remarks;
	                    $manager = $v->manager;

						if (mb_strlen($remarks, 'UTF-8') > 20) {
							$remarks = mb_substr($remarks, 0, 20, 'UTF-8') . '...';
						}
	                ?>
	                <tr>
	                    <td><?=$seq?></td>
	                    <td><?=$deposit_date?></td>
	                    <td class="aleft"><?=$deposit_amount?></td>
	                    <td><?=$depositor_name?></td>
	                    <td><?=$depositor_contact?></td>
                        <td class="aleft"><?=$remarks?></td>
	                    <td>
	                    	<button type="button" onclick="location.href='/admn/refunddeposit/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 수정</button>
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
