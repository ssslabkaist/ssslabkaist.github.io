<?
header('Content-type: text/xml; charset=utf-8');
@session_start();
@ob_start();


include "./config/dbconn_2.inc.php";


$message = "You have entered a invalid date";

if(!$_SESSION["session_usr_id"] && !$_COOKIE["moLogCookie"]) {
	$message="kkk";
}else{

	if($_SESSION["session_usr_id"] || $_COOKIE["moLogCookie"]) {
		if($_SESSION["session_usr_id"]) {
			$sql = mysql_query("SELECT * FROM member WHERE member_id='".$_SESSION["session_usr_id"]."' AND membership='Y'",$dbconn);
			$res = mysql_fetch_array($sql);
		}else{
			$sql = mysql_query("SELECT * FROM member WHERE md5(member_id)='".$_COOKIE["moLogCookie"]."' AND membership='Y'",$dbconn);
			$res = mysql_fetch_array($sql);
		}

		
		if($res["admin_auth"] && $res["admin_auth_confession"]=="Y") {
			$_SESSION["session_admin_id"]=$res["member_id"];
			$_SESSION["session_admin_name"]=$res["member_name"];
			$_SESSION["session_admin_email"]=$res["email"];
			$_SESSION["session_admin_auth"] = $res["admin_auth"];
			$_SESSION["session_admin_auth_conf"]=$res["admin_auth_confession"];
		}else{
			$_SESSION["session_admin_id"]="";
			$_SESSION["session_admin_name"]="";
			$_SESSION["session_admin_email"]="";
			$_SESSION["session_admin_auth"] = "";
			$_SESSION["session_admin_auth_conf"]="";
		}


		if($res["membership"]=="Y" && $res["member_auth_confession"]=="Y") {
			$_SESSION["session_usr_id"] = $res["member_id"];
			$_SESSION["session_usr_name"] = $res["member_name"];
			$_SESSION["session_usr_auth"] = $res["member_auth"];
			$_SESSION["session_usr_auth_num"] = $res["member_auth_num"];
			$_SESSION["session_usr_email"]= $res["email"];
			$_SESSION["session_usr_staff_yorn"] = $res["staff_yorn"];
			//$_SESSION["session_usr_external_kind"] = $res["external_kind"];
		}else{
			$_SESSION["session_usr_id"] = "";
			$_SESSION["session_usr_name"] = "";
			$_SESSION["session_usr_auth"] = "";
			$_SESSION["session_usr_auth_num"] = "";
			$_SESSION["session_usr_email"]= "";
			$_SESSION["session_usr_staff_yorn"] = "";
			//$_SESSION["session_usr_external_kind"] = "";
		}

	}



/*

	$session_usr_auth = $row[0];
	session_register("session_usr_auth");		//회원구분
*/
//	$message=$_SESSION["session_usr_id"];
}


?>
<response>
	<passed><?=$message?></passed>
	<message><?=$message?></message>
</response>