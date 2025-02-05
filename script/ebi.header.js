$(document).ready(function(){
	var nowidx = 0;//현재선택메뉴 번호
	var maxitem = $(".gnb>li").length;//메뉴개수
	var delaytime = 500;//지연시간
	var baseState = false;//1차 카테고리만 있는지 체크
	var activeState = false;//메뉴 사용 체크
	var arrow;//업다운방향
	
	//transition css 없애고 여기서 추가
	// $(".dep1Navi").css({transition:"none"})
	// $(".dep1Navi a").css({transition:"none"});
	setTimeout(reCss, 100);
	function reCss(){
		$("header").css({transition:"all .5s ease"})
		$(".dep1Navi .wrap").css({transition:"all .5s ease"})
		$("header a").css({transition:"all .5s ease"});
	}

	/* 메뉴명 복제*/
	$(".gnb>li").each(function(i){
		$(this).children().first().clone().appendTo(".dep1Base, .dep1Navi .wrap");
	});
	
	// 서브페이지에서는 메뉴 항상 열려있어야함
	if(!$("body").hasClass("mainArea")){
		// console.log("메인")
		if(!activeState){
			activeState = true;
			$(".dep1Navi").addClass("active")
			var dep1H = $(".dep1Navi a").outerHeight();
			var nowidx = $(".dep1Navi .wrap a.on").index()
			$(".dep1Navi .wrap").css({marginTop:(-dep1H*nowidx)+27});
			$(".dep1Navi .wrap a").eq(nowidx).addClass("active");
			$(".gnb>li").eq(nowidx).addClass("active").find("ul").addClass("active").find("a").addClass("active");
			dep1rollFn()
		}
		
	}
	
	//하단 돋보기 클릭시 1차메뉴 '오픈'만하기
	$(".main .search").on("click", function(){
		if(!activeState){
			// alert("v")
			$(".dep1Base a").stop().addClass("active");
			if(!baseState){
				$(".dep1Base").stop().addClass("active");
				baseState = true;
			}else{
				baseState = true;
				setTimeout(function(){
					$(".dep1Base").stop().addClass("active")
				},delaytime);
			}
		}
	})
	// end 돋보기 클릭시
	
	
	/* 로고 클릭 시 1차메뉴 보임 */
	$(".logo.main").on("click", function(){
		if(activeState){//메뉴 모두 닫기
			$(".dep1Navi").fadeOut(delaytime);
			$(".gnb li a").removeClass("active");
			setTimeout(function(){
				nowidx = 0;
				$(".gnb *").removeClass("active");
				$(".dep1Navi").toggleClass("active");
				activeState = false;
			},delaytime);
		}else{//메뉴 펼치기
			$(".dep1Base a").stop().toggleClass("active");
			if(!baseState){
				$(".dep1Base").stop().toggleClass("active");
				baseState = true;
			}else{
				baseState = false;
				setTimeout(function(){
					$(".dep1Base").stop().toggleClass("active")
				},delaytime);
			}
		}
	});
	

	/* 펼침 1차메뉴 클릭 시 */
	var anion = false;
	$(".dep1Base a").on("click",function(){
		/* 펼침메뉴 닫음 */
		// console.log(baseState +" / "+activeState);
		if(baseState && !activeState){
			nowidx = $(this).index();
			baseState = false;
			activeState = true;
			$(".dep1Base a").stop().toggleClass("active");
			$(".dep1Navi").stop().fadeIn(delaytime);
			setTimeout(function(){
				$(".dep1Base").stop().toggleClass("active");
			},delaytime);

			$(".dep1Navi").toggleClass("active");
			$(".dep1Navi .wrap a").removeClass("active").eq(nowidx).addClass("active");
			dep1rollFn();
		}
	});
	
	/* 업,다운 클릭 시 */
	$(".dep1Navi button").on("click",function(){
		arrow = $(this).attr("data-arrow");
		updownFn();
	});
	

	/* 열린 상태에서 1차메뉴 클릭 시 */
	$(".dep1Navi .wrap a").on("click",function(){
		if(!baseState && activeState){
			baseState = true;
			activeState = false;
			$(".dep1Navi").stop().fadeOut(delaytime);
			$(".dep1Navi .wrap a").stop().removeClass("active");
			$(".gnb li a").removeClass("active");
			setTimeout(function(){
				nowidx = 0;
				$(".dep1Navi").toggleClass("active");
				$(".gnb *").removeClass("active");
				$(".dep1Navi .wrap").stop().css({marginTop:27});
				$(".dep1Base").stop().toggleClass("active").find("a").stop().toggleClass("active");
			},delaytime);
		}
	});

	/* 마우스 휠 제어 / ie9이상,크롬,파폭,웨일 확인 */
	$(".dep1Navi").on("mousewheel DOMMouseScroll", function(e) {
		if(activeState){//메뉴가 펼친 상태에서만 구동
			if(e.originalEvent.wheelDelta > 0 ||  e.originalEvent.detail < 0){//휠 올릴 때
				arrow="up";
			}else if(e.originalEvent.wheelDelta < 0 ||  e.originalEvent.detail > 0){//휠 내릴 때
				arrow="down";
			}
			updownFn();
		}
		return false;
	});

	function updownFn(){
		if(arrow == "down"){//위로 클릭, 휠
			if(nowidx < maxitem-1){
				nowidx++;
				dep1rollFn();
			}
		}else{
			if(nowidx > 0){//아래로 클릭, 휠
				nowidx--;
				dep1rollFn();
			}
		}
		$(".dep1Navi .wrap a").removeClass("active").eq(nowidx).addClass("active");
	}

	/* 선택 오브젝트 구동 */
	function dep1rollFn(){
		console.log("현재메뉴 = "+nowidx);
		if(activeState){//메뉴가 펼친 상태에서만 구동
			/* 위 아래 버튼 상태 체크 */
			if(nowidx == 0){
				$(".dep1Navi .up").addClass("off");
				$(".dep1Navi .down").removeClass("off");
			}else if(nowidx == maxitem-1){
				$(".dep1Navi .up").removeClass("off");
				$(".dep1Navi .down").addClass("off");
			}else{
				$(".dep1Navi .up").removeClass("off");
				$(".dep1Navi .down").removeClass("off");
			}

			/* 위치 제어 */
			var dep1H = $(".dep1Navi a").outerHeight();
			$(".dep1Navi .wrap").stop().css({marginTop:(-dep1H*nowidx)+27});//여기서 17은 위에서 높이값
			$(".gnb>li a").removeClass("active");
			setTimeout(function(){
				$(".gnb>li").removeClass("active").eq(nowidx).addClass("active").children().addClass("active");
				$(".gnb>li").eq(nowidx).find("a").addClass("active");
			},delaytime);
		}
	}
	
});