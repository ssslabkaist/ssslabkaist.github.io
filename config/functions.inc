<?
if(!$autoProcess) {		//자동실행
	require_once("".$_SERVER['DOCUMENT_ROOT']."/config/connect_count.php");
}



//addslashes 로 DB 에 저장하던 값들 addslashes 제거 할려고 만든거임
function addSlashNotUsed($inputVar) {
	return addslashes($inputVar);
}
//stripslashes 로 DB 에 저장하던 값들 stripslashes 제거 할려고 만든거임
function stripSlashNotUsed($inputVar) {
	return stripslashes($inputVar);
}



$toDay = date("Y-m-d");
$toDayHour = date("YmdH");
$toDateTime = date("Y-m-d H:i:s");

$PHP_SELF=$_SERVER["PHP_SELF"];

$sevenDayAfter = date("Y-m-d",mktime(date("H"),date("i"),date("s"),date("m"),date("d")+7,date("Y")));
$sevenDayAfterNum = str_replace("-","",$sevenDayAfter);


$homeDir = "";
$homeDir2 = "";
$shopDir = "";


function fileTypeImg($type_name,$type) {

	$type_name = strtolower($type_name);

	//echo $type_name."<br>";

	if($type=="img") {

		if($type_name!="gif" && $type_name!="jpg" && $type_name!="jpeg" && $type_name!="png") {
			$alertMent = "jpg,gif,png 파일만 가능합니다";
			$alertYorN = "N";
		}else{
			$alertMent = "";
			$alertYorN = "Y";
		}

	}else if($type=="jpg_gif") {

		if($type_name!="gif" && $type_name!="jpg") {
			$alertMent = "jpg,gif 파일만 가능합니다";
			$alertYorN = "N";
		}else{
			$alertMent = "";
			$alertYorN = "Y";
		}

	}else if($type=="file") {

		if($type_name=="html" || $type_name=="htm" || $type_name=="php" || $type_name=="php3" || $type_name=="php4" || $type_name=="php5" || $type_name=="js" || $type_name=="inc") {
			$alertMent = "html,php,php4,php3,html,js 파일은 등록 불가능합니다.";
			$alertYorN = "N";
		}else{
			$alertMent = "";
			$alertYorN = "Y";
		}

	}else if($type=="wmv") {

		if($type_name!="wmv") {
			$alertMent = "wmv 파일만 등록 가능합니다.";
			$alertYorN = "N";
		}else{
			$alertMent = "";
			$alertYorN = "Y";
		}
	}

	//업로드 파일 확장자 제한
	if(strpos($type_name, "htm") !== false || strpos($type_name, "php") !== false || strpos($type_name, "js") !== false || strpos($type_name, "inc") !== false || strpos($type_name, "htaccess") !== false) {
		$alertMent = "html,php,js,inc 파일은 등록 불가능합니다.";
		$alertYorN = "N";
	}

	//echo $alertYorN;

	return array($alertYorN,$alertMent);
}



function fileUpload($fileName,$fileType,$origiFileName,$filePath) {
	//img1
	if( $fileName["name"] !="" && $fileName["name"] !='none'){
		$type=substr( strrchr($fileName["name"], "."), 1);

		$fileTypeYorN = fileTypeImg($type,$fileType);
		if($fileTypeYorN[0]=="N") {
			ERROR_BACK("".$fileTypeYorN[1]."");
		}

		if($origiFileName){
			unlink("".$filePath."/$origiFileName");
		}

		$okFileName = $fileName["name"];
		$okFileRealName = $okFileName;

		$position1=strrpos($okFileName,'.');
		$type1=substr("$okFileName",0,$position1);
		$type2=substr( strrchr($okFileName, "."), 1);
		$okFileName= randomFnc() . "_" . time() . "1." . ${type2};

		if(!move_uploaded_file($fileName["tmp_name"],"".$filePath."/$okFileName")){
			ERROR_BACK("파일을 upload하는데 실패하였습니다.");
		}

		$date=date("YmdHis");
		$retype1= randomFnc().$date.".".$type;

		if (!rename("".$filePath."/$okFileName", "".$filePath."/$retype1")) {
			ERROR_BACK("RENAME_FAILURE");
		}
		$okFileName=$retype1;

		return array($okFileName,$okFileRealName);
	}
}



//다중 업로드 파일
function fileUploadMulti($fileName,$fileTmpName,$fileType,$origiFileName,$filePath) {
	//img1
	if( $fileName !="" && $fileName !='none'){
		$type=substr( strrchr($fileName, "."), 1);

		$fileTypeYorN = fileTypeImg($type,$fileType);
		if($fileTypeYorN[0]=="N") {
			ERROR_BACK("".$fileTypeYorN[1]."");
		}

		if($origiFileName){
			unlink("".$filePath."/$origiFileName");
		}

		$okFileName = $fileName;
		$okFileRealName = $okFileName;

		$position1=strrpos($okFileName,'.');
		$type1=substr("$okFileName",0,$position1);
		$type2=substr( strrchr($okFileName, "."), 1);
		$okFileName= randomFnc() . "_" . time() . "1." . ${type2};

		if(!move_uploaded_file($fileTmpName,"".$filePath."/$okFileName")){
			ERROR_BACK("파일을 upload하는데 실패하였습니다.");
		}

		$date=date("YmdHis");
		$retype1= randomFnc().$date.".".$type;

		if (!rename("".$filePath."/$okFileName", "".$filePath."/$retype1")) {
			ERROR_BACK("RENAME_FAILURE");
		}
		$okFileName=$retype1;

		return array($okFileName,$okFileRealName);
	}
}

//게시판 내용 이미지 업로드
function getImgSizeBoard($imgName,$filePath,$imgMaxWidth){

	if(file_exists("".$filePath."/".$imgName."")) {
		$IMG_1=getimagesize("".$filePath."/".$imgName."");
		
		$imageWidth=$IMG_1[0];
		$imageHeight=$IMG_1[1];

		if ($imageWidth > $imgMaxWidth) {
			$imageWidth_down = $imgMaxWidth;
				
			//실제넓이:실제높이= $imageWidth_down : $imgHeight_down
			//imageHeight_down = $imageHeight * $imageWidth_down / $imageWidth;
			$imageHeight_down = round($imageHeight * $imageWidth_down / $imageWidth);
		}else{
			$imageWidth_down = $imageWidth;
			$imageHeight_down = $imageHeight;
		}
	}else{
		$imageWidth_down = 0;
		$imageHeight_down = 0;
	}

	return array($imageWidth_down,$imageHeight_down);
}


//이미지 사이즈 자동으로 줄이기
function imgSizeAuto($imgName,$imgPath,$imgWidth=1000) {

	##############이미지 작은넘으로 변환 START##############
	$tImg = $imgPath."/".$imgName;					//원본 이미지(경로+파일명)

	//$imgWidth = 1000;								//축소할 이미지 사이즈
	$tImgSize = getImgWidthSize($tImg,$imgWidth);	//이미지 사이즈(0:가로, 1:세로)
	//echo $tImg;
	//echo $tImgSize[0];
	//echo "<br>";
	//echo $tImgSize[1];
	//thumbnail2($tImg,"".$imgName,"170","120",$imgPath);

	//gif 일때 이미지 축소하면 gif 움직이지 않음
	$type=substr( strrchr($imgName, "."), 1);
	$type = strtolower($type);
	if($type!="gif" && $type!="bmp") {

		/*용량 큰거 줄일때 START
		$maxFileSize = 1*1024*1024;	//1메가
		$imgUpSize = filesize($tImg);
		if($imgWidth<$tImgSize[0] || $maxFileSize<$imgUpSize) {
		용량 큰거 줄일때 START*/

		if($imgWidth<$tImgSize[0]) {
			$imgThumb = thumbnail2($tImg,"".$imgName,$tImgSize[0],$tImgSize[1],$imgPath);
		}
	}
	/*이미지 작은넘으로 변환*/
	//exit;
	##############이미지 작은넘으로 변환 END##############


	$maxListImgSize = 3*1024*1024;
	$fileUpListImgSize = 0;
	if($tImg){
		$fileUpListImgSize += filesize($tImg);
	}

	
	if($maxListImgSize<$fileUpListImgSize) {
		Message_Echo("이미지 용량이 3MB를 초과하였습니다. 용량을 줄여주세요.");
		@unlink($tImg);
		$imgName = "";
		//exit;
	}

	return $imgName;
}


//달력
function Week($month,$yoil) {
	if($month=="1"){
		if ($yoil=="1") {
			return 1;
		}
	}else if($month=="3"){
		if ($yoil=="1") {
			return 1;
		}
	}else if($month=="5"){
		if ($yoil=="5") {
			return 1;
		}
	}else if($month=="6"){
		if ($yoil=="6") {
			return 1;
		}
	}else if($month=="8"){
		if ($yoil=="15") {
			return 1;
		}
	}else if($month=="10"){
		if ($yoil=="3") {
			return 1;
		}else if ($yoil=="9") {
			return 1;
		}
	}else if($month=="12"){
		if ($yoil=="25") {
			return 1;
		}
	}
}


//Y,N함수
function yORn($value){
	if(!$value) {
		$value="N";
	}
	return $value;
}

