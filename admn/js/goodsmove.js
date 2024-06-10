$(document).ready(function(){
	'use strict';

	//테이블 검색 및 등록 결과가 없을 경우..
	$(".table tbody tr").length == 0 && $(".table tbody").append("<tr><td class='empty' colspan='"+$(".table thead tr th").length+"'>목록 또는 검색 결과가 존재하지 않습니다.</td></tr>");

	$('#board_search_btn').click(function(){ searchFrm.submit(); });
	
	$('.startdate_wrap input').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    }).on("changeDate", function (e) {
    	var dt = new Date(e.date.valueOf());
    	$('.enddate_wrap input').datepicker('setStartDate', dt);
    });
	
	$('.enddate_wrap input').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    }).on("changeDate", function (e) {
    	var dt = new Date(e.date.valueOf());
    	$('.startdate_wrap input').datepicker('setEndDate', dt);
    });

	$('input[name="skeyword"]').keydown(function(key) {
		if (key.keyCode == 13) {
			$('input[name="skeyword"]').focus();
			searchFrm.submit();
		}
	});
	
	$('#excel_btn').click(function(){
		$('iframe[name="hiddenFrm"]').attr('src','/admn/productmove/excel?'+qs);
	});
});