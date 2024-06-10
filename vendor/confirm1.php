<?php
include 'loginchk.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>와이즈럭스 중고명품-위탁판매상품확인</title>
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
            <div class="guide">
           	고객님께서 맡겨주신 위탁상품의 내역확인 및 가격수정을 요청하실 수 있습니다<br/><br/>
   			· 품절 표기가 된 상태는 구매자분의 요청으로 상품예약 또는 온라인주문 후 구매확정/반품 선택중인 상태로 구매자분의 최종 구매확정이후 정산처리가 이루어집니다.<br/>
   			· 위탁 후 최소 3개월 동안은 의무 위탁기간입니다의무 위탁기간내 회수시, 품목당 위약금 30,000원이 발생됩니다.<br/>
   			· 위탁판매 기간 3개월 이후 반환 요청시 별도의 위약금은 발생되지 않습니다.<br/>
   			· 위탁판매중인 상품을 현금으로 즉시 지급받으실수있는 매입으로 변경 희망하실경우 대표번호 1600-1393번으로 문의주시면 관련상담 도와드리겠습니다<br/>

            </div>
            <div class="btn_wrap"><button type="submit" class="btn">목록 확인</button></div>
        </form>
    </div>
</body>