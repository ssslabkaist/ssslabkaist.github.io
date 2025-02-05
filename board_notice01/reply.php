<?session_start();
include "../config/dbconn.inc.php";
include "../config/functions.inc";

include "./config/common.php";


if($reply_auth) {
	if(!preg_match("/".$boardUsrAuth."/",$reply_auth) && !$_SESSION["session_admin_id"]) {
		ERROR_BACK("접근 권한이 없습니다.");
	}
}

baReplyAuthKor($tn,$uid);		//reply.php 접근 및 답변 권한


if($_SESSION["session_usr_id"] || $_SESSION["session_admin_id"]) {
	if($_SESSION["session_admin_id"]){
		//$writer_name = $_SESSION["session_admin_name"];
		$writer_name = $_SESSION["session_usr_name"];
		$writer_email = $_SESSION["session_usr_email"];
		$nameReadonly="";
	}else{
		//$writer_name = $_SESSION["session_usr_name"];
		$writer_name = $_SESSION["session_usr_name"];
		$writer_email = $_SESSION["session_usr_email"];
		$nameReadonly="readonly";
	}
}

$query = mysql_query("SELECT sid,gid,level,subject,content,pwd,member_id,secret_status FROM `".$tn."` WHERE uid='".$uid."'", $dbconn);
if(!$query) {
	echo("query_error");
}
$row = mysql_fetch_row($query);

$r_subject = stripSlashNotUsed($row[3]);
$r_subject = htmlspecialchars($r_subject);
$m_content = stripSlashNotUsed($row[4]);


$autoDefence = autoDefence();
$Word_Chk = $autoDefence[0];
$byte_chk = $autoDefence[1];

	
$Memo = "첨부파일 3개를 합친 용량이 20메가 이하만 업로드 가능합니다. 20메가 이상시 업로드 불가능 합니다.";



include "top.html";
?>

<script language="javascript">
var frmFlag;
function confirmation() {
	
	actionNum=1;	//fixed 메뉴 포커스 관련
	
	var form1 = document.frmName;

	if(!form1.name.value) {
		window.alert("이름을 입력하세요.");
		form1.name.focus();
		return;
	}
<?/*
<?if($_SESSION["session_admin_id"]) {?>
	var patternDate = /[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/;

	if(!form1.writedate.value) {
		window.alert("작성일을 입력하세요");
		form1.writedate.focus();
		return;
	}

	if(!patternDate.test(form1.writedate.value)) {
		window.alert("작성일 입력 형식에 맞지 않습니다.");
		form1.writedate.focus();
		return;
	}
<?}?>
*/?>
	if(form1.email.value!=''){
		if (form1.email.value.indexOf('@') < 0 ) {
			window.alert('e-mail을 정확하게 입력하세요.');
			form1.email.focus();
			return;
		}
		if (form1.email.value.indexOf('.') < 0 ) {
			window.alert('e-mail을 정확하게 입력하세요.');
			form1.email.focus();
				return;
		}
	}
	if(!form1.pwd.value) {
		window.alert("비밀번호를 입력하세요.");
		form1.pwd.focus();
		return;
	}
	if(!form1.subject.value) {
		window.alert("제목을 입력하세요.");
		form1.subject.focus();
		return;
	}

	if(!form1.chk_chk.value){
		alert("자동등록 방지 필수 입력입니다.");
		form1.chk_chk.focus();
		return;
	}
	if(form1.byte_chk.value!=form1.chk_chk.value){
		alert("자동등록방지용 파란색 숫자가 순서대로 입력되지 않았습니다.");
		form1.chk_chk.focus();
		return;
	}

	/*
	var contentText = window.frames["myEditor"].document.getElementById("myEditor").innerText;
	if(!contentText) {
		window.alert('내용을 입력하세요.');
		return;
	}
	*/
	form1.content.value =  window.frames["myEditor"].document.getElementById("myEditor").innerHTML;
	if(window.frames["myEditor"].document.getElementsByName("switchMode")[0].checked==true) {
		window.alert('HTML 소스을 체크 해제한 후 등록 가능합니다.');
		window.frames["myEditor"].document.getElementsByName("switchMode")[0].focus();
		return;
	}
	if(!form1.content.value.replace(/<br *\/?>/gi,"")) {
		window.alert('내용을 입력하세요.');
		return;
	}

	if(!frmFlag){
		frmFlag=true;
		form1.submit();
	}else{
		window.alert("등록중입니다. 잠시만 기다려주세요.");
	}
}
</script>




