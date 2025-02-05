<?session_start();
include "../common/loginchk.html";
include "../../config/dbconn.inc.php";
include "../../config/functions.inc";

//접속 권한, 네비게이션 위치 START
adminConAuth("A");
$menuMcategory = "03020000";
//접속 권한, 네비게이션 위치 END

$subquery= "subject_s=".urlencode($subject_s)."&name_s=".urlencode($name_s)."&publication_number_s=".urlencode($publication_number_s)."&application_number_s=".urlencode($application_number_s)."&view_state_s=".$view_state_s."";


//삭제-목록
if($_GET["mode"]=="delEnd") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$sql = mysql_query("SELECT * FROM patentList WHERE uid='".$uid."'",$dbconn);
	$row = mysql_fetch_array($sql);

	if($row["file1"]){
		@unlink("../../program_file/patent_list/file1/".$row["file1"]."");
	}

	$query = "DELETE FROM patentList WHERE uid='".$uid."'";
	$result = mysql_query($query,$dbconn);
	if(!$result){
		echo "DELETE_ERROR";
		exit;
	}

	
	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("patentList","", "listorder ASC, uid DESC");
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

		$UPD ="UPDATE patentList SET ";
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

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/patent_list/file1";		//업로드 파일 경로
	$file1 = fileUpload($_FILES["file1"],"file","",$filePath);
	//----------------------------------------------파일업로드 END-------------------------------------//

	$query ="INSERT INTO patentList SET ";
	$query.="view_state='".$view_state."',";
	$query.="year_of_publication='".$year_of_publication."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="name='".emojiRemoveAddslashes($name)."',";
	$query.="publication_number='".emojiRemoveAddslashes($publication_number)."',";
	$query.="publication_date='".$publication_date."',";
	$query.="registration_country='".emojiRemoveAddslashes($registration_country)."',";
	$query.="application_date='".$application_date."',";
	$query.="application_number='".emojiRemoveAddslashes($application_number)."',";
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
	listorderSort("patentList","", "listorder ASC, uid DESC");
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

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/patent_list/file1";		//업로드 파일 경로
	if($file1_del=="Y") {
		@unlink($filePath."/".$_POST["origi_file1"]);
	}else{
		$file1 = fileUpload($_FILES["file1"],"file",$origi_file1,$filePath);
	}
	//----------------------------------------------파일업로드 END-------------------------------------//


	$query ="UPDATE patentList SET ";
	$query.="view_state='".$view_state."',";
	$query.="year_of_publication='".$year_of_publication."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="name='".emojiRemoveAddslashes($name)."',";
	$query.="publication_number='".emojiRemoveAddslashes($publication_number)."',";
	$query.="publication_date='".$publication_date."',";
	$query.="registration_country='".emojiRemoveAddslashes($registration_country)."',";
	$query.="application_date='".$application_date."',";
	$query.="application_number='".emojiRemoveAddslashes($application_number)."',";
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