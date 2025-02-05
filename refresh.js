//=====================================================세션 유지 시간==============================================//
var xmlHttp;
function createXMLHttpRequest() {
	if(window.ActiveXObject) {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHttp = new XMLHttpRequest();
	}
}

function validate() {
	createXMLHttpRequest();
	var date = 0;
	var url = "/refresh.php?birthDate=" + escape(date.value);
	xmlHttp.open("GET", url, true);
	xmlHttp.onreadystatechange = callback;
	xmlHttp.send(null);
}

function callback() {
	if(xmlHttp.readyState == 4) {
		if(xmlHttp.status == 200) {
			//var mes = xmlHttp.responseXML.getElementsByTagName("message")[0].firstChild.data;
			//var val = xmlHttp.responseXML.getElementsByTagName("passed")[0].firstChild.data;
			//window.alert(val);
			//setMessage(mes, val);
		}
	}
}
validate();
setInterval(validate,60000);			// 1000/1000
//validate();
//=====================================================세션 유지 시간==============================================//