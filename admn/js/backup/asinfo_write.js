$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		$('.board_insert_btn').prop('disabled', true);
		boardForm.submit();
	}
	
	$('#pcode').keydown(function(key) {
		if (key.keyCode == 13) {
			$('#pcode').focus();
			getPurchaseInfoFromCode()
		}
	});
	
	$('.start_date_btn').click(function(){
		$('#start_date').val(getTimeStamp());
	});
	
	$('.end_date_btn').click(function(){
		$('#end_date').val(getTimeStamp());
	});
	
	$('#purchase_pdate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });

    $('input[name=reference_etc_chk]').click(function(){
		$('input[name=reference_etc_txt]').prop('disabled', !$(this).is(':checked'));
		if(!$(this).is(':checked')){
			$('input[name=reference_etc_txt]').val('');
		}
	})
	
});

function getPurchaseInfoFromCode(){
	var pcode = $('#pcode').val();
	if(pcode != ''){
		$.ajax({
			type: "POST",
	        url:'/admn/asinfo/getPurchaseInfoFromCode',
	        data: "pcode="+pcode,
	        success:function(data){
	        	var res = JSON.parse(data);
	        	if(res.result == 'ok'){
	        		$('input[name="purchase_seq"]').val(res.data.seq);
	        		if(res.data.thumb) $('#thumb').attr('src', res.data.thumb);
	        		var date_arr = res.data.pdate.split(' ');
	        		$('#purchase_pdate').datepicker("update", date_arr[0]); 
	        		$('#purchase_pdate').attr('disabled', false);
	        		$('#purchase_seller').val(res.data.seller).attr('disabled', false);
                    $('#trade_buyer').val(res.data.trade_buyer);
	        		var sellerphone_arr = res.data.sellerphone.split('-');
	        		$('#purchase_sellerphone1').val(sellerphone_arr[0]).attr('disabled', false);
	        		$('#purchase_sellerphone2').val(sellerphone_arr[1]).attr('disabled', false);
	        		$('#purchase_sellerphone3').val(sellerphone_arr[2]).attr('disabled', false);
	        		$('input[name="reference[]"]').each(function(){
	        			if(('|'+res.data.reference+'|').indexOf('|'+$(this).val()+'|') > -1){
	        				$(this).prop('checked', true);
	        			}
	        		});
	        		if((res.data.reference).indexOf('기타!@#^') > -1){
        				$('input[name="reference_etc_chk"]').prop('checked', true);
	        			$('input[name="reference_etc_chk"]').attr('disabled', false);
        			}
        			var temparr = res.data.reference.split('|');
        			var purchase_reference_etc_txt = '';
        			$(temparr).each(function(k, v){
        				if(v.indexOf('기타!@#^') > -1){
        					purchase_reference_etc_txt = v.replace('기타!@#^','');
        					$('input[name="reference_etc_txt"]').val(purchase_reference_etc_txt);
        					$('input[name="reference_etc_txt"]').attr('disabled', false);
        					return false;
        				}
        			});
	        		$('input[name="guarantee[]"]').each(function(){
	        			if(('|'+res.data.guarantee+'|').indexOf('|'+$(this).val()+'|') > -1){
	        				$(this).prop('checked', true);
	        			}
	        		});

	        		$('#purchase_note').val(res.data.note).attr('disabled', false);

	        		$('#purchase_modelname').text(res.data.modelname);

                    $('input[name="astype[]"]').each(function(){
                        if(('|'+res.data.astype+'|').indexOf('|'+$(this).val()+'|') > -1){
                            $(this).prop('checked', true);
                        }
                    });
	        	}else if(res.result == 'already'){
	        		if(confirm('이미 등록된 거래데이터가 존재 합니다.\n이동하시겠습니까?')){
	        			location.href = '/admn/asinfo/modify?seq='+res.data;
	        		}
	        	}else if(res.result == 'notfound'){
	        		alert('해당 코드를 찾을수 없습니다.');
	        		$('#pcode').focus();
	        	}
	        }
	    })
	}else{
		alert('코드를 입력 하여 주십시요.');
		$('#pcode').focus();
	}
}

function leadingZeros(n, digits) {
  var zero = '';
  n = n.toString();

  if (n.length < digits) {
    for (i = 0; i < digits - n.length; i++)
      zero += '0';
  }
  return zero + n;
}

function getTimeStamp() {
  var d = new Date();
  var s =
    leadingZeros(d.getFullYear(), 4) + '-' +
    leadingZeros(d.getMonth() + 1, 2) + '-' +
    leadingZeros(d.getDate(), 2) + ' '

  return s;
}


