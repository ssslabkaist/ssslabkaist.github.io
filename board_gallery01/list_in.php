<?
if(!$list_count_s) {
	$list_count_s = $list_count;
}
/*
if ($cpage == ""  || $cpage<1){	$cpage = 1; }
if ($spage == "" ){ $spage = 1; }
*/

if ($cpage == "" ){	$cpage = 1; }
if ($spage == "" ){ $spage = 1; }

if($search_word_s) {
	$Search.=" AND INSTR(".$keyfield_s.",'".$search_word_s."')";
}

$sql = mysql_query("SELECT COUNT(*) FROM `".$tn."` WHERE 1 $Search", $dbconn);
if(!$sql) {
	echo("query_error");
}

$row = mysql_fetch_row($sql);

if (($row[0] % $list_count_s) == 0) {
	$max_page = intval($row[0] / $list_count_s);
}else{
	$max_page = intval($row[0] / $list_count_s + 1);
}
$zid = $row[0]-($cpage-1)*$list_count_s;
$set_num = intval(($cpage - 1) * $list_count_s);
?>

<script language="javascript">
function boardListDel() {
	form1 = document.frmDel;
	var len = form1.elements.length;
	var NUM=0;

	for(z=0;z<len;z++) {
		if (form1.elements[z].name=="uUid[]" && form1.elements[z].checked==true) {
			NUM++;
		}
	}

	if(NUM==0) {
		window.alert("체크된 목록이 없습니다. 삭제할 목록을 체크하세요.");
		return;
	}
	if(!confirm("삭제하면 복구가 불가능합니다. 선택한 게시글을 삭제하시겠습니까?")){
		return;
	}

	form1.submit();
}

function boardListCheck() {
	var form1 = document.frmDel;

	var len = form1.elements.length;

	var NUM = 0;

	for(i=0;i<len;i++) {
		if(document.getElementsByName("boardListCheckBox")[0].checked==true) {
			for(z=0;z<len;z++) {
				if (form1.elements[z].name=="uUid[]") {
					form1.elements[z].checked=true;
				}
			}
		}else{
			for(z=0;z<len;z++) {
				if (form1.elements[z].name=="uUid[]") {
					form1.elements[z].checked=false;
				}
			}
		}
	}
}
</script>

		<!-- 리스트 -->
		<div class="h10"></div>
		<div class="count">전체 : <?=number_format($row[0])?>, 현재 : <?=number_format($cpage)?> / <?=number_format($max_page)?> 페이지</div>
		<div class="h10"></div>

		
		<!-- 검색 -->
		<div class="search_box">


<form role="form" action="./list.php" name="myform" method="get">
<input type="hidden" name="tn" value="<?=$tn?>">
<input type="hidden" name="list_count_s" value="<?=$list_count_s?>">
<input type="hidden" name="G_state" value="<?=$G_state?>">
<input type="hidden" name="pm" value="<?=$pm?>">

				<div class="col-xs-3 col-sm-2 kind">
					<select id="kind" name="keyfield_s" class="w100">
						<option value="subject" <?if($keyfield_s=="subject"){ echo " selected";}?>>제목</option>
						<option value="content" <?if($keyfield_s=="content"){ echo " selected";}?>>내용</option>
<!--						<option value="name" <?if($keyfield_s=="name"){ echo " selected";}?>>작성자</option>-->
					</select>
				</div>
				<div class="col-xs-9 col-sm-10">
					<label for="search_word">검색어</label>
					<input type="text" class="w100" id="search_word" name="search_word_s" value="<?=inputTextPrint($search_word_s)?>" placeholder="검색어를 입력하세요.">
					
					<div class="btn search">
						<a href="#void" alt="검색" title="검색" onclick="myform.submit();"></a>
					</div>
				</div>
</form>

		</div>

		

<?if ($_SESSION["session_admin_id"] && $G_state!="Y") {?>
		<div style="height:10px;"></div>
		<div>
			<input type="button" class="btn-delete" value="선택삭제" style="min-width:30px;" onclick="boardListDel();">
			
			<label for="boardListCheckBoxId" style="display:inline-block;"><span>전체선택</span></label> <input type="checkbox" id="boardListCheckBoxId" name="boardListCheckBox" onclick="boardListCheck();" title="전체선택">
		</div>
		<div style="height:10px;"></div>
<?}?>


<?
if($_SESSION["session_admin_id"] && $G_state!="Y") {
	echo "
<form method=\"post\" action=\"./process_board.php\" name=\"frmDel\">
<input type=\"hidden\" name=\"mode\" value=\"delList\">";
include "./config/input_hidden.html";
}
?>

		<!-- 리스트 -->
		<div class="list_gallery box galleryListBg" data-ratioH="70">
			<ul>
