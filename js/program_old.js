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

//############################광고 투찰 광고비 계산 START
function subHapCalculationAd(){
	var ad_unit_price_hap = 0;
	var ad_quantity_hap = 0;
	var ad_period_hap = 0;
	var ad_supply_price_hap = 0;
	var ad_vat_hap = 0;
	var ad_total_hap = 0;
	
	var len = document.getElementsByName("ad_unit_price[]").length;

	for(var z=0; z<len; z++) {

		var ad_unit_price = 0;
		var ad_quantity = 0;
		var ad_period = 0;
		var totalAmount = 0;
		var supplyPrice = 0;
		var vatPrice = 0;

		if(eval(document.getElementsByName("ad_unit_price[]")[z].value.replace(/,/g,""))>0) {
			ad_unit_price = document.getElementsByName("ad_unit_price[]")[z].value.replace(/,/g,"");
		}

		if(eval(document.getElementsByName("ad_quantity[]")[z].value.replace(/,/g,""))>0) {
			ad_quantity = document.getElementsByName("ad_quantity[]")[z].value.replace(/,/g,"");
		}
		if(eval(document.getElementsByName("ad_period[]")[z].value.replace(/,/g,""))>0) {
			ad_period = document.getElementsByName("ad_period[]")[z].value.replace(/,/g,"");
		}

		totalAmount = ad_unit_price;

		if(eval(ad_quantity)>0) {
			totalAmount = eval(totalAmount)*eval(ad_quantity);
		}

		if(eval(ad_period)>0) {
			totalAmount = eval(totalAmount)*eval(ad_period);
		}


		supplyPrice = eval(totalAmount);
		vatPrice = Math.round(eval(totalAmount)*0.1);


		document.getElementsByName("ad_supply_price[]")[z].value = (eval(supplyPrice)>0) ? commaNum(supplyPrice) : "";
		document.getElementsByName("ad_vat[]")[z].value = (eval(vatPrice)>0) ? commaNum(Math.round(vatPrice)) : "";
		document.getElementsByName("ad_total[]")[z].value = (eval(totalAmount)>0) ? commaNum(eval(totalAmount)+eval(vatPrice)) : "";


		ad_unit_price_hap += eval(ad_unit_price);
		ad_quantity_hap += eval(ad_quantity);
		ad_period_hap += eval(ad_period);
		ad_supply_price_hap += eval(supplyPrice);
		ad_vat_hap += eval(vatPrice);
		ad_total_hap += eval(totalAmount)+eval(vatPrice);
	}

	//document.getElementsByName("ad_unit_price_hap")[0].value = (eval(ad_unit_price_hap)>0) ? commaNum(ad_unit_price_hap) : "";
	//document.getElementsByName("ad_quantity_hap")[0].value = (eval(ad_quantity_hap)>0) ? ad_quantity_hap : "";
	//document.getElementsByName("ad_period_hap")[0].value = (eval(ad_period_hap)>0) ? ad_period_hap : "";
	document.getElementsByName("ad_supply_price_hap")[0].value = (eval(ad_supply_price_hap)>0) ?  commaNum(ad_supply_price_hap) : "";
	document.getElementsByName("ad_vat_hap")[0].value = (eval(ad_vat_hap)>0) ?  commaNum(Math.round(ad_vat_hap)) : "";
	document.getElementsByName("ad_total_hap")[0].value = (eval(ad_total_hap)>0) ?  commaNum(ad_total_hap) : "";


	allHapCalculation();
}
//############################투찰 광고비 계산 END

