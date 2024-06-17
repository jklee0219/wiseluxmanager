$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
        if($('input[name="purchase_seq"]').val() == ''){
            alert('상품코드 검색을 해주세요.');
            $('input[name="purchase_seq"]').focus();
        }else{
    		$('.board_insert_btn').prop('disabled', true);
    		boardForm.submit();
        }
	}
	
	$('#pcode').keydown(function(key) {
		if (key.keyCode == 13) {
			$('#pcode').focus();
			getPurchaseInfoFromCode()
		}
	});
	
	$('#movedate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko",
        autoclose: true
    });
	
});

function getPurchaseInfoFromCode(){
    var pcode = $('#pcode').val();
    if(pcode != ''){
        $.ajax({
            type: "POST",
            url:'/admn/productmvdate/getPurchaseInfoFromCode',
            data: "pcode="+pcode,
            success:function(data){
                var res = JSON.parse(data);
                if(res.result == 'ok'){
                    $('input[name="purchase_seq"]').val(res.data.seq);
                    if(res.data.thumb) $('#thumb').attr('src', res.data.thumb);
                    var date_arr = res.data.pdate.split(' ');
                    $('#purchase_pdate').text(date_arr[0]); 
                    $('#purchase_modelname').text(res.data.modelname);
                }else if(res.result == 'already'){
                    if(confirm('이미 등록된 데이터가 존재 합니다.\n이동하시겠습니까?')){
                        location.href = '/admn/productmvdate/modify?seq='+res.data.tb_productmovedate_seq;
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