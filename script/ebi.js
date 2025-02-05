// 두번 터치확대 방지
document.documentElement.addEventListener('touchstart', function (event) {
	if(event.touches.length > 1) {
		event.preventDefault(); 
	}
}, false);

var lastTouchEnd = 0; 

document.documentElement.addEventListener('touchend', function (event) {
	var now = (new Date()).getTime();
	if (now - lastTouchEnd <= 300) {
		event.preventDefault(); 
	} lastTouchEnd = now; 
}, false);


// 태블릿, 모바일에서 100vh 먹게 하는 스크립트 (css와 연동 - main.css)
var vh = window.innerHeight * 0.01;
document.documentElement.style.setProperty('--vh', vh+'px'); 
window.addEventListener('resize', function() {
	var vh = window.innerHeight * 0.01;		
	document.documentElement.style.setProperty('--vh', vh+'px'); 
}); 
window.addEventListener('touchend', function() {
	var vh = window.innerHeight * 0.01; 
	document.documentElement.style.setProperty('--vh', vh+'px'); 
});


$(document).on("ready", function(){
	
	tabWrap() //공통 탭
	journalTab() // 저널 탭
	ie_fixed() //IE에서 백그라운드 오류 방지
	
})



//공통 탭
function tabWrap(){
	
	$(".tabWrap>ul>li").on("click", function(){
		var idx = $(this).index()
		$(".tabWrap>ul>li").removeClass("on")
		$(this).addClass("on")
		$(".tabInner>ul>li").removeClass("on")
		$(".tabInner>ul>li").eq(idx).addClass("on")
	})
	
}

// 리스트 탭
function journalTab(){
	var mode;
	$(window).resize(function(){
		var winW = $(window).width()
		if(winW > 991){
			mode = "pc"
			$(".tabWrap.type2 ul").removeClass("mobile")
			$(".tabWrap.type2 ul").addClass("pc")
			$(".tabWrap.type2 ul").css({display:"block"})
		}else{
			mode = "mobile"
			$(".tabWrap.type2 ul").removeClass("pc")
			$(".tabWrap.type2 ul").addClass("mobile")
			$(".tabWrap.type2 ul").slideUp(0)
		}
	})
	$(".tabWrap.type2").on("click", function(){
		$(".tabWrap.type2 ul.mobile").slideToggle(300)
	})
}


//IE에서 백그라운드 오류 방지 스크립트
function ie_fixed(){
    if(navigator.userAgent.match(/Trident\/7\./)) { // if IE
        $('body').on("mousewheel", function () {
            // remove default behavior
            event.preventDefault(); 
 
            //scroll without smoothing
            var wheelDelta = event.wheelDelta;
            var currentScrollPosition = window.pageYOffset;
            window.scrollTo(0, currentScrollPosition - wheelDelta);
        });
		
		$('body').keydown(function (e) {
			e.preventDefault(); // prevent the default action (scroll / move caret)
			var currentScrollPosition = window.pageYOffset;
	
			switch (e.which) {
	
				case 38: // up
					window.scrollTo(0, currentScrollPosition - 120);
					break;
	
				case 40: // down
					window.scrollTo(0, currentScrollPosition + 120);
					break;
	
				default: return; // exit this handler for other keys
			} 
		});
	}
}