//############################투찰 제작비 계산 START
function subHapCalculationMa(partPart){
	var ma_unit_price_hap = 0;
	var ma_quantity_hap = 0;
	var ma_supply_price_hap = 0;
	var ma_vat_hap = 0;
	var ma_total_hap = 0;
	
	var len = document.getElementsByName("ma_unit_price[]").length;

	for(var z=0; z<len; z++) {

		var ma_unit_price = 0;
		var ma_quantity = 0;
		var totalAmount = 0;
		var supplyPrice = 0;
		var vatPrice = 0;

		if(eval(document.getElementsByName("ma_unit_price[]")[z].value.replace(/,/g,""))>0) {
			ma_unit_price = document.getElementsByName("ma_unit_price[]")[z].value.replace(/,/g,"");
		}

		if(eval(document.getElementsByName("ma_quantity[]")[z].value.replace(/,/g,""))>0) {
			ma_quantity = document.getElementsByName("ma_quantity[]")[z].value.replace(/,/g,"");
		}

		totalAmount = ma_unit_price;

		if(eval(ma_quantity)>0) {
			totalAmount = eval(totalAmount)*eval(ma_quantity);
		}


		supplyPrice = eval(totalAmount);
		vatPrice = Math.round(eval(totalAmount)*0.1);


		document.getElementsByName("ma_supply_price[]")[z].value = (eval(supplyPrice)>0) ? commaNum(supplyPrice) : "";
		document.getElementsByName("ma_vat[]")[z].value = (eval(vatPrice)>0) ? commaNum(Math.round(vatPrice)) : "";
		document.getElementsByName("ma_total[]")[z].value = (eval(totalAmount)>0) ? commaNum(eval(totalAmount)+eval(vatPrice)) : "";


		ma_unit_price_hap += eval(ma_unit_price);
		ma_quantity_hap += eval(ma_quantity);
		ma_supply_price_hap += eval(supplyPrice);
		ma_vat_hap += eval(vatPrice);
		ma_total_hap += eval(totalAmount)+eval(vatPrice);
	}



	//document.getElementsByName("ma_unit_price_hap")[0].value = (eval(ma_unit_price_hap)>0) ? commaNum(ma_unit_price_hap) : "";
	//document.getElementsByName("ma_quantity_hap")[0].value = (eval(ma_quantity_hap)>0) ? ma_quantity_hap : "";
	document.getElementsByName("ma_supply_price_hap")[0].value = (eval(ma_supply_price_hap)>0) ?  commaNum(ma_supply_price_hap) : "";
	document.getElementsByName("ma_vat_hap")[0].value = (eval(ma_vat_hap)>0) ?  commaNum(Math.round(ma_vat_hap)) : "";
	document.getElementsByName("ma_total_hap")[0].value = (eval(ma_total_hap)>0) ?  commaNum(ma_total_hap) : "";

	if(!partPart) {
		allHapCalculation();
	}else{
		allHapCalculation2();
	}
}
//############################투찰 제작비 계산 END

//############################투찰 제안금액(광고비+제작비) 총합 START
function allHapCalculation() {
	var all_total_hap = 0;
	var ad_total_hap = 0;
	var ma_total_hap = 0;

	if(eval(document.getElementsByName("ad_total_hap")[0].value.replace(/,/g,""))>0) {
		ad_total_hap = document.getElementsByName("ad_total_hap")[0].value.replace(/,/g,"");
	}

	if(eval(document.getElementsByName("ma_total_hap")[0].value.replace(/,/g,""))>0) {
		ma_total_hap = document.getElementsByName("ma_total_hap")[0].value.replace(/,/g,"");
	}

	all_total_hap = eval(ad_total_hap) + eval(ma_total_hap);


	document.getElementsByName("all_total_hap")[0].value = (eval(all_total_hap)>0) ?  commaNum(all_total_hap) : "";
}
//############################투찰 제안금액(광고비+제작비) 총합 END

//############################투찰 제안금액(제작비) 총합 START
function allHapCalculation2() {
	var all_total_hap = 0;
	var ma_total_hap = 0;

	if(eval(document.getElementsByName("ma_total_hap")[0].value.replace(/,/g,""))>0) {
		ma_total_hap = document.getElementsByName("ma_total_hap")[0].value.replace(/,/g,"");
	}

	all_total_hap = eval(ma_total_hap);


	document.getElementsByName("all_total_hap")[0].value = (eval(all_total_hap)>0) ?  commaNum(all_total_hap) : "";
}
//############################투찰 제안금액(제작비) 총합 END





//############################투찰 동종업종수행실적(최근 3 년간) 계산 START
function totalHapCalculationSR() {

	var te_result_amount = 0;
	
	var len = document.getElementsByName("tsr_contract_price[]").length;

	for(var z=0; z<len; z++) {

		var tsr_contract_price = 0;

		if(eval(document.getElementsByName("tsr_contract_price[]")[z].value.replace(/,/g,""))>0) {
			tsr_contract_price = document.getElementsByName("tsr_contract_price[]")[z].value.replace(/,/g,"");
		}

		te_result_amount += eval(tsr_contract_price);
	}


	document.getElementsByName("te_result_amount")[0].value = (eval(te_result_amount)>0) ? commaNum(te_result_amount) : "";


	if((eval(te_result_amount)>0)) {
		trans_han(commaNum(te_result_amount),"te_result_amount_han");
	}else{
		trans_han("","te_result_amount_han");
	}

}


function tsrOrderName() {
	var te_result_num = 0;
	
	var len = document.getElementsByName("tsr_order_name[]").length;

	for(var z=0; z<len; z++) {

		var str = trimAll(document.getElementsByName("tsr_order_name[]")[z].value);
		if(str) {
			te_result_num++;
		}
	}

	document.getElementsByName("te_result_num")[0].value = (eval(te_result_num)>0) ? commaNum(te_result_num) : "";
}
//############################투찰 동종업종수행실적(최근 3 년간) 계산 END





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