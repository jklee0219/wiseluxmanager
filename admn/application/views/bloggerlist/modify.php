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

		<div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

		<div class="content_write">

			<form class="form-horizontal" action="/admn/bloggerlist/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">

				<input type="hidden" name="seq" value="<?=$view->seq?>">

                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="name">성함<span class="red" title="필수 입력사항 입니다.">*</span></div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm' autocomplete="off" name='name' id="name" value="<?=$view->name?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="phone">연락처</div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='phone' id="phone" value="<?=$view->phone?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="keyword">키워드</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="150" class='form-control input-sm' autocomplete="off" name='keyword' id="keyword" value="<?=$view->keyword?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="accountnumber">계좌번호</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm' autocomplete="off" name='accountnumber' id="accountnumber" value="<?=$view->accountnumber?>">
                    </div>
                </div>
                <?php if(in_array($this->session->userdata('ADM_ID'), array('admin','admin1','dev'))){ ?>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="accountnumber">주민번호</div>
                    <div class="col-sm-6 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm' autocomplete="off" name='ssn' id="ssn" value="<?=$view->ssn?>">
                    </div>
                </div>
                <?php }?>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="place">방문지점</div>
                    <div class="col-sm-3 pt2">
                        <select class='form-control input-sm' name="place">
                            <?php foreach($goods_place as $v){ ?>
                            <option value="<?=$v?>"<?=($v==$view->place) ? ' selected="selected"' : ''?>><?=$v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="visdate">방문날짜</div>
                    <div class="col-sm-3 pt2">
                        <?php
                        $visdate = $view->visdate;
                        if($visdate && $visdate != '0000-00-00 00:00:00'){
                            $visdate = strtotime($visdate);
                            $visdate = date('Y-m-d', $visdate);
                        }else{
                            $visdate = '';
                        }
                        ?>
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='visdate' id="visdate" value="<?=$visdate?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="paydate">정산날짜</div>
                    <div class="col-sm-3 pt2">
                        <?php
                        $paydate = $view->paydate;
                        if($paydate && $paydate != '0000-00-00 00:00:00'){
                            $paydate = strtotime($paydate);
                            $paydate = date('Y-m-d', $paydate);
                        }else{
                            $paydate = '';
                        }
                        ?>
                        <input type='text' maxlength="20" class='form-control input-sm' autocomplete="off" name='paydate' id="paydate" value="<?=$paydate?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="payprice">정산금액</div>
                    <div class="col-sm-4 pt2">
                        <input type='text' maxlength="50" class='form-control input-sm numcomma' autocomplete="off" name='payprice' id="payprice" value="<?=number_format($view->payprice)?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fwb col-sm-3 control-label" for="link">링크</div>
                    <div class="col-sm-8 pt2">
                        <textarea autocomplete="off" class='form-control input-sm' name='link' id="link"><?=$view->link?></textarea>
                    </div>
                </div>
				
			</form>

            <form class="form-horizontal" action="/admn/bloggerlist/delproc?<?=$param?>" method="post" role="form" name="delForm">
                <input type="hidden" name="seq" value="<?=$view->seq?>">
            </form>

			<div class="content_bottom">
                <div class="content_left">
					<button type="button" class="btn btn-success btn-sm" onclick="dosumbmit()"><i class="glyphicon glyphicon-floppy-disk"></i> 수정</button>
					<button type="button" class="btn btn-danger btn-sm" onclick="dodelete()"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
					<button type="button" class="btn btn-warning btn-sm" onclick="document.location.href='/admn/bloggerlist?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
				</div>
			</div>

		</div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>
