<?php
include 'loginchk.php';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<title>와이즈럭셔리-위탁판매상품</title>
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
   			· 위탁상품은 기존판매가에서 5% 이상부터 하향 조정이 가능합니다.<br/>
   			· 가격조정으로인한 수수료 변동이 발생될 수 있으니 최종 수령금액을 꼭 확인해주시기 바
   			랍니다.<br/>
   			· 위탁 후 최소 3개월 동안은 의무 위탁기간입니다. 의무 위탁기간내 회수시, 위약금 30,000
   			원이 발생됩니다.<br/>
   			· 3개월 초과시 판매되지않은 상품은 반환처리가 원칙으로 등록연장시엔 가격의 재협의가
   			이루어집니다.<br/>
   			· 위탁수수료는 현금판매와 카드판매가 동일합니다.<br/>
   			· 궁금하신 점은 와이즈럭스 고객센터 1600-1393 로 문의주세요.
            </div>
            <div class="btn_wrap"><button type="submit" class="btn">목록 확인</button></div>
        </form>
    </div>
</body>