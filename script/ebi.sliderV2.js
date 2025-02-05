/* *****************************************************************
슬라이더 좌우/상하/패이드 인아웃 - Ver 2
EBI-정팀장 2018.07
*******************************************************************
일반 : normal
상하 : updown
패이드인아웃 : fade
*******************************************************************
- ebi.sliderV2.css 파일필요.
- 페이징 버튼, 방향 아이콘은 ebi.sliderV2.css 에서 수정.
- 기존 animate 방식에서 css + transform 방식으로 변경 함.
***************************************************************** */

$(document).ready(function(){
	if($(".sliderV2").length){
		var countNow = new Array; //현재 보고 있는 카운터
		var countPrev = new Array; //이전에 봤던 카운터
		var sType = new Array; // 슬라이더 타입
		var sTime = new Array; // 지연시간
		var sBtn = new Array; // 좌우 버튼 (css에서 정의해서 사용)
		var sPage = new Array; // 페이지 버튼 (css에서 정의해서 사용)
		var sSpeed = new Array; // 전환 속도
		var timer = new Array; //전환 지연시간
		var reSetTimer = new Array; //전환 지연시간
		var sMax = new Array; // 전체 아이템 갯수
		var arrow = new Array; // 정방향, 역방향
		var clickNow = new Array; //페이지 버튼 클릭한 인덱스
		var clickState = new Array; //페이지 버튼 클릭한 상태인지 아닌지 체크 true, false
		var stopState = new Array; //플레이 스톱
		var onPos = new Array;//온 상태 멈춤위치
		var offPos = new Array;//닫힘 상태 멈춤위치
		var clickOk = new Array;//좌,우,페이지 버튼을 클릭할 수 있는 상태인지 체크 함.(모션중일 때는 클릭 못 함)
		var firstin = new Array;//처음 페이지를 열었는지를 체크.
		var easing = "easeInOutCubic"; //easing 타입

		$(".sliderV2").each(function(idx){
			sType[idx] = $(this).attr("data-type");
			sTime[idx] = Number($(this).attr("data-time"));
			sMax[idx] = Number($(this).find(".sliderObj").children().length -1);
			sSpeed[idx] = Number($(this).attr("data-speed"));
			sBtn[idx] = $(this).attr("data-btn");
			sPage[idx] = $(this).attr("data-page");
			arrow[idx] = "next"; //정방향 기본
			clickState[idx] = false; //페이지 버튼 클릭 아님이 기본
			stopState[idx] = false; //플레이가 기본값. 스톱이 기본
			countNow[idx] = 0;
			clickOk[idx] = true;
			firstin[idx] = true;

			$(this).find(".sliderObj").addClass(sType[idx]); //슬라이더 타입 클래스 넣기
			$(this).find(".sliderButton").addClass(sBtn[idx]);//버튼 타입 넣기
			$(this).find(".sliderPage").addClass(sPage[idx]); //페이지 타입 클래스 넣기
			$(this).find(".sliderObj").children().each(function(i){
				$(this).attr('style','background-image:url("'+$(this).find('img').attr('src')+'") !important');
				$(this).parent().parent().find(".sliderPage").children("div").append("<button><span>"+(i+1)+"</span></button>");
			});
			
			/* ******************************* 리사이즈 ****************************** */
			$(window).resize(function(){
				if(sType[idx] =="normal"){
					onPos[idx] = -($(".sliderV2").eq(idx).width());
					offPos[idx] = -($(".sliderV2").eq(idx).width()*2);
					$(".sliderV2").eq(idx).find(".sliderObj>li.on").stop().css({
						"transform":"translateX("+onPos[idx]+"px)",
						"-ms-transform":"translateX("+onPos[idx]+"px)",
						"-webkit-transform":"translateX("+onPos[idx]+"px)",
						"-moz-transform":"translateX("+onPos[idx]+"px)",
						"transition-duration":0+"ms",
						"-ms-transition-duration":0+"ms",
						"-webkit-transition-duration":0+"ms",
						"-moz-transition-duration":0+"ms"
					});
				};
				if(sType[idx] =="updown"){
					onPos[idx] = -($(".sliderV2").eq(idx).height());
					offPos[idx] = -($(".sliderV2").eq(idx).height()*2);
					$(".sliderV2").eq(idx).find(".sliderObj>li.on").stop().css({
						"transform":"translateY("+onPos[idx]+"px)",
						"-ms-transform":"translateY("+onPos[idx]+"px)",
						"-webkit-transform":"translateY("+onPos[idx]+"px)",
						"-moz-transform":"translateY("+onPos[idx]+"px)",
						"transition-duration":0+"ms",
						"-ms-transition-duration":0+"ms",
						"-webkit-transition-duration":0+"ms",
						"-moz-transition-duration":0+"ms"
					});
				};
				if(firstin[idx]) firstSet(onPos[idx]);//처음이이면  firstSet() 실행.
				return false;
			});

			/* ******************************* 처음 실행  구간 ****************************** */
			function firstSet(){
				if(sType[idx] =="normal"){
					$(".sliderV2").eq(idx).find(".sliderObj>li").eq(0).addClass("on").css({
						"transform":"translateX("+onPos[idx]+"px)","-ms-transform":"translateX("+onPos[idx]+"px)","-webkit-transform":"translateX("+onPos[idx]+"px)","-moz-transform":"translateX("+onPos[idx]+"px)",
						"transition-duration":0+"ms","-ms-transition-duration":0+"ms","-webkit-transition-duration":0+"ms","-moz-transition-duration":0+"ms"
					});
				}
				if(sType[idx] =="updown"){
					$(".sliderV2").eq(idx).find(".sliderObj>li").eq(0).addClass("on").css({
						"transform":"translateY("+onPos[idx]+"px)","-ms-transform":"translateY("+onPos[idx]+"px)","-webkit-transform":"translateY("+onPos[idx]+"px)","-moz-transform":"translateY("+onPos[idx]+"px)",
						"transition-duration":0+"ms","-ms-transition-duration":0+"ms","-webkit-transition-duration":0+"ms","-moz-transition-duration":0+"ms"
					});
				};
				$(".sliderV2").eq(idx).find(".sliderPage>div>button").eq(0).addClass("on");//페이지 버튼 첫번째 클래스 on
				firstin[idx] = false;//처음 한 번 실행 끝.
				return false;
			};


			/* ******************************* 롤링 타임마다 실행  구간 ****************************** */
			function sliderFn(){
				clickOk[idx] = false;//모션이 들어가므로 클릭 못하게 처리.
				if(sMax[idx] > 0){ //아이템 갯수가 2개 이상일 때 동작
					clearInterval(timer[idx]);
					$page = $(".sliderV2").eq(idx).find(".sliderPage").children("div").children();
					if(!clickState[idx]){ //페이지 버튼 클릭한게 아니면 (일반적인 상황)
						if(arrow[idx] == "next"){ // 정방향 일 때
							if(countNow[idx] < sMax[idx]){
								countPrev[idx] = countNow[idx];
								countNow[idx]++;
							}else if(countNow[idx] = sMax[idx]){
								countPrev[idx] = countNow[idx];
								countNow[idx] = 0;
							};
						}else{ //역방향 일 때
							if(countNow[idx] > 0){
								countPrev[idx] = countNow[idx];
								countNow[idx]--;
							}else if(countNow[idx] == 0){
								countPrev[idx] = countNow[idx];
								countNow[idx] = sMax[idx];
							};
						}
					}else{ //페이지버튼을 클릭 한 경우라면...
						countPrev[idx] = countNow[idx];
						countNow[idx] = clickNow[idx]+1;
						clickState[idx] = false;
					}
					$page.eq(countPrev[idx]).removeClass("on"); //내비아이콘의 온 없애고
					$page.eq(countNow[idx]).addClass("on"); //현재 카운터에 온 넣기



					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////// normal / updown 통합
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(sType[idx] =="normal" || sType[idx] =="updown" ){
						var delayTime = 20;
						if(arrow[idx] == "next"){
							//step1 : nowcout 아이템을 이동 전 위치에 배치
							//movFn(대상,이동위치,속도)
							movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]),0,0);

							//step2 : 현재,이전 아이템을 짧은 간격을 두고 이동시킴
							setTimeout(function(){
								$(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]).removeClass("on");
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]),offPos[idx],sSpeed[idx]);
								$(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]).stop().addClass("on");
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]),onPos[idx],sSpeed[idx]);
							},delayTime);

							//step3 : 이동을 마치면 이전 아이템을 이동 전 위치에 배치
							setTimeout(function(){
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]),0,0);
								clickOk[idx] = true;
							},sSpeed[idx]+delayTime);
						}
						if(arrow[idx] == "prev"){
							movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]),offPos[idx],0);

							setTimeout(function(){
								$(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]).removeClass("on");
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]),0,sSpeed[idx]);
								$(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]).stop().addClass("on");
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]),onPos[idx],sSpeed[idx]);
							},delayTime);

							setTimeout(function(){
								movFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]),0,0);
								clickOk[idx] = true;
							},sSpeed[idx]+delayTime);
						}
						// normal 각 아이템 이동
						function movFn(itemName,pos,speed){//itemName을 축약하여 받으면 정상작동하지 않음. 예)$item
							if(sType[idx] =="normal"){
								itemName.stop().css({
									"transform":"translateX("+pos+"px)",
									"-ms-transform":"translateX("+pos+"px)",
									"-webkit-transform":"translateX("+pos+"px)",
									"-moz-transform":"translateX("+pos+"px)",
									"transition-duration":speed+"ms",
									"-ms-transition-duration":speed+"ms",
									"-webkit-transition-duration":speed+"ms",
									"-moz-transition-duration":speed+"ms"
								});
							};
							// updown 각 아이템 이동
							if(sType[idx] =="updown"){
								itemName.stop().css({
									"transform":"translateY("+pos+"px)",
									"-ms-transform":"translateY("+pos+"px)",
									"-webkit-transform":"translateY("+pos+"px)",
									"-moz-transform":"translateY("+pos+"px)",
									"transition-duration":speed+"ms",
									"-ms-transition-duration":speed+"ms",
									"-webkit-transition-duration":speed+"ms",
									"-moz-transition-duration":speed+"ms"
								});
							};
						}
						if(!stopState[idx]){ //스톱모드가 아닐 때만 타이머 작동.
							timer[idx] = setInterval(sliderFn, sTime[idx]);
						}
					}//end normal, updown


					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////// fade - fade in/out 슬라이드
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if(sType[idx] =="fade"){
						fadeFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countNow[idx]),1);
						fadeFn($(".sliderV2").eq(idx).find(".sliderObj").children().eq(countPrev[idx]),0);
						function fadeFn(itemName,opacity){
							itemName.stop().css({
								"opacity":opacity,
								"transition-duration":sSpeed[idx]+"ms",
								"-ms-transition-duration":sSpeed[idx]+"ms",
								"-webkit-transition-duration":sSpeed[idx]+"ms",
								"-moz-transition-duration":sSpeed[idx]+"ms"
							});
							clickOk[idx]= true;
						};
						if(!stopState[idx]){ //스톱모드가 아닐 때만 타이머 작동.
							timer[idx] = setInterval(sliderFn, sTime[idx]);
						}
					}

				}; //end 아이템개수 2개 이상일 때 동작함.
			}; //sliderFn
			timer[idx] = setInterval(sliderFn, sTime[idx]);

			
			
			/* ******************************* 좌,우,카운트 버튼 제어 ****************************** */
			$(this).find("button.prev").click(function(){
				arrow[idx] = "prev";
				if(clickOk[idx]==true) sliderFn();
			});
			$(this).find("button.next").click(function(){
				arrow[idx] = "next";
				if(clickOk[idx]==true) sliderFn();
			});
			$(this).find(".sliderPage button").click(function(){
				if($(this).index() != countNow[idx] && clickOk[idx]==true){
					if($(this).index() > countNow[idx]){
						arrow[idx] = "next";
					}else{
						arrow[idx] = "prev";
					}
					clickNow[idx] = $(this).index()-1;
					clickState[idx] = true;
					sliderFn();
				};
			});
			
			
			
		}); //sliderV2 each
	};//hasClass 'sliderV2'
});