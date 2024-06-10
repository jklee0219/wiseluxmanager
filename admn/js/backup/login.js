var loginWarningTimer;

$(document).ready(function(){
	// var rn = Math.floor((Math.random() * 3) + 1);
	// var rand = rn;
	// setInterval(function(){
	// 	while (rand == rn) {
	// 	  rn = Math.floor((Math.random() * 3) + 1);
	// 	}
	// 	rand = rn;

	// 	var speed = 1200;
	// 	$('.bgArea2').css('background-image', 'url(/admn/img/bg'+rand+'.jpg?1)');
	// 	$('.bgArea').fadeTo(speed, 0, function(){
	// 		$(this).css('background-image', 'url('+$('.bgArea2').css('background-image')+')');
	// 		$('.bgArea').attr('class','bgArea3');
	// 		$('.bgArea2').attr('class','bgArea');
	// 		$('.bgArea3').attr('class','bgArea2');
	// 		$('.bgArea2').css('opacity', 0);
	// 		$('.bgArea').css('opacity', 1);
	// 	});
	// 	$('.bgArea2').fadeTo(speed, 1);
	// }, 6000);

	document.forms["loginFrm"]["id"].focus();
});


function validateForm() {

	var id = document.forms["loginFrm"]["id"];
	  if (id.value == null || id.value == "") {
	      alert("아이디를 입력하여 주십시요");
	      id.focus();
	      return false;
	  }
	
	  var pw = document.forms["loginFrm"]["pw"];
	  if (pw.value == null || pw.value == "") {
	      alert("비밀번호를 입력하여 주십시요");
	      pw.focus();
	      return false;
	  }

	$.ajax({
		async: true,
	    url: '/admn/login/loginconfirm',
	    type: 'POST',
	    data: 'id='+id.value+'&pw='+pw.value,
	    dataType: 'xml',
	    error: function(xhr, status, error) {
	    	console.log("오류 : "+error);
	    },
	    success: function(data) {
				var r = $(data).find('result').text();
	    	if(r == 'OK'){
					document.location.href = '/admn/purchase';
				}else{
					$('.loginWarning').text('[아이디 or 비밀번호]를 확인해주세요!');
					$('.loginWarning').css({'display':'block','opacity':0});
					$('.loginWarning').stop().animate({'opacity':1},500,function(){
						clearTimeout(loginWarningTimer);
						loginWarningTimer = setTimeout(function(){
							$('.loginWarning').stop().animate({'opacity':0},1000,function(){ $('.loginWarning').css('display','none');; });
						},2000);
					});
				}
	    }
	});
}
