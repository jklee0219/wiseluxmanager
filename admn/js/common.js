$(document).ready(function(){
	$(".waves-effect").mouseenter(function (e) {
		var target = e.target;
		var rect = target.getBoundingClientRect();
		var ripple = target.querySelector('.ripple');
		$(this).find('.ripple').remove();
		ripple = document.createElement('span');
		ripple.className = 'ripple';
		ripple.style.height = ripple.style.width = Math.max($(this).width(), $(this).height()) + 'px';
		target.appendChild(ripple);
		var top = e.pageY - rect.top - ripple.offsetHeight / 2 -  document.body.scrollTop;
		var left = e.pageX - rect.left - ripple.offsetWidth / 2 - document.body.scrollLeft;
		ripple.style.top = top + 'px';
		ripple.style.left = left + 'px';
		return false;
	});
});

if(location.href.indexOf('admn/login') == -1){
	ipchk(); setInterval(ipchk, 5000);
}

function ipchk(){
	$.ajax({
		type: "POST",
	    url:'/admn/access/ipchk',
	    success:function(data){
	    	if(data == 'block'){
	    		alert('접근이 제한된 아이피 입니다.');
	    		location.href = '/admn';
	    	}
	    }
	})
}

function strip_tags(input, allowed) {
	allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) { return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : ''; });
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}