//Member_카테고리
function staffCourseChg(objNum,objVal,step,aVal,bVal,cVal,dVal){
	//var Querying = {"objNum":objNum};
	var Querying = {"objNum":objNum, "objVal":objVal, "step":step, "aVal":aVal, "bVal":bVal, "cVal":cVal, "dVal":dVal};
	//window.alert(objVal);
	var setUrl = "/ajax_xml/staff_course_sel.php";	
	$.ajax({
		type:'POST',
		url : setUrl,
		cache : false,
		data : Querying ,
		async: false, // 리턴값 때문에 동기화 처리
		dataType: "xml" ,
		error : function(req,status,err){
			resVal = "err";
			window.alert("페이지에 오류가 있습니다. 페이지를 자동 갱신합니다.");
			window.location.reload();
		},
		success: function(res){
			//window.alert(res);
			//return;
			//window.alert($("#centerListBtnId > li > a").length);

			var page_yorn = res.getElementsByTagName("page_yorn")[0].firstChild.data;
			var obj_num = res.getElementsByTagName("obj_num")[0].firstChild.data;
			var step = res.getElementsByTagName("step")[0].firstChild.data;

			var aVal = res.getElementsByTagName("aVal")[0].firstChild.data;
			var bVal = res.getElementsByTagName("bVal")[0].firstChild.data;
			var cVal = res.getElementsByTagName("cVal")[0].firstChild.data;
			var dVal = res.getElementsByTagName("dVal")[0].firstChild.data;

			var depth2 = res.getElementsByTagName("depth2")[0].firstChild.data;
			//var depth3 = res.getElementsByTagName("depth3")[0].firstChild.data;

			if(page_yorn=="N") {
				window.alert("페이지에 오류가 있습니다. 페이지를 자동 갱신합니다.");
				window.location.reload();
				return;
			}else{
				if(step=="1") {
					document.getElementsByName(bVal)[obj_num].innerHTML = depth2;
					//document.getElementsByName(cVal)[obj_num].innerHTML = depth3;
					//window.alert(depth2);
				}

				return;
/*
				var aObj =$("#centerListBtnId > li > a");
				for(var i=0; i<aObj.length; i++) {
					if(aNum==i) {
						aObj.eq(i).addClass("on");
					}else{
						aObj.eq(i).removeClass("on");
					}
				}

				document.frmName.cl_uid.value = cl_uid;
				document.getElementById("center_contact_id").innerHTML = center_contact;
				return;
*/


			}
		}
	});
}