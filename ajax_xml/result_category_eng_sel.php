<?header("Content-type: text/xml; charset=utf-8");
echo "<?xml version='1.0' encoding='utf-8' ?>\n";

@session_start();

include "../config/dbconn_2.inc.php";
include "../config/functions.inc";

if($_POST["step"]==1 || $_POST["step"]==2) {
	$resultCategory = $_POST["objVal"];
}
//$bProductCategory = $_POST["".$bVal.""];
//$cProductCategory = $_POST["".$cVal.""];
//$dProductCategory = $_POST["".$dVal.""];
//$resultCategory = "00010001000000000000";
//$_POST["step"]=2;

$page_yorn = "Y";
?>


<response>

<?
if($_POST["step"]==1) {
	echo "
	<depth2><![CDATA[
		<option value=\"\">카테고리 2단계</option>";

	$bSql = mysql_query("SELECT catecode,catename FROM resultCategoryEng WHERE view_state='Y' AND catedeg='2' AND SUBSTRING(catecode,1,4)='".substr($resultCategory,0,4)."' ORDER BY listorder ASC, catecode ASC",$dbconn);
	while($bRow = mysql_fetch_array($bSql)) {
		echo "
		<option value=\"".$bRow[0]."\" title=\"".stripslashes($bRow[1])."\""; if(substr($bRow[0],0,8)==substr($resultCategory,0,8)) { echo " selected"; } echo ">".$bRow[1]."</option>";
	}

	echo "
	]]></depth2>";


	echo "
	<depth3><![CDATA[
		<option value=\"\">카테고리 3단계</option>
	]]></depth3>";
}else if($_POST["step"]==2) {
	echo "
	<depth2><![CDATA[
		<option value=\"\">카테고리 2단계</option>";

	$bSql = mysql_query("SELECT catecode,catename FROM resultCategoryEng WHERE view_state='Y' AND catedeg='2' AND SUBSTRING(catecode,1,4)='".substr($resultCategory,0,4)."' ORDER BY listorder ASC, catecode ASC",$dbconn);
	while($bRow = mysql_fetch_array($bSql)) {
		echo "
		<option value=\"".$bRow[0]."\" title=\"".stripslashes($bRow[1])."\""; if(substr($bRow[0],0,8)==substr($resultCategory,0,8)) { echo " selected"; } echo ">".$bRow[1]."</option>";
	}

	echo "
	]]></depth2>";


	echo "
	<depth3><![CDATA[
		<option value=\"\">카테고리 3단계</option>";

	$cSql = mysql_query("SELECT catecode,catename FROM resultCategoryEng WHERE view_state='Y' AND catedeg='3' AND SUBSTRING(catecode,1,8)='".substr($resultCategory,0,8)."' ORDER BY listorder ASC, catecode ASC",$dbconn);
	while($cRow = mysql_fetch_array($cSql)) {
		echo "
		<option value=\"".$cRow[0]."\" title=\"".stripslashes($cRow[1])."\""; if(substr($cRow[0],0,12)==substr($resultCategory,0,12)) { echo " selected"; } echo ">".$cRow[1]."</option>";
	}

	echo "
	]]></depth3>";
}
?>

	<page_yorn><?=$page_yorn?></page_yorn>
	<obj_num><?=$objNum?></obj_num>
	<step><?=$step?></step>
	<aVal><![CDATA[<?=$aVal?>]]></aVal>
	<bVal><![CDATA[<?=$bVal?>]]></bVal>
	<cVal><![CDATA[<?=$cVal?>]]></cVal>
	<dVal><![CDATA[<?=$dVal?>]]></dVal>
</response>