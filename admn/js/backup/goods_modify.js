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
	
	$('.thumb_wrap img').mouseover(function(){
		$('.thumb_wrap').removeClass('sel');
		$(this).parent().addClass('sel');
		$('.imageview').attr('src', $(this).attr('src'));
	});
	
	$('.image_delete_btn').click(function(){
		if(confirm('해당 이미지를 삭제 하시겠습니까?\n서버에서 완전히 삭제되며 복구가 불가능합니다.')){
			var obj = $(this);
			var seq = obj.attr('data-seq');
			$.ajax({
				type: "POST",
		        url:'/admn/goods/delimage',
		        data: "seq="+seq,
		        success:function(data){
		        	obj.parent().remove();
		        	var src = '';
		        	if($('.thumb_wrap').length == 0){
		        		src = '/admn/img/noimg.gif';  //빈이미지
		        	}else{
		        		src = $('.thumb_wrap:eq(0) img').attr('src');
		        	}
		        	$('.imageview').attr('src', src);
		        }
		    })
		}
	});
	
	$('.board_delete_btn').click(function(){
		if(confirm('선택한 게시물을 정말 삭제하시겠습니까? 연계된 데이터 모두가 삭제 됩니다.')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/goods/delproc?seq='+seq+"&"+qs;
		}
	});
	
	$('.board_copy_btn').click(function(){
		if($('input[name="purchase_seq"]').val() != '0' && $('input[name="purchase_seq"]').val() != ''){
			alert('매입정보가 등록된 게시물은 복사가 불가능 합니다.');
		}else if(confirm('선택한 게시물을 복사 하시겠습니까?')){
			var seq = $(this).attr('data-seq');
			location.href = '/admn/trade/copyproc?seq='+seq+"&"+qs;
		}
	});
	
	$('#purchase_pdate').datepicker({
		format: "yyyy-mm-dd",
	    language: "ko"
    });
	
	var purchase_seq = $('input[name="purchase_seq"]').val();
	if(purchase_seq != '' && purchase_seq != '0'){
		getPurchaseInfoFromCode();
	}
	
	$('.representchk').click(function(){
		if($(this).is(':checked')){
			$(this).parent().parent().find("input[data-seq!='"+$(this).attr('data-seq')+"']").prop('checked', false);
		}else{
			$(".representchk:eq(0)").prop('checked', true);
		}
		$('input[name="represent"]').val($(".representchk:checked").attr('data-seq'));
	});
	
	//아무것도 체크가 안되어 있는 경우
	if($(".representchk:checked").length == 0){
		$(".representchk:eq(0)").prop('checked', true);
		$('input[name="represent"]').val($(".representchk:checked").attr('data-seq'));
	}
	
	$('.orderchk').change(function(){
		$('.orderchk').attr('disabled', true);
		var orderbefore = $(this).attr('data-order');
		var orderafter = $(this).val();
		
		//숫자가 줄어드는경우  해당숫자의 앞으로 이동, 숫자가 늘어나는경우 해당숫자의 뒤로 이동
		if((orderafter - orderbefore) > 0){
			$('select[data-order="'+orderafter+'"]').parent().after($('select[data-order="'+orderbefore+'"]').parent());
		}else{
			$('select[data-order="'+orderafter+'"]').parent().before($('select[data-order="'+orderbefore+'"]').parent());
		}
		$('.orderchk').each(function(e){
			$(this).val(e+1);
			$(this).attr('data-order', e+1);
		});
		
		var orderseqs = '';
		var ordernums = '';
		$('.orderchk').each(function(){
			if(orderseqs != '') orderseqs = orderseqs+',';
			if(ordernums != '') ordernums = ordernums+',';
			orderseqs += $(this).attr('data-seq');
			ordernums += $(this).attr('data-order');
		})
//		console.log(orderseqs);
//		console.log(ordernums);
		$.ajax({
			type: "POST",
	        url:'/admn/goods/imageOrderChange',
	        data: "orderseqs="+orderseqs+"&ordernums="+ordernums,
	        success:function(data){
	        	$('.orderchk').attr('disabled', false);
	        }
	    })
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
				//alert("아싸!");
			}
		}, //boolean
		
		fOnAppLoad : function(){
			if($('#content_editor').val() == '<p> </p>'){
				oEditors.getById["content_editor"].setDefaultFont('맑은 고딕', 12);
				oEditors.getById["content_editor"].exec("SET_WYSIWYG_STYLE", [{"fontSize":"12pt", "fontFamily":"맑은 고딕", "textAlign":"center"}]);
			}
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

	$('#price').on("change keyup paste", function() {
	    var v = $(this).val();
		getPriceGuide(v);
	});

	if($('#price').val() != '') getPriceGuide($('#price').val());
	
});