//Y,N함수 텍스트
function yORnText($value){
	$ret="아니요";
	if($value=="Y") {
		$ret="예";
	}
	return $ret;
}



//체크박스 배열값 DB 저장 위해
function chkArrayValue($field){
	for($i=0;$i<sizeof($field);$i++) {
		if($i==sizeof($field)-1) {
			$field_value=$field_value."@@".$field[$i]."@@";
		}else{
			$field_value=$field_value."@@".$field[$i];
		}
	}

	return $field_value;
}
//체크박스 배열(체크박스에 해당하는 텍스트)값 DB 저장 위해
function chkArrayValue2($field,$fieldText){
	for($i=0;$i<sizeof($field);$i++) {
	
		$fieldTvalue = $fieldText.$field[$i];
		global ${$fieldTvalue};	//텍스트 필드값


		if($i==sizeof($field)-1) {
			$field_value=$field_value."@@".$field[$i]."^^".${$fieldTvalue}."@@";
		}else{
			$field_value=$field_value."@@".$field[$i]."^^".${$fieldTvalue};
		}
	}
	return $field_value;
}

//텍스트박스 배열값 DB 저장 위해
function chkArrayValue3($field){
	for($i=0;$i<sizeof($field);$i++) {
		if($i==sizeof($field)-1) {
			$field_value=$field_value."@@".addSlashNotUsed($field[$i])."@@";
		}else{
			$field_value=$field_value."@@".addSlashNotUsed($field[$i]);
		}
	}

	return $field_value;
}

//@@ 배열값 뽑기
function getChkArrayName($field) {
	global $dbconn;

	$fieldArray=explode("@@",$field);
	$fieldName="";
	for($s=0;$s<sizeof($fieldArray);$s++) {

		if($fieldArray[$s]) {
			if(!$fieldName) {
				$fieldName=stripSlashNotUsed($fieldArray[$s]);
			}else{
				$fieldName.=", ".stripSlashNotUsed($fieldArray[$s]);
			}
		}
	}
	return $fieldName;
}



//특정태그 제거
function removeTag($str, $remove_tags) {
	foreach ($remove_tags as $key => $val) {
		//$str = preg_replace("/<{$val}[^>]*>/i", '', $str);		//특정태그 부부만 제거
		//$str = preg_replace("/<\/{$val}>/i", '', $str);			//특정태그 부분만 제거
		
		$str = preg_replace("!<{$val}(.*?)<\/{$val}>!is","",$str);	//태그를 포함한 속성 자체를 제거
	}

	$str = preg_replace("/<!--(.*?)-->/is","",$str);			//주석 제거
	$str = preg_replace("/&lt;!--(.*?)-->/is","",$str);			//주석 제거

	//
	//echo $str;

	return $str;
}


