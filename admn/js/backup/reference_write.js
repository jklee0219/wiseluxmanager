$(document).ready(function(){
	'use strict';

	$('.board_insert_btn').click(function(){ doSubmit(); });

	function doSubmit(){
		if($('input[name="title"]').val() == ''){
			alert('제목을 입력하여 주십시요.');
			$('input[name="title"]').focus();
		}else if($('input[name="writer"]').val() == ''){
			alert('작성자를 입력하여 주십시요.');
			$('input[name="writer"]').focus();
		}else{
			$('.board_insert_btn').prop('disabled', true);
			oEditors.getById["content_editor"].exec("UPDATE_CONTENTS_FIELD", []);
			boardForm.submit();
		}
	}
	
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
			oEditors.getById["content_editor"].setDefaultFont('나눔고딕', 12);
        },
        
		fCreator: "createSEditor2"
	});
	
});