function getPriceGuide(price){
	v = price.replace(/,/gi, ""); 
	v = parseInt(v);

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

function setDBCategory(){
	var c24_category = $('#c24_category').val();
	
	if(c24_category != ''){
		var json = JSON.parse(c24_category);
		$(json).each(function(idx, val){
			var no = val.category_no;
			var newstr = val.new;
			var recommend = val.recommend;
			
			var dom  = "<div data-cno='"+no+"'>";
				dom += "<span></span>";
				dom += '<div class="checkbox_wrap">';
				dom += '<label><input type="checkbox" '+((newstr=='T') ? 'checked="checked"' : '')+' class="form-control input-sm"> 신상품 영역</label>';
				dom += '<label><input type="checkbox" '+((recommend=='T') ? 'checked="checked"' : '')+' class="form-control input-sm"> 추천상품 영역</label>';
				dom += '<button class="add_cafe24_category btn btn-sm btn-danger" type="button" onclick="category_del(this)"><i class="glyphicon glyphicon-trash"></i> 삭제</button>';
				dom += '</div>';
				dom += '</div>';
			
			$('.category_add_txt').append(dom);
		});
		
		$('.category_add_txt > div').each(function(){
			var no = $(this).attr('data-cno');
			var obj = $(this).find('span');
			$.ajax({
				type: "POST",
		        url:'/admn/goods/getCategoryStr',
		        data: "no="+no,
				success:function(data){
					obj.text(data);
		        }
			})
		})
	}
}

function setTaxType(){
	if($('#c24_tax_type').val() == 'A'){
		$('.tax_area').show();
		if($('#c24_tax_amount').val() == 0) $('#c24_tax_amount').val('10');
	}else{
		$('.tax_area').hide();
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
	var seq = $('input[name="seq"]').val();
	if(pcode != ''){
		$.ajax({
			type: "POST",
	        url:'/admn/goods/getPurchaseInfoFromCode',
	        data: "pcode="+pcode+"&seq="+seq,
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
	        		if((res.data.reference).indexOf('기타!@#^') > -1){
        				$('input[name="purchase_reference_etc_chk"]').prop('checked', true);
	        			$('input[name="purchase_reference_etc_chk"]').attr('disabled', false);
        			}
        			var temparr = res.data.reference.split('|');
        			var purchase_reference_etc_txt = '';
        			$(temparr).each(function(k, v){
        				if(v.indexOf('기타!@#^') > -1){
        					purchase_reference_etc_txt = v.replace('기타!@#^','');
        					$('input[name="purchase_reference_etc_txt"]').val(purchase_reference_etc_txt);
        					$('input[name="purchase_reference_etc_txt"]').attr('disabled', false);
        					return false;
        				}
        			});
	        		$('#brand_seq').val(res.data.pbrand_seq);
	        		$('#note').val(res.data.note);
	        		$('.price_guide3').text($.number(res.data.asprice));

	        		getPriceGuide($('#price').val()); //ajax 문제로 한번더 처리
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
