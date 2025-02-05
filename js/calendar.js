//=====================================================달력==============================================//
var dataDiv;
var dataTable;
var dataTableBody;
var offsetEl;
var dataSelect_Rayer;
var DayDate = "";
var layerPosition = "";				//레이어 위치지정

var xmlHttp_Cal;
function createXMLHttpRequest_Cal() {
	if(window.ActiveXObject) {
		xmlHttp_Cal = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHttp_Cal = new XMLHttpRequest();
	}
}


function initVars() {
	dataTableBody = document.getElementById("cursorDataBody");
	dataTable = document.getElementById("cursorData");
	dataDiv = document.getElementById("calenderPop");
	dataSelect_Rayer = document.getElementById("Select_Rayer");
}

function getCursorData2() {
	getCursorData("",layerPosition);
}
function getCursorData(element,layer_position,sel_year) {
	//window.alert(element);
	initVars();
	createXMLHttpRequest_Cal();
	if(element) {
		offsetEl = element;
		//window.alert(element.value);

		var datePattern = /[0-9]{4}-[0-9]{2}-[0-9]{2}/;
		
		if(!datePattern.test(element.value) && element.value) {
			window.alert("날짜 형식에 맞게 입력하세요");
			element.focus();
			return;
		}

		if(element.value) {
			var date_sel = element.value;
			var date_sel_a = date_sel.split("-");
			
			var date_year = date_sel_a[0];
			var date_mon = date_sel_a[1];
		}else{
			var date_year = "";
			var date_mon = "";
		}
	}else{
		var date_year = document.getElementsByName("date_year")[0].value;
		var date_mon = document.getElementsByName("date_mon")[0].value;
	}

	layerPosition = layer_position;


		/*
		else{
		var date_year = document.getElementsByName("date_year")[0].value;
		var date_mon = document.getElementsByName("date_mon")[0].value;
	}*/
	//window.alert(s_date_year);
	//window.alert(s_date_mon);

	var url = "/ajax_xml/calendar.php?year=" + escape(date_year) + "&month=" + escape(date_mon) + "&sel_year=" + escape(sel_year);

	//window.alert(url);

	//setInterval("",5000);

	xmlHttp_Cal.open("GET",url,true);
	xmlHttp_Cal.onreadystatechange = callback_cal;
	xmlHttp_Cal.send(null);
}

function callback_cal() {
	if(xmlHttp_Cal.readyState == 4) {
		if(xmlHttp_Cal.status == 200) {
			setData(xmlHttp_Cal.responseXML);
		}
	}
}

function setData(cursorData) {
	clearData_T();
	setOffsets();
	titleSetting();

	var size = cursorData.getElementsByTagName("day").length;

	var week = cursorData.getElementsByTagName("week")[0].firstChild.data;
	var lastday = cursorData.getElementsByTagName("lastday")[0].firstChild.data;


	document.getElementsByName("date_year")[0].value = cursorData.getElementsByTagName("year")[0].firstChild.data;
	document.getElementsByName("date_mon")[0].value = cursorData.getElementsByTagName("month")[0].firstChild.data;

	//window.alert(week);
	var row = "";
	var DayDate = "";
	var z = 1;
	var num = 1;
	for (var i=0; i<size; i++)
	{

		DayDate+=cursorData.getElementsByTagName("day")[i].firstChild.data+"@@";

		//var a = i%7;
		//window.alert(a);

		if((z!=1 && z%7==0) || i==lastday-1) {
			row = createRow(DayDate,week,num);
			dataTableBody.appendChild(row);
			var DayDate = "";
			num++;
		}
		z++;
	}
}

function createRow(data,week,num) {
	var row, cell, txtNode;
	//row = document.createElement("tr");
	row = document.createElement("tr");

	//window.alert(data);
	var data = data.split("@@");
	var z = 0 ;
	//window.alert(data.length);


	for (var i=1; i<data.length; i++) {


		var dataP = data[z].split("||");

		//window.alert(dataP[1]);

		if(data[z]=="nothing") { 
			cell = document.createElement("td");
			cell.setAttribute("bgcolor", "#ffffff");
			cell.setAttribute("border", "0");
			txtNode = document.createTextNode("");
			cell.appendChild(txtNode);
		}else{
			var dataColor = dataP[1].split("^^");

			var datavalue = dataColor[1];
			//window.alert(dataColor[1]);
			cell = document.createElement("td");
			cell.setAttribute("bgcolor", "#ffffff");
			cell.setAttribute("border", "0");
			txtNode = document.createTextNode(dataP[0]);
			cell.appendChild(txtNode);
			cell.datamsg = dataColor[1];
			cell.datacolor = dataColor[0];
			cell.onclick = function () { go_location(this.datamsg) }

			cell.style.color = dataColor[0];
			cell.style.textAlign = "center";
			cell.style.cursor = "pointer";

			if(dataColor[2]=="Y") {
				cell.style.backgroundImage = "url('/img/calendar/calendar_bg.gif')";
				//cell.style.backgroundColor = "#dedede";
				cell.style.backgroundRepeat = "no-repeat";
				cell.style.backgroundPosition = "2px 2px";
			}


			cell.onmouseover = function() { this.className='Calendar_Day_mouseOver';  this.style.color="#FFFFFF";}
			cell.onmouseout = function() { 
				this.className='Calendar_Day_mouseOut';
				this.style.color = this.datacolor; 
				}
			
			//window.alert(cell.datamsg);

		}
		

		row.appendChild(cell);

		z++;
	}

	return row;
}

