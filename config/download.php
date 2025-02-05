<?
@session_start();
@header("Content-Type:text/html; charset=UTF-8");
@header("X-Robots-Tag: noindex,nofollow");				//로봇 접근 금지
/*
if(!$_SESSION["session_admin_id"] && !$_SESSION["session_usr_id"]) {
	echo "
		<script>
		 window.alert(\"로그인 후 다운 가능합니다.\");
		 history.back();
		</script>
	";
	exit;
}
*/


$filename = stripslashes($filename);
$filenameReal = stripslashes($filenameReal);
$setFileVal = urlencode($filename).urlencode($path).urlencode($filenameReal)."sun!~2918";
$setFileVal = hash("sha256", $setFileVal);

if(!$getFileVal || $getFileVal!=$setFileVal) {
	echo "접속권한이 없습니다.!!!";
	exit;
}


$filename_copy = $filename;
$filenameReal_copy = $filenameReal;

//UTF-8일경우 파일명 깨지는거 방지하기 위함 START//
$ie = isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false); 
if($ie)
{
	$filename = rawurlencode($filename);	
	$filenameReal = rawurlencode($filenameReal);
	$filename = iconv('UTF-8', 'cp949//IGNORE', $filename);
	$filenameReal = iconv('UTF-8', 'cp949//IGNORE', $filenameReal);
}


$file="".$_SERVER["DOCUMENT_ROOT"]."".$path."/$filename";    // 화일이 실제로 있는 위치를.. 

if(!is_file("$file")) {													//서버(ftp)에 한글 파일이 올라가 있을때 다운이 안되어서 추가해 놓음
	$filename = iconv('UTF-8', 'cp949//IGNORE', $filename_copy);
	$filenameReal = iconv('UTF-8', 'cp949//IGNORE', $filenameReal_copy);

	$file="".$_SERVER["DOCUMENT_ROOT"]."".$path."/$filename";    // 화일이 실제로 있는 위치를.. 
}

$file_size=filesize($file); 



if(!$filenameReal) {
	$filenameReal = $filename;
}
$filenameReal = str_replace(";","_",$filenameReal);
$filenameReal = str_replace(",","_",$filenameReal);			//파일명에 콤마 있을때 크롬에서 멘트가 뜸 => 서버에서 중복 헤더를 수신했습니다.



if (is_file("$file")) { 

    if(eregi("(MSIE 5.0|MSIE 5.1|MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT)) 
    { 
      if(strstr($HTTP_USER_AGENT, "MSIE 5.5")) 
      { 
        header("Content-Type: doesn/matter;"); 
        header("Content-disposition: filename=".$filenameReal.""); 
        header("Content-Transfer-Encoding: binary"); 
		Header("Cache-control: private"); 
		Header('Pragma: private');
        header("Expires: 0"); 
      } 

      if(strstr($HTTP_USER_AGENT, "MSIE 5.0")) 
      { 
        Header("Content-type: file/unknown;"); 
        header("Content-Disposition: attachment; filename=".$filenameReal.""); 
        Header("Content-Description: PHP3 Generated Data"); 
		Header("Cache-control: private"); 
		Header('Pragma: private');
        header("Expires: 0"); 
      } 

      if(strstr($HTTP_USER_AGENT, "MSIE 5.1")) 
      { 
        Header("Content-type: file/unknown;"); 
        header("Content-Disposition: attachment; filename=".$filenameReal.""); 
        Header("Content-Description: PHP3 Generated Data"); 
		Header("Cache-control: private"); 
		Header('Pragma: private');
        header("Expires: 0"); 
      } 
      
      if(strstr($HTTP_USER_AGENT, "MSIE 6.0")) 
      { 

        Header("Content-type: application/x-msdownload;"); 
        Header("Content-Length: ".(string)(filesize("$file"))); 
        Header("Content-Disposition: attachment; filename=".$filenameReal."");  
        Header("Content-Transfer-Encoding: binary");  
		Header("Cache-control: private"); 
		Header('Pragma: private');
        Header("Expires: 0");  
      } 
    } else { 
      Header("Content-type: file/unknown;");    
      Header("Content-Length: ".(string)(filesize("$file"))); 
      Header("Content-Disposition: attachment; filename=".$filenameReal.""); 
      Header("Content-Description: PHP3 Generated Data"); 
	  Header("Cache-control: private"); 
	  Header('Pragma: private');
      Header("Expires: 0"); 


    } 

	$fp = fopen("$file","r"); 
	if (!fpassthru($fp)){ 
		fpassthru($fp); 
	}

} else { 
  
  echo "<script>alert('해당 파일의 정보가 존재하지 않습니다.');</script>"; 

} 


?>