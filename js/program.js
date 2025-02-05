
var frmFlag=false;

function trans_han(amount,targ){ 
	s = amount.replace(/,/g,"");
	t = document.getElementById(targ); 
	if(s.length > 16){ 
		t.innerHTML = ''; 
		return; 
	} else if(isNaN(s)){ 
		t.innerHTML = ''; 
		return; 
	} 
	b1 = ' 일이삼사오육칠팔구'; 
	b2 = '천백십조천백십억천백십만천백십 '; 
	tmp = ''; 
	cnt = 0; 
	while(s != ''){ 
		cnt++; 
		tmp1 = b1.substring(s.substring(s.length-1,s.length), Number(s.substring(s.length-1,s.length))+1); // 숫자 
		tmp2 = b2.substring(b2.length-1,b2.length); // 단위 
		if(tmp1==' '){ // 숫자가 0일때 
			if(cnt%4 == 1){ // 4자리로 끊어 조,억,만,원 단위일때만 붙여줌 
				tmp = tmp2 + tmp; 
			} 
		} else{ 
			if(tmp1 == '일' && cnt%4 != 1){ // 단위가 조,억,만,원일때만 숫자가 일을 붙여주고 나머지는 생략 ex) 삼백일십만=> 삼백십만 
				tmp = tmp2 + tmp; 
			} else{ 
				tmp = tmp1 + tmp2 + tmp; // 그외에는 단위와 숫자 모두 붙여줌 
			} 
		} 
		b2 = b2.substring(0, b2.length-1); 
		s = s.substring(0, s.length-1); 
	}

	if(tmp) {
		tmp = tmp.replace('억만','억').replace('조억','조').replace(' ',''); // 조,억,만,원 단위는 모두 붙였기 때문에 필요없는 단위 제거 
		t.innerHTML = "("+tmp+"원)"; 
	}else{
		t.innerHTML = ""; 
	}
}


function isNumber(value){
	var result = true;
	for(j = 0; result && (j < value.length); j++)
	{
		if( (value.substring(j, j+1) < "0") || (value.substring(j, j+1) > "9"))
			result = false; 
	}
	return result;
}
function commaNum(num) {  
	if (num < 0) { num *= -1; var minus = true} 
	else var minus = false 
	 
	var dotPos = (num+"").split(".") 
	var dotU = dotPos[0] 
	var dotD = dotPos[1] 
	var commaFlag = dotU.length%3 

	if(commaFlag) { 
			var out = dotU.substring(0, commaFlag)  
			if (dotU.length > 3) out += "," 
	} 
	else var out = "" 

	for (var i=commaFlag; i < dotU.length; i+=3) { 
			out += dotU.substring(i, i+3)  
			if( i < dotU.length-3) out += "," 
	} 

	if(minus) out = "-" + out 
	if(dotPos.length>1) return out + "." + dotD 
	else return out  
} 

function comma_price_a(price,num,Fname){
	var price_=price.replace(/,/g,"");
	var EEE="";

	//window.alert(document.getElementsByName("bi_amount")[0].value);

	if(price_.match(/[^0-9]/))
	{
		//alert(a);
		window.alert("숫자만 기재할 수 있습니다.");
		eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'EEE'+";");
		eval("document.getElementsByName('"+Fname+"')["+num+"].focus();");
		return;
	}
	if(isNaN(price_))
	{
		//alert(a);
		window.alert("숫자만 기재할 수 있습니다.");
		eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'EEE'+";");
		eval("document.getElementsByName('"+Fname+"')["+num+"].focus();");
		return;

	}

	var commaprice=commaNum(price_);
	eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'commaprice'+";");
}


function comma_price_b(price,num,Fname){
	var price_=price.replace(/,/g,"");
	var priceMinus_=-price_;
	var EEE="";

	//window.alert(document.getElementsByName("bi_amount")[0].value);

	if(isNaN(price_) && isNaN(priceMinus_) && price_!="-")
	{
		//alert(a);
		window.alert("숫자만 기재할 수 있습니다.");
		eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'EEE'+";");
		eval("document.getElementsByName('"+Fname+"')["+num+"].focus();");
		return;

	}

	var commaprice=commaNum(price_);
	eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'commaprice'+";");
}


