$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		if($('select[name="type"]').val() == ''){
			alert('구분을 선택하여 주십시요.');
			$('select[name="type"]').focus();
		}else if($('select[name="pbrand_seq"]').val() == ''){
			alert('브랜드명을 선택하여 주십시요.');
			$('select[name="pbrand_seq"]').focus();
		}else{
			$('.board_insert_btn').prop('disabled', true);
			boardForm.submit();
		}
	}
	
	$('.onlynum').keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});
	
	$('select[name="pbrand_seq"]').change(function(){
		if($('#modelname').val() == ''){
			var sel = $(this).find('option:selected').text();
			$('#modelname').val(sel);
		}
	});

    $('input[name=astype_etc_chk]').click(function(){
        $('input[name=astype_etc_txt]').prop('disabled', !$(this).is(':checked'));
        if(!$(this).is(':checked')){
            $('input[name=astype_etc_txt]').val('');
        }
    });

	$('input[name=reference_etc_chk]').click(function(){
		$('input[name=reference_etc_txt]').prop('disabled', !$(this).is(':checked'));
		if(!$(this).is(':checked')){
			$('input[name=reference_etc_txt]').val('');
		}
	});

	$('#type').change(function(){
		var v = $(this).val();
		if(v == '위탁'){
			$('#asprice').attr('readonly', false);
		}else{
			$('#asprice').val(0);
			$('#asprice').attr('readonly', true);
		}
	});

	$('#goods_price').on("change keyup paste", function() {
	    var v = $(this).val();
		getPriceGuide(v);
	});
	
});

function getPriceGuide(price){
	v = price.replace(/,/gi, ""); 
	v = parseInt(v);

	$('.price_guide3').text($.number($('#asprice').val()));

	var asprice = $('.price_guide3').text();
	asprice = asprice.replace(/,/gi, ""); 
	asprice = parseInt(asprice);

	var price_guide1 = 0; //수수료
	var price_guide2 = 0; //정산예정금액
	if(v == 0){
		price_guide1 = 0;
	}else if(v <= 500000){
		price_guide1 = 70000;
	}else if(v <= 1000000){
		price_guide1 = (v * 13) / 100;		
	}else if(v <= 10000000){
		price_guide1 = (v * 13) / 100;
	}else{
		price_guide1 = (v * 9) / 100;
	}

	
	var kind = $('select[name="kind"]').val();
	//if(kind == '신발' || kind == '의류') price_guide1 = (v * 17) / 100;

	price_guide1 = Math.floor(price_guide1);
	price_guide2 = v - price_guide1 - asprice;
	price_guide1 = $.number(price_guide1);
	price_guide2 = $.number(price_guide2);
	$('.price_guide1').text(price_guide1);
	$('.price_guide2').text(price_guide2);
}