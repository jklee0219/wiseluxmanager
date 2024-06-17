<!DOCTYPE html>
<html lang='ko'>
<head>
    <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
    <link rel="stylesheet" href="/admn/css/productmvdate.css?<?=time()?>">
    <script src="/admn/js/jquery.number.min.js"></script>
    <script src="/admn/js/comma.js"></script>
    <script>
    var qs = "<?=$param?>";
    </script>
    <script src="/admn/js/productmvdate_modify.js?v=1"></script>
</head>
<body>

    <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/navi.php';?>

    <div id="content">
        <div id="content_title"><script>document.write($('.nav li .active').text());</script> 수정</div>

        <div class="content_write">

            <form class="form-horizontal" action="/admn/productmove/modifyproc?<?=$param?>" method="post" role="form" name="boardForm">
                <input type="hidden" name="seq" value="<?=$view->seq?>">
                <input type="hidden" name="purchase_seq" value="<?=$view->purchase_seq?>">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="pcode">상품코드 검색</label>
                    <div class="col-sm-9 pt2">
                        <input type='text' style="width:100px;display:inline-block;" maxlength="7" class='form-control input-sm' autocomplete="off" name='pcode' id="pcode" value="<?=$view->pcode?>" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">모델명</label>
                    <div class="col-sm-6 pt2">
                        <p id="purchase_modelname"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">이미지</label>
                    <div class="col-sm-9 pt2">
                        <img src="/admn/img/noimg.gif" class="thumb" id="thumb">
                        <p>※ 이미지는 상품목록에서만 등록/수정이 가능합니다.</p>
                    </div>
                </div>
                <div class="form-group">
               		<label class="col-sm-3 control-label" for="movedate">이동일자</label>
               		<div class="col-sm-3 pt2">
                  		<div class="input-group date">
                            <input type="text" name='movedate' id='movedate' class="form-control input-sm" autocomplete="off" placeholder="" value="<?=$view->movedate?>">
                            <div class="input-group-addon input-sm">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
               		</div>
            	</div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="moveyn">이동결과</label>
                    <div class="col-sm-3 pt2">
                        <select name="moveyn" id="moveyn" class="form-control input-sm">
                            <option value="N" <?=($view->moveyn=='N') ? "selected='selected'" : ""?>>미처리</option>
                            <option value="Y" <?=($view->moveyn=='Y') ? "selected='selected'" : ""?>>처리완료</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="shipplace">발송지점</label>
                    <div class="col-sm-3 pt2">
                        <select name="shipplace" id="shipplace" class="form-control input-sm">
                            <?php foreach($goods_place as $v){ ?>
                            <option value="<?=$v?>" <?=($view->shipplace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="reciveplace">수령지점</label>
                    <div class="col-sm-3 pt2">
                        <select name="reciveplace" id="reciveplace" class="form-control input-sm">
                            <?php foreach($goods_place as $v){ ?>
                            <option value="<?=$v?>" <?=($view->reciveplace==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="memo">비고</label>
                    <div class="col-sm-3 pt2">
                        <textarea class="form-control input-sm" autocomplete="off" style="width:500px;height:100px" name="memo" id="memo"><?=$view->note?></textarea>
                    </div>
                </div>
            </form>

            <div class="content_bottom">
                <div class="content_left">
                    <button type="button" class="board_insert_btn btn btn-success btn-sm"><i class="glyphicon glyphicon-floppy-disk"></i> 수정</button>
                    <button type="button" data-seq="<?=$view->seq?>" class="board_delete_btn btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> 삭제</button>
                    <button type="button" class="board_cancel_btn btn btn-warning btn-sm" onclick="document.location.href='/admn/productmvdate?<?=$param?>'" type="button"><i class="glyphicon glyphicon-remove"></i> 취소</button>
                </div>
            </div>

        </div>

   </div>

   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/footer.php';?>

</body>
</html>