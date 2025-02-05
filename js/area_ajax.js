var xmlHtml;
function createxmlHtmlRequest() {
	if(window.ActiveXObject) {
		xmlHtml = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHtml = new XMLHttpRequest();
	}
}

var KK = 0;
var urlValue = "";

function areaHtmlLoad(urlGo) {
	urlValue = urlGo;

	//loadingPop();

	createxmlHtmlRequest();
	
	var date = new Date();
	//window.alert(document.getElementsByName('address_sido_v')[0].value);

	var BcategorySelValue=document.getElementsByName('address_sido_v')[0].value;
	var McategorySelValue=document.getElementsByName('address_gugun_v')[0].value;
	var ScategorySelValue=document.getElementsByName('address_detail_v')[0].value;

	//window.alert(McategorySelValue);

	var queryString = "sido="+BcategorySelValue+"&gugun="+McategorySelValue+"&dong="+ScategorySelValue+"&bDate="+escape(date);
	
	//window.alert(queryString);
	var url = urlGo;
	//window.alert(urlGo);
	xmlHtml.open("POST", url, true);
	xmlHtml.onreadystatechange = callbackCarHtml2;
	xmlHtml.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
	xmlHtml.send(queryString);
}

function areaHtml(urlGo) {
	urlValue = urlGo;

	//loadingPop();

	createxmlHtmlRequest();
	
	var date = new Date();
	//window.alert(document.getElementsByName('address_sido')[0].value);

	var BcategorySelValue=document.getElementsByName('address_sido')[0].value;
	var McategorySelValue=document.getElementsByName('address_gugun')[0].value;
	var ScategorySelValue=document.getElementsByName('address_detail')[0].value;

	//window.alert(McategorySelValue);

	var queryString = "sido="+BcategorySelValue+"&gugun="+McategorySelValue+"&dong="+ScategorySelValue+"&bDate="+escape(date);
	
	//window.alert(queryString);
	var url = urlGo;
	//window.alert(urlGo);
	xmlHtml.open("POST", url, true);
	xmlHtml.onreadystatechange = callbackCarHtml;
	xmlHtml.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
	xmlHtml.send(queryString);
}
function callbackCarHtml() {
	if(xmlHtml.readyState == 4) {
		if(xmlHtml.status == 200) {
			setCarSel(xmlHtml.responseXML);
			setCarExe(urlValue);
			KK++;
		}
	}
}
function setCarExe(urlValue) {
	if(KK==0) {
		//window.alert(urlValue);
		areaHtml(urlValue);
		return;
	}
}

function callbackCarHtml2() {
	if(xmlHtml.readyState == 4) {
		if(xmlHtml.status == 200) {
			setCarSel(xmlHtml.responseXML);
			setCarExe2(urlValue);
			KK++;
		}
	}
}
function setCarExe2(urlValue) {
	if(KK==0) {
		//window.alert(urlValue);
		areaHtmlLoad(urlValue);
		return;
	}
}

function setCarSel(xmlResponse) {


	//window.alert("왔냥1");
	for (var q=document.getElementsByName('address_gugun')[0].options.length;q>=0;q--) {
		document.getElementsByName('address_gugun')[0].options[q]=null;
	}
	

	var McategoryValueSize = xmlResponse.getElementsByTagName("McategoryValue").length;
	var firstMcategoryValue = xmlResponse.getElementsByTagName("McategoryValue")[0].getAttribute("value");

	//window.alert(McategoryValueSize);
	//window.alert(urlValue);

	if(McategoryValueSize>0 && firstMcategoryValue) {
		myEle = document.createElement("option") ;
		myEle.value = "" ;
		myEle.text = "시/군/구" ;
		myEle.selected = "";
		document.getElementsByName('address_gugun')[0].add(myEle) ;


		var k=1;
		for (var i=0; i<McategoryValueSize; i++){
			if(xmlResponse.getElementsByTagName("McategoryValue")[i].getAttribute("value")) {
				myEle = document.createElement("option");


				myEle.value = xmlResponse.getElementsByTagName("McategoryValue")[i].getAttribute("value");
				myEle.text = xmlResponse.getElementsByTagName("McategoryValue")[i].getAttribute("name");

				//window.alert(xmlResponse.getElementsByTagName("McategoryValue")[i].getAttribute("mSel"));
				if (xmlResponse.getElementsByTagName("McategoryValue")[i].getAttribute("mSel") == "Y"){
					var AA=k;

					var McategoryValueDom = xmlResponse.getElementsByTagName("McategoryValue")[i];	

				}

				document.getElementsByName('address_gugun')[0].add(myEle);
				k++;
			}
			//window.alert(AA);
			document.getElementsByName('address_gugun')[0].selectedIndex=AA;
		}
	}


	
	if(!document.getElementsByName('address_sido')[0].value) {

		for (var q=document.getElementsByName('address_gugun')[0].options.length;q>=0;q--) {
			document.getElementsByName('address_gugun')[0].options[q]=null;
		}
		
		myEle = document.createElement("option") ;
		myEle.value = "" ;
		myEle.text = "시/군/구" ;
		myEle.selected = "";
		document.getElementsByName('address_gugun')[0].add(myEle) ;
	}
	
	//chkClose();
}
function changeArea(urlGo) {
	KK=0;
	areaHtml(urlGo);
}