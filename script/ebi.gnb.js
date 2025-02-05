/* **********************************************************
정팀장 2020ver
- 왼쪽 열림, 오른쪽 열림 두 가지 지원
- 열림방향 제어는 html에서 <nav class="mobile box" data-arrow="right"> 와 같이 data-arrow로 제어 함.
- 패드, 탭 등 1차 메뉴 선택 시 링크 타지 않도록 처리.
********************************************************** */
$(window).load(function(){
	$("nav.mobile").css({visibility:"visible"});
});
$(document).ready(function(){
	// 접속 디바이스 체크
	var deviceCk = "win16|win32|win64|mac|macintel";
	var device;
	if(navigator.platform){
		if( deviceCk.indexOf(navigator.platform.toLowerCase())<0 ){
			device = "mobile";
		}else{
			device = "pc";			
		}
	}
	
	// 가속도
	var easingType1 = "easeInOutExpo";
	var easingType2 = "easeInOutCubic";

	var onmenu;//활성 메뉴
	var nowmenu;//현재 클릭 메뉴
	var speed = 300;//gnb 열림,닫힘 시간 / 1초=1000

	// $("nav.pc .gnb").clone().appendTo($("nav.mobile"));	
	$(".gnb").clone().appendTo($("nav.mobile"));
	$("nav.mobile a").addClass("trans0");	
	$(".gnbHead.pc .menu").clone().appendTo($("nav.mobile .gnb"));
	$("nav.mobile").css({visibility:"hidden"});
	$("nav.mobile .gnb").removeAttr("data-orgH data-gap data-leftPos");

	/* ********************* PC ******************** */
	$(function pcFn(){
		/* ON 메뉴 찾기 */
		onmenu = $("nav.pc .gnb>ul>li.on").index();
		if(onmenu<0) onmenu = "null";

		var orgH = Number($("nav.pc .gnb").attr("data-orgH"));
		var leftPos = $("nav.pc .gnb").attr("data-leftPos");
		var gap = $("nav.pc .gnb").attr("data-gap");

		$("nav.pc").css({height:orgH});
		$("nav.pc .gnb").css({height:"",left:leftPos});
		$("nav.pc .gnb>ul>li").each(function(){
			var device = $(this).attr("data-device");
			if(device == "mobile"){
				$(this).css({display:"none"});
			}else{
				$(this).css({paddingRight:gap}).children("ul").css({width:$(this).outerWidth()-20});
				$("nav.pc").css({visibility:"visible"});
			}
			
			/* 서브메뉴 제어 */
			$(".lnb .wrap>div").on("mouseover",function(){
				$(".lnb .wrap ul").css({display:"none"});
				$(this).children("ul").css({display:"block"});
			});
			$(".lnb .wrap>div").on("mouseleave",function(){
				$(this).children("ul").css({display:"none"});
			});
		});
/*
		$("nav.pc").on("mouseenter touch focusin",function(){//열림
			/* 하위 카테고리 높이 중 가장큰 것 구하기 */
/*
			var lnbHeight = $("nav.pc .gnb>ul>li>ul").map(function(){  
				return $(this).outerHeight();
			}).get(),
			lnbMaxH = Number(Math.max.apply(null, lnbHeight));
			console.log(lnbMaxH);
			$(this).stop().animate({height:lnbMaxH+orgH}, speed);
			$(this).css({boxShadow:"0 5px 5px rgba(0,0,0,.15)"});
			return false;
		}).on("mouseleave focusout",function(){//닫힘
			$(this).stop().animate({height:orgH}, speed, function(){$(this).css({boxShadow:""})});
			return false;
		});
*/		
		//패드, 탭 등 가로사이즈가 Pc모드를 충족하지만 1차 메뉴 선택 시 링크를 타면 하위 메뉴를 볼 수 없기 때문에 처리 함.
		if(device == "mobile"){
			$("nav.pc .gnb>ul>li>a").attr("href","#void");
			$("body").on("touchmove",function(){
				$("nav.pc").stop().animate({height:orgH}, speed);
			});
			$("nav.pc .gnb>ul>li>a").on("click",function(){
				$("nav.pc").stop().animate({height:lnbMaxH}, speed);
			});
		}
	});//end pc

	/* ********************* 모바일 ******************** */
	$(function mobileFn(){
		// pc용 dom을 복제해서 사용함.
		$("nav.mobile").removeAttr("style");
		$("nav.mobile .gnb").removeAttr("style");
		$("nav.mobile .gnb").removeClass("pc");
		var mMenu = "";
		
		var winH;
		var winW;
		var slideArrow = $("nav.mobile").attr("data-arrow");//왼쪽열림 or 오른쪽 열림
		var gnbState=false;//gnb 열림상태
		var gnbPos;//gnb 열림,닫힘 위치
		var closePos;//gnb 닫힐 때 이동위치
		var $dep1 = $("nav.mobile .gnb>li");


		$(window).on("resize",function(){
			winH = $(window).height();
			winW = $(window).width();
			if(slideArrow == "left"){//왼쪽 열림 방식
				closePos = $("nav.mobile").width();//닫힘위치
				$("nav.mobile").css({left:-closePos});
			}
			if(slideArrow == "right"){//오른쪽 열림 방식
				closePos = $("body").width();//닫힘위치
				$("nav.mobile").css({left:closePos});
			}
			$("nav.mobile .gnb").css({height:winH-$("header .closeWrap").outerHeight()});

			// 열린상태이면...(가로,세로 모드로 돌렸을 때 대응)
			if(gnbState==true){
				$(".gnbCover").fadeOut(speed);
				closeFn();
			}else{
				if(slideArrow == "left") $("nav.mobile").stop().css({visibility:"hidden", left:-closePos+20});
				if(slideArrow == "right") $("nav.mobile").stop().css({visibility:"hidden", left:closePos+20});
			}
		}).trigger("resize");

		function gnbActionFn(gnbPos,speed){
			$("nav.mobile").stop()
			.css({
				"transform":"translateX("+gnbPos+"px)",
				"-ms-transform":"translateX("+gnbPos+"px)",
				"-webkit-transform":"translateX("+gnbPos+"px)",
				"-moz-transform":"translateX("+gnbPos+"px)",
				"transition-duration":speed/1000+"s",
				"-ms-transition-duration":speed/1000+"s",
				"-webkit-transition-duration":speed/1000+"s",
				"-moz-transition-duration":speed/1000+"s"
			});
			return false;
		};

		// gnb 열기
		function openFn(){
			if(gnbState==false){
				gnbState = null;//버튼을 연타할 경우를 대비...
				$("html,body").css({position:"fixed"});
				$dep1.removeClass("on").children("ul").css({display:"none"});
				$dep1.eq(onmenu).addClass("on").children("ul").css({display:"block"});

				if(slideArrow == "left") gnbPos=closePos;
				if(slideArrow == "right"){
					$("nav.mobile").css({left:$("body").width()});//여기서 위치를 바디 넓이로 잡는 것은, 크롬계열 브라우저에서는 스크롤바 넓이의 간섭이 생기기 때문.
					gnbPos=-($("nav.mobile").width());
				}
				$("nav.mobile").stop().css({visibility:"visible"});
				gnbActionFn(gnbPos,speed);
				$(".gnbCover").delay(speed).fadeIn(speed,function(){
					gnbState = true;
					return false;
				});
			};
			return false;
		};//end openFn

		// gnb 닫기
		function closeFn(){
			if(gnbState==true){
				gnbState = null;//버튼을 연타할 경우를 대비...
				$("html,body").css({position:"static"});

				gnbPos = 0;
				gnbActionFn(gnbPos,speed);
				$(".gnbCover").delay(speed).fadeOut(speed,function(){
					$dep1.children("a").css({backgroundColor:"",color:""});
					gnbState = false;
					return false;
				});
			};
		};// end closeFn

		$(".gnbView").on("click touchstart focusin", function(){openFn();return false;});
		$(".gnbCover").on("click touchstart", function(){closeFn(); return false;});
		$(".gnbClose").on("click touchstart", function(){closeFn();return false;});

		// gnb 버튼제어
		$dep1.each(function(index){
			if($dep1.eq(index).hasClass("on")){
				onmenu = $(this).index();
				return false;
			};
		});

		$dep1.children("a").on("click",function(){
			if($(this).siblings().length>0){
				if(!$(this).parent().hasClass("on")){
					$dep1.removeClass("on").children("ul").slideUp(300);
					$(this).parent().addClass("on");
					$(this).next("ul").stop().slideDown(300);
				};
				$(this).attr("href","#void");
			}else{
				self.location.href = $(this).attr("href");
			};
		});
		
	});//end mobile
});//end document.ready