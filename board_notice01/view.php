<?session_start();
ob_start();
include "../config/dbconn.inc.php";
include "../config/functions.inc";


include "config/common.php";


if($view_auth) {
	if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("접근 권한이 없습니다.");
	}
}

$bSql = mysql_query("SELECT sid FROM `".$tn."` WHERE uid='".$uid."'",$dbconn);
$bRow = mysql_fetch_row($bSql);
if(!$bRow[0]) {
	ERROR_BACK("잘못된 접속 경로입니다.");
	exit;
}else{
	$sid = $bRow[0];
}


baViewAuthKor($tn,$uid);		//view.php 접근 및 답변 권한


$query = mysql_query("SELECT * FROM `".$tn."` WHERE sid='".$sid."'", $dbconn);
$row = mysql_fetch_object($query);

if (!$row) {
	echo(" 쿼리 에러 ");
}

$v_uid = $row->uid;
$v_sid = $row->sid;
$v_gid = $row->gid;
$v_level = $row->level;
$v_name = $row->name;
$v_email = $row->email;
$v_homepage = $row->homepage;
$v_subject = $row->subject;
$v_content = $row->content;
$v_upfile = $row->upfile;
$v_upfile2 = $row->upfile2;
$v_upfile3 = $row->upfile3;

$v_upfile_name = $row->upfile_name;
$v_upfile2_name = $row->upfile2_name;
$v_upfile3_name = $row->upfile3_name;

$v_writedate = $row->writedate;
$v_ref = $row->ref;
$v_tag = $row->tag;
$v_gonggi_status = $row->gonggi_status;

$v_member_id = $row->member_id;
$v_pwd = $row->pwd;

if($v_member_id=="admin") {
	//$v_name="<img src=\"/newhome/img/icon/admin.gif\" border=\"0\">";
	$v_name=$v_name;
}else{
	$v_name=$v_name;
}


$v_writedateA = explode(" ",$v_writedate);
$v_writedate = $v_writedateA[0];
$v_writedate = str_replace("-",".",$v_writedate);

$v_homepage = preg_replace( "([a-z0-9\_\-\./~@?=%&\-]+)", "<a href=http://\\1 target=_blank>http://\\1 </a>", $v_homepage); 
$v_subject = stripSlashNotUsed($v_subject);	


$u_ref = $v_ref+1;

$sql = mysql_query("UPDATE `".$tn."` SET ref='".$u_ref."' WHERE sid='".$sid."'", $dbconn);
if(!$sql) {
	echo("query_error");
}

include "top.html";
?>

<script>
function DEL(url) {
	if(!confirm("목록에서 삭제됩니다. 복구가 불가능합니다. 삭제하시겠습니까?")){
		return;
	}

	location.href=url;
}
</script>

<script src="/js/comment.js"></script>
<script src="/js/program.js"></script>





		<div class="view_normal_head">
			<p class="title blue"><?=$v_subject?></p>
            <div class="data">
                <p>
					<?if($v_email && $_SESSION["session_usr_id"]){ echo "<a href=\"mailto:".$v_email."\">".$v_name."</a>"; } else { echo "".$v_name.""; }?>&nbsp; ㅣ &nbsp;<?=$v_writedate?>&nbsp; ㅣ &nbsp;조회:<?=$v_ref?>
<?/*
					<a class="btn_print pc" href="#void" alt="인쇄" title="인쇄"  onclick="window.open('../board/print/print_popup.html?tn=<?=$tn?>&uid=<?=$v_uid?>','popup','scrollbars=yes,resizable=no, width=950, height=700'); return false;"></a>
*/?>

				</p>
    
    
    <?
    if ($v_upfile || $v_upfile2 || $v_upfile3) {
        echo "
                <p class=\"files\">";
    
        if($v_upfile) {
                    file_download_name($v_upfile,$fileUpDownPath,$v_upfile_name,"",$view_auth);
					echo " &nbsp;&nbsp; ";
        }
        if($v_upfile2) {
                    file_download_name($v_upfile2,$fileUpDownPath,$v_upfile2_name,"",$view_auth);
					echo " &nbsp;&nbsp; ";
        }
        if($v_upfile3) {
                    file_download_name($v_upfile3,$fileUpDownPath,$v_upfile3_name,"",$view_auth);
        }
    
        echo "
                </p>";
    }
    ?>
            </div>
        </div>

		<div class="view_normal_con">