function comma_price_a_blur(price,num,Fname){
	var price_=price.replace(/,/g,"");
	var EEE="";

	//window.alert(document.getElementsByName("bi_amount")[0].value);

	if(price_.match(/[^0-9]/))
	{
		//alert(a);
		//window.alert("숫자만 기재할 수 있습니다.");
		eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'EEE'+";");
		eval("document.getElementsByName('"+Fname+"')["+num+"].focus();");
		return;
	}
	if(isNaN(price_))
	{
		//alert(a);
		//window.alert("숫자만 기재할 수 있습니다.");
		eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'EEE'+";");
		eval("document.getElementsByName('"+Fname+"')["+num+"].focus();");
		return;

	}

	var commaprice=commaNum(price_);
	eval("document.getElementsByName('"+Fname+"')["+num+"].value="+'commaprice'+";");
}



function comma_price(price,I,Fname){
	var price_=price.replace(/,/g,"");
	var EEE="";

	if(isNaN(price_))
	{
		//alert(a);
		window.alert("숫자만 기재할 수 있습니다.");
		I.value = EEE;
		I.focus();
		return;
	}

	var commaprice=commaNum(price_);

	I.value=commaprice;
}

function CheckNumber(a) {
	if(isNaN(a.value) || eval(a.value)<1)
	{
		//alert(a);
		window.alert("숫자만 기재할 수 있습니다.");
		a.value="";
		a.focus();

	}
}

//############################공백제거
function trimAll(str){
	var trimStr = str.replace(/(^\s*)|(\s*$)/g,"");
	return trimStr;
}



//숫자만 입력
function digit_check(evt){
    var code = evt.which?evt.which:event.keyCode;
    if(code < 48 || code > 57){
        return false;
    }
}


//숫자만 입력
function onlyNumber(obj) {

	$(obj).val($(obj).val().replace(/[^0-9]/g,""));

/*
    $(obj).blur(function(){
    }); 
    $(obj).keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,""));
    });
*/

}
//바이트 체크
function byte_check(cont, text_byte){
	var i = 0;
	var cnt = 0;
	var exceed = 0;
	var ch = '';
	
	for (i=0; i<cont.value.length; i++) {
		ch = cont.value.charAt(i);
		if (escape(ch).length > 4) {
			cnt += 2;
		} else {
			cnt += 1;
		}
	}
	
	text_byte.innerHTML = cnt + ' / 200 Byte';
	
	if (cnt > 200) {
		exceed = cnt - 200;
		alert('메시지 내용은 200바이트를 넘을수 없습니다.\r\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\r\n초과된 부분은 자동으로 삭제됩니다.');
		var tcnt = 0;
		var xcnt = 0;
		var tmp = cont.value;
		
		for (i=0; i<tmp.length; i++) {
			ch = tmp.charAt(i);
			if (escape(ch).length > 4) {
				tcnt += 2;
			} else {
				tcnt += 1;
			}
			
			if (tcnt > 200) {
				tmp = tmp.substring(0,i);
				break;
			} else {
				xcnt = tcnt;
			}
		}
		cont.value = tmp;
		text_byte.innerHTML = xcnt + ' / 200 Byte';
		return;
	}
}

//바이트 체크
function byteChkReview(cont, text_byte){
	var i = 0;
	var cnt = 0;
	var exceed = 0;
	var ch = '';
	
	for (i=0; i<cont.value.length; i++) {		
		cnt += 1;
	}
	
	text_byte.innerHTML = cnt + ' / 3000자';
	
	if (cnt > 3000) {
		exceed = cnt - 3000;
		alert('메시지 내용은 3000자를 넘을수 없습니다.\r\n작성하신 메세지 내용은 '+ exceed +'자가 초과되었습니다.\r\n초과된 부분은 자동으로 삭제됩니다.');
		var tcnt = 0;
		var xcnt = 0;
		var tmp = cont.value;
		
		for (i=0; i<tmp.length; i++) {

			tcnt += 1;
			
			if (tcnt > 3000) {
				tmp = tmp.substring(0,i);
				break;
			} else {
				xcnt = tcnt;
			}
		}
		cont.value = tmp;
		text_byte.innerHTML = xcnt + ' / 3000자';
		return;
	}
}

function getElementsByClassName(classname,tag) {
	if(!tag) tag = "*";
	var anchs =  document.getElementsByTagName(tag);
	var total_anchs = anchs.length;
	var regexp = new RegExp('\\b' + classname + '\\b');
	var class_items = new Array()

	var NUM=0;
	for(var i=0;i<total_anchs;i++) { //Go thru all the links seaching for the class name
		var this_item = anchs[i];
		if(regexp.test(this_item.className)) {
			class_items.push(this_item);
			NUM++;
		}
	}
	//window.alert(NUM);

	//return NUM; 
	return class_items; 
}

