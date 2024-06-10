<?php
include 'loginchk.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>와이즈럭스-위탁판매상품</title>
<meta charset="UTF-8">
<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="./vendor.css?<?=time?>">
<script src="/lib/jquery/jquery-1.12.0.min.js"></script>
<script src="./vendor.js?<?=time()?>"></script>
<body>
    <div class="confirm_wrapper">
        <div class="header">나의 위탁내역 조회하기</div>
        <form action="proc.php" onsubmit="dosubmit(); return false;" name="frm" method="post">
            <input type="hidden" name="type" value="confirm">
            <div class="body">
			      <div>
                  <span>이름</span>
                  <input type="text" required autocomplete="off" maxlength="20" name="nm" class="nm">
               </div>
               <div>
			    	   <span>연락처</span>
                  <input type="text" required autocomplete="off" class="ph onlynum" name="ph1" maxlength="4"> - <input type="text" required autocomplete="off" class="ph onlynum" name="ph2" maxlength="4"> - <input type="text" required autocomplete="off" class="ph onlynum" name="ph3" maxlength="4">
               </div>
               <div>
                  <span>비밀번호</span>
                  <input type="password" required autocomplete="off" name="pw" maxlength="10" class="pw">
               </div>
            </div>
<p style="text-align:center; font-weight:600; ">고객님께서 맡겨주신 위탁상품의 내역확인 및 가격수정을 요청하실 수 있습니다.<p/>
            <div class="btn_wrap"><button type="submit" class="btn">목록 확인</button></div>
        </form>
    </div>
</body>