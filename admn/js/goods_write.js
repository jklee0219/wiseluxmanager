
$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		$('.board_insert_btn').prop('disabled', true);
		
		//cafe24 카테고리 정리
		if($('.category_add_txt > div').length > 0){
			var category_str = '';
			$('.category_add_txt > div').each(function(){
				var category_no = $(this).attr('data-cno');
				var newstr = $(this).find('input:eq(0)').is(":checked") ? 'T' : 'F';
				var recommend = $(this).find('input:eq(1)').is(":checked") ? 'T' : 'F';
				var str = '{"category_no": '+category_no+',"recommend": "'+recommend+'","new": "'+newstr+'"}';
				if(category_str) category_str = category_str+',';
				category_str = category_str + str;
			});
			
			if(category_str != '') category_str = '['+category_str+']';
		}
		$('#c24_category').val(category_str);
		
		oEditors.getById["content_editor"].exec("UPDATE_CONTENTS_FIELD", []);
		boardForm.submit();
	}
	
	$('#pcode').keydown(function(key) {
		if (key.keyCode == 13) {
			$('#pcode').focus();
			getPurchaseInfoFromCode()
		}
	});
	
	$('#purchase_pdate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	getCategoryInfo(1, 1);
	
	$('#cafe24_category_refresh_btn').click(function(){
		getCate24CategoryInfo();
	});
	
	//스마트 에디터
	var oEditors = [];
	var UserAgent = navigator.userAgent;
	var UserAgentKind_oEditor_Mode="WYSIWYG";
	var Change_oEditor_Mode = true;

	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,

		elPlaceHolder: "content_editor",

		sSkinURI: "/lib/smarteditor/SmartEditor2Skin.html",

		htParams : {
			bUseToolbar : true,					// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseVerticalResizer : false,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseModeChanger : Change_oEditor_Mode,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
			SE_EditingAreaManager : { sDefaultEditingMode : UserAgentKind_oEditor_Mode},
			fOnBeforeUnload : function() {
//				alert("아싸!");
			}
		}, //boolean

		fOnAppLoad : function(){
			oEditors.getById["content_editor"].setDefaultFont('맑은 고딕', 12);
			oEditors.getById["content_editor"].exec("SET_WYSIWYG_STYLE", [{"fontSize":"12pt", "fontFamily":"맑은 고딕", "textAlign":"center"}]);
        },
        
		fCreator: "createSEditor2"
	});
	
	
	
	$('#c24_tax_type').change(function(){
		var v = $(this).val();
		$('#c24_tax_amount').val('');
		if(v == 'A'){
			$('.tax_area').show();
		}else{
			$('.tax_area').hide();
		}
	});

	$('input[name=reference_etc_chk]').click(function(){
		$('input[name=reference_etc_txt]').prop('disabled', !$(this).is(':checked'));
		if(!$(this).is(':checked')){
			$('input[name=reference_etc_txt]').val('');
		}
	});

	$('#price').on("change keyup paste", function() {
	    var v = $(this).val();
		getPriceGuide(v);
	});

    $('#purchase_type').change(function(){
        var v = $(this).val();
        if(v == '위탁'){
            $('#purchase_asprice').attr('readonly', false);
        }else{
            $('#purchase_asprice').val(0);
            $('#purchase_asprice').attr('readonly', true);
        }
    });

});

function getPriceGuide(price){
	v = price.replace(/,/gi, ""); 
	v = parseInt(v);

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


	var kind = $('select[name="purchase_kind"]').val();
	if(kind == '신발' || kind == '의류') price_guide1 = (v * 17) / 100;

	price_guide1 = Math.floor(price_guide1);
	price_guide2 = v - price_guide1 - asprice;
	price_guide1 = $.number(price_guide1);
	price_guide2 = $.number(price_guide2);
	$('.price_guide1').text(price_guide1);
	$('.price_guide2').text(price_guide2);
}

