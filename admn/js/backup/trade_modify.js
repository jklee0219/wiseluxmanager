$(document).ready(function(){
	'use strict';
	
	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		if($('#selltype').val() == ''){
			alert('판매구분을 선택하여 주십시요.');
			$('#selltype').focus();
		}else if($('#sellprice').val() == ''){
			alert('실판매가를 입력하여 주십시요.');
			$('#sellprice').focus();
		}else if($('#paymethod').val() == ''){
			alert('결제방법을 선택하여 주십시요.');
			$('#paymethod').focus();
		}else{
			$('.board_insert_btn').prop('disabled', true);
			boardForm.submit();
		}
	}
	
	$('.onlynum').keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});
	
	$('.board_delete_btn').click(function(){
		if(confirm('선택한 게시물을 정말 삭제하시겠습니까? 연계된 데이터 모두가 삭제 됩니다.')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/trade/delproc?seq='+seq+"&"+qs;
		}
	});
	
	$('#purchase_pdate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('input[name="selldate"]').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	$('.board_copy_btn').click(function(){
		if($('input[name="purchase_seq"]').val() != '0' && $('input[name="purchase_seq"]').val() != ''){
			alert('매입정보가 등록된 게시물은 복사가 불가능 합니다.');
		}else if(confirm('선택한 게시물을 복사 하시겠습니까?')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/trade/copyproc?seq='+seq+"&"+qs;
		}
	});
	
	var purchase_seq = $('input[name="purchase_seq"]').val();
	if(purchase_seq != '' && purchase_seq != '0'){
		getPurchaseInfoFromCode();
	}
	
});

function getPurchaseInfoFromCode(){
	var pcode = $('#pcode').val();
	var seq = $('input[name="seq"]').val();
	if(pcode != ''){
		$.ajax({
			type: "POST",
	        url:'/admn/trade/getPurchaseInfoFromCode',
	        data: "pcode="+pcode+"&seq="+seq,
	        success:function(data){
	        	var res = JSON.parse(data);
	        	if(res.result == 'ok'){
	        		$('input[name="purchase_seq"]').val(res.data.seq).attr('disabled', false);
	        		var date_arr = res.data.pdate.split(' ');
	        		$('#purchase_pdate').datepicker("update", date_arr[0]); 
	        		$('#purchase_pdate').attr('disabled', false);
	        		$('#purchase_kind').val(res.data.kind).attr('disabled', false);
	        		$('#purchase_modelname').val(res.data.modelname).attr('disabled', false);
	        		$('#purchase_pprice').val($.number(res.data.pprice)).attr('disabled', false);
	        		$('#goods_price').val($.number(res.data.price)).attr('disabled', false);
	        		$('#purchase_method').val(res.data.method).attr('disabled', false);
	        		$('#purchase_class').val(res.data.class).attr('disabled', false);
	        		$('#goods_selfcode').val(res.data.selfcode).attr('disabled', false);
	        		$('#goods_stock').val(res.data.stock).attr('disabled', false);
	        	}else if(res.result == 'already'){
	        		if(confirm('이미 등록된 거래데이터가 존재 합니다.\n이동하시겠습니까?')){
	        			location.href = '/admn/trade/modify?seq='+res.data;
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
