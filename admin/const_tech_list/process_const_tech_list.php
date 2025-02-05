<?session_start();
include "../common/loginchk.html";
include "../../config/dbconn.inc.php";
include "../../config/functions.inc";

//접속 권한, 네비게이션 위치 START
adminConAuth("A");
$menuMcategory = "03030000";
//접속 권한, 네비게이션 위치 END

$subquery= "subject_s=".urlencode($subject_s)."&creator_name_s=".urlencode($creator_name_s)."&registration_number_s=".urlencode($registration_number_s)."&view_state_s=".$view_state_s."";


//삭제-목록
if($_GET["mode"]=="delEnd") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$query = "DELETE FROM constTechList WHERE uid='".$uid."'";
	$result = mysql_query($query,$dbconn);
	if(!$result){
		echo "DELETE_ERROR";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("constTechList","", "listorder ASC, uid DESC");
	/////////=================정렬순번 세팅 END=====================//
	

	Message_Echo("삭제되었습니다.");
	GO_REFRESH("./list.html?cpage=".$cpage."&spage=".$spage."&".$subquery."");
	exit;
}



//수정-목록
if($_POST["mode"]=="listMod") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	for($i=0;$i<sizeof($UID);$i++) {

		$UPD ="UPDATE constTechList SET ";
		$UPD.="view_state='".$VIEW_STATE[$UID[$i]]."',";
		$UPD.="modifydate=now() ";
		$UPD.="WHERE uid='".$UID[$i]."' ";

		$result = mysql_query($UPD,$dbconn);

		if(!$result) {
			ERROR_BACK("DB UPDATE ERROR");
		}
		//echo "$UPD <br>";
	}

	GO_REFRESH("./list.html?cpage=".$cpage."&spage=".$spage."&".$subquery."");
	exit;	
}




//등록
if ($_POST["mode"]=="frmReg") {
	
	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$query ="INSERT INTO constTechList SET ";
	$query.="view_state='".$view_state."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="creator_name='".emojiRemoveAddslashes($creator_name)."',";
	$query.="registration_date='".$registration_date."',";
	$query.="registration_number='".emojiRemoveAddslashes($registration_number)."',";
	$query.="writedate=now(),";
	$query.="modifydate=now() ";

	$result = mysql_query($query,$dbconn);

	if (!$result) {
		echo " INSERT INTO ERROR ";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("constTechList","", "listorder ASC, uid DESC");
	/////////=================정렬순번 세팅 END=====================//
	

	Message_Echo("등록 되었습니다.");
	GO_REFRESH("./list.html");
	exit;
}


//수정
if($_POST["mode"]=="frmMod"){

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END


	$query ="UPDATE constTechList SET ";
	$query.="view_state='".$view_state."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="creator_name='".emojiRemoveAddslashes($creator_name)."',";
	$query.="registration_date='".$registration_date."',";
	$query.="registration_number='".emojiRemoveAddslashes($registration_number)."',";
	$query.="modifydate=now()";
	$query.="WHERE uid='".$uid."' ";

	$result = mysql_query($query,$dbconn);

	if (!$result) {
		echo "UPDATE  ERROR";
		//echo "<br> $query";
		exit;
	}

	Message_Echo("수정 되었습니다.");
	GO_REFRESH("./list.html?uid=".$uid."&cpage=".$cpage."&spage=".$spage."&".$subquery."");
	exit;
}
?>