<form class="type1" name="frmName" method="post" action="./process_board.php" onSubmit="return false;" ENCTYPE="multipart/form-data">
<input type="hidden" name="mode" value="endReply">
<input type="hidden" name="r_sid" value="<?=$row[0]?>">
<input type="hidden" name="r_gid" value="<?=$row[1]?>">
<input type="hidden" name="level" value="<?=$row[2]?>">
<input type="hidden" name="uid" value="<?=$uid?>">
<input type="hidden" name="cpage" value="<?=$cpage?>">
<input type="hidden" name="spage" value="<?=$spage?>">

<? include "./config/input_hidden.html";?>





		<div class="write_normal_head">
			<i class="pc"></i>
			<p>타 사이트에 등록된 글이나, 사진등을 사용할때 반드시 저작권 부분에 대해서 확인해주세요.<br>
			작성된 글에 대한 저작권 문제 발생시 모든 책임은 작성자에게 있음을 알려 드립니다.</p>
		</div>

		<div class="write_normal box">
			<div class="divinner col-xs-12 bor0">
				<label class="col-xs-12 col-sm-2 control-label">작성자</label>
				<div class="col-xs-12 col-sm-10">
					<input type="text" maxlength="10" name="name" value="<?=$writer_name?>" <?=$nameReadonly?>>
				</div>
			</div>
<?/*
<?if($_SESSION["session_admin_id"]) {?>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">작성일</label>
				<div class="col-xs-12 col-sm-10">
					<div class="col-xs-12 col-sm-3">
						<input type="text" name="writedate" maxlength="10" value="<?=date("Y-m-d")?>">
					</div>
					<p>반드시 (yyyy-mm-dd) 형식으로 입력하세요.</p>
				</div>
			</div>
<?}else{?>
			<input name="writedate" type="hidden" value="<?=date("Y-m-d")?>">
<?}?>
*/?>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">이메일</label>
				<div class="col-xs-12 col-sm-10 mailwrap">
					<input type="text" maxlength="40" name="email" value="<?=$writer_email?>">
				</div>
			</div>
<?
if (!$_SESSION["session_admin_id"] && !$_SESSION["session_usr_id"]) {	
	if($row[7]=="N") {
		echo "
			<div class=\"divinner col-xs-12\">
				<label class=\"col-xs-12 col-sm-2 control-label\">비밀번호</label>
				<div class=\"col-xs-12 col-sm-10\">
					<div class=\"col-xs-12 col-sm-5\">
						<input type=\"password\" maxlength=\"10\" name=\"pwd\">
					</div>
					<p>(수정/삭제/비밀글 확인시 필요)</p>
				</div>
			</div>";
	}else{
		if($row[5]) {
			echo "<input type=\"hidden\" name=\"pwd\" value=\"".$row[5]."\">";
		}else{
			echo "<input type=\"hidden\" name=\"pwd\" value=\"".$row[6]."\">";
		}
	}
}else{
	if ($row[7]=="N") {
		echo "
			<input type=\"hidden\" name=\"pwd\" value=\"".date("ymdHis")."\">";
	}else{
		if($row[5]) {
			echo "<input type=\"hidden\" name=\"pwd\" value=\"".$row[5]."\">";
		}else{
			echo "<input type=\"hidden\" name=\"pwd\" value=\"".$row[6]."\">";
		}
	}
}
?>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">글제목</label>
				<div class="col-xs-12 col-sm-10">
					<input type="text" maxlength="125" name="subject" value="<?=$r_subject?>">
				</div>
			</div>


<?if($_SESSION["session_admin_id"]) {?>
<script language="javascript">
function subjectColor(colorValue) {

	//window.alert(colorValue);
	document.getElementsByName('subject_color')[0].value = colorValue;
	//window.alert(document.getElementById('subject_color').value);

}
</script>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">제목 색상</label>
				<div class="col-xs-12 col-sm-5">
					<div class="col-xs-12 col-sm-6">
						<input type="text" placeholder="색상코드" name="subject_color" maxlength="7">
					</div>
					<p>직접 입력 가능합니다.</p>
				</div>
				<div class="col-xs-12 col-sm-5">
					<div class="col-xs-12">
						<select class="w100" name="subject_color_select" onChange="subjectColor(this.value);">
							<option value="">색상을 선택하세요</option>
							<option value="#FF0000">빨강</option>
							<option value="#0000FF">파랑</option>
						</select>
					</div>
				</div>
			</div>
<?}?>


