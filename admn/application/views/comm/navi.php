<div class="navbar-fixed-top">
  <a href="/admn/">WISELUX <span>중고명품 매장관리</span></a>
  <div class="iparea">접속 IP : <?=$_SERVER['REMOTE_ADDR']?></div>
  <a href="/admn/login/logout" class="pull-right"><div class="waves-effect"><i class="glyphicon glyphicon-off"></i> 로그아웃</div></a>
</div>
<div class="navbar-inverse navbar-fixed-left">
  <ul class="nav">
   <li><a href="/admn/purchase" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'purchase')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-list-alt"></i> 매입목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/goods" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'goods')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-gift"></i> 상품목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/trade" class="waves-effect<?=(stripos($_SERVER['PHP_SELF'],'trade')!==false && stripos($_SERVER['PHP_SELF'],'trade2')===false) ? ' active' : ''?>"><i class="glyphicon glyphicon-retweet"></i> 판매목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/trade2" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'trade2')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-retweet"></i> 위탁판매목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/request" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'request')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-duplicate"></i> 위탁가 수정요청<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/overtime" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'overtime')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-time"></i> 위탁경과목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/refund" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'refund')!==false && stripos($_SERVER['PHP_SELF'],'refunddeposit')===false ? ' active' : ''?>"><i class="glyphicon glyphicon-sort"></i> 반품목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/refunddeposit" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'refunddeposit')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-sort"></i> 반품비 입금내역<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/productmove" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'productmove')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-duplicate"></i> 상품이동현황<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/asinfo" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'asinfo')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-warning-sign"></i> AS목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/reference" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'reference')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-duplicate"></i> 참고리스트<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/access" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'access')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-user"></i> 접속리스트<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <?php if($this->session->userdata('ADM_AUTH') != '3'){?>
   <li><a href="/admn/stockcheck" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'stockcheck')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-screenshot"></i> 재고검수목록<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <?php } ?>
   <li><a href="/admn/sendsms" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'sendsms')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-phone"></i> 문자발송<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <?php if($this->session->userdata('ADM_AUTH') == '9'){?>
   <li><a href="/admn/member" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'member')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-user"></i> 사용자관리<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <?php } ?>
   <li><a href="/admn/workers" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'workers')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-user"></i> 직원명부<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
   <li><a href="/admn/bloggerlist" class="waves-effect<?=stripos($_SERVER['PHP_SELF'],'bloggerlist')!==false ? ' active' : ''?>"><i class="glyphicon glyphicon-duplicate"></i> 블로거리스트<i class="glyphicon glyphicon-menu-right pull-right"></i></a></li>
  </ul>
</div>