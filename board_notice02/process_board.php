<?session_start(); 
include "../config/dbconn.inc.php";
include "../config/functions.inc";

include "./config/common.php";


//삭제 - 비밀번호 입력 삭제
if($_POST["mode"]=="delPwd") {

	if($delete_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$delete_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("삭제 권한이 없습니다.");
		}
	}

	$query = mysql_query("SELECT sid,pwd,upfile,gid,level,upfile2,upfile3,list_img1 FROM `".$tn."` WHERE uid=$uid AND (pwd='".$pass."' OR pwd=PASSWORD('".$pass."'))", $dbconn);
	if(!$query) {
		echo("query_error");
	}
	$row = mysql_fetch_row($query);
	

	if ($row[1]) {
		$low=mysql_query("SELECT gid FROM `".$tn."` WHERE level > '".$row[4]."' AND gid='".$row[3]."'",$dbconn);
		$low_row=mysql_fetch_row($low);

		if ($low_row[0]) {
			ERROR_BACK("하위의 리플이 있어 삭제하지 못합니다.");
		}

		if ($comment_yorn=="Y") {
			$Comment_Table_Name = $tn."_comment";

			$csql = mysql_query("SELECT count(*) FROM `".$Comment_Table_Name."` WHERE buid='".$uid."'",$dbconn);
			$crow = mysql_fetch_row($csql);

			if ($crow[0]) {
				ERROR_BACK("코멘트가 있어 삭제하지 못합니다.");
			}
		}

		if($row[2]) {
			if(!unlink($fileUpDownFullPath."/".$row[2])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[5]) {
			if(!unlink($fileUpDownFullPath."/".$row[5])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[6]) {
			if(!unlink($fileUpDownFullPath."/".$row[6])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[7]) {
			if(!unlink($fileUpDownFullPath."/".$row[7])) {
			  echo "목록이미지를 삭제하지 못했습니다.";
			  exit;
			}
		}
		
		$sql1 = "UPDATE `".$tn."` SET sid=sid-1 WHERE sid > '".$row[0]."'";
		$result1=mysql_query($sql1, $dbconn);
		if(!$result1) { echo("업데이트 쿼리에러");}

		$sql2 = "DELETE FROM `".$tn."` WHERE uid = '".$uid."'";
		$result2=mysql_query($sql2, $dbconn);
		if(!$result2) { echo("삭제 쿼리에러");}

		GO_REFRESH("./list.php?cpage=$cpage&spage=$spage&$subquery");
	}else{
		ERROR_BACK("비밀번호를 정확하게 입력하세요.");
	}

	exit;
}



//삭제 - view 클릭 삭제
if($_GET["mode"]=="delView") {

	if($delete_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$delete_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("삭제 권한이 없습니다.");
		}
	}

	$query = mysql_query("SELECT sid,member_id,upfile,gid,level,upfile2,upfile3,list_img1 FROM `".$tn."` WHERE uid='".$uid."'", $dbconn);
		
	if(!$query) {
		echo("query_error");
	}
		
	$row = mysql_fetch_row($query);
		

	if (($row[1] && $row[1]==$_SESSION["session_usr_id"]) || ($_SESSION["session_admin_id"] && $adminAuth=="Y")) {

		$low=mysql_query("SELECT gid FROM `".$tn."` WHERE level > '".$row[4]."' AND gid='".$row[3]."'",$dbconn);
		$low_row=mysql_fetch_row($low);

		if ($low_row[0]) {
			ERROR_BACK("하위의 리플이 있어 삭제하지 못합니다.");
			exit;
		}

		if ($comment_yorn=="Y") {
			$Comment_Table_Name = $tn."_comment";

			$csql = mysql_query("SELECT count(*) FROM `".$Comment_Table_Name."` WHERE buid='".$uid."'",$dbconn);
			$crow = mysql_fetch_row($csql);

			if ($crow[0]) {
				ERROR_BACK("코멘트가 있어 삭제하지 못합니다.");
			}
		}


		if($row[2]) {
			if(!unlink($fileUpDownFullPath."/".$row[2])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[5]) {
			if(!unlink($fileUpDownFullPath."/".$row[5])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[6]) {
			if(!unlink($fileUpDownFullPath."/".$row[6])) {
			  echo "첨부파일을 삭제하지 못했습니다.";
			  exit;
			}
		}
		if($row[7]) {
			if(!unlink($fileUpDownFullPath."/".$row[7])) {
			  echo "목록이미지를 삭제하지 못했습니다.";
			  exit;
			}
		}
			
		$sql1 = "UPDATE `".$tn."` SET sid=sid-1 WHERE sid > '".$row[0]."'";
		$result1=mysql_query($sql1, $dbconn);
		if(!$result1) { echo("업데이트 쿼리에러");}

		$sql2 = "DELETE FROM `".$tn."` WHERE uid = '".$uid."'";
		$result2=mysql_query($sql2, $dbconn);		
		if(!$result2) { echo("삭제 쿼리에러");}

		GO_REFRESH("./list.php?cpage=$cpage&spage=$spage&$subquery");
		exit;
	}else{
		ERROR_BACK("삭제 권한이 없습니다.");
	}

	exit;
}



//삭제 - list 선택 삭제
if ($_POST["mode"]=="delList") {

	if($list_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$list_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("접근 권한이 없습니다.");
		}
	}

	if(!$_SESSION["session_admin_id"] || $adminAuth!="Y") {
		ERROR_BACK("삭제 권한이 없습니다.");
		exit;
	}

	if ($uUid) {
		for ($k=0;$k<sizeof($uUid);$k++) {

			$query = mysql_query("SELECT sid,pwd,upfile,uid,upfile2,upfile3,list_img1 FROM `".$tn."` WHERE uid='".$uUid[$k]."'", $dbconn);
			if(!$query) {
				echo("query_error");
			}

			$row = mysql_fetch_row($query);


			if($row[2]) {
				if(!unlink($fileUpDownFullPath."/".$row[2])) {
				  echo "첨부파일을 삭제하지 못했습니다.";
				}
			}
			if($row[4]) {
				if(!unlink($fileUpDownFullPath."/".$row[4])) {
				  echo "첨부파일을 삭제하지 못했습니다.";
				}
			}
			if($row[5]) {
				if(!unlink($fileUpDownFullPath."/".$row[5])) {
				  echo "첨부파일을 삭제하지 못했습니다.";
				}
			}
			if($row[6]) {
				if(!unlink($fileUpDownFullPath."/".$row[6])) {
				  echo "첨부파일을 삭제하지 못했습니다.";
				}
			}

			if($comment_yorn=="Y") {
				$Comment_Table_Name = $tn."_comment";

				$csql = mysql_query("DELETE FROM `".$Comment_Table_Name."` WHERE buid='".$row[3]."'",$dbconn);
				//echo "DELETE FROM $Comment_Table_Name WHERE buid=$row[3]";
				//exit;
			}

			$sql1 = "UPDATE `".$tn."` SET sid=sid-1 WHERE sid >  '".$row[0]."'";
			$result1=mysql_query($sql1, $dbconn);
			if(!$result1) { echo("업데이트 쿼리에러");}


			$del=mysql_query("DELETE FROM `".$tn."` WHERE uid='".$row[3]."'",$dbconn);
		
			if (!$del) {
				echo "DELETE ERROR";
				exit;
			}
		}
	}else{
		ERROR_BACK("체크된 목록이 없습니다");
		exit;
	}

	GO_REFRESH("./list.php?$subquery");
	exit;
}





//글 등록
if ($_POST["mode"]=="endWrite") {
	if($write_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$write_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("접근 권한이 없습니다.");
		}
	}

	if(!$_POST["chk_chk"] || !$_POST["byte_chk"]) {
		ERROR_BACK("비정상적인 접근입니다.");
		exit;
	}

	if($chk_chk!=$byte_chk) {
		ERROR_BACK("자동등록방지용 파란색 숫자가 순서대로 입력되지 않았습니다.");
		exit;
	}

//--------------------------------------------------------이미지-----------------------------------//

	//=========================================첨부파일 용량 체크 START================================//
	$maxFileSize = 20*1024*1024;
	$fileUpSize = 0;
	if($_FILES["file1"]["tmp_name"]){
		$fileUpSize += filesize($_FILES["file1"]["tmp_name"]);
	}
	if($_FILES["file2"]["tmp_name"]){
		$fileUpSize += filesize($_FILES["file2"]["tmp_name"]);
	}
	if($_FILES["file3"]["tmp_name"]){
		$fileUpSize += filesize($_FILES["file3"]["tmp_name"]);
	}
	//echo $fileUpSize;
	//echo "$maxFileSize<$fileUpSize";
	//exit;

	$fileUploadYorN = "Y";
	if($maxFileSize<$fileUpSize) {
		Message_Echo("첨부파일을 합친 용량이 20메가를 초과하여 업로드 되지 않았습니다.\\n용량을 줄인 후 업로드 또는 첨부파일을 하나씩 업로드 하세요.");
		$fileUploadYorN = "N";
		//exit;

		$file1 = "";
		$file2 = "";
		$file3 = "";
	}
	//=========================================첨부파일 용량 체크 END================================//

	if($fileUploadYorN=="Y") {
		$file1 = fileUpload($_FILES["file1"],"file",$origi_file1,$fileUpDownFullPath);
		$file2 = fileUpload($_FILES["file2"],"file",$origi_file2,$fileUpDownFullPath);
		$file3 = fileUpload($_FILES["file3"],"file",$origi_file3,$fileUpDownFullPath);
	}

	//=========================================목록이미지 용량 체크 START================================//
	$list_img1 = fileUpload($_FILES["list_img1"],"img","",$fileUpDownFullPath);
	if($list_img1[0]) {
		$list_img1[0] = imgSizeAuto($list_img1[0],$fileUpDownFullPath);
	}
	//=========================================목록이미지 용량 체크 END================================//
//--------------------------------------------------------E N D-------------------------------------//

	$sql = mysql_query("SELECT MAX(sid), MAX(gid) FROM `".$tn."`",$dbconn);
	if(!$sql) {
		echo("query_error");
	}

	$row = mysql_fetch_row($sql);

	if($row[0]) {
	   $new_sid = $row[0] + 1;
	} else {
	   $new_sid = 1;
	} 

	if($row[1]) {
	   $new_gid = $row[1] + 1;
	} else {
	   $new_gid = 1;
	} 

	$level = 0;

	//$subject = emojiRemoveAddslashes($subject);
	//if ($tag=="H") {
	//	$content = emojiRemoveAddslashes($icomment);
	//}else{
	//$content = emojiRemoveAddslashes($content);
	//}


	if(!$writedate) {
		$writedate = date("Y-m-d H:i:s");
	}else{
		$writedate = $writedate." ".date("H:i:s");
	}

	if(!$ref) {
		$ref = 1;
	}

	$query ="INSERT INTO `".$tn."` SET ";
	$query.="sid='".$new_sid."',";
	$query.="gid='".$new_gid."',";
	$query.="level='".$level."',";
	$query.="name='".emojiRemoveAddslashes($name)."',";
	$query.="email='".emojiRemoveAddslashes($email)."',";
	$query.="homepage='".emojiRemoveAddslashes($homepage)."',";
	$query.="subject='".emojiRemoveAddslashes($subject)."',";
	$query.="subject_color='".emojiRemoveAddslashes($subject_color)."',";
	$query.="content='".emojiRemoveAddslashes($content)."',";
	$query.="pwd='".$pwd."',";
	if($_SESSION["session_usr_id"]) {
		$query.="member_id='".$_SESSION["session_usr_id"]."',";
	}else if($_SESSION["session_admin_id"]) {
		$query.="member_id='".$_SESSION["session_admin_id"]."',";
	}else{
		$query.="member_id='',";
	}
	$query.="list_img1='".$list_img1[0]."',";
	$query.="list_comment1='".emojiRemoveAddslashes($list_comment1)."',";
	$query.="upfile='".$file1[0]."',";
	$query.="upfile2='".$file2[0]."',";
	$query.="upfile3='".$file3[0]."',";
	$query.="upfile_name='".$file1[1]."',";
	$query.="upfile2_name='".$file2[1]."',";
	$query.="upfile3_name='".$file3[1]."',";
	$query.="ip='".$_SERVER["REMOTE_ADDR"]."',";
/*
	if($_SESSION["session_admin_id"]) {
		$query.= "'$writedate',";
	}else{
		$query.= "now(),";
	}
*/
	$query.="writedate='".$writedate."',";
	$query.="ref='".$ref."',";
	$query.="tag='".$tag."',";
	$query.="gonggi_status='".$gonggi_status."',";
	$query.="secret_status='".$secret_status."' ";

	$result=mysql_query($query, $dbconn);

	if(!$result) {
	   echo("쿼리에러");
	}
	
	//echo "$query";
	//db_close($dbconn);

	GO_REFRESH("./list.php?tn=".$tn."&G_state=".$G_state."&list_count_s=".$list_count_s."");
	exit;
}




//글 수정
if ($_POST["mode"]=="endModify") {

	if($modify_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$modify_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("접근 권한이 없습니다.");
		}
	}

	baModifyAuthKor($tn,$_POST["uid"]);		//modify.php 접근 및 수정 권한

	$query = mysql_query("SELECT pwd,upfile,member_id FROM `".$tn."` WHERE uid='".$_POST["uid"]."'", $dbconn);
	if(!$query) {
		echo("query_error");
	}
	$row = @mysql_fetch_row($query);
	
	if($row[2]) {
		if ($row[2]!=$_SESSION["session_usr_id"] && (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			ERROR_BACK("수정권한이 없습니다.");
			exit;
		}
	}else{
		if ($row[0]!=$_POST['pwd']&& (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			ERROR_BACK("수정권한이 없습니다.");
			exit;
		}
	}

	if ( $row[0]==$_POST['pwd'] || $row[2]==$session_usr_id || ($_SESSION["session_admin_id"] && $adminAuth=="Y") ) {



		//--------------------------------------------------------이미지-----------------------------------//
		//=========================================첨부파일 용량 체크 START================================//
		$maxFileSize = 20*1024*1024;
		$fileUpSize = 0;
		if($_FILES["file1"]["tmp_name"]){
			$fileUpSize += filesize($_FILES["file1"]["tmp_name"]);
		}
		if($_FILES["file2"]["tmp_name"]){
			$fileUpSize += filesize($_FILES["file2"]["tmp_name"]);
		}
		if($_FILES["file3"]["tmp_name"]){
			$fileUpSize += filesize($_FILES["file3"]["tmp_name"]);
		}
		//echo $fileUpSize;
		//echo "$maxFileSize<$fileUpSize";
		//exit;

		$fileUploadYorN = "Y";
		if($maxFileSize<$fileUpSize) {
			Message_Echo("첨부파일을 합친 용량이 20메가를 초과하여 업로드 되지 않았습니다.\\n용량을 줄인 후 업로드 또는 첨부파일을 하나씩 업로드 하세요.");
			$fileUploadYorN = "N";
			//exit;

			$file1 = "";
			$file2 = "";
			$file3 = "";
		}
		//=========================================첨부파일 용량 체크 END================================//

		if($fileUploadYorN=="Y") {
			if($file1_del=="Y") {
				@unlink($fileUpDownFullPath."/".$_POST['origi_file1']."");
			}else{
				$file1 = fileUpload($_FILES["file1"],"file",$origi_file1,$fileUpDownFullPath);
			}

			if($file2_del=="Y") {
				@unlink($fileUpDownFullPath."/".$_POST['origi_file2']."");
			}else{
				$file2 = fileUpload($_FILES["file2"],"file",$origi_file2,$fileUpDownFullPath);
			}

			if($file3_del=="Y") {
				@unlink($fileUpDownFullPath."/".$_POST['origi_file3']."");
			}else{
				$file3 = fileUpload($_FILES["file3"],"file",$origi_file3,$fileUpDownFullPath);
			}
		}

		//=========================================이미지 용량 체크 START================================//
		if($list_img1_del=="Y") {
			@unlink($fileUpDownFullPath."/".$_POST['origi_list_img1']."");
		}else{
			$list_img1 = fileUpload($_FILES["list_img1"],"img",$_POST['origi_list_img1'],$fileUpDownFullPath);
			if($list_img1[0]) {
				$list_img1[0] = imgSizeAuto($list_img1[0],$fileUpDownFullPath);
			}
		}
		//=========================================이미지 용량 체크 END================================//
		//--------------------------------------------------------E N D-------------------------------------//


		$writedate = $writedate." ".date("H:i:s");

		$query ="UPDATE `".$tn."` SET ";
		$query.="name='".emojiRemoveAddslashes($name)."',";
		$query.="email='".emojiRemoveAddslashes($email)."',";
		$query.="homepage='".emojiRemoveAddslashes($homepage)."',";
		$query.="subject='".emojiRemoveAddslashes($subject)."',";
		$query.="subject_color='".emojiRemoveAddslashes($subject_color)."',";
		$query.="content='".emojiRemoveAddslashes($content)."',";

		if($list_img1_del=="Y") {
			$query.="list_img1='',";
		}else{
			if ($list_img1[0]) {
				$query.="list_img1='".$list_img1[0]."',";
			}
		}
		$query.= "list_comment1='".emojiRemoveAddslashes($list_comment1)."',";
		
		if($file1_del=="Y") {
			$query.="upfile='',";
			$query.="upfile_name='',";
		}else{
			if ($file1[0]) {
				$query.="upfile='".$file1[0]."',";
				$query.="upfile_name='".$file1[1]."',";
			}
		}

		if($file2_del=="Y") {
			$query.="upfile2='',";
			$query.="upfile2_name='',";
		}else{
			if ($file2[0]) {
				$query.="upfile2='".$file2[0]."',";
				$query.="upfile2_name='".$file2[1]."',";
			}
		}

		if($file3_del=="Y") {
			$query.="upfile3='',";
			$query.="upfile3_name='',";
		}else{
			if ($file3[0]) {
				$query.="upfile3='".$file3[0]."',";
				$query.="upfile3_name='".$file3[1]."',";
			}
		}
		$query.="gonggi_status='".$gonggi_status."',";
		$query.="secret_status='".$secret_status."',";
		$query.="tag='".$tag."',";

		if($_SESSION["session_admin_id"]) {
			$query.= "writedate='$writedate',";
		}

		$query.="modifydate=now() ";
		$query.="where uid='".$uid."'";
		
		$result=mysql_query($query, $dbconn);

		if(!$result) {
			echo("쿼리에러");
		}

		mysql_close($dbconn);

		GO_REFRESH("./list.php?cpage=".$cpage."&spage=".$spage."&".$subquery."");

	}else{
		ERROR_BACK("패스워드를 정확하게 입력하세요.");
	}
	exit;
}



//게시판 코멘트 달기
if($_POST["mode"]=="endComment") {
	
	if($view_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("접근 권한이 없습니다.");
		}
	}

	baViewAuthKor($tn,$uid);		//view.php 접근 및 답변 권한

	if(!$_SESSION["session_usr_id"]) {
		if(!$_POST["chk_chk"] || !$_POST["byte_chk"]) {
			ERROR_BACK("비정상적인 접근입니다.");
			exit;
		}

		if($chk_chk!=$byte_chk) {
			ERROR_BACK("자동등록방지용 파란색 숫자가 순서대로 입력되지 않았습니다.");
			exit;
		}
	}


	$Comment_Table_Name = $tn."_comment";


	if($c_mode=="cmtMod") {
	
		$sql = mysql_query("SELECT member_id,pass FROM $Comment_Table_Name WHERE uid='".$c_uid."' AND buid='".$buid."'",$dbconn);
		$row = mysql_fetch_row($sql);
		

		if((!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			if($row[0]) {
				if($_SESSION["session_usr_id"]!=$row[0]) {
					ERROR_BACK("수정 권한이 없습니다.");
					exit;
				}
			}else{
				if($pass!=$row[1]) {
					ERROR_BACK("수정 권한이 없습니다.");
					exit;
				}
			}
		}


		$INS ="UPDATE $Comment_Table_Name SET ";
		$INS.="c_content='".emojiRemoveAddslashes($c_content)."',";
		$INS.="ip='".$_SERVER["REMOTE_ADDR"]."',";
		$INS.="modifydate=now() ";
		$INS.="WHERE uid='".$c_uid."' AND buid='".$buid."'";

		$result = mysql_query($INS,$dbconn);

		if (!$result) {
			echo "UPDATE ERROR";
			echo "<br> $INS";
			exit;
		}

		//ERROR_BACK("코멘트 수정이 완료 되었습니다.");
		//GO_REFRESH("./regist_list.html");
		
		//GO_REFRESH("./view.php?uid=$buid&cpage=$cpage&spage=$spage&$subquery");
		ERROR_BACK_NOMSG();
		exit;

	}else{

		if($c_level=="0") {

			$sql = mysql_query("SELECT MAX(c_sid), MAX(c_gid) FROM $Comment_Table_Name WHERE buid='".$buid."'",$dbconn);
			if(!$sql) {
				echo("query_error");
			}
			$row = mysql_fetch_row($sql);

			if($row[0]) {
				$new_c_sid = $row[0] + 1;
			} else {
				$new_c_sid = 1;
			} 

			if($row[1]) {
				$new_c_gid = $row[1] + 1;
			} else {
				$new_c_gid = 1;
			}
		}else{
			
			//$sql = mysql_query("SELECT MAX(c_sid) FROM $Comment_Table_Name WHERE bc_uid='".$c_uid."' AND c_gid='".$c_gid."' AND c_level='".$c_level."' AND buid='".$buid."'",$dbconn);
			//echo "SELECT MAX(c_sid) FROM $Comment_Table_Name WHERE bc_uid='".$c_uid."' AND c_gid='".$c_gid."' AND c_level='".$c_level."' AND buid='".$buid."'";
			//exit;
			//$row = mysql_fetch_row($sql);

			/*
			if($row[0]) {
				$new_c_sid = $row[0]+1;
			}else{
				$new_c_sid = $c_sid+1;
			}
			*/

			$new_c_sid = $c_sid;

			$new_c_gid = $c_gid;
			//$c_level = $row[2]+1;



			$UPD = mysql_query("UPDATE $Comment_Table_Name SET c_sid=c_sid+1 WHERE c_sid >= ".$new_c_sid." AND buid='".$buid."'", $dbconn);
		}
		

		$cSql = mysql_query("SELECT bc_uid FROM `".$Comment_Table_Name."` WHERE uid='".$c_uid."'",$dbconn);
		$cRow = mysql_fetch_row($cSql);
		
		if($c_level==0){
			$bc_uid="";
		}else{
			if($cRow[0]){
				$bc_uid=$cRow[0].$c_uid."||";
			}else{
				$bc_uid="||".$c_uid."||";
			}
		}


		$INS ="INSERT INTO `".$Comment_Table_Name."` SET ";
		$INS.="member_id='".$_SESSION["session_usr_id"]."',";
		$INS.="pass='".$pass."',";
		$INS.="comment_name='".emojiRemoveAddslashes($comment_name)."',";
		$INS.="buid='".$buid."',";
		$INS.="bc_uid='".$bc_uid."',";
		$INS.="c_sid='".$new_c_sid."',";
		$INS.="c_gid='".$new_c_gid."',";
		$INS.="c_level='".$c_level."',";
		$INS.="c_content='".emojiRemoveAddslashes($c_content)."',";
		$INS.="ip='".$_SERVER["REMOTE_ADDR"]."',";
		$INS.="writedate=now() ";

		$result = mysql_query($INS,$dbconn);

		if (!$result) {
			echo "INSERT INTO ERROR";
			echo "<br> $INS";
			exit;
		}

		$cuid = mysql_insert_id();
		commentAdd($Comment_Table_Name,$buid,$cuid);

		//ERROR_BACK("코멘트 등록이 완료 되었습니다.");
		//GO_REFRESH("./regist_list.html");

		//GO_REFRESH("./view.php?uid=$buid&cpage=$cpage&spage=$spage&$subquery");
		ERROR_BACK_NOMSG();
		exit;
	}
	
}


//게시판 코멘트 삭제
if($_GET["mode"]=="delComment") {
	
	if($view_auth) {
		if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
			ERROR_BACK("접근 권한이 없습니다.");
		}
	}

	baViewAuthKor($tn,$uid);		//view.php 접근 및 답변 권한


	$Comment_Table_Name = $tn."_comment";

	//&c_uid=".$c_uid."&buid=".$buid."


	
	$sql = mysql_query("SELECT member_id,pass,c_sid FROM `".$Comment_Table_Name."` WHERE uid='".$c_uid."' AND buid='".$buid."'",$dbconn);
	$row = mysql_fetch_row($sql);
	
	if((!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
		if($row[0]) {
			if($_SESSION["session_usr_id"]!=$row[0]) {
				ERROR_BACK("삭제 권한이 없습니다.");
				exit;
			}
		}else{
			if($pass!=$row[1]) {
				ERROR_BACK("삭제 권한이 없습니다.");
				exit;
			}
		}
	}



	$sql2 = mysql_query("SELECT uid,member_id,pass FROM `".$Comment_Table_Name."` WHERE INSTR(bc_uid,'||".$c_uid."||') AND buid='".$buid."'",$dbconn);
	$row2 = mysql_fetch_row($sql2);



	if($row2[0]) {
		ERROR_BACK("하위에 답글이 있어 삭제 할 수 없습니다.");
		exit;
	}



	$DEL ="DELETE FROM `".$Comment_Table_Name."` WHERE uid='".$c_uid."' AND buid='".$buid."'";

	$result = mysql_query($DEL,$dbconn);

	if (!$result) {
		echo "DELETE ERROR";
		echo "<br> $INS";
		exit;
	}

	$sql1 = "UPDATE `".$Comment_Table_Name."` SET c_sid=c_sid-1 WHERE c_sid > '".$row[2]."'";
	$result1=mysql_query($sql1, $dbconn);
	if(!$result1) {
		echo "DELETE ERROR";
		echo "<br> $sql1";
		exit;
	}


	commentDel($Comment_Table_Name,$c_uid);

	ERROR_BACK_NOMSG();
	//GO_REFRESH("./regist_list.html");
	exit;
}


ERROR_BACK("잘못된 접속입니다.");
?>