function selectStep(obj) {
	var cName = getElementsByClassName("depth_con","div");
	var len = cName.length;
	//window.alert(len);

	//var len=document.getElementsByClassName("depth_con").length;
	for(var i=0; i<len; i++) {
		if(obj==i) {
			if(cName[i].style.display=="none") {
				cName[i].style.display="block";
			}else{
				cName[i].style.display="none";
			}
		}else{
			cName[i].style.display="none";
		}
	}
}


function goParentSite(url) {
	opener.location.href=url;
	self.close();
}



//해당 영역으로 스크롤 이동
function idPositionMove(id,rollTime){
	if($(".calPop").hasClass("on")==false) {
		var offset = $("#"+id).offset();
		$('html, body').animate({scrollTop : offset.top}, rollTime);
	}
}



/*
//모바일 maxlength 안 먹는거 길이제한
$(document).ready(function(){
    $('input').keyup(function(){
        if ($(this).val().length > $(this).attr('maxlength')) {
            //alert('제한길이 초과');
            $(this).val($(this).val().substr(0, $(this).attr('maxlength')));
        }
    });

	//오직 숫자만 입력 class=onlyNumber 정의 해야지 됨
    $(".onlyNumber").blur(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,""));
    }); 
    $(".onlyNumber").keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,""));
    });
	
    //$(".onlyNumber").keypress(function(){
	//	$(this).val($(this).val().replace(/[^0-9]/g,""));
    //});
});
*/


//숫자가 아닌건 없애기(콤마, 문자등)
function replacePatternNumber(obj) {
	//window.alert(Number(obj.replace(/[^0-9\.]/g,"").replace(/,/g,"")));

	if(!isNaN(Number(obj.replace(/[^0-9\.]/g,"").replace(/,/g,"")))) {	//소수점만 입력했을경우
		return Number(obj.replace(/[^0-9\.]/g,"").replace(/,/g,""));
	}else{
		return Number(obj.replace(/[^0-9]/g,"").replace(/,/g,""));
	}
}

//모바일 maxlength 안 먹는거 길이제한
$(document).ready(function(){
    $('input').keyup(function(){

		//var length = $(this).val().length;
		var maxLen = $(this).attr('maxlength');

		//숫자 - 콤마가 자동으로 찍히는것들
		if($(this).attr("comma")=="yes"){
			//window.alert($(this).val());
			if($(this).val().match(/,/g)) {
				maxLen = eval(maxLen)+eval(($(this).val().match(/,/g)).length);			
				//window.alert($(this).val().length+" "+maxLen+" "+($(this).val().match(/,/g)).length);
			}
		}

        if ($(this).val().length-1 > eval(maxLen)) {
            $(this).val($(this).val().substr(0, maxLen));
        }
    });
/*
    $('input').focus(function(){
		if($(this).attr("readonly")){
			$(this).blur();
		}
	});
*/	
	
	//오직 숫자만(양수) 입력 =====콤마 자동 찍힘====== class=onlyNumberComma 정의 해야지 됨
	$(document).on("keydown blur keyup",".onlyNumber",function(event){
		//window.alert("aaa");
		var str;
		
		$(this).val($(this).val().replace(/[^0-9]/g,""));

		//$(this).val(commaNum($(this).val()));
	});


	
	//오직 숫자만(양수) 입력 =====콤마 자동 찍힘====== class=onlyNumberComma 정의 해야지 됨
	$(document).on("keydown blur keyup",".onlyNumberComma",function(event){
		//window.alert("aaa");
		var str;
		
		if(event.keyCode != 8){
			if (!(event.keyCode >=37 && event.keyCode<=40)) {
				var inputVal = $(this).val();
				 
				str = inputVal.replace(/[^0-9]/gi,'');

				if(1<str.length && str.substring(0,1)==0) {
					str = str.replace(/(^0+)/, "");
				}

				$(this).val(commaNum(str));
			}
		}

		//$(this).val(commaNum($(this).val()));
	});

	//오직 숫자만(음수,양수) 입력 =====콤마 자동 찍힘====== class=onlyNumberComma 정의 해야지 됨
	$(document).on("keydown blur keyup",".onlyNumberCommaPM",function(event){
		//window.alert("aaa");
		var str;
		
		if(event.keyCode != 8){
			if (!(event.keyCode >=37 && event.keyCode<=40)) {
				var inputVal = $(this).val();
				 
				str = inputVal.replace(/[^-0-9]/gi,'');
				 
				if(str.lastIndexOf("-")>0){ //중간에 -가 있다면 replace
					if(str.indexOf("-")==0){ //음수라면 replace 후 - 붙여준다.
						str = "-"+str.replace(/[-]/gi,'');
					}else{
						str = str.replace(/[-]/gi,'');
					}
				}

				if(1<str.length && str.substring(0,1)==0) {
					str = str.replace(/(^0+)/, "");
				}

				//$(this).val(str);

				$(this).val(commaNum(str));
			}
		}
	});





	//오직 숫자+소수점만 입력 =====콤마 자동 찍힘====== class=onlyNumberCommaFs 정의 해야지 됨
	$(document).on("keydown blur keyup",".onlyNumberCommaFs",function(event){
		//window.alert("aaa");
		var str;
		
		if(event.keyCode != 8){
			if (!(event.keyCode >=37 && event.keyCode<=40)) {
				var inputVal = $(this).val();
				 
				str = inputVal.replace(/[^-0-9\.]/gi,'');
				 
				if(1<str.length && str.substring(0,1)==0) {
					str = str.replace(/(^0+)/, "");
				}

				//$(this).val(str);

				$(this).val(commaNum(str));
			}
		}
	});

/*
    $(".onlyNumberCommaFs").keydown(function(){
		$(this).val(commaNum($(this).val().replace(/[^0-9\.]/g,"")));
    });
    $(".onlyNumberCommaFs").blur(function(){
		$(this).val(commaNum($(this).val().replace(/[^0-9\.]/g,"")));
    }); 
    $(".onlyNumberCommaFs").keyup(function(){
		$(this).val(commaNum($(this).val().replace(/[^0-9\.]/g,"")));
    });
*/



	//오직 숫자+콤마 입력 =====콤마 자동 찍힘====== class=notEnter 정의 해야지 됨 keydown, keyup 없애면 입력폼 젤 끝으로 가지 않음
	//$(document).on("keydown blur keyup",".notEnter",function(){
	$(document).on("blur",".notEnter",function(){
		$(this).val($(this).val().replace(/\s+/, "").replace(/\s+$/g, "").replace(/\n/g, "").replace(/\r/g, "").replace(/[^0-9\,]/g,""));
	});


	/*
    $(".onlyNumber").keypress(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,""));
    });
	*/
});





