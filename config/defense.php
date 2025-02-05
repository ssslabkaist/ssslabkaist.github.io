<?
//################################ mysql 인젝션 공격에 대비 2021-05-07 START #######################################//
$resultInje = mysql_list_tables($DB, $dbconn);
$rowInje = mysql_num_rows($resultInje);
$tnChkNum = 0;
for ($i=0; $i < $rowInje; $i++){
	$table_n = mysql_tablename($resultInje, $i);
	if($tn==$table_n) {
		$tnChkNum++;
	}
}
if(0<strlen($tn) && $tnChkNum<1){
	echo "잘못된 접속입니다";
	exit;
}

$paraArr = array("list_count_s","uid","sid","gid","cpage","spage");
for($p=0; $p<sizeof($paraArr); $p++) {
	if(${$paraArr[$p]}) {
		if(!is_numeric(${$paraArr[$p]})) {

			echo "잘못된 접속입니다.";
			exit;
		}
	}
}
//################################ mysql 인젝션 공격에 대비 2021-05-07 END #######################################//
?>