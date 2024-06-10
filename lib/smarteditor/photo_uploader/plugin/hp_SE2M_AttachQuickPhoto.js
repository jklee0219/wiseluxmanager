/**
 * @use 간단 포토 업로드용으로 제작되었습니다.
 * @author cielo
 * @See nhn.husky.SE2M_Configuration 
 * @ 팝업 마크업은 SimplePhotoUpload.html과 SimplePhotoUpload_html5.html이 있습니다. 
 */
function scriptQuery(){ 
   var script = document.getElementsByTagName('script'); // 자신을 찾기위해 <script> 태그들을 찾습니다. 
   script = script[script.length-1].src // 가장 마지막이 자신이 됩니다 
     .replace(/^[^\?]+\?/, '') // 물음표 이전을 지우고 
     .replace(/#.+$/, '') // 혹시 모를 해쉬도 지웁니다 
     .split('&') // '&'으로 나눕니다 
   var queries = {} // 결과용 
     , query; 
   while(script.length){ // &으로 나눈 갯수만큼 
        query = script.shift().split('='); // =로 나눠 
        queries[query[0]] = query[1]; // 앞은 배열키, 뒤는 배열 값 
   } 
   return queries; 
} 
var our = scriptQuery(); // 스크립트 주소에서 쿼리를 받아 저장 

nhn.husky.SE2M_AttachQuickPhoto = jindo.$Class({		
	name : "SE2M_AttachQuickPhoto",
	board : our.board,

	$init : function(){},
	
	$ON_MSG_APP_READY : function(){
		this.oApp.exec("REGISTER_UI_EVENT", ["photo_attach", "click", "ATTACHPHOTO_OPEN_WINDOW"]);
	},
	
	$LOCAL_BEFORE_FIRST : function(sMsg){
		if(!!this.oPopupMgr){ return; }
		// Popup Manager에서 사용할 param
		this.htPopupOption = {
			oApp : this.oApp,
			sName : this.name,
			bScroll : false,
			sProperties : "",
			sUrl : ""
		};
		this.oPopupMgr = nhn.husky.PopUpManager.getInstance(this.oApp);
	},
	
	/**
	 * 포토 웹탑 오픈
	 */
	$ON_ATTACHPHOTO_OPEN_WINDOW : function(){			
		this.htPopupOption.sUrl = this.makePopupURL();
		this.htPopupOption.sProperties = "left=0,top=0,width=403,height=359,scrollbars=no,location=no,status=0,resizable=no";

		this.oPopupWindow = this.oPopupMgr.openWindow(this.htPopupOption);
		
		// 처음 로딩하고 IE에서 커서가 전혀 없는 경우
		// 복수 업로드시에 순서가 바뀜	
		this.oApp.exec('FOCUS');
		return (!!this.oPopupWindow ? true : false);
	},
	
	/**
	 * 서비스별로 팝업에  parameter를 추가하여 URL을 생성하는 함수	 
	 * nhn.husky.SE2M_AttachQuickPhoto.prototype.makePopupURL로 덮어써서 사용하시면 됨.
	 */
	makePopupURL : function(){
		var sPopupUrl = "./photo_uploader/popup/photo_uploader.html?board="+this.board;
		
		return sPopupUrl;
	},
	
	/**
	 * 팝업에서 호출되는 메세지.
	 */
	$ON_SET_PHOTO : function(aPhotoData){
		var sContents, 
			aPhotoInfo,
			htData;
		
		if( !aPhotoData ){ 
			return; 
		}
		
		try{
			sContents = "";
			for(var i = 0; i <aPhotoData.length; i++){				
				htData = aPhotoData[i];
				
				if(!htData.sAlign){
					htData.sAlign = "";
				}
				
				aPhotoInfo = {
				    sName : htData.sFileName || "",
				    sOriginalImageURL : htData.sFileURL,
					bNewLine : htData.bNewLine || false 
				};
				
				sContents += this._getPhotoTag(aPhotoInfo);
			}

			this.oApp.exec("PASTE_HTML", [sContents]); // 위즐 첨부 파일 부분 확인
		}catch(e){
			// upload시 error발생에 대해서 skip함
			return false;
		}
	},

	/**
	 * @use 일반 포토 tag 생성
	 */
	_getPhotoTag : function(htPhotoInfo){
		// id와 class는 썸네일과 연관이 많습니다. 수정시 썸네일 영역도 Test
		var sTag = '<img src="{=sOriginalImageURL}" title="{=sName}" >';
		if(htPhotoInfo.bNewLine){
			sTag += '<br style="clear:both;">';
		}
		sTag = jindo.$Template(sTag).process(htPhotoInfo);
		
		return sTag;
	}
});