<?session_start();
include "../common/loginchk.html";
include "../../config/dbconn.inc.php";
include "../../config/functions.inc";

//접속 권한, 네비게이션 위치 START
adminConAuth("A");
//접속 권한, 네비게이션 위치 END

$subquery="subject_s=".$subject_s."&author_name_s=".urlencode($author_name_s)."&view_state_s=".$view_state_s."&s_regi_date=".$s_regi_date."&e_regi_date=".$e_regi_date."&list_count=".$list_count."";


$tableWidth = "800";

//삭제
if($_GET["mode"]=="resultDel") {

	//접속 권한, 네비게이션 위치 START
	adminConAuth("A");
	//접속 권한, 네비게이션 위치 END

	$sql = mysql_query("SELECT * FROM bookList WHERE uid='".$uid."'",$dbconn);
	$row = mysql_fetch_array($sql);

	if($row["file1"]){
		@unlink("../../program_file/book_list/file1/".$row["file1"]."");
	}

	$query = "DELETE FROM bookList WHERE uid = '".$uid."'";
	$result = mysql_query($query,$dbconn);
	if(!$result){
		echo "DELETE_ERROR";
	}

	/////////=================정렬순번 세팅 START=====================//
	//listorderSort(테이블명,where,정렬순서);
	listorderSort("bookList","", "listorder ASC, uid DESC");
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

		$UPD ="UPDATE bookList SET ";
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

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/book_list/file1";		//업로드 파일 경로
	$file1 = fileUpload($_FILES["file1"],"file","",$filePath);
	//----------------------------------------------파일업로드 END-------------------------------------//

	$INS ="INSERT INTO bookList SET ";
	$INS.="view_state='".$view_state."',";
	$INS.="year='".$year."',";
	$INS.="subject='".emojiRemoveAddslashes($subject)."',";
	$INS.="chapter_title='".emojiRemoveAddslashes($chapter_title)."',";
	$INS.="author_name='".emojiRemoveAddslashes($author_name)."',";
	$INS.="pub_name='".emojiRemoveAddslashes($pub_name)."',";
	$INS.="publication_date='".$publication_date."',";	
	$INS.="link_url='".emojiRemoveAddslashes($link_url)."',";
	$INS.="content='".emojiRemoveAddslashes($content)."',";
	$INS.="file1='".$file1[0]."',";
	$INS.="file1_real='".$file1[1]."',";
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
	listorderSort("bookList","", "listorder ASC, uid DESC");
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

	$filePath=$_SERVER["DOCUMENT_ROOT"]."/program_file/book_list/file1";		//업로드 파일 경로
	if($file1_del=="Y") {
		@unlink($filePath."/".$_POST["origi_file1"]);
	}else{
		$file1 = fileUpload($_FILES["file1"],"file",$origi_file1,$filePath);
	}
	//----------------------------------------------파일업로드 END-------------------------------------//

	$INS ="UPDATE bookList SET ";
	$INS.="view_state='".$view_state."',";
	$INS.="year='".$year."',";
	$INS.="subject='".emojiRemoveAddslashes($subject)."',";
	$INS.="chapter_title='".emojiRemoveAddslashes($chapter_title)."',";
	$INS.="author_name='".emojiRemoveAddslashes($author_name)."',";
	$INS.="pub_name='".emojiRemoveAddslashes($pub_name)."',";
	$INS.="publication_date='".$publication_date."',";	
	$INS.="link_url='".emojiRemoveAddslashes($link_url)."',";
	$INS.="content='".emojiRemoveAddslashes($content)."',";
	if($file1_del=="Y") {
		$INS.="file1='',";
		$INS.="file1_real='',";
	}else{
		if($file1[0]) {
			$INS.= "file1='".$file1[0]."',";
			$INS.= "file1_real='".$file1[1]."',";
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