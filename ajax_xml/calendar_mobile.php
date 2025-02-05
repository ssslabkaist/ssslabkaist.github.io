<?
header('Content-type: text/xml; charset=UTF-8');
@session_start();

include "../config/dbconn_2.inc.php";
$toDay = date("Y-m-d");

if(strlen($month)==1) {
	$month="0".$month;
}

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


if (!$year) {
	$year=date("Y")-$sel_year;
}

if (!$month) {
	$month=date("m");
}

if (!$day) {
	$day=date("j");
}

$presentDay=$year."-".$month."-".$day;


$lastday=date("t",mktime(0,0,0,$month,1,$year)); // 이달의 마지막 날
$week=date("D",mktime(0,0,0,$month,1,$year)); // 이달의 1일 요일

switch($week) {
	case "Sun";
		$wee=1;
	break;
	case "Mon";
		$wee=2;
	break;
	case "Tue";
		$wee=3;
	break;
	case "Wed";
		$wee=4;
	break;
	case "Thu";
		$wee=5;
	break;
	case "Fri";
		$wee=6;
	break;
	case "Sat";
		$wee=7;
	break;
}

$lastday_num = $lastday + $wee - 1;
//$School = iconv("CP949", "UTF-8",  "학교");
//$School_Sub = iconv("CP949", "UTF-8", "종이 땡땡땡");
?>

<response>
<year><?=$year?></year>
<month><?=$month?></month>
<week><?=$wee?></week>
<lastday><?=$lastday_num?></lastday>
<?
for($i=1;$i<=$lastday;$i++) {

	$week=date("D",mktime(0,0,0,$month,$i,$year)); // 요일

	switch($week) {
		case "Sun";
			$wee=1;
		break;
		case "Mon";
			$wee=2;
		break;
		case "Tue";
			$wee=3;
		break;
		case "Wed";
			$wee=4;
		break;
		case "Thu";
			$wee=5;
		break;
		case "Fri";
			$wee=6;
		break;
		case "Sat";
			$wee=7;
		break;
	}	
	
	$holliday="#000000";
	$exDay = "N";
	if ($wee==1) {
		$holliday="#FF0000";
		$exDay = "Y";
	}else if ($wee=="7") {
		$holliday="#007BF7";
		$exDay = "Y";
	}
	$holiday = Week($month,$i);
	if ($holiday==1) {
		$holliday="#FF0000";
		$exDay = "Y";
	}

	if($exHoliday!="Y") {
		$exDay = "N";
	}
	
	if(strlen($month)==1) {
		$mon="0".$month;
	}else{
		$mon=$month;
	}
	if(strlen($i)==1) {
		$day="0".$i;
	}else{
		$day=$i;
	}
	$yearmonday = $year."-".$month."-".$day;

	if($yearmonday==$presentDay) {
		$Check_YorN="Y";
	}else{
		$Check_YorN="N";
	}

	if($i==1 && $wee!=1) {
		for($z=0;$z<$wee-1;$z++) {
?>

<day>nothing</day>
<?
		}
	}

	if($exDay=="N") {
?>
<day><?=$i?>||<?=$holliday?>^^<?=$yearmonday?>^^<?=$Check_YorN?></day>
<?
	}
}
?>
</response>