<!--상세 START-->
			<script language="JavaScript">
			$(document).ready(function () {
				var windowWidth = $(window).width();
				$(window).resize(function(){
					var win = $(window).width();
					if(windowWidth != win) {
						windowWidth = win;
						document.getElementById('v_content_id').contentWindow.parentOnload();
					}
				});
			});
			$(window).load(function(){
				tid = setInterval(vContentSizeTime(), 1000);
				clearInterval(tid);
			});
			function vContentSizeTime() {
				document.getElementById('v_content_id').contentWindow.parentOnload();
			}
			</script>


			<script language="JavaScript">
			function vContentSize(bodyHeight){
				document.getElementById("v_content_id").height=bodyHeight;
			}
			</script>
			<iframe id="v_content_id" name="v_content_name" width="100%" scrolling="no" frameborder="0" marginwidth="0" src="./v_content_iframe.html?sid=<?=$sid?>&tn=<?=$tn?>"></iframe>
<!--상세 END-->
		</div>






		<!-- 버튼 -->
		<div class="btn_box">
			<ul>
<?
echo "
				<li><a class=\"btn\" href=\"./list.php?".$subquery."&cpage=".$cpage."&spage=".$spage."\">LIST</a></li>";

if($_SESSION["session_admin_id"] && $adminAuth=="Y") {
/*
				<li><a class=\"btn\" href=\"./reply.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">답변</a></li>
				<li><a class=\"btn\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>
				<li><a class=\"btn\" href=\"./delete.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">삭제</a></li>*/

/*
				<li><a class=\"btn\" href=\"./reply.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">답변</a></li>
*/

	echo "
				<li><a class=\"btn bg_green\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">MODIFY</a></li>
				<li><a class=\"btn bg_red\" href=\"./delete.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">DELETE</a></li>";
	/*
				<li><a class=\"btn\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>
				<li><a class=\"btn\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">삭제</a></li>
				<li><a class=\"btn\" href=\"./reply.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">답변</a></li>";
	*/
}else{

/*
	if($reply_auth) {
		if(preg_match("/".$boardUsrAuth."/",$reply_auth) || $_SESSION["session_admin_id"]) {
			echo "
				<li><a class=\"btn\" href=\"./reply.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">답변</a></li>";
		}
	}else{
		echo "
				<li><a class=\"btn\" href=\"./reply.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">답변</a></li>";
	}
*/

	if ($chk_row[0]!="admin") {

		
		if($modify_auth) {
			if(preg_match("/".$boardUsrAuth."/",$modify_auth) || $_SESSION["session_admin_id"]) {
				if($v_member_id) {		//수정
					if($_SESSION["session_usr_id"]) {
						if($v_member_id==$session_usr_id) {
							echo "
				<li><a class=\"btn bg_green\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>";
						}
					}			
				}else{
					echo "
				<li><a class=\"btn bg_green\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>";
				}
			}
		}else{
			if($v_member_id) {		//수정
				if($_SESSION["session_usr_id"]) {
					if($v_member_id==$session_usr_id) {
						echo "
				<li><a class=\"btn bg_green\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>";
					}
				}			
			}else{
				echo "
				<li><a class=\"btn bg_green\" href=\"./modify.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">수정</a></li>";
			}
		}


		if($delete_auth) {
			if(preg_match("/".$boardUsrAuth."/",$delete_auth) || $_SESSION["session_admin_id"]) {
				if($v_member_id) {		//삭제
					if($_SESSION["session_usr_id"]) {
						if($v_member_id==$session_usr_id) {
							echo "
				<li><a class=\"btn bg_red\" href=\"#void\" onclick=\"DEL('./process_board.php?mode=delView&".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."');\">삭제</a></li>";
						}
					}
				}else{
					echo "
				<li><a class=\"btn bg_red\" href=\"./delete.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">삭제</a></li>";
				}
			}
		}else{
			if($v_member_id) {		//삭제
				if($_SESSION["session_usr_id"]) {
					if($v_member_id==$session_usr_id) {
						echo "
				<li><a class=\"btn bg_red\" href=\"#void\" onclick=\"DEL('./process_board.php?mode=delView&".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."');\">삭제</a></li>";
					}
				}
			}else{
				echo "
				<li><a class=\"btn bg_red\" href=\"./delete.php?".$subquery."&uid=".$v_uid."&cpage=".$cpage."&spage=".$spage."\">삭제</a></li>";
			}
		}
	}
}
?>

			</ul>
		</div>
		<div class="h50"></div>


