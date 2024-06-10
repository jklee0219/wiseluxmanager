$(document).ready(function(){
	'use strict';

	getAccessList(); setInterval(getAccessList, 3000); 
	getBlockList(); setInterval(getBlockList, 3000);
	
	$('.pwch_wrap .closebtn').click(function(){
		$('.pwch_wrap').hide();
	})
});

function getAccessList(){
	$.ajax({
		type: "POST",
	    url:'/admn/access/accesslist',
	    success:function(data){
	    	var res = JSON.parse(data);
	    	$('.access_list_wrap .list_wrap').html('');
	    	for(var i=0; i<res.length; i++){
	    		var ip = res[i].ip;
	    		var id = res[i].id;
	    		var logindate = res[i].logindate;
	    		var str = '<p><span>'+(i+1)+'</span>'+ip+' | '+id+' | '+logindate+'(최초로그인) <button onclick="setblockip(\''+id+'\', \''+ip+'\')" class="btn btn-danger btn-sm">IP차단 및 강제종료</button></p>';
	    		$('.access_list_wrap .list_wrap').append(str);
	    	}
	    }
	})
}

function getBlockList(){
	$.ajax({
		type: "POST",
	    url:'/admn/access/blocklist',
	    success:function(data){
	    	var res = JSON.parse(data);
	    	$('.block_list_wrap .list_wrap').html('');
	    	for(var i=0; i<res.length; i++){
	    		var ip = res[i].ip;
	    		var str = '<p><span>'+(i+1)+'</span>'+ip+' <button onclick="removeblockip(\''+ip+'\')" class="btn btn-success btn-sm">차단해제</button></p>';
	    		$('.block_list_wrap .list_wrap').append(str);
	    	}
	    }
	})
}

function setblockip(id, ip){
	$('#chid').val(id);
	$('#ip').val(ip);
	$('.pwch_wrap .idstr').text(id);
	$('.pwch_wrap').show();
}

function setPassword(){
	if($('#pwd1').val() == ''){
		alert('패스워드를 입력 하여 주십시요.');
		$('#pwd1').focus();
	}else if($('#pwd2').val() == ''){
		alert('패스워드확인을 입력 하여 주십시요.');
		$('#pwd2').focus();
	}else if($('#pwd1').val() != $('#pwd2').val()){
		alert('입력한 패스워드 값이 서로 다릅니다.');
		$('#pwd2').focus();
	}else{
		$.ajax({
			type: "POST",
		    url:'/admn/access/setpassword',
		    data: "id="+$('#chid').val()+"&pw="+$('#pwd1').val(),
		    success:function(data){
		    	if(data == 'notaccess'){
		    		alert('권한이 없습니다.');
		    	}else{
		    		$('.pwch_wrap').hide();
			    	$.ajax({
						type: "POST",
					    url:'/admn/access/setblockip',
					    data: "ip="+$('#ip').val(),
					    success:function(data){
					    	getAccessList();
					    	getBlockList();
					    }
					});
				}
		    }
		});
	}
}

function removeblockip(ip){
	$.ajax({
		type: "POST",
	    url:'/admn/access/removeblockip',
	    data: "ip="+ip,
	    success:function(data){
	    	if(data == 'notaccess'){
	    		alert('권한이 없습니다.');
	    	}else{
		    	getAccessList();
		    	getBlockList();
		    }
	    }
	})
}