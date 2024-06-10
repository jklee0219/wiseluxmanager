<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/bloggerlist.css?<?=time()?>">
   <script src="/admn/js/jquery.number.min.js"></script>
   <script src="/admn/js/comma.js"></script>
   <script src="/admn/js/bloggerlist.js?<?=time()?>"></script>
</head>
<body>

	<?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

	<div id="content">

		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 등록</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/bloggerlist/writeproc?<?=$param?>" method="post" role="form" name="boardForm">

                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="name">성함<span class="red" title="필수 입력사항 입니다.">*</span></div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm' autocomplete="off" name='name' id="name">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="phone">연락처</div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='phone' id="phone">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="keyword">키워드</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="150" class='form-control input-sm' autocomplete="off" name='keyword' id="keyword">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="accountnumber">계좌번호</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="150" class='form-control input-sm' autocomplete="off" name='accountnumber' id="accountnumber">
                    </div>
                </div>
                <?php if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){ ?>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="accountnumber">주민번호</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm' autocomplete="off" name='ssn' id="ssn">
                    </div>
                </div>
                <?php }?>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="place">방문지점</div>
                    <div class="col-sm-3 pt2">
                        <select class='form-control input-sm' name="place">
                            <?php foreach($goods_place as $v){ ?>
                            <option value="<?=$v?>"><?=$v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="visdate">방문날짜</div>
                    <div class="col-sm-3 pt2">
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='visdate' id="visdate">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="paydate">정산날짜</div>
                    <div class="col-sm-3 pt2">
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='paydate' id="paydate">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="payprice">정산금액</div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm numcomma' autocomplete="off" name='payprice' id="payprice">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="link">링크</div>
                    <div class="col-sm-8 pt2">
                        <textarea autocomplete="off" class='form-control input-sm' name='link' id="link"></textarea>
                    </div>
                </div>

			</form>

			<div class="content_bottom">
         	<div class="content_left">
					<button type="button" class="btn btn-success btn-sm" onclick="dosumbmit()"><i class="glyphicon glyphicon-floppy-disk"></i> 등록</button>
					<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/bloggerlist?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
				</div>
			</div>

		</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
