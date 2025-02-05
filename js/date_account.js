function getDate(s, e, i){
	var newDt = new Date();
	newDt.setDate( newDt.getDate() - i );

	if(i<0) {
		document.getElementsByName(s)[0].value="";
		document.getElementsByName(e)[0].value="";
	}else{
		document.getElementsByName(s)[0].value=converDateString(newDt);
		document.getElementsByName(e)[0].value=converDateString(new Date());
	}
}

function converDateString(dt){
	//window.alert(dt);
	return dt.getFullYear() + "-" + addZero(eval(dt.getMonth()+1)) + "-" + addZero(dt.getDate());
}
function addZero(i){
	var rtn = i + 100;
	return rtn.toString().substring(1,3);
}




function getYear(s, e, y, m){
	if(y<0) {
		document.getElementsByName(s)[0].value="";
		document.getElementsByName(e)[0].value="";
	}else{
		document.getElementsByName(s)[0].value=y;
		document.getElementsByName(e)[0].value=m;
	}
}