function getCategoryInfo(depth, no){
	$.ajax({
		type: "POST",
        url:'/admn/goods/getCategory',
        data: "depth="+depth+"&no="+no,
        success:function(data){
        	var res = JSON.parse(data);
        	if(depth == 1){
        		$('#cafe24_category1 ul').html('');
        		$(res).each(function(idx, value){
        			$('#cafe24_category1 ul').append('<li data-no="'+value.category_no+'" data-pno="'+value.parent_category_no+'" data-name="'+value.category_name+'" onclick="getCategoryInfo(2,'+value.category_no+')">'+value.category_name+'&nbsp;&nbsp;> </li>');
        		});
        	}else if(depth == 2){
        		$('#cafe24_category2 ul').html('');
        		$(res).each(function(idx, value){
        			$('#cafe24_category2 ul').append('<li data-no="'+value.category_no+'" data-pno="'+value.parent_category_no+'" data-name="'+value.category_name+'" onclick="getCategoryInfo(3,'+value.category_no+')">'+value.category_name+'&nbsp;&nbsp;> </li>');
        		});
        	}else if(depth == 3){
        		$('#cafe24_category3 ul').html('');
        		$(res).each(function(idx, value){
        			$('#cafe24_category3 ul').append('<li data-no="'+value.category_no+'" data-pno="'+value.parent_category_no+'" data-name="'+value.category_name+'" onclick="getCategoryInfo(4,'+value.category_no+')">'+value.category_name+'&nbsp;&nbsp;> </li>');
        		});
        	}
        	
        	if($('li[data-no="'+no+'"]').length > 0){
	        	var str = '';
	        	if(depth == 2){
	        		str = $('li[data-no="'+no+'"]').attr('data-name');
	        	}else if(depth == 3){
	        		str = $('li[data-no="'+$('li[data-no="'+no+'"]').attr('data-pno')+'"]').attr('data-name')+" > "+$('li[data-no="'+no+'"]').attr('data-name');
	        	}else if(depth == 4){
	        		str = $('li[data-no="'+$('li[data-no="'+$('li[data-no="'+no+'"]').attr('data-pno')+'"]').attr('data-pno')+'"]').attr('data-name')+" > "+$('li[data-no="'+$('li[data-no="'+no+'"]').attr('data-pno')+'"]').attr('data-name')+" > "+$('li[data-no="'+no+'"]').attr('data-name');
	        	}
	        	$('.category_sel_txt span').text(str);
	        	$('.category_sel_txt span').attr('data-no', no);
        	}
        }
    })
}

function addcategory(){
	var str = $('.category_sel_txt span').text();
	var no = $('.category_sel_txt span').attr('data-no');
	
	if(str == ''){
		alert('상품분류를 선택 해 주세요.');
	}else if($('div[data-cno="'+no+'"]').length > 0){
		alert('이미 등록된 상품분류 입니다.');
	}else{
		var dom  = "<div data-cno='"+no+"'>";
			dom += "<span>"+str+"</span>";
			dom += '<div class="checkbox_wrap">';
			dom += '<label><input type="checkbox" class="form-control input-sm"> 신상품 영역</label>';
			dom += '<label><input type="checkbox" class="form-control input-sm"> 추천상품 영역</label>';
			dom += '<button class="add_cafe24_category btn btn-sm btn-danger" type="button" onclick="category_del(this)"><i class="glyphicon glyphicon-trash"></i> 삭제</button>';
			dom += '</div>';
			dom += '</div>';
			
		$('.category_add_txt').append(dom);
	}
}

function category_del(obj){
	$(obj).parent().parent().remove();
}

function getCate24CategoryInfo(){
	$.ajax({
        url:'/admn/goods/getApiCategory',
        success:function(data){
        	alert('카페24에서 상품분류 데이터를 새로 가져왔습니다.');
        	$('#cafe24_category2 ul').html('');
        	$('#cafe24_category3 ul').html('');
        	$('.category_sel_txt span').text('');
        	$('.category_add_txt').html('');
        	getCategoryInfo(1, 1);
        }
    })
} 

