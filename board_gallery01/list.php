<?session_start();
ob_start();
include "../config/dbconn.inc.php";
include "../config/functions.inc";

include "config/common.php";

if($list_auth) {
	if(!preg_match("/".$boardUsrAuth."/",$list_auth) && !$_SESSION["session_admin_id"]) {

/*
		if(preg_match("/".$boardUsrAuth."/","/@@@@/")){
			if(!$_SESSION["session_usr_id"]) {
				errorBackNoUrl("정회원에게 제공되는 메뉴입니다.\\n이용하시려면 로그인을 하여 주십시오.");
				//errorBackUrl("정회원에게 제공되는 메뉴입니다. 이용하시려면 로그인을 하여 주십시오.","/member/login.html?nexturl=/board_gallery01/list.php^^tn=board_gallery01&G_state=Y");
			}
		}		
		//준회원
		if($_SESSION["session_usr_auth"]=="Z") {
			$backMent = "'".getMemberAuth($_SESSION["session_usr_auth"])."' 상태에서는 회원 전용의 게시판을 이용하실 수 없습니다.\\n";
			$backMent.= "홈페이지 정회원으로 승격되신 후에 회원 자격으로 이용하여 주십시오.";
			ERROR_BACK($backMent);
		}
*/

		ERROR_BACK("접근 권한이 없습니다.");
	}
}

include "top.html";
include "list_in.php";
include "bottom.html";
?>