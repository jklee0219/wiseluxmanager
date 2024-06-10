$(document).ready(function(){
	'use strict';

	$('#id').on('input', function() {
		var v = $(this).val();
   	$.ajax({
			url: "/admn/member/idchk",
			type: "POST",
			data: "chkstr="+v,
			success: function(data){
				if(data == 1){
					$('#idchk').val(0);
					$('.notuseid').hide();
					$('.useid').show();
				}else{
					$('#idchk').val(1);
					$('.useid').hide();
					$('.notuseid').show();
				}
			}
		});

	});

});

function dosumbmit(){
	if($('#id').val() == ''){
		alert('아이디는 필수 입력사항 입니다.');
		$('#id').focus();
	}else if($('#idchk').val() == 0){
		alert('이미 사용중인 아이디 입니다.');
		$('#id').focus();
	}else if($('#password').val() == ''){
		alert('패스워드는 필수 입력사항 입니다.');
		$('#password').focus();
	}else if($('#password').val() != $('#password_c').val()){
		alert('패스워드가 일치하지 않습니다.');
		$('#password_c').focus();
	}else{
		boardForm.submit();
	}
}

function dosumbmit2(){
	if($('#idchk').val() == 0){
		alert('이미 사용중인 아이디 입니다.');
		$('#id').focus();
	}else if($('#password').val() != $('#password_c').val()){
		alert('패스워드가 일치하지 않습니다.');
		$('#password_c').focus();
	}else{
		var action = boardForm.action;
		action = action.replace('delproc','modifyproc');
		boardForm.action = action;
		boardForm.submit();
	}
}

function dodelete(){
	if(confirm('정말 삭제하시겠습니까?\n복구가 불가능 합니다.')){
		var action = boardForm.action;
		action = action.replace('modifyproc','delproc');
		boardForm.action = action;
		boardForm.submit();
	}
}