//이모지 제거 => UTF-8 4byte 문자
function emojiRemoveAddslashes($string) {
	$string = preg_replace("/\xF0[\x90-\xBF][\x80-\xBF]{2}|
					[\xF1-\xF3][\x80-\xBF]{3}|
					\xF4[\x80-\x8F][\x80-\xBF]{2}/", "", addSlashNotUsed($string));

	return $string;
}

//자동링크
function url_auto_link($str = '', $popup = false)
{
    if (empty($str)) {
        return false;
    }
    $target = $popup ? 'target="_blank"' : '';
    $str = str_replace(
        array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"),
        array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"),
        $str
    );
    $str = preg_replace(
        "/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
        "\\1<a href=\"\\2\" {$target}>\\2</A>",
        $str
    );
    $str = preg_replace(
        "/(^|[\"'\s(])(www\.[^\"'\s()]+)/i",
        "\\1<a href=\"http://\\2\" {$target}>\\2</A>",
        $str
    );
    $str = preg_replace(
        "/[0-9a-z_-]+@[a-z0-9._-]{4,}/i",
        "<a href=\"mailto:\\0\">\\0</a>",
        $str
    );
    $str = str_replace(
        array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"),
        array("&nbsp;", "&lt;", "&gt;", "&#039;"),
        $str
    );
    return $str;
}

//문자에 공백을 제거한뒤 값이 존재하는지 여부
function onlyBlankSpaceChk($value) {
	$ret = false;
	if(preg_replace("/\s+/", "", $value)) {
		$ret = true;
	}
	return $ret;
}

//저자 밑줄, 굵게
function authorChg($author) {
	$ret = $author;
	$ret = str_replace("{","<span style=\"text-decoration:underline;\">",$ret);
	$ret = str_replace("}","</span>",$ret);

	
	$ret = str_replace("[","<span style=\"font-weight:bold;\">",$ret);
	$ret = str_replace("]","</span>",$ret);

	return $ret;
}

//#######################################출력순서 순번 부여 START####################################//
function listorderSort($tableName, $searchQuery, $orderBy) {
	global $dbconn;

	mysql_query("SELECT @listorderNumber:=0",$dbconn);			//mysql 변수 설정
	mysql_query("UPDATE `".$tableName."` SET listorder=@listorderNumber:=@listorderNumber+1 ".$searchQuery." ORDER BY ".$orderBy."",$dbconn);
}
//#######################################출력순서 순번 부여 END####################################//




//#######################################프로젝트 관리 함수 START####################################//
//프로젝트 상태
function projectState($project_state) {
	$ret = "";
	if($project_state=="CU") {
		$ret = "<span class=\"bgc3\">Current</span>";
	}else if($project_state=="CO") {
		$ret = "<span class=\"bgc4\">Past</span>";
	}

	return $ret;
}
//#######################################프로젝트 관리 함수 END####################################//



//utf-8 일때 euc-kr 로 변환 하면 뷁 웱 문자 뒤에 짤리는 현상을 방지하기 위해
//사용방법 : $sendMsgChk = htmlEncConvert($sendMsg, "UTF-8", "EUC-KR");
function htmlEncConvert($str, $before_enc, $after_enc){
	$i=0;
	$result = "";
	$len = mb_strlen($str, $before_enc);
	while ($i <= $len)
	{
		if (mb_convert_encoding(mb_substr($str,$i,1,$before_enc), $after_enc, $before_enc) ==iconv($before_enc,$after_enc,mb_substr($str,$i,1,$before_enc))) {
			$result .= mb_convert_encoding(mb_substr($str,$i,1,$before_enc), $after_enc, $before_enc);
		}else{
			$result .= mb_convert_encoding(mb_substr($str,$i,1,$before_enc), "html-entities", $before_enc);
		}
		$i++;
	}
	return $result;
}

//이미지 view
function selfDiagnosisImgView($path,$img_name) {
	//$imgView = "/img/common_responsive/noimg1.png";
	if($img_name) {
		$imgView = $path.$img_name;
		
	}
	
	//"/img/common_responsive/noimg.png";
	return $imgView;
}

//이미지 view
function productImgView($path,$img_name) {
	//$imgView = "/img/common_responsive/noimg1.png";
	if($img_name) {
		$imgView = $path.$img_name;
		
	}
	
	//"/img/common_responsive/noimg.png";
	return $imgView;
}

//조치사항 갯수
function actionContentNum($action_content) {

	$actionContentArr = explode("\r\n",stripSlashNotUsed($action_content));
	
	for($i=0; $i<sizeof($actionContentArr); $i++) {
		if(onlyBlankSpaceChk($actionContentArr[$i])) {
			$actionContent[] = $actionContentArr[$i];
		}
	}
	return $actionContent;
}





##########################################자원봉사 및 후원 관리 함수 START
/*후원 처리상태*/
function supportAppState($a_state) {
	switch($a_state) {
		case "A01" : return "<span class=\"o_btn o_btn_etc\"><i><strong>접수</strong></i></span>"; break;
		case "B01" : return "<span class=\"o_btn o_btn_2\"><i><strong>처리중</strong></i></span>"; break;
		case "C01" : return "<span class=\"o_btn o_btn_11\"><i><strong>처리완료</strong></i></span>"; break;
		case "Z99" : return "<span class=\"o_btn o_btn_4\"><i><strong>취소</strong></i></span>"; break;
	}
}

/*자원봉사 처리상태*/
function volunteerAppState($a_state) {
	switch($a_state) {
		case "A01" : return "<span class=\"o_btn o_btn_etc\"><i><strong>접수</strong></i></span>"; break;
		case "B01" : return "<span class=\"o_btn o_btn_2\"><i><strong>처리중</strong></i></span>"; break;
		case "C01" : return "<span class=\"o_btn o_btn_11\"><i><strong>처리완료</strong></i></span>"; break;
		case "Z99" : return "<span class=\"o_btn o_btn_4\"><i><strong>취소</strong></i></span>"; break;
	}
}

function fieldBlankChk($obj) {
	if(0<strlen($obj)) {
		$ret = $obj;
	}else{
		$ret = "-";
	}
	return $ret;
}
//#######################################자원봉사 및 후원 관리 함수 END####################################//


//좌측메뉴 표현
function orderStepNum($Search,$order_step) {
	global $dbconn;
	
	$query ="SELECT COUNT(*) FROM ";
	$query.="orderInfo O, ";
	$query.="orderInfoProd OP ";
	$query.="WHERE 1 AND O.orderno=OP.orderno AND OP.order_step='".$order_step."' ".$Search." GROUP BY O.orderno";
	//echo $query;
	$sql = mysql_query($query,$dbconn);
	$row = mysql_num_rows($sql);

	return number_format($row);

}


//#######################################게시판 코멘트 전체 관리 함수 START####################################//
//코멘트 신청일때
function commentAdd($table_name,$buid,$cuid) {
	global $dbconn;


	$INS ="INSERT INTO board_comment_total SET ";
	$INS.="member_id='".$_SESSION["session_usr_id"]."',";
	$INS.="table_name='".$table_name."',";
	$INS.="buid='".$buid."',";
	$INS.="cuid='".$cuid."',";
	$INS.="writedate=now() ";
	
	$result = mysql_query($INS,$dbconn);
}

//코멘트 삭제일때
function commentDel($table_name,$cuid) {
	global $dbconn;

	$DEL = mysql_query("DELETE FROM board_comment_total WHERE table_name='".$table_name."' AND cuid='".$cuid."'",$dbconn);
}


//코멘트 상위글 작성자 이름 가져 오기
function borderCommentHighName($tn,$bc_uid) {
	global $dbconn;

	$bcUid = explode("||",$bc_uid);
	$bcUid = $bcUid[sizeof($bcUid)-2];

	$sql = mysql_query("SELECT comment_name FROM ".$tn." WHERE uid='".$bcUid."'",$dbconn);
	$row = mysql_fetch_row($sql);

	return stripSlashNotUsed($row[0]);	
}
//#######################################게시판 코멘트 전체 관리 함수 END####################################//


//#######################################게시판 관리 함수 START####################################//
function baMemberAuthOnly($auth_only) {
	switch($auth_only) {
		case "Y" : return "○"; break;
		case "N" : return "×"; break;
	}
}

function baUpload($upload) {
	switch($upload) {
		case "T" : return "○"; break;
		case "F" : return "×"; break;
	}
}
function baGonggiAdmin($gonggi_admin) {
	switch($gonggi_admin) {
		case "Y" : return "○"; break;
		case "N" : return "×"; break;
	}
}
function baSecretAdmin($secret_admin) {
	switch($secret_admin) {
		case "1" : return "사용안함"; break;
		case "2" : return "사용함"; break;
		case "3" : return "자동등록"; break;
	}
}
function baCommentYorn($comment_yorn) {
	switch($comment_yorn) {
		case "Y" : return "○"; break;
		case "N" : return "×"; break;
	}
}


$getMemberAuthAll = getMemberAuthAll();
if($getMemberAuthAll[0]) {
	$boardMemberAuthNameArr = "관리자,".$getMemberAuthAll[1].",제한없음";
	$boardMemberAuthNameArr = explode(",",$boardMemberAuthNameArr);
	$boardMemberAuthArr = "A,".$getMemberAuthAll[0].",";
}else{
	$boardMemberAuthNameArr = "관리자,제한없음";
	$boardMemberAuthNameArr = explode(",",$boardMemberAuthNameArr);
	$boardMemberAuthArr = "A,";
}
$boardMemberAuthArr = explode(",",$boardMemberAuthArr);


function baMemberAuthName($field_name) {
	
	//echo $field_name."<br>";

	global $boardMemberAuthNameArr;
	global $boardMemberAuthArr;

	$field_name_view=explode("@@",$field_name);

	for($s=1;$s<sizeof($field_name_view)-1;$s++) {

		for($i=0; $i<sizeof($boardMemberAuthArr); $i++) {
			if($boardMemberAuthArr[$i]==$field_name_view[$s]) {
				$memberAuthName.="<div>".$boardMemberAuthNameArr[$i]."</div>";
			}

		}

	}

	return $memberAuthName;
}


function baMemberAuthNameExcel($field_name) {
	
	//echo $field_name."<br>";

	global $boardMemberAuthNameArr;
	global $boardMemberAuthArr;

	$field_name_view=explode("@@",$field_name);

	for($s=1;$s<sizeof($field_name_view)-1;$s++) {

		for($i=0; $i<sizeof($boardMemberAuthArr); $i++) {
			if($boardMemberAuthArr[$i]==$field_name_view[$s]) {
				if($memberAuthName) {
					$memberAuthName.="<br>";
				}
				$memberAuthName.="".$boardMemberAuthNameArr[$i];
			}

		}

	}

	return $memberAuthName;
}


//modify.php 접근 및 수정 권한
function baModifyAuthKor($tn,$uid) {
	global $dbconn;
	global $adminAuth;

	$chk_sql = mysql_query("SELECT member_id FROM $tn WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	if($chk_row[0]=="admin" && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("수정권한이 없습니다.");
	}

	$chk_sql = mysql_query("SELECT member_id,secret_status,gonggi_status,gid FROM $tn WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	//echo $_SESSION["inquire_pwd"];
	if($chk_row[1]=="Y" && $chk_row[2]=="N") {


		if(!$_SESSION["inquire_pwd"]) {
			if($chk_row[0]==$_SESSION["session_usr_id"]) {
				$_SESSION["inquire_pwd"]=$_SESSION["session_usr_id"];
			}
		}

		if (!$_SESSION["inquire_pwd"] && (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			ERROR_BACK("이 게시글은 비밀글입니다.");
		}else{
			if ((!$_SESSION["session_admin_id"] || $adminAuth!="Y") && $_SESSION["inquire_pwd"]!="admin") {
				$psql=mysql_query("SELECT uid FROM $tn WHERE uid=$uid AND (pwd='".$_SESSION["inquire_pwd"]."' OR member_id='".$_SESSION["inquire_pwd"]."' OR pwd='".$_SESSION["session_usr_id"]."' OR member_id='".$_SESSION["session_usr_id"]."')",$dbconn);
				$prow=mysql_fetch_row($psql);


				$pQuery2 ="SELECT pwd FROM $tn WHERE member_id='".$_SESSION["session_usr_id"]."' AND member_id<>'' AND gid='".$chk_row[3]."' AND level='0'";
				$pSql2 = mysql_query($pQuery2,$dbconn);
				$pRow2 = mysql_fetch_row($pSql2);


				if (!$prow[0] && !$pRow2[0]) {
					ERROR_BACK("비밀번호가 일치하지 않습니다.");
				}
			}
		}
	}
}




//reply.php 접근 및 답변 권한
function baReplyAuthKor($tn,$uid) {
	global $dbconn;
	global $adminAuth;

	$chk_sql = mysql_query("SELECT member_id FROM $tn WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	if($chk_row[0]=="admin" && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("답변권한이 없습니다.");
	}

	$chk_sql = mysql_query("SELECT member_id,secret_status,gonggi_status,gid FROM $tn WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	//echo $_SESSION["inquire_pwd"];
	if($chk_row[1]=="Y" && $chk_row[2]=="N") {


		if(!$_SESSION["inquire_pwd"]) {
			if($chk_row[0]==$_SESSION["session_usr_id"]) {
				$_SESSION["inquire_pwd"]=$_SESSION["session_usr_id"];
			}
		}

		if (!$_SESSION["inquire_pwd"] && (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			ERROR_BACK("이 게시글은 비밀글입니다.");
		}else{
			if ((!$_SESSION["session_admin_id"] || $adminAuth!="Y") && $_SESSION["inquire_pwd"]!="admin") {
				$psql=mysql_query("SELECT uid FROM $tn WHERE uid=$uid AND (pwd='".$_SESSION["inquire_pwd"]."' OR member_id='".$_SESSION["inquire_pwd"]."' OR pwd='".$_SESSION["session_usr_id"]."' OR member_id='".$_SESSION["session_usr_id"]."')",$dbconn);
				$prow=mysql_fetch_row($psql);


				$pQuery2 ="SELECT pwd FROM $tn WHERE member_id='".$_SESSION["session_usr_id"]."' AND member_id<>'' AND gid='".$chk_row[3]."' AND level='0'";
				$pSql2 = mysql_query($pQuery2,$dbconn);
				$pRow2 = mysql_fetch_row($pSql2);


				if (!$prow[0] && !$pRow2[0]) {
					ERROR_BACK("비밀번호가 일치하지 않습니다.");
				}
			}
		}
	}
}



//reply.php 접근 및 답변 권한
function baViewAuthKor($tn,$uid) {
	global $dbconn;
	global $adminAuth;

	$chk_sql = mysql_query("SELECT member_id,secret_status,gonggi_status,gid FROM $tn WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	//echo $_SESSION["inquire_pwd"];
	if($chk_row[1]=="Y" && $chk_row[2]=="N") {


		if(!$_SESSION["inquire_pwd"]) {
			if($chk_row[0]==$_SESSION["session_usr_id"]) {
				$_SESSION["inquire_pwd"]=$_SESSION["session_usr_id"];
			}
		}

		if (!$_SESSION["inquire_pwd"] && (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			ERROR_BACK("이 게시글은 비밀글입니다.");
		}else{
			if ((!$_SESSION["session_admin_id"] || $adminAuth!="Y") && $_SESSION["inquire_pwd"]!="admin") {
				$psql=mysql_query("SELECT uid FROM $tn WHERE uid='".$uid."' AND (pwd='".$_SESSION["inquire_pwd"]."' OR member_id='".$_SESSION["inquire_pwd"]."' OR pwd='".$_SESSION["session_usr_id"]."' OR member_id='".$_SESSION["session_usr_id"]."')",$dbconn);
				$prow=mysql_fetch_row($psql);


				$pQuery2 ="SELECT pwd FROM $tn WHERE member_id='".$_SESSION["session_usr_id"]."' AND member_id<>'' AND gid='".$chk_row[3]."' AND level='0'";
				$pSql2 = mysql_query($pQuery2,$dbconn);
				$pRow2 = mysql_fetch_row($pSql2);


				if (!$prow[0] && !$pRow2[0]) {
					ERROR_BACK("비밀번호가 일치하지 않습니다.");
				}
			}
		}
	}
}

//게시판 첨부파일 이미지 여부
function getFileType($file_name) {
	$ret = "file";

	$type_name=substr(strrchr($file_name, "."),1);
	$type_name = strtolower($type_name);

	if($type_name=="gif" || $type_name=="jpg" || $type_name=="jpeg" || $type_name=="png") {
		$ret="img";
	}
	return $ret;
}
//#######################################게시판 관리 함수 END####################################//



//####################################회원관련 함수 START##########################################//
function getMemberAuth($member_auth){
	global $dbconn;

	$mauSql = mysql_query("SELECT member_auth,member_auth_name FROM memberAuth WHERE member_auth='".$member_auth."'",$dbconn);
	$mauRow = mysql_fetch_row($mauSql);

	if($mauRow[0]) {
		return $mauRow[1];
	}else{
		return "비회원";
	}
}

function getMemberAuthAll() {
	global $dbconn;
	
	$mauSql = mysql_query("SELECT member_auth,member_auth_name FROM memberAuth ORDER BY listorder ASC, uid ASC",$dbconn);
	$mau = 0;
	while($mauRow = mysql_fetch_row($mauSql)) {

		if($mau==0) {
			$memberAuth = "".$mauRow[0]."";
			$memberAuthName = "".$mauRow[1]."";
		}else{
			$memberAuth.=",".$mauRow[0]."";
			$memberAuthName.=",".$mauRow[1]."";
		}
		$mau++;
	}

	return array($memberAuth,$memberAuthName);
}




function sexFnc($sex) {
	switch($sex) {
		case "1" : return "남"; break;
		case "2" : return "여"; break;
	}
}


function memberIdName($member_id) {
	global $dbconn;

	$mSql = mysql_query("SELECT member_name FROM member WHERE member_id='".$member_id."'",$dbconn);
	$mRow = mysql_fetch_row($mSql);

	if($member_id){
		//$memberIdName = "".stripSlashNotUsed($mRow[0])."<div style=\"color:#555555\">".$member_id."</div>";
		$memberIdName = "".stripSlashNotUsed($mRow[0])."";
	}else{
		$memberIdName = "";
	}
	return $memberIdName;
}


//임시비밀번호 생성
function getTempPass($length) {

	global $dbconn;
	
	settype($template, "string"); 
	$template = "23456789abcdefghijklmnopqrstuvwxyz"; // 랜덤만들기 코드
		
	settype($length, "integer"); 
	settype($rndstring, "string"); 
	settype($a, "integer"); 
	settype($b, "integer"); 

	for ($a = 0; $a < $length; $a++) { 
		  $b = rand(0, strlen($template) - 1); 
		  $rndstring .= $template[$b]; 
	}
	
	return $rndstring;
	break;
}


function memberIdCut($member_id) {
	if($member_id!="비회원") {
		for($i=0; $i<strlen($member_id); $i++) {
			if($i<3) {
				$memberIdView.=substr($member_id,$i,1);
			}else{
				$memberIdView.="*";
			}
		}
	}else{
		$memberIdView="비회원";
	}
	return $memberIdView;
}

//회원, 비회원
function memberORguest($member_id) {
	if($member_id) {
		return $member_id;
	}else{
		return "비회원";
	}
}

//=====================회원 존재여부================//
function getMemberExist($member_id){
	global $dbconn;

	$ret = "Y";

	$mRow = mysql_fetch_row(mysql_query("SELECT uid FROM member WHERE member_id='".$member_id."'",$dbconn));
	
	if(!$mRow[0]) {
		$ret = "N";
	}

	return $ret;
}

//=====================회원 이름================//
function getMemberName($member_id,$type){
	global $dbconn;

	$mRow = mysql_fetch_row(mysql_query("SELECT uid,member_name FROM member WHERE member_id='".$member_id."'",$dbconn));

	if($mRow[0]) {
		if($type=="All") {
			return array(stripSlashNotUsed($mRow[1]),stripSlashNotUsed($mRow[2]));
		}else if($type=="A") {
			return stripSlashNotUsed($mRow[1]);
		}else if($type=="B") {
			return stripSlashNotUsed($mRow[2]);
		}
	}else{
		return "";
	}
}

//==================회원연령
function memberAge($birthday) {
	$birthDay = substr($birthday,0,4);
	$age = date("Y")-$birthDay+1;
	
	return $age;
}

function getMemberBrithday($birthday,$type=""){
	$birth_year = substr($birthday,0,4);
	$birth_month = substr($birthday,4,2);
	$birth_day = substr($birthday,6,2);
	$birth_type = substr($birthday,8,1);

/*
	if($birth_type=="+") {
		$birthType="양력";
	}else{
		$birthType="음력";
	}
*/

	if($birthday) {
		if($type=="A") {
			return $birth_year."-".$birth_month."-".$birth_day;
		}else{
			return $birth_year."-".$birth_month."-".$birth_day." ".$birthType;
		}
	}else{
		return "";
	}
}



//회원 중복 가입 체크
function duplicationMemberChk(){
	global $dbconn;

	if($_SESSION["session_cid"]) {
		$mSql = mysql_query("SELECT member_id,membership FROM member WHERE ipin_cid='".$_SESSION["session_cid"]."' ORDER BY uid DESC LIMIT 1",$dbconn);
		$mRow = mysql_fetch_row($mSql);
	}

	/*
	else{
		$mSql = mysql_query("SELECT member_id,membership FROM member WHERE INSTR(birthday,'".$_SESSION["session_birthday"]."') AND member_name='".$_SESSION["session_name"]."' AND ipin_dupinfo='' ORDER BY uid DESC LIMIT 1",$dbconn);
		$mRow = mysql_fetch_row($mSql);
	}
	*/
	
	$moSql = mysql_query("SELECT member_id,writedate FROM memberOut WHERE member_id='".$mRow[0]."'",$dbconn);
	$moRow = mysql_fetch_row($moSql);


	if($mRow[0] && $mRow[1]=="Y") {
		ERROR_BACK("입력하신 정보는 이미 가입된 회원입니다.");
		exit;
	}


	if($moRow[0]) {
		$reJoinWrite = date("Y-m-d H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));		//7일후 재가입 가능

		if($reJoinWrite < $moRow[1]) {
			ERROR_BACK("회원탈퇴 후 회원 재가입은 7일 이후 가능합니다.");
			exit;
		}
	}
}


//탈퇴완료시 회원 정보 삭제
function memberInfoDel($member_id) {
	global $dbconn;

	//회원 기본정보
	$UPD ="UPDATE member SET ";
	$UPD.="passwd='',";
	$UPD.="sex='',";
	$UPD.="birthday='',";
	$UPD.="zipcode='',";
	$UPD.="addr1='',";
	$UPD.="addr2='',";
	$UPD.="phone1='',";
	$UPD.="phone2='',";
	$UPD.="sms_accept='',";
	$UPD.="email='',";
	$UPD.="email_accept='',";
	$UPD.="memo='',";
	$UPD.="ipin_dupinfo='',";
	$UPD.="ipin_cid='',";
	$UPD.="admin_auth='',";
	$UPD.="admin_auth_confession='N' ";
	$UPD.="WHERE member_id='".$member_id."'";
	$result = mysql_query($UPD,$dbconn);
}
//####################################회원관련 함수 END##########################################//





//=====================카테고리명================//
function categoryName($tableName, $product_category){
	global $dbconn;


	$aCategory = substr($product_category,0,4);
	$bCategory = substr($product_category,0,8);
	$cCategory = substr($product_category,0,12);

	$bca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,4)='".$aCategory."' AND catedeg='1'",$dbconn));
	$mca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,8)='".$aCategory."' AND catedeg='2'",$dbconn));
	$sca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,12)='".$aCategory."' AND catedeg='3'",$dbconn));


	if($bca_row[0]) {
		$Page_Title = $bca_row[1];
	}
	/*
	if($mca_row[0]) {		
		$Page_Title = $mca_row[1];
	}
	if($sca_row[0]) {
		$Page_Title = $sca_row[1];
	}
	*/

	return stripSlashNotUsed($Page_Title);
}

//관리자 상품 목록
function categoryNameList($tableName, $product_category){
	global $dbconn;

	$aCategory = substr($product_category,0,4);
	$bCategory = substr($product_category,0,8);
	//$cCategory = substr($product_category,0,12);

	$apcRow = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,4)='".$aCategory."' AND catedeg='1'",$dbconn));
	$bpcRow = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,8)='".$bCategory."' AND catedeg='2'",$dbconn));
	//$sca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,12)='".$aCategory."' AND catedeg='3'",$dbconn));

	if($apcRow[0]) {
		$cateName.= $apcRow[1];
	}
	if($bpcRow[0]) {		
		$cateName.= " → ".$bpcRow[1];
	}
	/*
	if($sca_row[0]) {
		$cateName.= $sca_row[1];
	}
	*/

	return stripSlashNotUsed($cateName);
}


//프론트페이지 상품 목록
function categoryNameFrontList($tableName, $product_category){
	global $dbconn;

	$aCategory = substr($product_category,0,4);
	$bCategory = substr($product_category,0,8);
	//$cCategory = substr($product_category,0,12);

	$apcRow = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,4)='".$aCategory."' AND catedeg='1'",$dbconn));
	$bpcRow = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,8)='".$bCategory."' AND catedeg='2'",$dbconn));
	//$sca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,12)='".$aCategory."' AND catedeg='3'",$dbconn));

	if($apcRow[0]) {
		$cateName = $apcRow[1];
	}
	if($bpcRow[0]) {		
		$cateName = $bpcRow[1];
	}

	return stripSlashNotUsed($cateName);
}


//=====================카테고리명 대분류================//
function categoryNameA($tableName, $product_category){
	global $dbconn;

	$aCategory = substr($product_category,0,4);

	$bca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,4)='".$aCategory."' AND catedeg='1'",$dbconn));

	return stripSlashNotUsed($bca_row[1]);
}
//=====================카테고리명 중분류================//
function categoryNameB($tableName, $product_category){
	global $dbconn;

	$bCategory = substr($product_category,0,8);

	$mca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,8)='".$bCategory."' AND catedeg='2'",$dbconn));

	return stripSlashNotUsed($mca_row[1]);
}
//=====================카테고리명 소분류================//
function categoryNameC($tableName, $product_category){
	global $dbconn;

	$cCategory = substr($product_category,0,12);

	$sca_row = mysql_fetch_row(mysql_query("SELECT catecode,catename FROM `".$tableName."` WHERE SUBSTRING(catecode,1,12)='".$cCategory."' AND catedeg='3'",$dbconn));

	return stripSlashNotUsed($sca_row[1]);
}





