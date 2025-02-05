<?
//include "./dbconn.inc.php";
//include "./functions.inc";

require("".$_SERVER['DOCUMENT_ROOT']."/smtp_mail/class.phpmailer.php");

//**************************메일보내기 START**************************//
$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->SMTPAuth = false;     // turn on SMTP authentication
$mail->SMTPSecure = "";                 // sets the prefix to the servier
$mail->Host = "mail.ebiweb.co.kr";  // specify main and backup server
$mail->Port = 25;
$mail->Username = "";  // SMTP username
$mail->Password = ""; // SMTP password
//$mail->Password = "0000"; // SMTP password
//$mail->WordWrap   = 80; // set word wrap

$mail->From = "no-reply@saharc.or.kr";
$mail->FromName = "사하구장애인종합복지관";
//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->AddAttachment("filename");         // add attachments
$mail->IsHTML(true);                                  // set email format to HTML
//exit;
//**************************메일보내기 END**************************//


//아이디 찾기 /main_form/content_05.html
function mailIdSearch($member_id) {

	global $mail;
	global $dbconn;
	global $HTTP_HOST;
	global $homeDir;
	//global $mail_from;
	//global $mail_from_name;
	//global $mail_url;
	//global $remote_ip;
	
	$mSql = mysql_query("SELECT * FROM member WHERE member_id='".$member_id."'",$dbconn);
	$mRow = mysql_fetch_array($mSql);

	$mail_to = $mRow[email];

	if($mail_to) {

		include "".$_SERVER['DOCUMENT_ROOT']."".$homeDir ."/mail_form/content_search_id.html";
		
		$mail_to=trim($mail_to);														//받는사람메일
		$subject = "[사하구장애인종합복지관] 회원님의 아이디를 알려드립니다.";				//제목

		$mailContent = str_replace("{memberName}",stripSlashNotUsed($mRow[member_name]),$mailContent);
		$mailContent = str_replace("{memberId}",stripSlashNotUsed($mRow[member_id]),$mailContent);

		$content = $mailContent;								//본문내용

		//$mail_content = str_replace("{아이디}",$mRow[member_id],$mail_content);
//		$subject_s = emojiRemoveAddslashes($subject);
//		$content_s = emojiRemoveAddslashes($content);
//		$message = chunk_split(base64_encode($content));

		$mail->AddAddress("$mail_to","");                  // name is optional

		$mail->Subject = $subject;
		$mail->Body    = $content;

		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

		if(!$mail->Send())
		{
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			$mail->ClearAddresses();
			exit;
		}
		$mail->ClearAddresses();
	}
}


//비밀번호 찾기 /main_form/content_06.html
function mailPwdSearch($member_id) {

	global $mail;
	global $dbconn;
	global $HTTP_HOST;
	global $homeDir;
	//global $mail_from;
	//global $mail_from_name;
	//global $mail_url;
	//global $remote_ip;
	
	
	$ret="";

	$mSql = mysql_query("SELECT * FROM member WHERE member_id='".$member_id."'",$dbconn);
	$mRow = mysql_fetch_array($mSql);

	$mail_to = $mRow[email];

	if($mail_to) {
		$tempPass = getTempPass(12);

		include "".$_SERVER['DOCUMENT_ROOT']."".$homeDir ."/mail_form/content_search_pwd.html";
		
		$mail_to=trim($mail_to);														//받는사람메일
		$subject = "[사하구장애인종합복지관] 회원님의 임시 비밀번호를 알려드립니다.";				//제목

		$mailContent = str_replace("{memberName}",stripSlashNotUsed($mRow[member_name]),$mailContent);
		$mailContent = str_replace("{memberPass}",stripSlashNotUsed($tempPass),$mailContent);				//비밀번호 암호화
		//$mailContent = str_replace("{memberPass}",stripSlashNotUsed($mRow[passwd]),$mailContent);

		$content = $mailContent;								//본문내용

//		$subject_s = emojiRemoveAddslashes($subject);
//		$content_s = emojiRemoveAddslashes($content);
//		$message = chunk_split(base64_encode($content));

		$mail->AddAddress("$mail_to","");                  // name is optional

		$mail->Subject = $subject;
		$mail->Body    = $content;

		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

		if(!$mail->Send())
		{
			echo "Message could not be sent. <p>";
			echo "Mailer Error: " . $mail->ErrorInfo;
			$mail->ClearAddresses();
			exit;
		}
		$mail->ClearAddresses();

		$mUpd = mysql_query("UPDATE member SET passwd='".md5($tempPass)."',passwd_moddt=now() WHERE member_id='".$member_id."'",$dbconn);

		$ret=$tempPass;
	}

	return $ret;
}
?>