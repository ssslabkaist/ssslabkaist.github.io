<?session_start();
ob_start();
include "../config/dbconn.inc.php";
include "../config/functions.inc";

include "config/common.php";

if($list_auth) {
	if(!preg_match("/".$boardUsrAuth."/",$list_auth) && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("접근 권한이 없습니다.");
	}
}

include "top.html";
include "list_in.php";
include "bottom.html";
?>