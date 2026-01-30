$(document).ready(function(){
	
	// 달력 초기화
	$('.input-group.date input').datepicker({
		format: "yyyy-mm-dd",
		language: "ko",
		autoclose: true,
		todayHighlight: true
	});
	
	// 엑셀 다운로드
	$('#excel_btn').click(function(){
		var url = '/admn/attendance/excel?' + qs;
		location.href = url;
	});
	
	// 쿠키 설정
	window.setCookie = function(name, value, days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}
	
	// 쿠키 가져오기
	window.getCookie = function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}
	
});
