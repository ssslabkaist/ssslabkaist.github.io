<?

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

/*
.htaccess 에 설정되어 있음(리다이렉트)
$HTTP_HOST_RE = "https://".str_replace(":4440","",$HTTP_HOST).":4440";
//$HTTP_HOST_RE = "";
$HTTP_HOST = str_replace(":4443","",$HTTP_HOST);
*/

//$HTTP_HOST_RE = "https://".str_replace(":4443","",$HTTP_HOST).":4443";		//SSL 사용할때					2019-12-05 15:10 설정
$HTTP_HOST_RE = "";														//SSL 사용안할때



$httpProtocol = "http";
if($_SERVER["HTTPS"]=="on") {
	$httpProtocol = "https";
}


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
?>
