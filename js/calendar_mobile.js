//=====================================================달력==============================================//
var DayDateMobile = "";
var offsetEl;
var exHoliday = "";



function mobileCalendarLayerOpen(){				
	$(".mobileCal .mobileCalPopup").css({display:"block"});
	
	var innerH;
	var innerW;
	$(window).resize(function(){					
		$('.mobileCalInner').load(function(){
			innerW = $(".mobileCalInner").contents().find(".container").outerWidth();
			innerH = $(".mobileCalInner").contents().find(".container").outerHeight();
			$(".mobileCalInner").css({width:innerW+"px"});
			$(".mobileCalInner").css({height:innerH+"px"});
		});					

		innerH = $(".mobileCalInner").contents().find(".container").outerHeight();
		var winH = $(window).height()/2;
		var winW = $(window).width()/2;
		var popH = $(".mobileCalFrame").outerHeight(true)/2;
		var popW = $(".mobileCalFrame").outerWidth(true)/2;														
		$(".mobileCalFrame").css({top:winH-popH, left:winW-popW+"px"});
	});
	$(window).resize();
}

function mobileCalendarLayerClose(){	
	$(".mobileCal .mobileCalPopup").css({display:"none"});
}


var xmlHttp_MobileCal;
function createXMLHttpRequest_mobileCal() {
	if(window.ActiveXObject) {
		xmlHttp_MobileCal = new ActiveXObject("Microsoft.XMLHTTP");
	}else if(window.XMLHttpRequest) {
		xmlHttp_MobileCal = new XMLHttpRequest();
	}
}


function getCursorMobileData(element,sel_year,ex_holiday) {
	//window.alert(element);
	createXMLHttpRequest_mobileCal();
	if(element) {
		offsetEl = element;
		if(element.value) {
			var date_sel = element.value;
			var date_sel_a = date_sel.split("-");
			
			var date_year = date_sel_a[0];
			var date_mon = date_sel_a[1];
			var date_day = date_sel_a[2];
			//window.alert(date_day);
		}else{
			var date_year = "";
			var date_mon = "";
			var date_day = "";
		}
		exHoliday = "";
		if(ex_holiday) {
			exHoliday = "Y";
		}
	}else{
		var date_year = document.getElementsByName("date_year_mobile")[0].value;
		var date_mon = document.getElementsByName("date_mon_mobile")[0].value;
		var date_day = document.getElementsByName("date_day_day_mobile")[0].value;
	}

	var url = "/ajax_xml/calendar_mobile.php?year=" + escape(date_year) + "&month=" + escape(date_mon) + "&day=" + escape(date_day) + "&sel_year=" + escape(sel_year) + "&exHoliday=" + escape(exHoliday);

	//window.alert(url);

	//setInterval("",5000);

	xmlHttp_MobileCal.open("GET",url,true);
	xmlHttp_MobileCal.onreadystatechange = callback_mobileCal;
	xmlHttp_MobileCal.send(null);
}

function callback_mobileCal() {
	if(xmlHttp_MobileCal.readyState == 4) {
		if(xmlHttp_MobileCal.status == 200) {
			setDataMobile(xmlHttp_MobileCal.responseXML);
		}
	}
}

function setDataMobile(cursorData) {
	var size = cursorData.getElementsByTagName("day").length;

	var week = cursorData.getElementsByTagName("week")[0].firstChild.data;
	var lastday = cursorData.getElementsByTagName("lastday")[0].firstChild.data;


	document.getElementsByName("date_year_mobile")[0].value = cursorData.getElementsByTagName("year")[0].firstChild.data;
	document.getElementsByName("date_mon_mobile")[0].value = cursorData.getElementsByTagName("month")[0].firstChild.data;

	var DayDateMobile = "";
	for (var i=0; i<size; i++)
	{
		DayDateMobile+=cursorData.getElementsByTagName("day")[i].firstChild.data+"@@";
	}

	createDataMobile(DayDateMobile);
}

function createDataMobile(data) {

	mobileCalendarLayerOpen();

	//window.alert(data);
	var data = data.split("@@");
	var z = 0 ;
	var zz = 0 ;
	//window.alert(data.length);

	for (var q=document.getElementsByName('date_day_day_mobile')[0].options.length;q>=0;q--) {
		document.getElementsByName('date_day_day_mobile')[0].options[q]=null;
	}

	var AA = 0;
	for (var i=1; i<data.length; i++) {

		//window.alert(dataP[1]);
//window.alert(data[i]);
		if(data[z]!="nothing") {
			var dataPa = data[z].split("||");
			var dataPb = dataPa[1].split("^^");

			if(dataPb[2]=="Y") {
				AA = zz;
			}

			myEle = document.createElement("option") ;
			myEle.value = dataPb[1];
			myEle.text = dataPa[0];
			//myEle.text = dataP[0];

			document.getElementsByName('date_day_day_mobile')[0].add(myEle);

			zz++;
		}

		z++;
	}

	document.getElementsByName('date_day_day_mobile')[0].selectedIndex=AA;
}

function inputCalendarMobile() {
	//window.alert(dateValue);
	offsetEl.value = document.getElementsByName("date_day_day_mobile")[0].value;
	mobileCalendarLayerClose();
	//location.href=url;
}
//=====================================================달력==============================================//