<?if($_SESSION["session_admin_id"] || $_SESSION["session_usr_id"]) {?>
			<input type="hidden" name="byte_chk" value="<?=$byte_chk?>">
			<input type="hidden" name="chk_chk" value="<?=$byte_chk?>">
<?}else{?>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">자동입력방지</label>
				<div class="col-xs-12 col-sm-10">
					<div class="col-xs-6 col-sm-3 redcode">
						<div><?=$Word_Chk?></div>
					</div>
					<div class="col-xs-6 col-sm-3">
						<label for="redcode">코드</label>
						<input type="hidden" name="byte_chk" value="<?=$byte_chk?>">
						<input type="text" id="redcode" maxlength="10" name="chk_chk">
					</div>
					<p class="col-xs-12 col-sm-6">파란색 숫자만 순서대로 입력하세요.</p>
				</div>
			</div>
<?}?>
			<input type="hidden" name="tag" value="H">

<?
if ($_SESSION["session_admin_id"] && $gonggi_admin=="Y") {
	/*
	echo "
			<div class=\"form-group\">
				<label class=\"col-sm-2 control-label\">공지여부</label>
				<div class=\"col-sm-10\">
					<label class=\"radio-inline\"><input type=\"radio\" name=\"gonggi_status\" value=\"Y\">예</label>
					<label class=\"radio-inline\"><input type=\"radio\" name=\"gonggi_status\" value=\"N\" checked>아니요</label>
				</div>
			</div>";
	*/
	echo "
			<input type=\"hidden\" name=\"gonggi_status\" value=\"N\">";
}else{
	echo "
			<input type=\"hidden\" name=\"gonggi_status\" value=\"N\">";
}



if($secret_admin=="1") {
	echo "
			<input type=\"hidden\" name=\"secret_status\" value=\"N\">";
}else{
	if ($row[7]=="N") {
		echo "
			<input type=\"hidden\" name=\"secret_status\" value=\"N\">";
	}else{
		echo "
			<input type=\"hidden\" name=\"secret_status\" value=\"Y\">";
	}
}
?>
			<div class="h10"></div>
			<div>
				<div>
					<iframe src="/board/wysi/wysi_new.html?tableWidth=<?=$tableWidth?>" id="myEditorId" name="myEditor" frameborder="0" marginwidth="0" class="iframe_style" title="내용 입력란" style="width:100%;" scrolling="no"></iframe>
					<textarea style="display:none;" name="content"><?=$m_content?></textarea>

					<script language="JavaScript">
					$(window).load(function(){
						$(window).resize(function(){
							//window.alert($(window).width());
							if(992<=$(window).width()) {
								$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"500");
								document.getElementById("myEditorId").style.height = "537px";
							}else{
								$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"240");
								document.getElementById("myEditorId").style.height = "250px";
							}
						});

						
						if(992<=$(window).width()) {
							$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"500");
							document.getElementById("myEditorId").style.height = "537px";
						}else{
							$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"240");
							document.getElementById("myEditorId").style.height = "250px";
						}
					});

					function iframeReload() {
						//window.alert("aa");
						if(992<=$(window).width()) {
							$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"500");
							document.getElementById("myEditorId").style.height = "537px";
						}else{
							$("#myEditorId").get(0).contentWindow.vContentSize($(window).width(),"240");
							document.getElementById("myEditorId").style.height = "250px";
						}
					}
					</script>

				</div>
			</div>
			
<?if($upload=="T"){?>

			<div class="divinner col-xs-12 bor0">
				<label class="col-xs-12 col-sm-2 control-label">첨부파일1</label>
				<div class="col-xs-12 col-sm-10 ">
					<input type="file" name="file1" style="height:40px;" size="50">
				</div>
			</div>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">첨부파일2</label>
				<div class="col-xs-12 col-sm-10 ">
					<input type="file" name="file2" style="height:40px;" size="50">
				</div>
			</div>
			<div class="divinner col-xs-12">
				<label class="col-xs-12 col-sm-2 control-label">첨부파일3</label>
				<div class="col-xs-12 col-sm-10 ">
					<input type="file" name="file3" style="height:40px;" size="50">
					<p><?=$Memo?></p>
				</div>
			</div>
<?}?>

		</div>

		<!-- 버튼 -->
		<div class="btn_box">
			<ul>
				<li><a class="btn bg_blue" href="#void" onclick="confirmation();">답변완료</a></li>
				<li><a class="btn" href="./list.php?<?=$subquery?>">취소</a></li>
			</ul>
		</div>
</form>


<?include "bottom.html";?>