//********************모바일 접속시 전화 링크 START*****************************//
$mobileAgent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
function mobilePcPhoneLink($phone,$type) {
	global $mobileAgent;

	$ret = $phone;
	if(preg_match($mobileAgent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
		$ret = "<a href=\"tel:".$phone."\" title=\"".$phone."\">".$phone."</a>";
	}
	
	if($type=="A") {
		$ret = "<a href=\"#void\" title=\"".$phone."\" onclick=\"return false;\">전화문의</a>";
		if(preg_match($mobileAgent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
			$ret = "<a href=\"tel:".$phone."\" title=\"".$phone."\">전화문의</a>";
		}
	}else if($type=="B") {
		//echo onlyBlankSpaceChk($phone)." ".$phone;
		if(onlyBlankSpaceChk($phone)) {
			$ret = "<a href=\"#void\" title=\"".$phone."\" onclick=\"return false;\">".$phone."</a>";
			if(preg_match($mobileAgent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
				$ret = "<a href=\"tel:".$phone."\" title=\"".$phone."\">".$phone."</a>";
			}
		}
	}

	return $ret;
}
function mobilePcPhoneLinkIframe($phone,$type) {
	global $mobileAgent;

	$ret = $phone;
	if(preg_match($mobileAgent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
		$ret = "<a href=\"tel:".$phone."\" target=\"_parent\" title=\"".$phone."\">".$phone."</a>";
	}
	
	if($type=="A") {
		$ret = "<a href=\"#void\" onclick=\"return false;\" target=\"_parent\">전화문의</a>";
		if(preg_match($mobileAgent, $_SERVER['HTTP_USER_AGENT'])) {	// || preg_match("/\/mobile/",$PHP_SELF)) {
			$ret = "<a href=\"tel:".$phone."\" target=\"_parent\" title=\"".$phone."\">전화문의</a>";
		}
	}

	return $ret;
}
//********************모바일 접속시 전화 링크 END*****************************//





function getImgSize($imgUrl){

	if(file_exists("".$_SERVER['DOCUMENT_ROOT']."".$imgUrl."")) {
		$IMG_1=getimagesize("".$_SERVER['DOCUMENT_ROOT']."".$imgUrl."");
		
		$imageWidth=$IMG_1[0];
		$imageHeight=$IMG_1[1];

		if ($imageWidth > 760) {
			$imageWidth_down = 760;
				
			//실제넓이:실제높이= $imageWidth_down : $imgHeight_down
			//imageHeight_down = $imageHeight * $imageWidth_down / $imageWidth;
			$imageHeight_down = round($imageHeight * $imageWidth_down / $imageWidth);
		}else{
			$imageWidth_down = $imageWidth;
			$imageHeight_down = $imageHeight;
		}
	}else{
		$imageWidth_down = 0;
		$imageHeight_down = 0;
	}

	return array($imageWidth_down,$imageHeight_down);
}



function weekChange($weekNum) {
	switch($weekNum) {
		case "0" : return "일요일"; break;
		case "1" : return "월요일"; break;
		case "2" : return "화요일"; break;
		case "3" : return "수요일"; break;
		case "4" : return "목요일"; break;
		case "5" : return "금요일"; break;
		case "6" : return "토요일"; break;
	}
}
function weekChange2($weekNum) {
	switch($weekNum) {
		case "0" : return "일"; break;
		case "1" : return "월"; break;
		case "2" : return "화"; break;
		case "3" : return "수"; break;
		case "4" : return "목"; break;
		case "5" : return "금"; break;
		case "6" : return "토"; break;
	}
}

function numberFormatFnc($number) {
	if($number) {
		$number = number_format($number);
	}else{
		$number = "-";
	}
	return $number;
}

function numberFormatFncB($number) {
	if($number) {
		$number = number_format($number);
	}else{
		$number = "";
	}
	return $number;
}

function numberFormatFncC($number) {
	if($number) {
		$number = sprintf("%01.3f",$number);
		$number = round($number,2);
		$number = explode(".",$number);
		if($number[1]) {
			$number = number_format($number[0]).".".$number[1];
		}else{
			$number = number_format($number[0]);
		}
	}else{
		$number = "";
	}
	return $number;
}

function numberFormatFnPM($number) {
	
	$ret = $number;
	if(0<$number) {
		$ret = "+".number_format($number);
	}
	return $ret;
}

function numberFormatFncWon($number) {
	if(0<strlen($number) && 0<$number) {
		$number = number_format($number)." 원";
	}else{
		$number = "-";
	}
	return $number;
}

function inputTextPrint($textValue) {
	$textValue = stripSlashNotUsed($textValue);
	$textValue = htmlspecialchars($textValue,ENT_QUOTES);
	$textValue = str_replace("&amp;#","&#",$textValue);

	return $textValue;
}

function name($aaa) {	
	return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_a-z{|}~]+'.
	'.'.
	'[A-Z.a-z]',$aaa));
}


function randomFnc(){
	for($i=0; $i<3; $i++) { 
		switch(mt_rand(0, 3)) { 
			case 0: $ranstr .= @chr(mt_rand(0, 25) + 97); break; 
			case 1: $ranstr .= @chr(mt_rand(0, 25) + 65); break; 
			case 2: $ranstr .= @mt_rand(0, 9); break; 
		} 
	}
	return $ranstr;
}


//소수점 자리 $roundUp 에서 반올림
function getPerPointRound($per,$roundUp){
	//echo $per." ";
	
	$roundUpMinus = $roundUp-1;			//정수
	//$roundUpMinus = $roundUp;			//소수점 첫째
	//$roundUpMinus = $roundUp+1;		//소수점 둘째

	//$per=sprintf("%01.".$roundUp."f",$per);		//소수점 둘째자리 끊기
	$per=round($per,$roundUpMinus);
	$per=sprintf("%01.".$roundUpMinus."f",$per);

	return $per;
}


function monthChange($monthNum) {
	switch($monthNum) {
		case "01" : return "January"; break;
		case "02" : return "February"; break;
		case "03" : return "March"; break;
		case "04" : return "April"; break;
		case "05" : return "May"; break;
		case "06" : return "June"; break;
		case "07" : return "July"; break;
		case "08" : return "August"; break;
		case "09" : return "September"; break;
		case "10" : return "October"; break;
		case "11" : return "November"; break;
		case "12" : return "December"; break;
	}
}

//문자 숨기기
function closeOpen($text,$standard,$bi_open_close){

	if($bi_open_close=="C") {
		mb_internal_encoding("utf-8");
		$textLen = mb_strlen($text);

		if($standard=="all") {
			for($i=1;$i<=$textLen;$i++) {
				if($i!=$standard) {
					$textChange.="*";
				}
			}
		}else if($standard=="odd") {
			for($i=1;$i<=$textLen;$i++) {
				if($i%2==1) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}			
		}else{
			for($i=1;$i<=$textLen;$i++) {
				if($i>$standard) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}
		}
	}else{
		$textChange=$text;
	}

	return $textChange;
}

//문자 숨기기
function closeOpen2($text,$standard,$bi_open_close){

	if($bi_open_close=="C") {
		mb_internal_encoding("utf-8");
		$textLen = mb_strlen($text);

		if($standard=="all") {
			for($i=1;$i<=$textLen;$i++) {
				if($i!=$standard) {
					$textChange.="*";
				}
			}
		}else if($standard=="odd") {
			for($i=1;$i<=$textLen;$i++) {
				if($i%2==0) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}			
		}else{
			for($i=1;$i<=$textLen;$i++) {

				if($textLen-$standard<$i) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}
		}
	}else{
		$textChange=$text;
	}

	return $textChange;
}

//문자 숨기기
function closeOpen3($text,$standard,$bi_open_close){

	if($bi_open_close=="C") {
		mb_internal_encoding("utf-8");
		$textLen = mb_strlen($text);

		if($standard=="all") {
			for($i=1;$i<=$textLen;$i++) {
				if($i!=$standard) {
					$textChange.="*";
				}
			}
		}else if($standard=="odd") {
			for($i=1;$i<=$textLen;$i++) {
				if($i%2==0) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}			
		}else{
			for($i=1;$i<=$textLen;$i++) {
				if($standard>=$i) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}
		}
	}else{
		$textChange=$text;
	}

	return $textChange;
}


//문자 숨기기
function closeOpenSel($text,$hidden_num){

	mb_internal_encoding("utf-8");
	$textLen = mb_strlen($text);

	if($_SESSION["session_admin_id"]) {
		$textChange = $text;
	}else{
		$hiddenNumArr = explode(",",$hidden_num);
		for($i=1;$i<=$textLen;$i++) {
			for($j=0; $j<sizeof($hiddenNumArr); $j++) {
				if($i==$hiddenNumArr[$j]) {
					$textChange.="*";
				}else{
					$textChange.=mb_substr($text,$i-1,1);
				}
			}
		}
	}


	return $textChange;
}



//=====================메인 이미지 출력 진행 관리 START================//
function mainImgLink($link_url, $img, $img_width, $img_height, $img_path, $img_class="", $a_target="_blank") {

	$imgWidth = "";
	$imgHeight = "";
	$imgClass = "";
	$aTarget = "";

	if($img_width) {
		$imgWidth = "width:".$img_width.";";
	}
	if($img_height) {
		$imgHeight = "height:".$img_height.";";
	}

	if($img_class) {
		$imgClass = "class=\"".$img_class."\"";
	}
	if($a_target) {
		$aTarget = "target=\"".$a_target."\"";
	}

	if($link_url) {
		$imgLink = "<a href=\"".stripSlashNotUsed($link_url)."\" ".$aTarget."><img src=\"".$img_path."".$img."\" style=\"".$imgWidth." ".$imgHeight."\" ".$imgClass." /></a>";
	}else{
		$imgLink = "<img src=\"".$img_path."".$img."\" style=\"".$imgWidth." ".$imgHeight."\" ".$imgClass." />";
	}

	return $imgLink;
}
//=====================메인 이미지 출력 진행 관리 END================//








//////////////////////////////윈도우 창 컨트롤 & 경고 문구 START/////////////////////////
function selfClose() {
	echo "<script language=\"javascript\">
			self.close();
		  </script>";
}
function Message_Echo($msg) {

	echo "<script language='javascript'>
		  alert(\"$msg\");
		  </script>";
}
function windowReload() {
	echo "<script language='javascript'>
			opener.location.reload();
		  </script>";
}
function windowReloadMessage($msg) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			parent.location.reload();
		  </script>";
}
function windowReloadReload() {
	echo "<script language='javascript'>
			opener.location.reload();
			opener.opener.location.reload();
		  </script>";
}

function windowClose($msg) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			window.close();
		  </script>";
	exit;
}
function windowCloseReload($msg) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			opener.location.reload();
			window.close();
		  </script>";
}
function winCloseTgtReload($msg,$url) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			opener.location.href='$url';
			window.close();
		  </script>";
}
function winCloseTgtReload2($url) {
	echo "<script language='javascript'>
			opener.location.href='$url';
			window.close();
		  </script>";
}

