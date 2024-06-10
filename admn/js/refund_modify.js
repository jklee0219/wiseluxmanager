$(document).ready(function(){
	'use strict';
	
	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		if($('input[name="purchase_seq"]').val() == ''){
			alert('상품코드를 검색하여 주십시요.');
			$('#pcode').focus();
		}else{
			$('.board_insert_btn').prop('disabled', true);
			boardForm.submit();
		}
	}
	
	$('.board_delete_btn').click(function(){
		if(confirm('선택한 게시물을 정말 삭제하시겠습니까? 연계된 데이터 모두가 삭제 됩니다.')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/refund/delproc?seq='+seq+"&"+qs;
		}
	});
	
	$('#trade_selldate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('#applydate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('#completedate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	var purchase_seq = $('input[name="purchase_seq"]').val();
	if(purchase_seq != '' && purchase_seq != '0'){
		getPurchaseCode();
	}
	
});