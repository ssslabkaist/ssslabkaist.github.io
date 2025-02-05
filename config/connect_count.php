<?
@session_start();
@ob_start();
//echo $_COOKIE["setCook"];
//서버시간이 동기화가 안될 경우 쿠키가 생성되지 않음
if (!$_COOKIE["setCook"] && !$_SESSION["session_admin_id"]) {
	$SET_COOK=md5(uniqid(rand()));
	@setcookie("setCook","$SET_COOK",time()+600,"/");
	//setcookie("SET_COOK","$SET_COOK","0","/");
	
	$date=date("Y-m-d");
	
	$cou=mysql_query("select * from Mcount where wdate='$date'",$dbconn);
	$cou_row=mysql_fetch_row($cou);
	
	if (!$cou_row[0]) {
		$count=mysql_query("INSERT INTO Mcount values ('$date',1)",$dbconn);
	}else{
		$count=mysql_query("UPDATE Mcount SET count=count+1 where wdate='$date'",$dbconn);
	}


	$course=$_SERVER["HTTP_REFERER"];
	$IP=$_SERVER["REMOTE_ADDR"];
	$DateTime=date("Y-m-d H:i:s");
	//	echo "$HTTP_REFERER";
	$query = "INSERT INTO Log_analysis VALUES (";
	$query.= "'$DateTime',";
	$query.= "'$course',";
	$query.= "'$IP')";
	$result=mysql_query($query,$dbconn);

	//setcookie("SET_TIME","$DateTime","0","/");

}
?>