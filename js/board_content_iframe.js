$(document).ready(function() {
	//window.alert($('iframe').length);
	for(var i=0; i<$('iframe').length; i++) {
		if($('iframe').eq(i).attr('src').indexOf("youtube.com/embed")!=-1) {
			//window.alert("성공"+$('iframe').eq(i).attr('src'));
			$('iframe').eq(i).wrap('<div class=\"youtubevideowrapperdiv youtubevideowrapperdiv16-9blind\"></div>');
			//if($('iframe').attr('src')
		}else{
			//window.alert("실패"+$('iframe').eq(i).attr('src'));
			//window.alert("실패");
		}

	}
});






$(window).resize(function() {

	for(var i=0; i<$('img').length; i++) {			
		if($('img').eq(i).parents("a").hasClass("mobileImgBig")==true) {
			//window.alert("");
			$('img').eq(i).unwrap();
			//window.alert($('img').eq(i).parents("a").unwrap());
		}
	}



	if($(window).width() < 768){
		for(var i=0; i<$('img').length; i++) {
			if($("img").eq(i).parents("a").length<1) {
				$('img').eq(i).wrap('<a href=\"'+$('img').eq(i).attr('src')+'\" class=\"mobile mobileImgBig\" target=\"_blank\" title="이미지 크게 보기"></a>');
			}
		}
	}else{
		for(var i=0; i<$('img').length; i++) {
			//window.alert($("img").eq(i).parents("a").length);
			if($("img").eq(i).parents("a").length<1) {
				$("img").eq(i).wrap("<a href=\"/board/view_big_image.php?filename="+encodeURIComponent($('img').eq(i).attr('src'))+"\" class=\"boardImgBig\" title=\"이미지 크게 보기\" target=\"_blank\"></a>");
				//$("img").eq(i).wrap("<a href=\""+$('img').eq(i).attr('src')+"\" class=\"mobile mobileImgBig\" title=\"이미지 크게 보기\" onclick=\"window.open("+$('img').eq(i).attr('src')+",'_blank','left=0,top=0');\"></a>");

				//$("img").eq(i).wrap("<a href=\""+$('img').eq(i).attr('src')+"\" class=\"mobile view_image\" title=\"이미지 크게 보기\"></a>");
			}
		}
	}


/*
	if($(window).width() < 768){
		for(var i=0; i<$('img').length; i++) {
			if($('img').eq(i).parents("a").hasClass("mobileImgBig")==false) {
				$('img').eq(i).wrap('<a href=\"'+$('img').eq(i).attr('src')+'\" class=\"mobile mobileImgBig\" target=\"_blank\" title="이미지 크게 보기"></a>');
			}
		}
	}else{
		for(var i=0; i<$('img').length; i++) {
			if($('img').eq(i).parents("a").hasClass("mobileImgBig")==false) {
				$('img').eq(i).wrap('<a href=\"'+$('img').eq(i).attr('src')+'\" class=\"mobile mobileImgBig\" target=\"_blank\" title="이미지 크게 보기"></a>');
			}
		}
	}
*/
});