<?
$query = "SELECT * FROM `".$tn."` WHERE 1 $Search ORDER BY gonggi_status ASC, writedate DESC LIMIT $set_num, $list_count_s";
$result= mysql_query($query,$dbconn);
if (!$result) {
	echo(" 쿼리 에러 ");
}

$r = 0;
while($row = mysql_fetch_array($result)) {

	$writeDate = explode(" ",$row["writedate"]);
	$writeDateView = str_replace("-",".",$writeDate[0]);


	$commentNum="";
	$cSql = mysql_query("SELECT COUNT(uid) FROM `".$tn."_comment` WHERE buid='".$row["uid"]."'",$dbconn);
	$cRow = mysql_fetch_row($cSql);
	if($cRow[0]) {
		if(100<$cRow[0]) {
			$commentNum = "<span class=\"cmt\" title=\"댓글수\">+99</span>";
		}else{
			$commentNum = "<span class=\"cmt\" title=\"댓글수\">".$cRow[0]."</span>";
		}
	}


	$subjectView = stripSlashNotUsed($row["subject"]);
	if($row["subject_color"]) {
		$subjectView="<span style=\"color:".$row["subject_color"]."\">".$subjectView."</span>";
	}else{
		$subjectView="".$subjectView."";
	}


	$imgNew="";
	if (date("Y-m-d",mktime(0,0,0,date("m"),date("d")-100,date("Y")))<=$writeDate[0]) {
		$imgNew="<img src=\"/img/icon/new_01.gif\" style=\"vertical-align:middle\">";
	}

	$delChkBox = "";
	if ($_SESSION["session_admin_id"] && $G_state!="Y") {
		$delChkBox = "
				<div>
					<input type=\"checkbox\" name=\"uUid[]\" value=\"".$row["uid"]."\">
					<input type=\"hidden\" name=\"tn\" value=\"".$tn."\">
				</div>";
	}

	$listImg="<img src=\"/img/common_responsive/noimg.png\" class=\"nonImg\" alt=\"\">";
	if($row["list_img1"]) {
		$listImg="<img src=\"".$fileUpDownPath."/".$row["list_img1"]."\" alt=\"\">";
	}


	if(!preg_match("/".$boardUsrAuth."/",$view_auth) && !$_SESSION["session_admin_id"]) {
		$goBListLink="href=\"#void\" onclick=\"goLoginResult('".urlencode("../".$tn."/view.php?".$subquery."&uid=".$row["uid"]."&gid=".$row["gid"]."&cpage=".$cpage."&spage=".$spage."")."',''); return false;\"";
	}else{
		$goBListLink="href=\"./view.php?".$subquery."&uid=".$row["uid"]."&gid=".$row["gid"]."&cpage=".$cpage."&spage=".$spage."\"";
	}

	//메인 롤링 링크 주소 표현
	$urlLink = "";
	if($_SESSION["session_admin_id"] && $G_state!="Y") {
		$urlLink = "<div style=\"font-size:11px; color:#b3b3b3;\">/".$tn."/view.php?tn=".$tn."&uid=".$row["uid"]."&gid=".$row["gid"]."&G_state=Y</div>";
	}


	echo "
				<li class=\"col-xs-6 col-sm-4\">
					".$delChkBox."
					<a ".$goBListLink.">
						<div class=\"img\"><span>".$listImg."</span></div>
						<div class=\"subject hidden-xs\">
							<span class=\"subject_text\">".$subjectView."</span>
							".$imgNew."
							".$commentNum."
						</div>
					</a>
					".$urlLink."
				</li>";

	$r++;

}
?>

			</ul>
		</div>
<?
if ($_SESSION["session_admin_id"] && $G_state!="Y") {
	echo "
</form>";
}
?>

		<!-- 페이징 -->
		<div class="page_box">	
			<ul class="mobile">
<?
$page_count_mobile = 1;
if ($cpage>1) {

	$spagePrev = $spage;
	if(($cpage-1)%$page_count==0 && 1<($cpage-1)) {
		$spagePrev = (intval(($cpage-1)/$page_count)*$page_count)-$page_count+1;
	}
	if($spagePrev<=$page_count) {
		$spagePrev = 1;
	}

	echo "
				<li><a class=\"btn prev\" href=\"./list.php?$subquery&cpage=".($cpage-$page_count_mobile)."&spage=".$spagePrev."\" alt=\"이전\" title=\"이전\"></a></li>";
}else{
	echo "
				<li><a class=\"btn prev\" href=\"#void\" onclick=\"window.alert('처음입니다.');\" alt=\"이전\" title=\"이전\"></a></li>";
}


