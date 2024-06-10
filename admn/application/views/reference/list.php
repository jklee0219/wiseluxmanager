<!DOCTYPE html>
<html lang='ko'>
<head>
   <?php include $_SERVER['DOCUMENT_ROOT'].'/admn/application/views/comm/head.php'; ?>
   <link rel="stylesheet" href="/admn/css/reference.css?<?=time()?>">
   <script src="/admn/js/reference.js?<?=time()?>"></script>
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

                <div class="amount_info_wrap">
                    <table>
                        <tr>
                            <td class="bg">Q&A</td>
                            <td class="txt"><?=number_format($getTopCnt1)?>건</td>
                            <td class="bg">정품가품이미지</td>
                            <td class="txt"><?=number_format($getTopCnt2)?>건</td>
                            <td class="bg">기타</td>
                            <td class="txt"><?=number_format($getTopCnt3)?>건</td>
                        </tr>
                        <tr>
                            <td class="bg">상품관련</td>
                            <td class="txt"><?=number_format($getTopCnt4)?>건</td>
                            <td class="bg">회사내규</td>
                            <td class="txt"><?=number_format($getTopCnt5)?>건</td>
                            <td class="bg"></td>
                            <td class="txt"></td>
                        </tr>
                    </table>
                </div>

            	<div>
               		<form name="searchFrm" action="/admn/reference" method="get">
               			<select class="form-control input-sm" name="sreference_category">
							<option value="">카테고리(전체)</option>
							<?php foreach($reference_category as $v){ ?>
                  			<option value="<?=$v?>" <?=($sreference_category==$v) ? "selected='selected'" : ""?>><?=$v?></option>
                  			<?php } ?>
						</select>
						<select class="form-control input-sm" name="stype">
							<option value="title" <?=($stype=='title') ? "selected='selected'" : ""?>>제목</option>
                  			<option value="content" <?=($stype=='content') ? "selected='selected'" : ""?>>내용</option>
                            <option value="writer" <?=($stype=='writer') ? "selected='selected'" : ""?>>작성자</option>
						</select>
						<input type="text" class="form-control input-sm" autocomplete="off" name="skeyword" value="<?=$skeyword?>" placeholder="키워드 검색">
                  		<button type="button" onclick="searchFrm.submit()" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-search"></i> 검색</button>
                  		<button type="button" onclick="location.href='./reference/write?<?=$param?>'" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-pencil"></i> 등록</button>
                  		<p class="line"></p>
               		</form>
				</div>
         	</div>
	
         	<form name="frm" action="" method="post">
	            <table class="table">
	               <thead class="thead-inverse">
	                  <tr>
	                     <th>번호</th>
	                     <th>카테고리</th>
	                     <th>제목</th>
	                     <th>비고</th>
						 <th>작성자</th>
	                     <th>작성일</th>
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
	                     $category = $v->category;
	                     $title = $v->title;
	                     $note = $v->note;
	                     $writer = $v->writer;
	                     $rdate = $v->rdate;
						 if($rdate){
							 $rdate = strtotime($rdate);
							 $rdate = date('Y-m-d', $rdate);
						 }
						 if($note && mb_strlen($note) > 30){
						 	$note = mb_substr($note, 0, 30);
						 }
	               ?>
	                  <tr>
	                     <td><?=$seq?></td>
	                     <td><?=$category?></td>
	                     <td class="tal"><a href="/admn/reference/view?seq=<?=$seq?>"><?=$title?></a></td>
	                     <td><?=$note?></td>
	                     <td><?=$writer?></td>
	                     <td><?=$rdate?></td>
	                     <td>
	                     	<button type="button" onclick="location.href='/admn/reference/modify?seq=<?=$seq?>&<?=$param?>'" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-wrench"></i> 수정</button>
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
