/* * sns.js 파일 *--------------------------------------------------------------------------------------------*/ //sns 연동 관련 함수 
function goTwitter(msg,url) {
	var href = "http://twitter.com/home?status=" + encodeURIComponent(msg) + " " + encodeURIComponent(url); 
	var a = window.open(href, 'twitter', ''); 
	if ( a ) { 
		a.focus(); } 
	} 
	
function goMe2Day(msg,url,tag) { 
	var href = "http://me2day.net/posts/new?new_post[body]=" + encodeURIComponent(msg) + " " + encodeURIComponent(url) + "&new_post[tags]=" + encodeURIComponent(tag); 
	var a = window.open(href, 'me2Day', ''); 
	if ( a ) { 
		a.focus(); } 
	} 
	
function goFaceBook(msg,url) { 
	//window.alert(msg);
	//var href = "http://www.facebook.com/sharer.php?u=" + encodeURIComponent(url) + "&t=" + encodeURIComponent(msg); 
	var href = "http://www.facebook.com/sharer.php?t=" + encodeURIComponent(msg) + "&u=" + encodeURIComponent(url); 
	var a = window.open(href, 'facebook', ''); 
	if ( a ) { 
		a.focus();
	} 
} 

function goCyWorld(no) { 
	var href = "http://api.cyworld.com/openscrap/post/v1/?xu=http://ticketmonster.co.kr/html/cyworldConnectToXml.php?no=" + no +"&sid=suGPZc14uNs4a4oaJbVPWkDSZCwgY8Xe"; 
	var a = window.open(href, 'facebook', 'width=450,height=410'); 
	if ( a ) { 
		a.focus(); } 
	} 


function goYozmDaum(link,prefix,parameter) { 
	var href = "http://yozm.daum.net/api/popup/post?sourceid=&link=" + encodeURIComponent(link) + "&prefix=" + encodeURIComponent(prefix) + "¶meter=" + encodeURIComponent(parameter); 
	var a = window.open(href, 'yozmSend', 'width=466, height=356'); 
	if ( a ) { 
		a.focus(); } 
	} 
	
function sns_popup(src,width,height) { 
	var scrollbars = "1"; var resizable = "no"; 
	if (typeof(arguments[3])!="undefined") scrollbars = arguments[3]; 
	if (arguments[4]) resizable = "yes"; 
	window.open(src,'','width='+width+',height='+height+',scrollbars='+scrollbars+',toolbar=no,status=no,resizable='+resizable+',menubar=no,left=0,top=0'); 
}


