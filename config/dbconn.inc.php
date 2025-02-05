<?@header('Content-type: text/html; charset=utf-8');
@header('X-UA-Compatible:IE=Edge');
@session_start();
@ini_set("memory_limit", -1);		//메모리 부족에러 뜰때


@extract($_GET); 
@extract($_POST); 
@extract($_SERVER); 


$DB_SERVER="localhost";
$DB_LOGIN="ssslab";
$DB_PASSWORD="sssLab#@ss9";
$DB="ssslab";

$dbconn = mysql_connect($DB_SERVER,$DB_LOGIN,$DB_PASSWORD) or die("데이터베이스 연결에 실패하였습니다.");

$status = mysql_select_db($DB,$dbconn);
mysql_query("set names utf8",$dbconn);
if (!$status) {
   error("DB_CONNECT_ERROR");
   exit;
} 


//################################ mysql 인젝션 공격에 대비 2021-05-07 START #######################################//
require_once("".$_SERVER['DOCUMENT_ROOT']."/config/defense.php");
//################################ mysql 인젝션 공격에 대비 2021-05-07 END #######################################//


//$HTTP_HOST_RE = "https://".str_replace(":4443","",$HTTP_HOST).":4443";		//SSL 사용할때					2019-12-05 15:10 설정
$HTTP_HOST_RE = "";														//SSL 사용안할때

// HTTPS 체크 및 URL 리턴
//if(!isset($_SERVER["HTTPS"])) { 
	//header('Location: http://'.$HTTP_HOST.$_SERVER['REQUEST_URI']);
//}


$httpProtocol = "http";
if($_SERVER["HTTPS"]=="on") {
	$httpProtocol = "https";
}




//=============================인크루드 페이지 다이렉트 접속 못하게 START=============================//
$pageConYorN = "Y";	//페이지 접속 권한
//=============================인크루드 페이지 다이렉트 접속 못하게 END=============================//

//도메인 추출 정규식
if(!function_exists('getDomainName')){
    function getDomainName($url){
        $value = strtolower(trim($url));
        $url_patten = '/^(?:(?:[a-z]+):\/\/)?((?:[a-z\d\-]{2,}\.)+[a-z]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i';
        $domain_patten = '/([a-z\d\-]+(?:\.(?:asia|info|name|mobi|com|net|org|biz|tel|xxx|kr|co|so|me|eu|cc|or|pe|ne|re|tv|jp|tw)){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i';
 
        if (preg_match($url_patten, $value)){
            preg_match($domain_patten, $value, $matches);
            $host = (!$matches[1]) ? $value : $matches[1];
        }
        return $host;
    }
}

//echo getDomainName($_SERVER["HTTP_HOST"]);
$domainMainHost = getDomainName($_SERVER["HTTP_HOST"]);	//모바일 쿠키 구월때 사용됨
//$domainMainHost = "localhost";



//********************모바일 접속시 세션, 쿠키 체크 START*****************************//
$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
	@setcookie("mobileYorN","Y",time()+86400,"/",$domainMainHost);
	//@setcookie("pcYorN","",time()-3600,"/",$domainMainHost);
}else {
	@setcookie("mobileYorN","",time()-3600,"/",$domainMainHost);
	//@setcookie("pcYorN","Y",time()+86400,"/",$domainMainHost);
}
//********************모바일 접속시 세션, 쿠키 체크 END*****************************//
?>
<!DOCTYPE html>
<?
$dtdType="Y";
?>

<script language="Javascript" src="/refresh.js"></script>
<script language="Javascript" src="/js/calendar.js"></script>

<?
if(!$_SESSION["session_usr_id"] && $_COOKIE["moLogCookie"]){
	
	@setcookie("moLogCookie",$_COOKIE["moLogCookie"],time()+8640000,"/",$domainMainHost);

	echo "
	<script>
		//window.alert('aaa');
		location.reload();
	</script>";
}
?>

<?
/*
//https 일 경우 localStorage 공유가 안되는거 같음
if($_SESSION["session_usr_id"] && $_COOKIE["mobileYorN"]=="Y"){
	echo "
	<script>
		if(!localStorage.getItem('usrMid')) {
			localStorage.setItem('usrMid', '".$_SESSION["session_usr_id"]."'); 
			//window.alert('".$res["member_id"]."');
			//window.alert(localStorage.getItem('usrMid'));
		}
	</script>";
}

if(!$_SESSION["session_usr_id"]){
	echo "
	<script>
		if(localStorage.getItem('usrMid')) {
			//location.reload();
			//window.alert(localStorage.getItem('usrMid'));
		}
	</script>";
}
*/
?>
