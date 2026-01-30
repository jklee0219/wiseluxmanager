$(document).ready(function(){
	
	// 달력 초기화
	$('.input-group.date input').datepicker({
		format: "yyyy-mm-dd",
		language: "ko",
		autoclose: true,
		todayHighlight: true
	});
	
	// 폼 제출 전 유효성 검사
	$('#modifyFrm').submit(function(e){
		var worker_id = $('select[name="worker_id"]').val();
		var att_date = $('input[name="att_date"]').val();
		var att_type = $('select[name="att_type"]').val();
		
		if(!worker_id) {
			alert('직원을 선택해주세요.');
			$('select[name="worker_id"]').focus();
			return false;
		}
		
		if(!att_date) {
			alert('날짜를 입력해주세요.');
			$('input[name="att_date"]').focus();
			return false;
		}
		
		if(!att_type) {
			alert('근무유형을 선택해주세요.');
			$('select[name="att_type"]').focus();
			return false;
		}
		
		return true;
	});
	
});
