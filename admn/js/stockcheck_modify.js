$(document).ready(function(){
	'use strict';
	
	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		$('.board_insert_btn').prop('disabled', true);
		boardForm.submit();
	}
	
	$('.onlynum').keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});
	
	$('#purchase_pdate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
});