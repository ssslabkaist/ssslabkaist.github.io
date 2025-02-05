<?
/*
if(!$part_part_s) {
	$part_part_s=date("Y");
}

$pm = "2.1";
//$pm = "4.".substr($part_part_s,1,1).".1";
*/

$pm = "4.2.1";
if($pm) {
	$_SESSION["pm_sess"] = $pm;
}


$subquery ="tn=".$tn."&list_count_s=".$list_count_s."&G_state=".$G_state."&pm=".$_SESSION["pm_sess"]."&part_part_s=".$part_part_s."&subject_s=".urlencode($subject_s)."&content_s=".urlencode($content_s)."&name_s=".urlencode($name_s)."&writedate_s=".$writedate_s."&keyfield_s=".urlencode($keyfield_s)."&search_word_s=".urlencode($search_word_s)."";


$query = mysql_query("SELECT * FROM board_admin WHERE table_name = '".$_REQUEST['tn']."'", $dbconn);
$row = mysql_fetch_object($query);
if (!$row) {
	echo(" 게시판 데이타 쿼리 에러 ");
}
$q_subject = $row->subject;
$list_count = $row->list_count;
$page_count = $row->page_count;
$upload = $row->upload;
$tag_admin = $row->tag_admin;
$gonggi_admin = $row->gonggi_admin;
$secret_admin = $row->secret_admin;
$list_auth = $row->list_auth;
$view_auth = $row->view_auth;
$write_auth = $row->write_auth;
$modify_auth = $row->modify_auth;
$reply_auth = $row->reply_auth;
$delete_auth = $row->delete_auth;
$comment_yorn = $row->comment_yorn;
$comment_view_auth = $row->comment_view_auth;
$comment_write_auth = $row->comment_write_auth;


if($_SESSION["session_admin_auth"]<"B") {
	$adminAuth = "Y";
}else{
	$adminAuth = "N";
}

if($_SESSION["session_admin_auth"]<="A") {
	$adminAuthList = "Y";
}else{
	$adminAuthList = "N";
}




$boardUsrAuth = "@@".$_SESSION["session_usr_auth"]."@@";

/*
$q_subject = $row->subject;
$title_bg = $row->title_bg;
$line1_bg = $row->line1_bg;
$line2_bg = $row->line2_bg;
$sel_bg = $row->sel_bg;
$com_bg = $row->com_bg;
$list_count = $row->list_count;
$page_count = $row->page_count;
$align = $row->align;
$twidth = $row->twidth;
$upload = $row->upload;			 /그대로 가는 기능
$tag_admin = $row->tag_admin;
$write_admin = $row->write_admin;
$write_member = $row->write_member;
$gonggi_admin = $row->gonggi_admin;	/그대로 가는 기능

$comment_admin = $row->comment_admin;	/코멘트 사용여부
$comment_write_admin = $row->comment_write_admin;	/코멘트 사용권한

$secret_admin = $row->secret_admin;	/변경되는 기능
$secret_admin_all = $row->secret_admin_all;	/변경되는 기능
$gallery_board = $row->gallery_board;
*/

$fileUpDownFullPath = $_SERVER["DOCUMENT_ROOT"]."/program_file/board/".$tn;
$fileUpDownPath = "/program_file/board/".$tn;
?>