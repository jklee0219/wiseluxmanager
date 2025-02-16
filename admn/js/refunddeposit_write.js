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
	
});