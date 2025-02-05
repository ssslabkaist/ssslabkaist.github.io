<?session_start();
include "../common/loginchk.html";
include "../../config/dbconn.inc.php";
include "../../config/functions.inc";

//접속 권한, 네비게이션 위치 START
adminConAuth("A");
//접속 권한, 네비게이션 위치 END

$subquery="project_name_s=".urlencode($project_name_s)."&principal_investigator_s=".urlencode($principal_investigator_s)."&project_state_s=".$project_state_s."&view_state_s=".$view_state_s."&s_regi_date=".$s_regi_date."&e_regi_date=".$e_regi_date."&list_count=".$list_count."";


$tableWidth = "800";

//삭제
if($_GET["mode"]=="resultDel") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$sql = mysql_query("SELECT * FROM project WHERE uid='".$uid."'",$dbconn);
	$row = mysql_fetch_array($sql);

	if($row["img1"]){
		@unlink("../../program_file/project/img1/".$row["img1"]."");
	}

	$query = "DELETE FROM project WHERE uid = '".$uid."'";
	$result = mysql_query($query,$dbconn);
	if(!$result){
		echo "DELETE_ERROR";
	}


	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("project","", "listorder ASC, uid DESC");
	/////////=================정렬순번 세팅 END=====================//


	GO_REFRESH("./list.html?cpage=$cpage&spage=$spage&$subquery");
	exit;
}


//목록 수정
if($_POST["mode"]=="listMod") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END


	for($i=0;$i<sizeof($UID);$i++) {

		$UPD ="UPDATE project SET ";
		$UPD.="project_state='".$projectState[$UID[$i]]."',";
		$UPD.="view_state='".$viewState[$UID[$i]]."',";
		$UPD.="modifydate=now() ";
		$UPD.="WHERE uid='".$UID[$i]."' ";

		$result = mysql_query($UPD,$dbconn);

		if(!$result) {
			ERROR_BACK("DB_UPDATE ERROR");
		}
		//echo "$UPD <br>";
	}

	GO_REFRESH("./list.html?$subquery&cpage=$cpage&spage=$spage");
	exit;
}



//등록
if($_POST["mode"]=="ins"){

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	//=========================================목록 이미지 용량 체크 START================================//
	$img1Path=$_SERVER["DOCUMENT_ROOT"]."/program_file/project/img1";		//업로드 파일 경로
	$img1 = fileUpload($_FILES["img1"],"img","",$img1Path);
	if($img1[0]) {
		$img1[0] = imgSizeAuto($img1[0],$img1Path,$tableWidth);
	}
	//=========================================목록 이미지 용량 체크 END================================//


	$INS ="INSERT INTO project SET ";
	$INS.="view_state='".$view_state."',";
	$INS.="project_state='".$project_state."',";
	$INS.="project_name='".emojiRemoveAddslashes($project_name)."',";
	$INS.="principal_investigator='".emojiRemoveAddslashes($principal_investigator)."',";
	$INS.="research_period='".emojiRemoveAddslashes($research_period)."',";
	$INS.="financial_support='".emojiRemoveAddslashes($financial_support)."',";
	$INS.="content='".emojiRemoveAddslashes($content)."',";
	$INS.="img1='".$img1[0]."',";
	$INS.="listorder='".$listorder."',";
	$INS.="writedate=now(),";
	$INS.="modifydate=now() ";

	$result = mysql_query($INS,$dbconn);

	if (!$result) {
		echo "INSERT INTO ERROR";
		echo "<br> $INS";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("project","", "listorder ASC, uid DESC");
	/////////=================정렬순번 세팅 END=====================//

	Message_Echo("등록 되었습니다.");
	GO_REFRESH("./list.html?".$subquery."&cpage=".$cpage."&spage=".$spage."");
	exit;
}




//수정
if($_POST["mode"]=="mod"){

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	//=========================================이미지 용량 체크 START================================//
	$img1Path=$_SERVER["DOCUMENT_ROOT"]."/program_file/project/img1";		//업로드 파일 경로
	if($img1_del=="Y") {
		@unlink($img1Path."/".$_POST['origi_img1']);
	}else{
		$img1 = fileUpload($_FILES["img1"],"img",$_POST['origi_img1'],$img1Path);
		if($img1[0]) {
			$img1[0] = imgSizeAuto($img1[0],$img1Path,$tableWidth);
		}
	}
	//=========================================이미지 용량 체크 END================================//


	$INS ="UPDATE project SET ";
	$INS.="view_state='".$view_state."',";
	$INS.="project_state='".$project_state."',";
	$INS.="project_name='".emojiRemoveAddslashes($project_name)."',";
	$INS.="principal_investigator='".emojiRemoveAddslashes($principal_investigator)."',";
	$INS.="research_period='".emojiRemoveAddslashes($research_period)."',";
	$INS.="financial_support='".emojiRemoveAddslashes($financial_support)."',";
	$INS.="content='".emojiRemoveAddslashes($content)."',";
	if($img1_del=="Y") {
		$INS.="img1='',";
	}else{
		if ($img1_name) {
			$INS.="img1='".$img1[0]."',";
		}
	}
	$INS.="modifydate=now() ";
	$INS.="WHERE uid='".$uid."' ";

	$result = mysql_query($INS,$dbconn);

	if (!$result) {
		echo "UPDATE ERROR";
		echo "<br> $INS";
		exit;
	}

	Message_Echo("수정 되었습니다.");
	GO_REFRESH("./list.html?".$subquery."&cpage=".$cpage."&spage=".$spage."");
	exit;
}
?>