$(document).ready(function(){

	//동적 생성한 html 적용되게
	$(document).on("keyup",".firstBlankRm",function(){
		//window.alert(this.value);
		if(this.value.match(/^\s/)) { 
			this.value = this.value.replace(/(^\s*)/g,''); 
			this.focus(); return false;
		}
	});

	//첫번째 글자 공백 제거
    $(".firstBlankRm").keyup(function(){
		//window.alert(this.value);
		if(this.value.match(/^\s/)) { 
			this.value = this.value.replace(/(^\s*)/g,''); 
			this.focus(); return false;
		}

		//$(this).val($(this).val().replace(/[^0-9]/g,""));
    });
});


//인풋박스 포커스 오른쪽에 위치하게
$(document).ready(function(){
	$(document).on("click",".focusRight",function(){
		var selectionStart = this.value.length;
		var selectionEnd = this.value.length;
		//var selectionStart = 2;
		//var selectionEnd = 2;
		//window.alert(selectionStart);
		if (this.setSelectionRange) {
			this.focus();
			this.setSelectionRange(selectionStart, selectionEnd);
		} else if (this.createTextRange) {
			var range = this.createTextRange();
			range.collapse(true);
			range.moveEnd('character', selectionEnd);
			range.moveStart('character', selectionStart);
			range.select();
		}
	});
});



//==============================KSNET START==============================//
// 현금영수증 출력 스크립트
function CashreceiptView(tr_no)
{
    receiptWin = "http://pgims.ksnet.co.kr/pg_infoc/src/bill/ps2.jsp?s_pg_deal_numb="+tr_no;
    window.open(receiptWin , "" , "scrollbars=no,width=470,height=580");
}
// 신용카드 영수증 출력 스크립트
function receiptView(tr_no)
{
	receiptWin = "http://pgims.ksnet.co.kr/pg_infoc/src/bill/credit_view.jsp?tr_no="+tr_no;
    window.open(receiptWin , "" , "scrollbars=no,width=470,height=700");
}
//==============================KSNET END==============================//



//자동이미지사이즈 조절 팝업
function openWindow(file) { 
window.open (file,"NewWindow","left=100, top=100, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, width=268, height=337"); 
}

function openwin1()
 {
 window.open('/vol02/etc/popup01.html','_blank','toolbar=0, menubar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, width=520, height=300, top=100, left=100');
 }

