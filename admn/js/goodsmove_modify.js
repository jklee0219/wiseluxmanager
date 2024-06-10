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
    
    $('#shipdate').datepicker({
        format: "yyyy-mm-dd",
        language: "ko"
    });

    $('#recivedate').datepicker({
        format: "yyyy-mm-dd",
        language: "ko"
    });

    var purchase_seq = $('input[name="purchase_seq"]').val();
    if(purchase_seq != '' && purchase_seq != '0'){
        getPurchaseInfoFromCode();
    }

    $('.board_delete_btn').click(function(){
        if(confirm('삭제하시겠습니까?')){
            var seq = $(this).attr('data-seq');
            location.href = '/admn/productmove/delproc?seq='+seq+"&"+qs;
        }
    });
    
});

function getPurchaseInfoFromCode(){
    var pcode = $('#pcode').val();
    var seq = $('input[name="seq"]').val();
    if(pcode != ''){
        $.ajax({
            type: "POST",
            url:'/admn/productmove/getPurchaseInfoFromCode',
            data: "pcode="+pcode+"&seq="+seq,
            success:function(data){
                var res = JSON.parse(data);
                $('input[name="purchase_seq"]').val(res.data.seq);
                if(res.data.thumb) $('#thumb').attr('src', res.data.thumb);
                var date_arr = res.data.pdate.split(' ');
                $('#purchase_pdate').text(date_arr[0]); 
                $('#purchase_modelname').text(res.data.modelname);
            }
        })
    }else{
        alert('코드를 입력 하여 주십시요.');
        $('#pcode').focus();
    }
}