function getPurchaseInfoFromCode(){
	var pcode = $('#pcode').val();
	if(pcode != ''){
		$.ajax({
			type: "POST",
	        url:'/admn/goods/getPurchaseInfoFromCode',
	        data: "pcode="+pcode,
	        success:function(data){
	        	var res = JSON.parse(data);
	        	if(res.result == 'ok'){
	        		$('input[name="purchase_seq"]').val(res.data.seq);
	        		$('#purchase_kind').val(res.data.kind).attr('disabled', false);
	        		$('#purchase_modelname').val(res.data.modelname).attr('disabled', false);
	        		var date_arr = res.data.pdate.split(' ');
	        		$('#purchase_pdate').datepicker("update", date_arr[0]); 
	        		$('#purchase_pdate').attr('disabled', false);
	        		$('#purchase_pprice').val($.number(res.data.pprice)).attr('disabled', false);
	        		$('#purchase_method').val(res.data.method).attr('disabled', false);
	        		$('#purchase_class').val(res.data.class).attr('disabled', false);
	        		$('input[name="purchase_reference[]"]').each(function(){
	        			if(('|'+res.data.reference+'|').indexOf('|'+$(this).val()+'|') > -1){
	        				$(this).prop('checked', true);
	        			}
	        		});
	        		$('input[name="purchase_reference[]"]').attr('disabled', false);
                    $('input[name="purchase_reference_etc_chk"]').attr('disabled', false);
                    $('input[name="purchase_reference_etc_txt"]').attr('disabled', false);
	        		if((res.data.reference).indexOf('기타!@#^') > -1){
        				$('input[name="purchase_reference_etc_chk"]').prop('checked', true);
        			}
        			var temparr = res.data.reference.split('|');
        			var purchase_reference_etc_txt = '';
        			$(temparr).each(function(k, v){
        				if(v.indexOf('기타!@#^') > -1){
        					purchase_reference_etc_txt = v.replace('기타!@#^','');
        					$('input[name="purchase_reference_etc_txt"]').val(purchase_reference_etc_txt);
        					return false;
        				}
        			});
	        		$('#brand_seq').val(res.data.pbrand_seq);
	        		$('#note').val(res.data.note);
	        		$('.price_guide3').text($.number(res.data.asprice));
	        		$('#price').val($.number(res.data.goods_price));
                    $('#stock').val(res.data.goods_stock);
                    $('input[name="astype[]"]').each(function(){
                        if(('|'+res.data.astype+'|').indexOf('|'+$(this).val()+'|') > -1){
                            $(this).prop('checked', true);
                        }
                    });
                    $('input[name="astype[]"]').attr('disabled', false);
                    $('input[name="purchase_astype_etc_chk"]').attr('disabled', false);
                    $('input[name="purchase_astype_etc_txt"]').attr('disabled', false);
                    if((res.data.astype).indexOf('기타') > -1){
                        $('input[name="purchase_astype_etc_chk"]').prop('checked', true);
                    }
                    var temparr = res.data.astype.split('|');
                    var purchase_astype_etc_txt = '';
                    $(temparr).each(function(k, v){
                        if(v.indexOf('기타!@#^') > -1){
                            purchase_astype_etc_txt = v.replace('기타!@#^','');
                            $('input[name="purchase_astype_etc_txt"]').val(purchase_astype_etc_txt);
                            return false;
                        }
                    });
                    $('#purchase_place').val(res.data.place).attr('disabled', false);

                    if(res.data.goods_price) $('#price').val($.number(res.data.goods_price));

                    if(res.data.exprice){
                        $('#purchase_exprice').val($.number(res.data.exprice));
                    }
                    $('#purchase_exprice').attr('disabled', false);

					$('#reason').val(res.data.reason);

	        	}else if(res.result == 'already'){
	        		if(confirm('이미 등록된 상품데이터가 존재 합니다.\n이동하시겠습니까?')){
	        			location.href = '/admn/goods/modify?seq='+res.data;
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
