<?session_start(); 
include "../config/dbconn.inc.php";
include "../config/functions.inc";

include "./config/common.php";



if($delete_auth) {
	if(!preg_match("/".$boardUsrAuth."/",$delete_auth) && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("삭제 권한이 없습니다.");
	}
}


include "top.html";


$query = mysql_query("SELECT pwd FROM `".$tn."` WHERE uid='".$uid."'", $dbconn);
if(!$query) {
	echo("query_error");
}
$row = mysql_fetch_row($query);

//db_close($dbconn);
?>

<script language="javascript">
<!--
function check_form(){
	form=document.del;
	if(!form.pass.value){
		alert('비밀번호를 입력하세요.');
		form.pass.focus();
		return;
	}

	form.submit();
}

//-->
</script>


<form action="./process_board.php" method="post" name="del" onSubmit="return check_form()">
<input type="hidden" name="mode" value="delPwd">
<input type="hidden" name="uid" value="<?echo $uid?>">
<input type="hidden" name="cpage" value="<?echo $cpage?>">
<input type="hidden" name="spage" value="<?echo $spage?>">

<? include "./config/input_hidden.html";?>

<table style="width:100%;">
	<tr>
		<td>
			<table style="width:100%;" height="40">
				<tr> 
					<td style="border:4px solid #E4E5E8; padding:20px;"> 
						<TABLE style="width:100%;">
							<TR>
								<TD align="center" style="text-align:center;">
									<div style="padding-bottom:20px;">비밀번호를 입력하세요.</div>

									<div>
										<div class="col-sm-12">
												<input type="password" class="txt_center w100" maxlength="10" name="pass" <?if ($_SESSION["session_admin_id"] && $adminAuth=="Y") { echo "value='".$row[0]."'"; }?>>
										</div>
									</div>

									<div class="btn_box" style="border-top:none;">
										<ul>
											<li><a class="btn bg_blue" href="#void" onclick="check_form();">삭제완료</a></li>
											<li><a class="btn" href="./list.php?tn=<?=$tn?>&spage=<?=$spage?>&cpage=<?=$cpage?>&<?=$subquery?>">취소</a></li>
										</ul>
									</div>
								</td>
							</tr>
						</TABLE>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>




<?include "bottom.html";?>