function OpenWindow(url,intWidth,intHeight) { 
      window.open(url, "_blank", "width="+intWidth+",height="+intHeight+",resizable=0,scrollbars=0") ;
}



function pageGo(url) {
	location.href=url;
}





//해당 영역으로 스크롤 이동
function idPositionMove(id,rollTime){
	if($(".calPop").hasClass("on")==false) {
		var offset = $("#"+id).offset();
		$('html, body').animate({scrollTop : offset.top}, rollTime);
	}
}



//외부로그인 창 사이즈 및 화면 위치 START
var windowWexter = 300;  // 창의 가로 길이
var windowHexter = 500;  // 창의 세로 길이
var leftExter = Math.ceil((window.screen.width - windowWexter)/2);
var topExter = Math.ceil((window.screen.height - windowHexter)/2);
//외부로그인 창 사이즈 및 화면 위치 END

/////////////////////////////////////////////////////////////회원가입 START////////////////////////////////////////////////////////////
//다음 도로명 API - PC START
function memberZip(obj1,obj2,num) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
			var extraRoadAddr = ''; // 도로명 조합형 주소 변수


			// 법정동명이 있을 경우 추가한다.
			if(data.bname !== ''){
				extraRoadAddr += data.bname;
			}
			// 건물명이 있을 경우 추가한다.
			if(data.buildingName !== ''){
				extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
			}
			// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
			if(extraRoadAddr !== ''){
				extraRoadAddr = ' (' + extraRoadAddr + ')';
			}
			// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
			if(fullRoadAddr !== ''){
				fullRoadAddr += extraRoadAddr;
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			//document.getElementsByName(obj1)[num].value = data.postcode;		//우편번호 (6자리)
			document.getElementsByName(obj1)[num].value = data.zonecode;		//우편번호 (5자리)
			document.getElementsByName(obj2)[num].value = fullRoadAddr;			//도로명주소


//우편번호 앞자리			document.getElementsByName("sample4_postcode1").value = data.postcode1;
//우편번호 뒷자리			document.getElementsByName("sample4_postcode2").value = data.postcode2;
//지번주소					document.getElementsByName("addr2")[num].value = data.jibunAddress;


/*
			// 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
			if(data.autoRoadAddress) {
				//예상되는 도로명 주소에 조합형 주소를 추가한다.
				var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
				document.getElementById("guide").innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';

			} else if(data.autoJibunAddress) {
				var expJibunAddr = data.autoJibunAddress;
				document.getElementById("guide").innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';

			} else {
				document.getElementById("guide").innerHTML = '';
			}
*/

		}
	}).open();
}
//다음 도로명 API - PC END




//다음 도로명 API - MOBILE START
var ele_zipLayer = "";
$(window).load(function(){
	// 우편번호 찾기 화면을 넣을 element
	ele_zipLayer = document.getElementById('zipLayer');
});//end document.ready

	
function closeDaumPostcode() {
	// iframe을 넣은 element를 안보이게 한다.
	ele_zipLayer.style.display = 'none';
}

function memberZipMob(obj1,obj2,num) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = data.address; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 기본 주소가 도로명 타입일때 조합한다.
			if(data.addressType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementsByName(obj1)[num].value = data.zonecode; //5자리 새우편번호 사용
			document.getElementsByName(obj2)[num].value = fullAddr;
			//document.getElementById('sample2_addressEnglish').value = data.addressEnglish;

			// iframe을 넣은 element를 안보이게 한다.
			// (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
			ele_zipLayer.style.display = 'none';
		},
		width : '100%',
		height : '100%',
		maxSuggestItems : 5
	}).embed(ele_zipLayer);

	// iframe을 넣은 element를 보이게 한다.
	ele_zipLayer.style.display = 'block';

	// iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
	zipLayerPosition();
}

// 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
// resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
// 직접 ele_zipLayer의 top,left값을 수정해 주시면 됩니다.
function zipLayerPosition(){
	var width = 300; //우편번호서비스가 들어갈 element의 width
	var height = 400; //우편번호서비스가 들어갈 element의 height
	var borderWidth = 5; //샘플에서 사용하는 border의 두께

	// 위에서 선언한 값들을 실제 element에 넣는다.
	ele_zipLayer.style.width = width + 'px';
	ele_zipLayer.style.height = height + 'px';
	ele_zipLayer.style.border = borderWidth + 'px solid';
	// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
	ele_zipLayer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
	ele_zipLayer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
}
//다음 도로명 API - MOBILE END
/////////////////////////////////////////////////////////////회원가입 END////////////////////////////////////////////////////////////