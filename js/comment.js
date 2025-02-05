var xmlHttp_Comment;
function createXMLHttpRequest_Comment() {
	if(window.ActiveXObject) {
		xmlHttp_Comment = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHttp_Comment = new XMLHttpRequest();
	}
}




function validate_Comment() {
	createXMLHttpRequest_Comment();
	var queryString = createQuerying_Comment();
	var url = "/ajax_xml/comment_xml.php";
	//window.alert(url);
	xmlHttp_Comment.open("POST", url, true);
	xmlHttp_Comment.onreadystatechange = callback_Comment;
	xmlHttp_Comment.setRequestHeader("Content-Type","application/x-www-form-urlencoded;");
	xmlHttp_Comment.send(queryString);
}



function createQuerying_Comment() {

	//window.alert(document.getElementsByName("ax_c_mode")[0].value);
	var ax_tn = document.getElementsByName("ax_tn")[0].value;
	var ax_sid = document.getElementsByName("ax_sid")[0].value;
	var ax_c_mode = document.getElementsByName("ax_c_mode")[0].value;
	var ax_buid = document.getElementsByName("ax_buid")[0].value;
	var ax_c_sid = document.getElementsByName("ax_c_sid")[0].value;
	var ax_c_gid = document.getElementsByName("ax_c_gid")[0].value;
	var ax_c_level = document.getElementsByName("ax_c_level")[0].value;
	var ax_c_uid = document.getElementsByName("ax_c_uid")[0].value;

	var Querying = "ax_c_mode="+ax_c_mode+"&ax_buid="+ax_buid+"&ax_c_sid="+ax_c_sid+"&ax_c_gid="+ax_c_gid+"&ax_c_level="+ax_c_level+"&ax_c_uid="+ax_c_uid+"&ax_tn="+ax_tn+"&ax_sid="+ax_sid;  

	//document.getElementsByName("Querying")[0].value=Querying;

	return Querying;
}


function callback_Comment() {
	if(xmlHttp_Comment.readyState == 4) {
		if(xmlHttp_Comment.status == 200) {

			xmlResponse = xmlHttp_Comment.responseXML;

//			var selIdx = xmlResponse.getElementsByTagName("selIdx")[0].firstChild.data;
//			var languageSize = xmlResponse.getElementsByTagName("language_num")[0].firstChild.data;


			var mes = xmlHttp_Comment.responseText;
			mes = mes.replace('<![CDATA[','');
			mes = mes.replace(']]>','');

			/*등록, 수정폼 삭제 START*/
			var rstCmtMod = getElementsByClassName("cmtMod","div");
			var aLen = rstCmtMod.length;
			for(var z=0; z<aLen; z++) {
				rstCmtMod[z].innerHTML = "";
				rstCmtMod[z].style.display = "none";
			}

			var rstCmtRegReg = getElementsByClassName("cmtRegReg","div");
			var bLen = rstCmtRegReg.length;
			for(var z=0; z<bLen; z++) {
				rstCmtRegReg[z].innerHTML = "";
				rstCmtRegReg[z].style.display = "none";
			}

			var rstCmtReg = getElementsByClassName("cmtReg","div");
			var cLen = rstCmtReg.length;
			for(var z=0; z<cLen; z++) {
				rstCmtReg[z].innerHTML = "";
				rstCmtReg[z].style.display = "none";
			}

			var rstCmtDel = getElementsByClassName("cmtDel","div");
			var cLen = rstCmtDel.length;
			for(var z=0; z<cLen; z++) {
				rstCmtDel[z].innerHTML = "";
				rstCmtDel[z].style.display = "none";
			}
			/*등록, 수정폼 삭제 END*/

			
			var c_Num = document.getElementsByName("ax_c_Num")[0].value;
			var c_className = document.getElementsByName("ax_c_mode")[0].value;

			var cName = getElementsByClassName(c_className,"div");
			cName[c_Num].style.display = "block";
			cName[c_Num].innerHTML = mes;
			//window.alert(mes);
			
		}
	}
}