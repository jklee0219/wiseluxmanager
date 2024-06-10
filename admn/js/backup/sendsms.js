$(document).ready(function(){
	'use strict';

	$('.savesmscontent').keyup(function(){ chsmstxt($(this)) });
	
	$('.savesmscontent').each(function(){ chsmstxt($(this)) });
	
	function chsmstxt(obj){
		var bt = byteCheck(obj);
		if(bt < 80){
			obj.parent().find('.bytetxt').html('['+bt+' / 2000]byte (<span class="sms">SMS</span>)');
		}else{
			obj.parent().find('.bytetxt').html('['+bt+' / 2000]byte (<span class="lms">LMS</span>)');
			if(bt >= 2000){
				obj.val(obj.val().substr(0,2000));
				bt = byteCheck($(this));
				obj.parent().find('.bytetxt').html('['+bt+' / 2000]byte (<span class="lms">LMS</span>)');
			}
		}
	}
	
	$('.smssave_btn').click(function(){
		var seq = $(this).attr('data-seq');
		var txt = $(this).parent().find('.savesmscontent').val();
			txt = $.trim(txt);
		var smsconobj = $(this); 
		if(txt == ''){
			alert('저장할 문자내용을 입력하여 주십시요.');
			$(this).parent().find('.savesmscontent').focus();
		}else if(confirm(seq+'번 내용을 저장 하시겠습니까?')){
			$.ajax({
				type: "POST",
		        url:'/admn/sendsms/savesms',
		        data: "seq="+seq+"&txt="+txt,
		        success:function(data){
		        	smsconobj.parent().find('.savesmscontent').val(txt)
		        	alert('저장 되었습니다.');
		        }
		    })
		}
	});
	
	$('.smscopy_btn').click(function(){
		var txt = $(this).parent().find('.savesmscontent').val();
			txt = $.trim(txt);
		
		if(txt != ''){
			$('#smscontent').val(txt);
		}else{
			alert('사용 될 문자내용이 없습니다.');
		}
	});
});

function byteCheck(el){
    var codeByte = 0;
    for (var idx = 0; idx < el.val().length; idx++) {
        var oneChar = escape(el.val().charAt(idx));
        if ( oneChar.length == 1 ) {
            codeByte ++;
        } else if (oneChar.indexOf("%u") != -1) {
            codeByte += 2;
        } else if (oneChar.indexOf("%") != -1) {
            codeByte ++;
        }
    }
    return codeByte;
}

function byteCheck2(str){
    var codeByte = 0;
    for (var idx = 0; idx < str.length; idx++) {
        var oneChar = escape(str.charAt(idx));
        if ( oneChar.length == 1 ) {
            codeByte ++;
        } else if (oneChar.indexOf("%u") != -1) {
            codeByte += 2;
        } else if (oneChar.indexOf("%") != -1) {
            codeByte ++;
        }
    }
    return codeByte;
}

function sendsms(){
	var phonenum = $.trim($('#smsphonenumber').val());
	var smscontent = $.trim($('#smscontent').val());
	var smstype = byteCheck2(smscontent) < 80 ? 'SMS' : 'LMS';
	
	if(phonenum == ''){
		alert('수신번호를 입력하여 주십시요.');
		$('#smsphonenumber').focus();
	}else if(phonenum.length != 11){
		alert('수신번호 형태가 잘못 되었습니다.');
		$('#smsphonenumber').focus();
	}else if(!$.isNumeric(phonenum)){
		alert('수신번호 형태가 잘못 되었습니다.');
		$('#smsphonenumber').focus();
	}else if(smscontent == ''){
		alert('문자내용을 입력하여 주십시요.');
		$('#smscontent').focus();
	}else{
		if(confirm('발송하시겠습니까?')){
			$('.smssend_btn').prop('disabled', true);
			$.ajax({
				type: "POST",
		        url:'/admn/sendsms/send_sms',
		        data: "phonenum="+phonenum+"&smscontent="+smscontent+"&smstype="+smstype,
		        success:function(data){
		        	if(data.indexOf('"statusName":"success"') > -1){
		        		alert('발송 되었습니다.');
		        	}else{
		        		alert(data);
		        	}
		        	$('.smssend_btn').prop('disabled', false);
		        }
		    })
		}
	}
}