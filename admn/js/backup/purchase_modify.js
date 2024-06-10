$(document).ready(function(){
	'use strict';
	
	$('.board_modify_btn').click(function(){ doSubmit(); });

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
	
	$('.board_delete_btn').click(function(){
		if(confirm('선택한 게시물을 정말 삭제하시겠습니까? 연계된 데이터 모두가 삭제 됩니다.')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/purchase/delproc?seq='+seq+"&"+qs;
		}
	});
	
	$('.board_copy_btn').click(function(){
		if(confirm('선택한 게시물을 복사 하시겠습니까?')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/purchase/copyproc?seq='+seq+"&"+qs;
		}
	});
	
	$('select[name="pbrand_seq"]').change(function(){
		if($('#modelname').val() == ''){
			var sel = $(this).find('option:selected').text();
			$('#modelname').val(sel);
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

	if($('#type').val() == '위탁') $('#asprice').attr('readonly', false);

	$('#goods_price').on("change keyup paste", function() {
	   var v = $(this).val();
		getPriceGuide(v);
	});
	
	if($('#goods_price').val() != '') getPriceGuide($('#goods_price').val());
	
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
	if(v <= 1000000){
		price_guide1 = (v * 15) / 100;	
	}else if(v <= 10000000){
		price_guide1 = (v * 12) / 100;
	}else{
		price_guide1 = (v * 8) / 100;
	}

	var kind = $('select[name="kind"]').val();
	if(kind == '신발' || kind == '의류') price_guide1 = (v * 17) / 100;

	price_guide1 = Math.floor(price_guide1);
	price_guide2 = v - price_guide1 - asprice;
	price_guide1 = $.number(price_guide1);
	price_guide2 = $.number(price_guide2);
	$('.price_guide1').text(price_guide1);
	$('.price_guide2').text(price_guide2);
}