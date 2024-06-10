$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		if($('input[name="purchase_seq"]').val() == ''){
			alert('상품코드를 검색하여 주십시요.');
			$('#pcode').focus();
		}else{
			$('.board_insert_btn').prop('disabled', true);
			boardForm.submit();
		}
	}
	
	$('#pcode').keydown(function(key) {
		if (key.keyCode == 13) {
			$('#pcode').focus();
			getPurchaseCode()
		}
	});
	
	$('#trade_selldate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('#applydate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('#completedate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
});

function getPurchaseCode(){
	var pcode = $('#pcode').val();
	if(pcode != ''){
		$.ajax({
			type: "POST",
	        url:'/admn/refund/getPurchaseCode',
	        data: "pcode="+pcode,
	        success:function(data){
	        	var res = JSON.parse(data);
	        	if(res.result == 'ok'){
	        		$('input[name="pcode"]').val(res.data.pcode);
	        		$('input[name="purchase_seq"]').val(res.data.seq);
	        		$('input[name="thumb"]').val(res.data.thumb);
	        		$('input[name="modelname"]').val(encodeURIComponent(res.data.modelname));
	        		$('input[name="selltype"]').val(res.data.selltype);
	        		$('input[name="paymethod"]').val(res.data.paymethod);
	        		$('input[name="brand_seq"]').val(res.data.brand_seq);
	        		$('input[name="kind"]').val(res.data.kind);
	        		$('input[name="amount"]').val(res.data.amount);
	        		$('input[name="price"]').val(res.data.price);
	        		$('input[name="selldate"]').val(res.data.selldate).attr('disabled', false);
	        		$('input[name="buyer"]').val(res.data.buyer).attr('disabled', false);
	        		var trade_buyerphone_arr = res.data.buyerphone.split('-');
	        		$('input[name="buyerphone1"]').val(trade_buyerphone_arr[0]).attr('disabled', false);
	        		$('input[name="buyerphone2"]').val(trade_buyerphone_arr[1]).attr('disabled', false);
	        		$('input[name="buyerphone3"]').val(trade_buyerphone_arr[2]).attr('disabled', false);
	        		$('#pcode').prop('readonly', true);
	        	}else if(res.result == 'notfound'){
	        		$('input[name="purchase_seq"]').val('');
	        		$('input[name="thumb"]').val('');
	        		$('input[name="modelname"]').val('');
	        		$('input[name="selltype"]').val('');
	        		$('input[name="paymethod"]').val('');
	        		$('input[name="brand_seq"]').val('');
	        		$('input[name="kind"]').val('');
	        		$('input[name="amount"]').val('');
	        		$('input[name="price"]').val('');
	        		$('input[name="selldate"]').val('').attr('disabled', true);
	        		$('input[name="buyer"]').val('').attr('disabled', true);
	        		$('input[name="buyerphone1"]').val('').attr('disabled', true);
	        		$('input[name="buyerphone2"]').val('').attr('disabled', true);
	        		$('input[name="buyerphone3"]').val('').attr('disabled', true);
	        		alert('해당 코드는 판매목록에서 찾을수 없습니다.');
	        		$('#pcode').focus();
	        	}
	        }
	    })
	}else{
		alert('코드를 입력 하여 주십시요.');
		$('#pcode').focus();
	}
}