function ERROR_BACK_NOMSG() {

echo "<script language='javascript'>
	  history.back();
	  </script>";
	exit;
}

function ERROR_BACK($msg) {

echo "<script language='javascript'>
      alert(\"$msg\");
	  history.back();
	  </script>";
	exit;
}

function ERROR_BACK_URL($msg,$url) {
	global $homeDir;
	
	echo "
	<script language='javascript'>
		alert(\"$msg\");
	</script>";

	echo "<meta http-equiv='Refresh' content='0; URL=".$homeDir."".$url."'>";

	exit;	
}

function errorBackUrl($msg,$url) {

	echo "
		<script language='javascript'>
			window.alert(\"$msg\");
			location.href=\"".$url."\";
	  </script>";
	exit;
}

function errorBackNoUrl($msg) {

	echo "
		<script language='javascript'>
			window.alert(\"$msg\");
			history.back();
	  </script>";
	exit;
}

function LOGOUT() {
	echo "<script>
			alert('로그인 유지시간이 초과되었습니다. 다시로그인하세요!!');
			parent.location.href='/admin/index.html';
		  </script>";
//<meta http-equiv='Refresh' content='0; URL=index.html'>";
}
function GO_REFRESH($url) {
	echo "<meta http-equiv='Refresh' content='0; URL=$url'>";
}

function goPageMove($url) {
	echo "<script>
			location.href='".$url."';
		  </script>";
	exit;
}

function goPageMoveMent($url,$msg) {
	echo "<script>
			window.alert('".$msg."');
			location.href='".$url."';
		  </script>";
	exit;
}


function LOGIN_ERROR($nexturl,$homeType="") {
	global $homeDir;

	if($homeType=="MALL") {
		echo "
		<meta http-equiv='Refresh' content='0; URL=/member/login.html?nexturl=$nexturl'>";
	}else{
		echo "
		<meta http-equiv='Refresh' content='0; URL=/member/login.html?nexturl=$nexturl'>";
	}
}

function LOGIN_ERROR_2($nexturl,$homeType="") {
	global $homeDir;

	if($homeType=="MALL") {
		echo "
		<meta http-equiv='Refresh' content='0; URL=/member/login.html?nexturl=$nexturl'>";
	}else{
		echo "
		<meta http-equiv='Refresh' content='0; URL=/member/login.html?nexturl=$nexturl'>";
	}
}
function LOGIN_ERROR_M($nexturl) {
	global $homeDir;
	echo "
       <meta http-equiv='Refresh' content='0; URL=".$homeDir."/mobile/member/login.html?nexturl=$nexturl'>";
}

function LOGIN_USR_ERROR($nexturl) {
	echo "<meta http-equiv='Refresh' content='0; URL=/join/01.html?nexturl=$nexturl'>";
}

function LOGIN_POP_ERROR($mesg,$nexturl) {
	echo "<script>
		alert(\"$mesg\");
	      </script>

	<meta http-equiv='Refresh' content='0; URL=/members/login_pop.html?nexturl=$nexturl'>";
}

function LOGIN_JUMUN_ERROR($nexturl) {
 echo "
       <meta http-equiv='Refresh' content='0; URL=/member/login.html?nexturl=$nexturl'>";
}
function LOGIN_JUMUN_DELIVERY_ERROR($nexturl) {
 echo "
       <meta http-equiv='Refresh' content='0; URL=/mypage/order_login.html?nexturl=$nexturl'>";
}

function loginApplicationError($nexturl) {
	echo "
       <meta http-equiv='Refresh' content='0; URL=/application/check_before.html?nexturl=$nexturl'>";
}

function LOGIN_DELIVERY_ERROR($nexturl) {
 echo "
       <meta http-equiv='Refresh' content='0; URL=/shop/order_login.html?nexturl=$nexturl'>";
}

function email($address) {


// return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_a-z{|}~]+'.
//		'@'.
//		'[-!#$%&\'*+\\/0-9=?A-Z^_a-z{|}~]+\.'.
//		'[-!#$%&\'*+\\./0-9=?A-Z^_a-z{|}~]+$',
//		$address) || ereg('E-Mail', $address));
	if (!eregi("^[^@ ]+@[^@ ]+\.[a-zA-Z]+", $address)) {
		return false;
	}
	return true;
}
function cutting($str, $len, $suffix = "..."){

	if ($len >= strlen($str)) return $str;

	$klen = $len - 1;

	while(ord($str[$klen]) & 0x80) $klen--;

	return substr($str, 0, $len - (($len + $klen + 1) % 2)) . $suffix;

}
//cutting($Row[subject],$subject_limit) //함수 호출


function setFileVal($l_upfile,$path,$filenameReal) {
	$setFileVal = urlencode($l_upfile).urlencode($path).urlencode($filenameReal)."sun!~2918";
	$setFileVal = hash("sha256", $setFileVal);

	return $setFileVal;
}

function file_download($l_upfile,$path,$filenameReal,$file_icon_name,$view_auth="",$returnYorN="N") {

	$getFileVal = setFileVal($l_upfile,$path,$filenameReal);

	$type=substr(strrchr($l_upfile, "."), 1);
	$type=strtolower($type);

	if($type=="xlsx"){
		$type="xls";
	}

	if ($type=="hwp"||$type=="com"||$type=="exe"||$type=="txt"||$type=="wav"||$type=="xls"||$type=="doc"||
	    $type=="zip"||$type=="rar"||$type=="gif"||$type=="bmp"||$type=="mp3"||$type=="ppt"||$type=="pdf") {
		$file_icon = $type.".gif";
	}else if ($type=="ra"||$type=="ram") {
		$file_icon = "ra.gif";
	}else if ($type=="htm"||$type=="html") {
		$file_icon = "htm.gif";
	}else if ($type=="jpg"||$type=="jpeg") {
		$file_icon = "jpg.gif";
	}else if ($type=="pdf") {
		$file_icon = "pdf.gif";
	}else if ($type=="mpg"||$type=="mpeg"||$type=="avi") {
		$file_icon = "mpg.gif";
	}else{
		$file_icon = "etc.gif";
	}

	$file_icon_alt = $type." 파일";

	if(!$file_icon_name) {
		$file_icon_name="/img/file_image/$file_icon";
	}

	if($view_auth) {
		global $boardUsrAuth;
		if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
			$ret="<a href=\"#void\" onclick=\"window.alert('접근 권한이 없습니다.');\"><img src=\"$file_icon_name\" border=\"0\" style=\"vertical-align:middle;\" style=\"vertical-align:middle;\" alt=\"".$file_icon_alt."\"></a>";
		}else{
			if($l_upfile) {
				$ret="<a href=\"/config/download.php?filename=".urlencode($l_upfile)."&path=$path&filenameReal=".urlencode($filenameReal)."&getFileVal=".$getFileVal."\"><img src=\"$file_icon_name\" border=\"0\" style=\"vertical-align:middle;\" alt=\"".$file_icon_alt."\"></a>";
			}else{
				$ret="";
			}
		}
	}else{
		if($l_upfile) {
			$ret="<a href=\"/config/download.php?filename=".urlencode($l_upfile)."&path=$path&filenameReal=".urlencode($filenameReal)."&getFileVal=".$getFileVal."\"><img src=\"$file_icon_name\" style=\"vertical-align:middle;\" alt=\"".$file_icon_alt."\"></a>";
		}else{
			$ret="";
		}
	}

	/*회원만 가능할시에
	if($l_upfile) {
		if($_SESSION["session_admin_id"] || $_SESSION["session_usr_id"]) {
			return "<a href='/product_img/download.php?filename=$l_upfile&path=$path&filenameReal=".urlencode($filenameReal)."'><img src='/img/file_image/$file_icon' border='0'></a>";
		}else{
			return "<a href=\"#void\" onClick=\"javascript:window.alert('로그인 후 다운로드 가능합니다.');\"><img src='/img/file_image/$file_icon' border='0'></a>";
		}
	}else{
		return "";
	}
	*/

	if($returnYorN=="N") {
		echo $ret;
	}else{
		return $ret;
	}
}

function file_download_name($l_upfile,$path,$filenameReal,$file_icon_name,$view_auth="",$returnYorN="N") {

	$getFileVal = setFileVal($l_upfile,$path,$filenameReal);

	if($view_auth) {
		global $boardUsrAuth;
		if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
			$ret="<a href=\"#void\" onclick=\"window.alert('접근 권한이 없습니다.'); return false;\">".$filenameReal."</a>";
		}else{
			if($l_upfile) {
				$ret="<a href=\"/config/download.php?filename=".urlencode($l_upfile)."&path=$path&filenameReal=".urlencode($filenameReal)."&getFileVal=".$getFileVal."\">".$filenameReal."</a>";
			}else{
				$ret="";
			}
		}
	}else{
		$ret="<a href=\"/config/download.php?filename=".urlencode($l_upfile)."&path=$path&filenameReal=".urlencode($filenameReal)."&getFileVal=".$getFileVal."\">".$filenameReal."</a>";
	}


	if($returnYorN=="N") {
		echo $ret;
	}else{
		return $ret;
	}
}



//국문 제품 다운로드
function fileProdDownloadName($filenameReal,$aLinkTitle,$lang,$url) {
	
	$getFileVal = setFileVal($filenameReal,$path,$filenameReal);

	$ret = "<a href=\"#void\" onclick=\"goLoginResult('".$url."',''); return false;\">".$aLinkTitle."</a>";
	if($_SESSION["session_usr_id"]) {
		$ret="<a href=\"/config/download_prod.php?filename=".urlencode($filenameReal)."&filenameReal=".urlencode($filenameReal)."&lang=".$lang."&getFileVal=".$getFileVal."\">".$aLinkTitle."</a>";
	}

	return $ret;
}




//비회원일 경우 다운로드
//fileDownloadStyle("조합원가입신청서_20160107.hwp","/member/file1",urlencode("조합원가입신청서_20160107.hwp"),"A","조합원가입신청서 다운받기")


function fileDownloadStyle($l_upfile,$path,$filenameReal,$styleType,$linkTitle,$view_auth="",$returnYorN="N") {
	
	$l_upfile = urldecode($l_upfile);
	$getFileVal = setFileVal($l_upfile,$path,$filenameReal);

	if($view_auth) {
		global $boardUsrAuth;
		if(!eregi($boardUsrAuth,$view_auth) && !$_SESSION["session_admin_id"]) {
			$donwHref="href=\"#void\" onclick=\"window.alert('접근 권한이 없습니다.');\"";
		}else{
			if($l_upfile) {
				$donwHref="href=\"/config/download.php?filename=".rawurlencode($l_upfile)."&path=".$path."&filenameReal=".rawurlencode($filenameReal)."&getFileVal=".$getFileVal."\"";
			}else{
				$donwHref="";
			}
		}
	}else{
		$donwHref="href=\"/config/download.php?filename=".rawurlencode($l_upfile)."&path=".$path."&filenameReal=".rawurlencode($filenameReal)."&getFileVal=".$getFileVal."\"";
	}


	if($styleType=="scheduleDown") {
		$ret = "
				<div class=\"download\">
					<ul>
						<li>
							<a ".$donwHref.">
								".$filenameReal."
							</a>
						</li>
					</ul>
				</div>";
	}else if($styleType=="applicationDown") {
		$ret = "<a ".$donwHref.">".$filenameReal."</a>";
	}else if($styleType=="prodE") {
		$ret = "<span><a ".$donwHref.">".$filenameReal."</a></span>";
	}


	if($returnYorN=="N") {
		echo $ret;
	}else{
		return $ret;
	}
}



function autoDefence(){
	for ($i=65;$i<=122;$i++) {
		if($i<=90) {
			$a_id[]=chr($i);
			$b_id[]=chr($i);
			$c_id[]=chr($i);
			$d_id[]=chr($i);
			$e_id[]=chr($i);
			$f_id[]=chr($i);
			$g_id[]=chr($i);
		}
		if($i>=97) {
			$a_id[]=chr($i);
			$b_id[]=chr($i);
			$c_id[]=chr($i);
			$d_id[]=chr($i);
			$e_id[]=chr($i);
			$f_id[]=chr($i);
			$g_id[]=chr($i);
		}
	}

	//for($i=0;$i<100;$i++) {
	$a_r=rand(0,9);
	$b_r=rand(0,9);
	$c_r=rand(0,9);
	$d_r=rand(0,9);
	$e_r=rand(0,9);
	$f_r=rand(0,9);
	$g_r=rand(0,9);
	//}

	$W_Chk[]=$a_r;
	$W_Chk[]=$b_r;
	$W_Chk[]=$c_r;
	$W_Chk[]=$d_r;
	$W_Chk[]=$e_r;
	$W_Chk[]=$f_r;
	$W_Chk[]=$g_r;

	$rand_keys = array_rand ($W_Chk, 3); 

	/*특정 숫자만 입력할때*/
	for($z=0;$z<sizeof($W_Chk);$z++) {		
		if($W_Chk[$z]==$W_Chk[$rand_keys[0]] || $W_Chk[$z]==$W_Chk[$rand_keys[1]] || $W_Chk[$z]==$W_Chk[$rand_keys[2]]) {
			$Word_Chk.="<span style='color:#3e9eff; font-weight:bold; font-size:1.2em;'>$W_Chk[$z]</span>";
			$byte_chk.=$W_Chk[$z];
		}else{
			$Word_Chk.=$W_Chk[$z];
		}
	}
	/*전체숫자 다 입력할때
	for($z=0;$z<sizeof($W_Chk);$z++) {		
		$Word_Chk.="<span style='color:#3e9eff; font-weight:bold; font-size:1.2em; text-decoration: underline;'>$W_Chk[$z]</span>";
		$byte_chk.=$W_Chk[$z];
	}
	*/
	return array($Word_Chk,$byte_chk);
}


function thumbnail($file, $save_filename, $max_width, $max_height, $tn) {

	//$file --> 이미지 경로 및 이미지 이름
	$img_info = getImageSize($file);
	if($img_info[2] == 1)   $src_img = ImageCreateFromGif($file); //gif파일일경우
	elseif($img_info[2] == 2)   $src_img = ImageCreateFromJPEG($file); //jpg파일일경우
	elseif($img_info[2] == 3)   $src_img = ImageCreateFromPNG($file); //png파일일경우
	elseif($img_info[2] == 6)   $src_img = ImageCreateFromWBMP($file); //bmp파일일경우
	else    return 0;

	$img_width = $img_info[0];
	$img_height = $img_info[1];

	if(($img_width/$max_width) == ($img_height/$max_height)) {
		$srcx=0;
		$srcy=0;
		$srcW=$img_width;
		$srcH=$img_height;
	}else if(($img_width/$max_width) < ($img_height/$max_height)) {
		$srcx=0;
		$srcH=ceil(($img_width / $max_width) * $max_height);
		$srcy=($img_height - $srcH) / 2;
		$srcW=$img_width;
	}else{
		$srcy=0;
		$srcW=ceil(($img_height / $max_height) * $max_width);
		$srcx=($img_width - $srcW) / 2;
		$srcH=$img_height;
	}

	if($img_info[2] == 1) $dst_img = imagecreate($max_width, $max_height);
	else $dst_img = imagecreatetruecolor($max_width, $max_height);
		
	$bgc = ImageColorAllocate($dst_img, 255, 255, 255);
	ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc);
	ImageCopyResized($dst_img, $src_img, 0, 0, $srcx, $srcy, $max_width, $max_height, $srcW, $srcH);
	ImageCopyResampled($dst_img, $src_img, 0, 0, $srcx, $srcy, $max_width, $max_height, $srcW, $srcH);


	/*
	$thumbneil = $save_dir.$Thump_name;
	$image = ImageCreateFromJPEG($file_name);
	$thumb_image = imagecreatetruecolor($width,$height); 
	$bgc = ImageColorAllocate($thumb_image,255,255,255);
	//ImageColorAllocate($thumb_image,255,255,255);
	ImageFilledRectangle($thumb_image, 0, 0, $width, $height, $bgc);
	imagecopyresampled($thumb_image,$image,0,0,0,0,$width,$height,ImageSX($image),ImageSY($image)); 
	ImageInterlace($thumb_image);
	ImageJPEG($thumb_image,$thumbneil); 
	ImageDestroy($image);
	ImageDestroy($thumb_image); 

	if ($max_width>=200) $over=$url."images/logol.png";
	elseif ($max_width>70 and $max_width<200) $over=$url."images/logom.png";
	else $over=$url."images/logos.png"; //사이즈별로 사용할 로고이미지를 지정합니다.

	$overlay_img=ImageCreateFromPNG($over);

	$overlay_img_x = imageSX($overlay_img); //오버레이 이미지 사이즈 X값
	$overlay_img_y = imageSY($overlay_img); //오버레이 이미지 사이즈 Y값
	$offsetX = $max_width - ImageSX($overlay_img) - 3;
	$offsetY = $max_height - ImageSY($overlay_img) - 3;

	ImageAlphaBlending($dst_img, true);
	ImageCopy($dst_img,$overlay_img,$offsetX,$offsetY,0,0,$overlay_img_x,$overlay_img_y);
	imagedestroy($overlay_img);
	*/

	$file_path = "./".$tn."/thumb/";

	if($img_info[2] == 1) {
		ImageInterlace($dst_img);
		ImageGIF($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 2){
		ImageInterlace($dst_img);
		ImageJPEG($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 3){
		ImagePNG($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 6){
		ImageInterlace($dst_img);
		ImageWBMP($dst_img, $file_path.$save_filename);
	}
	ImageDestroy($dst_img);
	ImageDestroy($src_img);

	$Thumb = $file_path.$save_filename;

	return $Thumb;
} 


//실질적인 이미지 사이즈 줄이기
function thumbnail2($file, $save_filename, $max_width, $max_height, $filePath) {

	//$file --> 이미지 경로 및 이미지 이름
	$img_info = getImageSize($file);
	if($img_info[2] == 1)   $src_img = ImageCreateFromGif($file); //gif파일일경우
	elseif($img_info[2] == 2)   $src_img = ImageCreateFromJPEG($file); //jpg파일일경우
	elseif($img_info[2] == 3)   $src_img = ImageCreateFromPNG($file); //png파일일경우
	elseif($img_info[2] == 6)   $src_img = ImageCreateFromWBMP($file); //bmp파일일경우
	else    return 0;

	$img_width = $img_info[0];
	$img_height = $img_info[1];

	if(($img_width/$max_width) == ($img_height/$max_height)) {
		$srcx=0;
		$srcy=0;
		$srcW=$img_width;
		$srcH=$img_height;
	}else if(($img_width/$max_width) < ($img_height/$max_height)) {
		$srcx=0;
		$srcH=ceil(($img_width / $max_width) * $max_height);
		$srcy=($img_height - $srcH) / 2;
		$srcW=$img_width;
	}else{
		$srcy=0;
		$srcW=ceil(($img_height / $max_height) * $max_width);
		$srcx=($img_width - $srcW) / 2;
		$srcH=$img_height;
	}

	if($img_info[2] == 1) $dst_img = imagecreate($max_width, $max_height);
	else $dst_img = imagecreatetruecolor($max_width, $max_height);
		
	$bgc = ImageColorAllocate($dst_img, 255, 255, 255);
	ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc);
	ImageCopyResized($dst_img, $src_img, 0, 0, $srcx, $srcy, $max_width, $max_height, $srcW, $srcH);
	ImageCopyResampled($dst_img, $src_img, 0, 0, $srcx, $srcy, $max_width, $max_height, $srcW, $srcH);


	$file_path = $filePath."/";

	if($img_info[2] == 1) {
		ImageInterlace($dst_img);
		ImageGIF($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 2){
		ImageInterlace($dst_img);
		ImageJPEG($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 3){
		ImagePNG($dst_img, $file_path.$save_filename);
	}elseif($img_info[2] == 6){
		ImageInterlace($dst_img);
		ImageWBMP($dst_img, $file_path.$save_filename);
	}
	ImageDestroy($dst_img);
	ImageDestroy($src_img);

	$Thumb = $file_path.$save_filename;

	return $save_filename;
}


//이미지 회전하고 가로사이즈에 맞는 세로 사이즈 구하기
function getImgWidthSize($imgUrl,$imgWidth){

	if(!$imgWidth) {
		$imgWidth = 1000;
	}

	if(file_exists("".$imgUrl."")) {


		//이미지 정보 가져오기.(정보가 없는 이미지는 패스)
		$exifData = @exif_read_data($imgUrl);
		 

		//####################################시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨(사진의 세부정보 방향을 가져옴) START####################################//
		if( isset($exifData['Orientation']) )
		{
			if($exifData['Orientation'] == 6)
			{
				$degree = 270;
			}
			// 반시계방향으로 90도 돌려줘야 정상
			else if ($exifData['Orientation'] == 8)
			{
				$degree = 90;
			}
			else if ($exifData['Orientation'] == 3)
			{
				$degree = 180;
			}
			 
			if($degree) {
				if($exifData['FileType'] == 1)
				{
					$source = imagecreatefromgif($imgUrl);
					$source = imagerotate ($source , $degree, 0);
					imagegif($source, $imgUrl);
				}
				else if($exifData['FileType'] == 2)
				{
					$source = imagecreatefromjpeg($imgUrl);
					$source = imagerotate ($source , $degree, 0);
					imagejpeg($source, $imgUrl);
				}
				else if($exifData['FileType'] == 3)
				{
					$source = imagecreatefrompng($imgUrl);
					$source = imagerotate ($source , $degree, 0);
					imagepng($source, $imgUrl);
				}

				imagedestroy($source);
			}
		}

		//echo $exifData['Orientation'];
		//exit;
		//####################################시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨 END####################################//


		$IMG_1=getimagesize("".$imgUrl."");
		
		$imageWidth=$IMG_1[0];
		$imageHeight=$IMG_1[1];

		if ($imageWidth > $imgWidth) {
			$imageWidth_down = $imgWidth;
				
			//실제넓이:실제높이= $imageWidth_down : $imgHeight_down
			//imageHeight_down = $imageHeight * $imageWidth_down / $imageWidth;
			$imageHeight_down = round($imageHeight * $imageWidth_down / $imageWidth);
		}else{
			$imageWidth_down = $imageWidth;
			$imageHeight_down = $imageHeight;
		}
	}else{
		$imageWidth_down = 0;
		$imageHeight_down = 0;
	}

	return array($imageWidth_down,$imageHeight_down);
}

//---------------------------------------------------------------------------------------------------
// 이미지 변환 함수 마침
//---------------------------------------------------------------------------------------------------












//===관리자 접속권한 START
function adminConAuth($connectAuth,$popup="N") {
	//if(!preg_match("/@@".$_SESSION["session_admin_auth"]."@@/",$connectAuth)) {
	if(!preg_match("/".$_SESSION["session_admin_auth"]."/",$connectAuth)) {
		if($popup=="N") {
			ERROR_BACK("접속 권한이 없습니다.");
		}else{
			windowClose("접속 권한이 없습니다.");
		}
	}
}
//===관리자 접속권한 END



//===관리자 접속권한 아이디별 START
function adminConAuth2($connectAdminId,$popup="N") {
	if($_SESSION["session_admin_id"]!=$connectAdminId && $_SESSION["session_admin_id"]!="admin") {
		if($popup=="N") {
			ERROR_BACK("접속 권한이 없습니다.");
		}else{
			windowClose("접속 권한이 없습니다.");
		}
	}
}
//===관리자 접속권한 아이디별 END
?>