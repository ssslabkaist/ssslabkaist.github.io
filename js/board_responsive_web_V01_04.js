// 갤러리 가로크기 기준으로 이미지 높이 정사각형으로 만들기
/*
$(document).ready(function(){
	if($("*").hasClass("imgfull")){
		$(".imgfull").css({maxWidth:"",minWidth:"",maxHeight:"",minHeight:""});
		$(".imgfull .img").css({maxWidth:"",minWidth:"",maxHeight:"",minHeight:""});
		var maxW;
		$(window).resize(function(){
			maxW = $(".imgfull .img").eq(0).width();
			$(".imgfull .img").css({height:maxW,overflow:"hidden"});
		});
		return false;
	}
});
*/


// 갤러리등 이미지를 부모의 BG로 깔아버리고 안 보이게 하기
$(document).ready(function(){
	if($("*").hasClass("galleryListBg")){
		$(".galleryListBg img").css({display:"none"});//실제 img는 안보이게 처리

		$(".galleryListBg .img").each(function(){//이미지를 배경으로 집어 넣기
			$(this).css({backgroundImage:"url("+$(this).find("img").attr("src")+")"});
		});
		$(".galleryListBg").each(function(idx){//한 페이지에 다수의 galleryListBg가 있을 수 있으므로 개별적으로 처리함.
			if($(this).attr("data-ratioH")){
				$(window).resize(function(){//리사이즈에 대응
					var ratio = $(".galleryListBg:eq("+idx+")").attr("data-ratioH");//세로비율 찾고
					var imgW = $(".galleryListBg:eq("+idx+") .img:first").width();
					$(".galleryListBg:eq("+idx+") .img").css({height:parseInt(imgW*(ratio/100))+"px"});
					console.log("galleryListBg 이미지 넓이 = "+imgW);
				});$(window).resize();
			};
		});
	
		/*
		$(".galleryListBg .img").css({//모든 img클래스에 동일한 스타일
			display:"block",overflow:"hidden",
			backgroundRepeat:"no-repeat",backgroundPosition:"50%",backgroundSize:"cover"
		});
		*/
		//목록 이미지 업로드 하지 않았을때 적용
		$(".galleryListBg .img").each(function(idx){			//목록이미지 없으면
			if(0<$(".galleryListBg .img:eq("+idx+") img.nonImg").length) {
				$(".galleryListBg .img:eq("+idx+")").css({//모든 img클래스에 동일한 스타일
					display:"block",overflow:"hidden",
					backgroundRepeat:"no-repeat",backgroundPosition:"50%"
				});
			}else{
				$(".galleryListBg .img:eq("+idx+")").css({//모든 img클래스에 동일한 스타일
					display:"block",overflow:"hidden",
					backgroundRepeat:"no-repeat",backgroundPosition:"50%",backgroundSize:"cover"
				});
			}
		});
	};
});