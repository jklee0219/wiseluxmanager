$(document).ready(function(){
	'use strict';

	$('.startdate_wrap input[name="svisdate"]').datepicker({
        format: "yyyy-mm-dd",
        orientation: 'auto bottom',
        language: "ko"
    }).on("changeDate", function (e) {
        var dt = new Date(e.date.valueOf());
        $('.enddate_wrap  input[name="evisdate"]').datepicker('setStartDate', dt);
    });
    
    $('.enddate_wrap input[name="evisdate"]').datepicker({
        format: "yyyy-mm-dd",
        orientation: 'auto bottom',
        language: "ko"
    }).on("changeDate", function (e) {
        var dt = new Date(e.date.valueOf());
        $('.startdate_wrap  input[name="svisdate"]').datepicker('setEndDate', dt);
    });

    $('.startdate_wrap input[name="spaydate"]').datepicker({
        format: "yyyy-mm-dd",
        orientation: 'auto bottom',
        language: "ko"
    }).on("changeDate", function (e) {
        var dt = new Date(e.date.valueOf());
        $('.enddate_wrap  input[name="epaydate"]').datepicker('setStartDate', dt);
    });
    
    $('.enddate_wrap input[name="epaydate"]').datepicker({
        format: "yyyy-mm-dd",
        orientation: 'auto bottom',
        language: "ko"
    }).on("changeDate", function (e) {
        var dt = new Date(e.date.valueOf());
        $('.startdate_wrap  input[name="spaydate"]').datepicker('setEndDate', dt);
    });

    $('#visdate').datepicker({
        format: "yyyy-mm-dd",
        language: "ko"
    });

    $('#paydate').datepicker({
        format: "yyyy-mm-dd",
        language: "ko"
    });

    $('#excel_btn').click(function(){
        $('iframe[name="hiddenFrm"]').attr('src','/admn/bloggerlist/excel?'+qs);
    });

    $('input[name="skeyword"]').keydown(function(key) {
        if (key.keyCode == 13) {
            $('input[name="skeyword"]').focus();
            searchFrm.submit();
        }
    });

});

function dosumbmit(){
	if($('#name').val() == ''){
		alert('성함은 필수 입력사항 입니다.');
		$('#name').focus();
	}else{
		boardForm.submit();
	}
}

function dodelete(){
	if(confirm('정말 삭제하시겠습니까?\n복구가 불가능 합니다.')){
		delForm.submit();
	}
}