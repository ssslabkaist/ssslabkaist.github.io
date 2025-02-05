var xmlHttp_EmailDupl;
function createXMLHttpRequest_EmailDupl() {
	if(window.ActiveXObject) {
		xmlHttp_EmailDupl = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHttp_EmailDupl = new XMLHttpRequest();
	}
}




function validate_EmailDupl() {
	createXMLHttpRequest_EmailDupl();
	var queryString = createQryEmailDupl();
	var url = "/ajax_xml/email_dupl_xml.php";
	//window.alert(url);
	xmlHttp_EmailDupl.open("POST", url, true);
	xmlHttp_EmailDupl.onreadystatechange = callback_EmailDupl;
	xmlHttp_EmailDupl.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
	xmlHttp_EmailDupl.send(queryString);
}



function createQryEmailDupl() {

	email = document.getElementsByName('email')[0].value;
	var memberId = "";

	if(document.getElementsByName('member_id')[0]) {
		memberId = document.getElementsByName('member_id')[0].value;
	}
	

	var Querying = "email="+email+"&memberId="+memberId;

	//window.alert(Querying);
	//document.getElementsByName("Querying")[0].value=Querying;

	return Querying;
}


function callback_EmailDupl() {
	if(xmlHttp_EmailDupl.readyState == 4) {
		if(xmlHttp_EmailDupl.status == 200) {

			xmlResponse = xmlHttp_EmailDupl.responseXML;

//			var selIdx = xmlResponse.getElementsByTagName("selIdx")[0].firstChild.data;
//			var languageSize = xmlResponse.getElementsByTagName("language_num")[0].firstChild.data;


			var returnCode = xmlResponse.getElementsByTagName("ireturncode")[0].getAttribute("returnCode");


			if(returnCode=="1") {
				document.getElementsByName('emailChk')[0].value = "Y";
				window.alert("사용 가능한 이메일입니다.");
			}else if(returnCode=="2") {
				document.getElementsByName('emailChk')[0].value = "N";
				window.alert("이미 회원으로 등록되어 있는 이메일입니다.");
			}
		}
	}
}