<?
$comment_view_yorn="N";

if($comment_yorn=="Y"){
	if($comment_view_auth) {
		if(preg_match("/".$boardUsrAuth."/",$comment_view_auth) || $_SESSION["session_admin_id"]) {
			$comment_view_yorn="Y";
		}
	}else{
		$comment_view_yorn="Y";
	}
}

if($comment_view_yorn=="Y") {
	$Comment_Table_Name = $tn."_comment";

	$ctnRow = mysql_fetch_row(mysql_query("SELECT COUNT(uid) FROM `".$Comment_Table_Name."` WHERE buid='".$v_uid."'",$dbconn));

	$autoDefence = autoDefence();
	$Word_Chk = $autoDefence[0];
	$byte_chk = $autoDefence[1];
?>

<script>
var submitNum = 0;
function frmCmtChk() {
	form1=document.frmCmtRegName;

	if(submitNum==1) {
		window.alert("코멘트를 등록중입니다.");
		return;
	}

	<?if(!$_SESSION["session_usr_id"]) {?>
	if(!form1.comment_name.value) {
		window.alert("이름을 입력하세요");
		form1.comment_name.focus();
		return;
	}

	if(!form1.pass.value) {
		window.alert("비밀번호를 입력하세요");
		form1.pass.focus();
		return;
	}

	if(!form1.chk_chk.value) {
		window.alert('자동등록 방지 필수 입력입니다.');
		form1.chk_chk.focus();
		return;
	}
	if(form1.byte_chk.value!=form1.chk_chk.value) {
		window.alert('자동등록방지용 파란색 숫자가 순서대로 입력되지 않았습니다.');
		form1.chk_chk.focus();
		return;
	}
	<?}?>

	if (!form1.c_content.value) {
		window.alert("내용을 입력하세요");
		form1.c_content.focus();
		return;
	}

	submitNum=1;
	form1.submit();
}
</script>





<script>
var submitNum = 0;
function frmCmtReChk() {
	form1=document.frmCmtReName;

	if(submitNum==1) {
		window.alert("코멘트를 등록중입니다.");
		return;
	}

	<?if(!$_SESSION["session_usr_id"]) {?>
	if(!form1.comment_name.value) {
		window.alert("이름을 입력하세요");
		form1.comment_name.focus();
		return;
	}

	if(!form1.pass.value) {
		window.alert("비밀번호를 입력하세요");
		form1.pass.focus();
		return;
	}

	if(!form1.chk_chk.value) {
		window.alert('자동등록 방지 필수 입력입니다.');
		form1.chk_chk.focus();
		return;
	}
	if(form1.byte_chk.value!=form1.chk_chk.value) {
		window.alert('자동등록방지용 파란색 숫자가 순서대로 입력되지 않았습니다.');
		form1.chk_chk.focus();
		return;
	}
	<?}?>

	if (!form1.c_content.value) {
		window.alert("내용을 입력하세요");
		form1.c_content.focus();
		return;
	}

	submitNum=1;
	form1.submit();
}
</script>


<script>
function frmModChk() {
	form1=document.frmCmtModName;
	var len = form1.elements.length;

	if(!form1.comment_name.value) {
		window.alert("이름을 입력하세요.");
		return;
	}
	if(!form1.pass.value) {
		window.alert("비밀번호를 입력하세요.");
		return;
	}
	if (!form1.c_content.value) {
		window.alert("내용을 입력하세요.");
		form1.c_content.focus();
		return;
	}

	form1.submit();
}
</script>

<script>
function deleteCommentEbi(url){
	if(!confirm("작성한 글을 삭제 하시겠습니까?")) {
		return;
	}

	location.href = url;
}
</script>


<script>
function deleteCommentEbiNoMem(url){

	form1=document.frmCmtDelName;

	if(!form1.pass.value) {
		window.alert("비밀번호를 입력하세요.");
		form1.pass.focus();
		return;
	}


	if(!confirm("작성한 글을 삭제 하시겠습니까?")) {
		return;
	}

	location.href = url+"&pass="+form1.pass.value;
}
</script>
	
		<div class="cmt_box">
			<div class="title">댓글&nbsp;:&nbsp;<span class="red"><?=number_format($ctnRow[0])?>개</span></div>
			<span class="fs12">저작권 등 다른 사람의 권리를 침해하거나 명예를 훼손하는 게시물은 이용약관 및 관련법률에 의해 제재를 받으실 수 있습니다.</span>

<?
	$commentWriteView = "N";
	if($comment_write_auth) {
		if(preg_match("/".$boardUsrAuth."/",$comment_write_auth) || $_SESSION["session_admin_id"]) {
			$commentWriteView = "Y";
		}
	}else{
		$commentWriteView = "Y";
	}
?>


<?
/* 코멘트 쓰기 START */
	if($commentWriteView=="Y") {
?>
<form class="type1" name="frmCmtRegName" method="post" action="./process_board.php">
<input type="hidden" name="mode" value="endComment">
<input type="hidden" name="uid" value="<?=$uid?>">
<input type="hidden" name="tn" value="<?=$tn?>" />
<input type="hidden" name="sid" value="<?=$sid?>" />
<input type="hidden" name="buid" value="<?=$uid?>" />
<input type="hidden" name="c_mode" value="cmtReg" />
<input type="hidden" name="c_sid" value="" />
<input type="hidden" name="c_gid" value="" />
<input type="hidden" name="c_uid" value="" />
<input type="hidden" name="c_level" value="0" />

			<div class="comment box">
				<div class="user_box">

		<?if(!$_SESSION["session_usr_id"]) {?>
					<div class="col-xs-6 col-sm-2">
						<label for="usr">Name</label>
						<input type="text" id="usr" placeholder="이름" maxlength="20" name="comment_name">
					</div>
					<div class="col-xs-6  col-sm-2">
						<label for="pwd">Password</label>
						<input type="password" id="pwd" placeholder="패스워드" maxlength="10" name="pass">
					</div>
					<div class="col-xs-3 col-sm-2 redcode">
						<input type="hidden" name="byte_chk" value="<?=$byte_chk?>">
						<div><?=$Word_Chk?></div>
					</div>
					<div class="col-xs-3 col-sm-2">
						<label for="redcode">자동입력방지코드</label>
						<input type="text" id="redcode" maxlength="10" name="chk_chk">
					</div>
					<div class="col-xs-6 col-sm-4 fs12">파란색 숫자만 순서대로 입력하세요.</div>

		<?}else{?>
					<div class="col-xs-6 col-sm-2">
						<label for="usr">Name</label>
						<input type="text" id="usr" placeholder="이름" disabled value="<?=inputTextPrint($_SESSION["session_usr_name"])?>">
					</div>
					<div style="clear:both; height:0px; padding:0;"></div>
					<input type="hidden" name="comment_name" value="<?=inputTextPrint($_SESSION["session_usr_name"])?>" />
					<input type="hidden" name="pass" value="<?=$_SESSION["session_usr_id"]?>" />
		<?}?>
					<div class="col-xs-12 col-sm-10 textarea">
						<label for="comment">Comment</label>
						<textarea rows="3" id="comment" name="c_content"></textarea>
					</div>

					<div class="col-xs-12 col-sm-2 btnBox">
						<a class="btnOk" href="#void" onclick="frmCmtChk();">등록</a>
					</div>
				</div>
			</div><!-- //comment -->
</form>
<?
	}
/* 코멘트 쓰기 END */
?>




<?


echo "
			<!-- 코멘트 리스트 -->
			<div class=\"list\">
				<ul>";



$buid = $v_uid;
$c_Num = 0;

$cSql = mysql_query("SELECT c_gid FROM ".$Comment_Table_Name." WHERE buid='".$v_uid."' GROUP BY c_gid ORDER BY c_sid ASC",$dbconn);
while($cRow = mysql_fetch_row($cSql)) {




	$cSql2 = mysql_query("SELECT * FROM ".$Comment_Table_Name." WHERE buid='".$v_uid."' AND c_gid='".$cRow[0]."' ORDER BY c_sid ASC",$dbconn);
	while($cRow2 = mysql_fetch_array($cSql2)) {
		
		$maxCsql = mysql_query("SELECT MAX(c_sid) FROM ".$Comment_Table_Name." WHERE buid='".$v_uid."' AND INSTR(bc_uid,'||".$cRow2[uid]."||') AND c_level>'".$cRow2[c_level]."' ORDER BY c_level ASC",$dbconn);
		$maxCrow = mysql_fetch_row($maxCsql);

		if($maxCrow[0]){
			$r_c_sid = $maxCrow[0]+1;
		}else{
			$r_c_sid = $cRow2[c_sid]+1;
		}


		$cWrite = explode(" ",$cRow2[writedate]);
		$cDate = substr($cWrite[0],2,10);
		$cHour = substr($cWrite[1],0,5);
		
		$c_sid = $cRow2[c_sid];
		$c_gid = $cRow2[c_gid];
		$c_level = $cRow2[c_level]+1;
		$c_uid = $cRow2[uid];
	
		$paddingLeft = $cRow2[c_level]*20;

		$leftPadding = "style=\"padding-left:".$paddingLeft."px\"";
		
		$reName = "";
		if($cRow2["c_level"]==0){
			$divClass = "class=\"normal\"";
		}else if($cRow2["c_level"]==1){
			$divClass = "class=\"re_dep1\"";
		}else{
			$divClass = "class=\"re_dep1\"";
			$reName = "<span class=\"re\">(".borderCommentHighName($Comment_Table_Name,$cRow2["bc_uid"]).")</span>";
		}

		echo "
					<li>
						<div ".$divClass.">
							<div class=\"info\">
								<p>
									<span class=\"bold black\">".stripSlashNotUsed($cRow2["comment_name"])."</span><span class=\"date\">".$cDate." ".$cHour."</span>";

		if($comment_write_auth) {
			if(preg_match("/".$boardUsrAuth."/",$comment_write_auth) || $_SESSION["session_admin_id"]) {
				echo "
									<a class=\"btn bg_default btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtRegReg','".$buid."','".$r_c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">댓글</a>
									<a class=\"btn bg_green btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtMod','".$buid."','".$c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">수정</a>";
			}
		}else{
				echo "
									<a class=\"btn bg_default btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtRegReg','".$buid."','".$r_c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">댓글</a>
									<a class=\"btn bg_green btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtMod','".$buid."','".$c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">수정</a>";
		}


		if($_SESSION["session_admin_id"] && $adminAuth=="Y") {
			echo "
									<a class=\"btn bg_red btn-xs\" href=\"#void\" onclick=\"deleteCommentEbi('./process_board.php?mode=delComment&tn=".$tn."&uid=".$buid."&sid=".$sid."&c_uid=".$c_uid."&buid=".$buid."');\">삭제</a>";
		}else{
			if($comment_write_auth) {
				if(preg_match("/".$boardUsrAuth."/",$comment_write_auth) || $_SESSION["session_admin_id"]) {
					if($cRow2["member_id"]) {
						if($_SESSION["session_usr_id"]==$cRow2[member_id]) {
							echo "
									<a class=\"btn bg_red btn-xs\" href=\"#void\" onclick=\"deleteCommentEbi('./process_board.php?mode=delComment&tn=".$tn."&uid=".$buid."&sid=".$sid."&c_uid=".$c_uid."&buid=".$buid."');\">삭제</a>";
						}
					}else{
						echo "
									<a class=\"btn bg_red btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtDel','".$buid."','".$c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">삭제</a>";
					}
				}
			}else{
				if($cRow2["member_id"]) {
					if($_SESSION["session_usr_id"]==$cRow2[member_id]) {
						echo "
									<a class=\"btn bg_red btn-xs\" href=\"#void\" onclick=\"deleteCommentEbi('./process_board.php?mode=delComment&tn=".$tn."&uid=".$buid."&sid=".$sid."&c_uid=".$c_uid."&buid=".$buid."');\">삭제</a>";
					}
				}else{
					echo "
									<a class=\"btn bg_red btn-xs\" href=\"#void\" onclick=\"createCommentEbi('cmtDel','".$buid."','".$c_sid."','".$c_gid."','".$c_level."','".$c_uid."','".$c_Num."');\">삭제</a>";
				}
			}
		}


		echo "
								</p>							
								<p>".$reName."".nl2br(stripSlashNotUsed($cRow2["c_content"]))."</p>
							</div>
							<!--수정 START-->
							<div class=\"answ_01 cmtMod\"></div>
							<!--수정 END-->

							<!--답글 START-->
							<div class=\"answ_01 cmtRegReg\"></div>
							<!--답글 END-->

							<!--삭제 START-->
							<div class=\"answ_01 cmtDel\"></div>
							<!--삭제 END-->
						</div>
					</li>";

		$c_Num++;
	}
}

echo "
				</ul>
			</div>";
?>
		</div>
		<div style="height:20px;"></div>


<input type="hidden" name="uid" value="<?=$uid?>">
<input type="hidden" name="ax_tn" value="<?=$tn?>">
<input type="hidden" name="ax_sid" value="<?=$sid?>">
<input type="hidden" name="ax_buid" value="<?=$uid?>">
<input type="hidden" name="ax_c_mode" value="">
<input type="hidden" name="ax_c_sid" value="">
<input type="hidden" name="ax_c_gid" value="">
<input type="hidden" name="ax_c_level" value="">
<input type="hidden" name="ax_c_uid" value="">
<input type="hidden" name="ax_c_Num" value="">

<script>
function createCommentEbi(c_mode,buid,c_sid,c_gid,c_level,c_uid,c_Num) {

	document.getElementsByName("ax_c_mode")[0].value=c_mode;
	document.getElementsByName("ax_buid")[0].value=buid;
	document.getElementsByName("ax_c_sid")[0].value=c_sid;
	document.getElementsByName("ax_c_gid")[0].value=c_gid;
	document.getElementsByName("ax_c_level")[0].value=c_level;
	document.getElementsByName("ax_c_uid")[0].value=c_uid;
	document.getElementsByName("ax_c_Num")[0].value=c_Num;

	validate_Comment();
}
</script>

<?
/*
	if($comment_write_auth) {
		if(preg_match("/".$boardUsrAuth."/",$comment_write_auth) || $_SESSION["session_admin_id"]) {
			echo "
	<script language=\"javascript\">
		createCommentEbi('cmtReg','".$buid."','','','','','0');
	</script>";
		}
	}else{
			echo "
	<script language=\"javascript\">
		createCommentEbi('cmtReg','".$buid."','','','','','0');
	</script>";
	}
*/
}//댓글 END
?>



<?
include "list_in.php";	
include "bottom.html";
?>