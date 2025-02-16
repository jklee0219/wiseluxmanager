$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		$('.board_insert_btn').prop('disabled', true);
		boardForm.submit();
	}
	
	$('#deposit_date').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko",
		autoclose: true
    });

	$('.board_delete_btn').click(function(){
		if(confirm('선택한 게시물을 정말 삭제하시겠습니까?')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/refunddeposit/delproc?seq='+seq+"&"+qs;
		}
	});
	
});