function setOffsets() {
	var end = offsetEl.offsetWidth;
	var top = calculateOffsetTop(offsetEl);
	var left = calculateOffsetLeft(offsetEl);
	dataDiv.style.border = "1px solid #a9a9a9";
	dataDiv.style.backgroundColor = "#FFFFFF";

	//window.alert(top);
	//window.alert(left);

	if(layerPosition=="TOP") {
		top = eval(top)-100;
		left = eval(left)+100;
	}else if(0<eval(layerPosition)){
		top = eval(top)-eval(layerPosition);
		left = eval(left)+100;		
	}else{
		top = eval(top)+22;
	}



	dataDiv.style.top = top+"px";
	dataDiv.style.left = left+"px";
	//dataDiv.style.width = "150px";
	//dataDiv.style.height = "150px";
	dataDiv.style.visibility = 'visible';
	dataSelect_Rayer.style.display="block";
}

function titleSetting() {

	var row, cell, txtNode;
	var title = new Array();
	var titleColor = new Array();

	title[0]="일";	titleColor[0]="#777777";
	title[1]="월";	titleColor[1]="#777777";
	title[2]="화";	titleColor[2]="#777777";
	title[3]="수";	titleColor[3]="#777777";
	title[4]="목";	titleColor[4]="#777777";
	title[5]="금";	titleColor[5]="#777777";
	title[6]="토";	titleColor[6]="#777777";


	row = document.createElement("tr");

	for (var i=0 ; i<title.length ; i++)
	{
		cell = document.createElement("td");
		//cell.setAttribute("bgcolor", "red");
		cell.setAttribute("border", "0");
		txtNode = document.createTextNode(title[i]);
		cell.appendChild(txtNode);
		cell.style.color = titleColor[i];
		cell.style.textAlign = "center";
//		cell.style.borderTop = "1px solid #f4f4f4";
//		cell.style.borderBottom = "1px solid #f4f4f4";
//		cell.style.background = "#fbfbfb";
		//window.alert(cell.outerHTML);
		row.appendChild(cell);
	}
	
	dataTableBody.appendChild(row);
}


function calculateOffsetTop(field) {
	return calculateOffset(field, "offsetTop");
}
function calculateOffsetLeft(field) {
	return calculateOffset(field, "offsetLeft");
}

function calculateOffset(field,attr) {
	var offset = 0;
	while(field) {
		offset += field[attr];
		field = field.offsetParent;
	}
	return offset;
}


function clearData_T() {
	var ind = dataTableBody.childNodes.length;
	for (var i= ind -1; i>=0 ; i--)
	{
		dataTableBody.removeChild(dataTableBody.childNodes[i]);
	}
	dataDiv.style.border = "none";
}

function clearData() {
	var ind = dataTableBody.childNodes.length;
	for (var i= ind -1; i>=0 ; i--)
	{
		dataTableBody.removeChild(dataTableBody.childNodes[i]);
	}
	dataDiv.style.border = "none";


//	now  = new Date(); 
//  year = now.getYear(); 
//  month= now.getMonth(); 


	dataSelect_Rayer.style.border = "none";
	dataSelect_Rayer.style.display = "none";
}



function go_location(dateValue) {
	//window.alert(dateValue);
	offsetEl.value = dateValue;
	clearData();
	//location.href=url;
}




//setYear(start_year,end_year,sel_year) => 시작년, 종료년, 선택년
function setYear(start_year,end_year,sel_year) {
	for (var q=document.getElementsByName('date_year')[0].options.length;q>=0;q--) {
		document.getElementsByName('date_year')[0].options[q]=null;
	}


	var date = new Date();

	if(start_year) {
		startYear = start_year;
	}else{
		startYear = 1950;
	}
	if(end_year) {
		endYear = end_year;
	}else{
		endYear = date.getFullYear()+1;
	}
	if(sel_year) {
		selYear = sel_year;
	}else{
		selYear = date.getFullYear();
	}

	var z = 0;
	for(var i=startYear; i<=endYear; i++) {
		myEle = document.createElement("option") ;
		myEle.value = i;
		myEle.text = i;

		if(i==selYear) {
			var sel=z;
		}
		document.getElementsByName('date_year')[0].add(myEle);
		z++;
	}
}
//=====================================================달력==============================================//