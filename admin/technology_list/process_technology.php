<?session_start();
include "../common/loginchk.html";
include "../../config/dbconn.inc.php";
include "../../config/functions.inc";

//접속 권한, 네비게이션 위치 START
adminConAuth("A");
$menuMcategory = "03040000";
//접속 권한, 네비게이션 위치 END

$subquery= "subject_s=".urlencode($subject_s)."&name_s=".urlencode($name_s)."&company_s=".urlencode($company_s)."&view_state_s=".$view_state_s."";


//삭제-목록
if($_GET["mode"]=="delEnd") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$sql = mysql_query("SELECT * FROM technologyList WHERE uid='".$uid."'",$dbconn);
	$row = mysql_fetch_array($sql);

	if($row["file1"]){
		@unlink("../../program_file/technology_list/file1/".$row["file1"]."");
	}

	$query = "DELETE FROM technologyList WHERE uid='".$uid."'";
	$result = mysql_query($query,$dbconn);
	if(!$result){
		echo "DELETE_ERROR";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("technologyList","", "listorder ASC, uid DESC");
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

		$UPD ="UPDATE technologyList SET ";
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

	//----------------------------------------------파일업로드 START-------------------------------------//
	//=========================================첨부파일 용량 체크 START================================//
	$maxFileSize = 20*1024*1024;
	$fileUpSize = 0;
	if($_FILES["file1"]["tmp_name"]){
		$fileUpSize += filesize($_FILES["file1"]["tmp_name"]);
	}
	//echo $fileUpSize;
	//echo "$maxFileSize<$fileUpSize";
	//exit;

	if($maxFileSize<$fileUpSize) {
		ERROR_BACK("첨부파일을 합친 용량이 20메가를 초과하였습니다.");
		exit;
	}
	//=========================================첨부파일 용량 체크 END================================//

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/technology_list/file1";		//업로드 파일 경로
	$file1 = fileUpload($_FILES["file1"],"file","",$filePath);
	//----------------------------------------------파일업로드 END-------------------------------------//

	$query ="INSERT INTO technologyList SET ";
	$query.="view_state='".$view_state."',";
	$query.="year_tech_trans='".$year_tech_trans."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="name='".emojiRemoveAddslashes($name)."',";
	$query.="company='".emojiRemoveAddslashes($company)."',";
	$query.="transfer_date='".$transfer_date."',";
	$query.="contract_amount=".$contract_amount.",";
	$query.="amount_unit=".$amount_unit.",";
	$query.="status_current='".$status_current."',";
	$query.="content='".emojiRemoveAddslashes($content)."',";
	$query.="file1='".$file1[0]."',";
	$query.="file1_real='".$file1[1]."',";
	$query.="writedate=now(),";
	$query.="modifydate=now() ";

	$result = mysql_query($query,$dbconn);

	if (!$result) {
		echo " INSERT INTO ERROR ";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("technologyList","", "listorder ASC, uid DESC");
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

	//----------------------------------------------파일업로드 START-------------------------------------//
	//=========================================첨부파일 용량 체크 START================================//
	$maxFileSize = 20*1024*1024;
	$fileUpSize = 0;
	if($_FILES["file1"]["tmp_name"]){
		$fileUpSize += filesize($_FILES["file1"]["tmp_name"]);
	}
	//echo $fileUpSize;
	//echo "$maxFileSize<$fileUpSize";
	//exit;

	if($maxFileSize<$fileUpSize) {
		ERROR_BACK("첨부파일을 합친 용량이 20메가를 초과하였습니다.");
		exit;
	}
	//=========================================첨부파일 용량 체크 END================================//

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/technology_list/file1";		//업로드 파일 경로
	if($file1_del=="Y") {
		@unlink($filePath."/".$_POST["origi_file1"]);
	}else{
		$file1 = fileUpload($_FILES["file1"],"file",$origi_file1,$filePath);
	}
	//----------------------------------------------파일업로드 END-------------------------------------//


	$query ="UPDATE technologyList SET ";
	$query.="view_state='".$view_state."',";
	$query.="year_tech_trans='".$year_tech_trans."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="name='".emojiRemoveAddslashes($name)."',";
	$query.="company='".emojiRemoveAddslashes($company)."',";
	$query.="transfer_date='".$transfer_date."',";
	$query.="contract_amount='".emojiRemoveAddslashes($contract_amount)."',";
	$query.="amount_unit=".$amount_unit.",";
	$query.="status_current='".$status_current."',";
	$query.="content='".emojiRemoveAddslashes($content)."',";
	if($file1_del=="Y") {
		$query.="file1='',";
		$query.="file1_real='',";
	}else{
		if($file1[0]) {
			$query.= "file1='".$file1[0]."',";
			$query.= "file1_real='".$file1[1]."',";
		}
	}
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