if ($cpage<$max_page) {

	$spageNext = $spage;
	if($cpage%$page_count==0 && $cpage<=$max_page) {
		$spageNext = (intval($cpage/$page_count)*$page_count)+1;
	}

	echo "
				<li><a class=\"btn next\" href=\"./list.php?$subquery&cpage=".($cpage+$page_count_mobile)."&spage=".$spageNext."\" alt=\"다음\" title=\"다음\"></a></li>";
}else{
	echo "
				<li><a class=\"btn next\" href=\"#void\" onclick=\"window.alert('마지막입니다.');\" alt=\"다음\" title=\"다음\"></a></li>";
}

echo "
				<li>
					<label for=\"page\" class=\"col-lg-2 control-label\">페이징</label>
					<select id=\"page\" onChange=\"pageGo(this.value);\">";

$spageGo = 1;
for($pageGo=1; $pageGo<=$max_page; $pageGo++) {

/*	
	if ($spage+$page_count<=$max_page){
		$spageNext = $spage+$page_count;
	}else{
		$spageNext = $spage;
	}
*/
	
	if(($pageGo-1)%$page_count==0 && 0<($pageGo-1)) {
		$spageGo = (intval($pageGo/$page_count)*$page_count)+1;
	}



	echo "
						<option value=\"./list.php?cpage=".$pageGo."&spage=".$spageGo."&$subquery\""; if($cpage==$pageGo) { echo " selected"; } echo ">".$pageGo."</option>";
}

echo "
					</select>
				</li>";

if($write_auth) {
	if(preg_match("/".$boardUsrAuth."/",$write_auth) || $_SESSION["session_admin_id"]) {
		echo "
				<li><a class=\"btn write\" href=\"./write.php?$subquery\">글쓰기</a></li>";
	}
}else{
	echo "
				<li><a class=\"btn write\" href=\"./write.php?$subquery\">글쓰기</a></li>";
}
?>
			</ul>


			<!-- PC 페이징 -->
			<ul class="pagination pc">
<?
echo "
				<li><a class=\"first\" href=\"./list.php?$subquery&cpage=1&spage=1\"><img src=\"/img/common_responsive/first.png\" alt=\"처음\"></a></li>";
if ($spage>1) {

	if($spage-$page_count<1 || $spage-$page_count<1) {
		echo "
				<li><a class=\"prev\" href=\"./list.php?$subquery&cpage=1&spage=1\"><img src=\"/img/common_responsive/prev.png\" alt=\"이전\"></a></li>";		
	}else{
		echo "
				<li><a class=\"prev\" href=\"./list.php?$subquery&cpage=".($spage-$page_count)."&spage=".($spage-$page_count)."\"><img src=\"/img/common_responsive/prev.png\" alt=\"이전\"></a></li>";
	}
}else{
	echo "
				<li><a class=\"prev\" href=\"#void\" onclick=\"window.alert('처음입니다.');\"><img src=\"/img/common_responsive/prev.png\" alt=\"이전\"></a></li>";
}


$a=0;
for ($i=$spage; $i<$spage+$page_count; $i++) {
	if ($i<=$max_page){
/*
		if ($a!=0) {
			echo " | ";
		}
*/
		if ($cpage==intval($i)) {
			echo "
				<li><a href=\"#void\" class=\"active\" style=\"background:#EEEEEE;\">".$i."</a></li>";
		}else{
			echo "
				<li><a href=\"./list.php?$subquery&cpage=$i&spage=$spage\">".$i."</a></li>";
		}
		$a++;
	}
}

if($max_page/$page_count<=1) {
	$last_spage="1";
}else{
	$last_spage=(intval($max_page/$page_count)*$page_count) + 1;
}


if ($i<=$max_page) {
	echo "
				<li><a class=\"next\" href=\"./list.php?$subquery&cpage=".($spage+$page_count)."&spage=".($spage+$page_count)."\"><img src=\"/img/common_responsive/next.png\" alt=\"다음\"></a></a></li>";
}else{
	echo "
				<li><a class=\"next\" href=\"#void\" onclick=\"window.alert('마지막입니다.');\"><img src=\"/img/common_responsive/next.png\" alt=\"다음\"></a></a></li>";
}



if($max_page<1) {
	$max_page=1;
}

echo "
				<li><a class=\"last\" href=\"./list.php?$subquery&cpage=$max_page&spage=$last_spage\"><img src=\"/img/common_responsive/last.png\" alt=\"마지막\"></a></li>";

if($write_auth) {
	if(preg_match("/".$boardUsrAuth."/",$write_auth) || $_SESSION["session_admin_id"]) {
		echo "
				<a class=\"btn write\" href=\"./write.php?$subquery\">글쓰기</a>";
	}
}else{
	echo "
				<a class=\"btn write\" href=\"./write.php?$subquery\">글쓰기</a>";
}
?>

			</ul>
		</div>