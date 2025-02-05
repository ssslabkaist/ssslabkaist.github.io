<?header("Content-type: text/xml; charset=utf-8");
echo "<?xml version='1.0' encoding='utf-8' ?>\n";

@session_start();

$Comment_Table_Name = $ax_tn."_comment";


include "../config/dbconn_2.inc.php";
include "../config/functions.inc";



$autoDefence = autoDefence();
$Word_Chk = $autoDefence[0];
$byte_chk = $autoDefence[1];

?><response><![CDATA[

<?if($ax_c_mode=="cmtRegReg") {?>
<form name="frmCmtReName" method="post" action="./process_board.php">
	<input type="hidden" name="mode" value="endComment">
	<input type="hidden" name="uid" value="<?=$uid?>">
	<input type="hidden" name="tn" value="<?=$ax_tn?>" />
	<input type="hidden" name="sid" value="<?=$ax_sid?>" />
	<input type="hidden" name="buid" value="<?=$ax_buid?>" />
	<input type="hidden" name="c_mode" value="<?=$ax_c_mode?>" />
	<input type="hidden" name="c_sid" value="<?=$ax_c_sid?>" />
	<input type="hidden" name="c_gid" value="<?=$ax_c_gid?>" />
	<input type="hidden" name="c_uid" value="<?=$ax_c_uid?>" />
	<input type="hidden" name="c_level" value="<?=$ax_c_level?>" />


	<!-- 코멘트 답글 -->
	<div class="comment box">
		<div class="form-group user_box">
<?if(!$_SESSION["session_usr_id"]) {?>
			<div class="col-xs-6 col-sm-2">
				<label for="usr">Name</label>
				<input type="text" class="form-control input-sm" id="usr" placeholder="이름" maxlength="20" name="comment_name">
			</div>
			<div class="col-xs-6  col-sm-2">
				<label for="pwd">Password</label>
				<input type="password" class="form-control input-sm" id="pwd" placeholder="패스워드" maxlength="10" name="pass">
			</div>
			<div class="col-xs-3 col-sm-2 redcode">
				<input type="hidden" name="byte_chk" value="<?=$byte_chk?>">
				<div><?=$Word_Chk?></div>
			</div>
			<div class="col-xs-3 col-sm-2">
				<label for="redcode">자동입력방지코드</label>
				<input type="text" class="form-control input-sm" id="redcode" maxlength="10" name="chk_chk">
			</div>
			<div class="col-xs-6 col-sm-4 msg">빨간색 숫자만 순서대로 입력하세요.</div>

<?}else{?>
			<div class="col-xs-6 col-sm-2">
				<label for="usr">Name</label>
				<input type="text" class="form-control input-sm" id="usr" placeholder="이름" disabled value="<?=inputTextPrint($_SESSION["session_usr_name"])?>">
			</div>
			<div style="clear:both; height:0px; padding:0;"></div>
			<input type="hidden" name="comment_name" value="<?=inputTextPrint($_SESSION["session_usr_name"])?>" />
			<input type="hidden" name="pass" value="<?=$_SESSION["session_usr_id"]?>" />
<?}?>
			<div class="col-xs-12 col-sm-10 textarea">
				<label for="comment">Comment</label>
				<textarea class="form-control" rows="3" id="comment" name="c_content"></textarea>
			</div>
			<div class="col-xs-12 col-sm-2 btnBox">
				<a class="btnOk" href="#void" onclick="frmCmtReChk();">등록</a>
			</div>
		</div>
	</div>
	<div style="height:10px;"></div>
	<!-- //comment -->
</form>
<?}?>


<?
if($ax_c_mode=="cmtMod") {
	
	$sql = mysql_query("SELECT c_content,pass,comment_name FROM $Comment_Table_Name WHERE uid='".$ax_c_uid."'",$dbconn);
	$row = mysql_fetch_row($sql);


	###########################비밀글일 경우 comment_xml.php 파일을 직접 치고 들어 올 경우에 코멘트 글 보기 제한 START#############################
	if($_SESSION["session_admin_auth"]<"B") {
		$adminAuth = "Y";
	}else{
		$adminAuth = "N";
	}

	$commentModYorN = "Y";	//수정내용 출력여부

	$chk_sql = mysql_query("SELECT member_id,secret_status,gonggi_status,gid FROM ".$ax_tn." WHERE uid='".$uid."'",$dbconn);
	$chk_row = mysql_fetch_row($chk_sql);
	//echo $_SESSION["inquire_pwd"];
	if($chk_row[1]=="Y" && $chk_row[2]=="N") {

		if(!$_SESSION["inquire_pwd"]) {
			if($chk_row[0]==$_SESSION["session_usr_id"]) {
				$_SESSION["inquire_pwd"]=$_SESSION["session_usr_id"];
			}
		}

		if (!$_SESSION["inquire_pwd"] && (!$_SESSION["session_admin_id"] || $adminAuth!="Y")) {
			$commentModYorN = "N";	//수정내용 출력여부
		}else{
			if ((!$_SESSION["session_admin_id"] || $adminAuth!="Y") && $_SESSION["inquire_pwd"]!="admin") {
				$psql=mysql_query("SELECT uid FROM ".$ax_tn." WHERE uid='".$uid."' AND (pwd='".$_SESSION["inquire_pwd"]."' OR member_id='".$_SESSION["inquire_pwd"]."' OR pwd='".$_SESSION["session_usr_id"]."' OR member_id='".$_SESSION["session_usr_id"]."')",$dbconn);
				$prow=mysql_fetch_row($psql);


				$pQuery2 ="SELECT pwd FROM ".$ax_tn." WHERE member_id='".$_SESSION["session_usr_id"]."' AND member_id<>'' AND gid='".$chk_row[3]."' AND level='0'";
				$pSql2 = mysql_query($pQuery2,$dbconn);
				$pRow2 = mysql_fetch_row($pSql2);


				if (!$prow[0] && !$pRow2[0]) {
					$commentModYorN = "N";	//수정내용 출력여부
				}
			}
		}
	}

	if($commentModYorN=="N") {
		$row[0] = "";			//코멘트 내용 없애기
	}
	###########################비밀글일 경우 comment_xml.php 파일을 직접 치고 들어 올 경우에 코멘트 글 보기 제한 END#############################
?>
<form name="frmCmtModName" method="post" action="./process_board.php">
	<input type="hidden" name="mode" value="endComment">
	<input type="hidden" name="uid" value="<?=$uid?>">
	<input type="hidden" name="tn" value="<?=$ax_tn?>" />
	<input type="hidden" name="sid" value="<?=$ax_sid?>" />
	<input type="hidden" name="buid" value="<?=$ax_buid?>" />
	<input type="hidden" name="c_mode" value="<?=$ax_c_mode?>" />
	<input type="hidden" name="c_sid" value="<?=$ax_c_sid?>" />
	<input type="hidden" name="c_gid" value="<?=$ax_c_gid?>" />
	<input type="hidden" name="c_uid" value="<?=$ax_c_uid?>" />
	<input type="hidden" name="c_level" value="<?=$ax_c_level?>" />
	


	<!-- 코멘트 수정 -->
	<div class="comment box">
		<div class="form-group user_box">


<?if($_SESSION["session_usr_id"]==$row[1] || $_SESSION["session_admin_id"]) {?>
			<div class="col-xs-6 col-sm-2">
				<label for="usr">Name</label>
				<input type="text" class="form-control input-sm" id="usr" placeholder="이름" disabled value="<?=inputTextPrint($row[2])?>">
			</div>
			<div style="clear:both; height:0px; padding:0;"></div>
			<input type="hidden" name="comment_name" value="<?=inputTextPrint($row[2])?>" />
			<input type="hidden" name="pass" value="." />
<?}else{?>
			<div class="col-xs-4 col-sm-2">
				<label for="usr">Name</label>
				<input type="text" class="form-control input-sm" id="usr" placeholder="이름" maxlength="20" name="comment_name" disabled value="<?=inputTextPrint($row[2])?>">
			</div>
			<div class="col-xs-4  col-sm-2">
				<label for="pwd">Password</label>
				<input type="password" class="form-control input-sm" id="pwd" placeholder="패스워드" maxlength="10" name="pass">
			</div>
			<input type="hidden" name="byte_chk" value="<?=$byte_chk?>">
			<input type="hidden" name="chk_chk" value="<?=$byte_chk?>">
<?}?>


			<div class="col-xs-12 col-sm-10 textarea">
				<label for="comment">Comment</label>
				<textarea class="form-control" rows="3" id="comment" name="c_content"><?=stripSlashNotUsed($row[0])?></textarea>
			</div>
			<div class="col-xs-12 col-sm-2 btnBox">
				<a class="btnOk" href="#void" onclick="frmModChk();">수정</a>
			</div>
		</div>
	</div>
	<div style="height:10px;"></div>
	<!-- //comment -->	
</form>
<?}?>





<?if($ax_c_mode=="cmtDel") {
	
	$sql = mysql_query("SELECT c_content,pass,comment_name FROM $Comment_Table_Name WHERE uid='".$ax_c_uid."'",$dbconn);
	$row = mysql_fetch_row($sql);
?>
<form name="frmCmtDelName" method="post">
	<input type="hidden" name="uid" value="<?=$uid?>">
	<input type="hidden" name="tn" value="<?=$ax_tn?>" />
	<input type="hidden" name="sid" value="<?=$ax_sid?>" />
	<input type="hidden" name="buid" value="<?=$ax_buid?>" />
	<input type="hidden" name="c_mode" value="<?=$ax_c_mode?>" />
	<input type="hidden" name="c_sid" value="<?=$ax_c_sid?>" />
	<input type="hidden" name="c_gid" value="<?=$ax_c_gid?>" />
	<input type="hidden" name="c_uid" value="<?=$ax_c_uid?>" />
	<input type="hidden" name="c_level" value="0" />

	<!-- 코멘트 삭제 -->
	<div class="comment box">
		<span class="cmt fs12 red">게시물을 삭제합니다</span>
		<div class="form-group user_box">
			<div class="col-xs-4">
				<label for="usr">Name</label>
				<input type="text" class="form-control input-sm" id="usr" placeholder="이름" disabled value="<?=inputTextPrint($row[2])?>">
			</div>
			<div class="col-xs-4">
				<label for="pwd">Password</label>
				<input type="password" class="form-control input-sm" id="pwd" placeholder="패스워드" maxlength="10" name="pass">
			</div>									
			<div class="col-xs-4">
				<a class="btn btn-normal btn-sm" href="#void" onclick="deleteCommentEbiNoMem('./process_board.php?mode=delComment&tn=<?=$ax_tn?>&uid=<?=$uid?>&sid=<?=$ax_sid?>&c_uid=<?=$ax_c_uid?>&buid=<?=$ax_buid?>');">삭제</a>
			</div>
		</div>
	</div>
	<div style="height:10px;"></div>
	<!-- //comment -->

</form>
<?}?>



]]></response>