<?header("Content-type: text/xml; charset=utf-8");
echo "<?xml version='1.0' encoding='utf-8' ?>\n";
@session_start();
//이메일체크
include "../config/dbconn_2.inc.php";


$email = str_replace(" ","",$email);

if($_SESSION["session_usr_id"]) {
	$Search.=" AND member_id<>'".$_SESSION["session_usr_id"]."'";
}
if($memberId) {
	$Search.=" AND member_id<>'".$memberId."'";
}

$sql = mysql_query("SELECT * FROM member WHERE email='".$email."' AND membership='Y' ".$Search."",$dbconn);
$row = mysql_fetch_row($sql);

if(!$row[0]) {
	$iReturnCode=1;
}else{
	$iReturnCode=2;
}

echo "
<response>
	<ireturncode returnCode=\"".$iReturnCode."\" />
</response>";
?>