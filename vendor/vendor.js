$(document).ready(function(){
	$('.onlynum').keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,''));
	});

	$('#submit_btn').click(function(){

        let price_check = true;
        let price_check_dom = '';
        $('.fees_calc').each(function(){
            let ori_price = $('.ori_price').text();
            ori_price = ori_price.replaceAll(",", "");
            ori_price = $.trim(ori_price);
            ori_price = parseInt(ori_price);
            ori_price = (ori_price * 95) / 100;

            let req_price = $('.req_price').val();
            req_price = req_price.replaceAll(",", "");
            req_price = $.trim(req_price);
            req_price = parseInt(req_price);    

            if(ori_price <= req_price){
                price_check = false;
                price_check_dom = $(this);
                return false;
            }
        });
        
        if(!price_check){
            alert('가격인하는 5%이상부터 신청이 가능합니다.');
            price_check_dom.focus();
		}else if(confirm('수정 요청을 하시겠습니까?')){
			$(this).prop('disabled', true);
			var data = '';

			$('.req_price').each(function(){

				var row = $(this).attr('data-seq')+'|'+$(this).val();
				if(data != '') data = data+'@';
				data += row;

			});

			if(data != ''){
				$.ajax({
					type: "POST",
			        url:'./proc.php',
			        data: "type=list&data="+data,
			        success:function(data){
			        	alert('수정 요청 되었습니다.');
			        	$('#submit_btn').prop('disabled', false);
			        }
		        })
	        }
		}

	});

	$('#logout_btn').click(function(){
		$(this).prop('disabled', true);
		$.ajax({
			type: "POST",
	        url:'./proc.php',
	        data: "type=logout",
	        success:function(data){
	        	location.reload();
	        }
        })
	})

	$('.fees_calc').each(function(){
		var req_price = $(this).find('.req_price').val();
		getPriceGuide($(this), req_price);
	});

	$('.req_price').on("change keyup paste", function(){
		var v = $(this).val();
		getPriceGuide($(this).parent().parent(), v);
	})
});

function dosubmit(){
   frm.submit();
}

function getPriceGuide(obj, price){
	v = price.replace(/,/gi, ""); 
	v = parseInt(v);

	var asprice = obj.find('.asprice').text();
	asprice = asprice.replace(/,/gi, ""); 
	asprice = parseInt(asprice);

	var comv = obj.find('input').attr('data-comv');
	comv = parseInt(comv);

	if(comv < v){
		alert('판매수정금액이 위탁판매금액 보다 높을 수 없습니다.');
		v = comv;
		obj.find('input').val($.number(v));
	}

	var price_guide1 = 0; //수수료
	var price_guide2 = 0; //정산예정금액
	if(v == 0){
		price_guide1 = 0;
	}else if(v <= 500000){
		price_guide1 = 70000;
	}else if(v <= 1000000){
		price_guide1 = (v * 13) / 100;		
	}else if(v <= 10000000){
		price_guide1 = (v * 11) / 100;
	}else{
		price_guide1 = (v * 7) / 100;
	}


	var kind = obj.find('input').attr('data-kind');
	if(kind == '신발' || kind == '의류') price_guide1 = (v * 17) / 100;

	price_guide1 = Math.floor(price_guide1);
	price_guide2 = v - price_guide1 - asprice;
	price_guide1 = $.number(price_guide1);
	price_guide2 = $.number(price_guide2);
	obj.find('.fees').text(price_guide1);
	obj.find('.expprice').text(price_guide2);
}