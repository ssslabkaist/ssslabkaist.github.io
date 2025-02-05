<?php
include "../config/dbconn.inc.php";
include "../config/functions.inc";

$dataPath = $_SERVER["DOCUMENT_ROOT"]."".$filename;

$dataUrl = "http://".$_SERVER["HTTP_HOST"]."".$filename;

/*
echo $dataPath;
echo "<br>";
echo $dataUrl;
exit;
*/

/*
$filename = $_GET['fn'];
$bo_table = $_GET['bo_table'];

if(strpos($filename, 'data/editor')) {
    $editor_file = strstr($filename, 'editor');
    $filepath = $dataPath.'/'.$editor_file;
} else if(strpos($filename, 'data/qa')) {
    $editor_file = strstr($filename, 'qa');
    $filepath = $dataPath.'/'.$editor_file;
} else {
    $editor_file = '';
    $filepath = $dataPath.'/file/'.$bo_table.'/'.$filename;
}
*/


if(is_file($dataPath)) {
    $size = @getimagesize($dataPath);
    if(empty($size))
        windowClose('이미지 파일이 아닙니다.');

    $width = $size[0];
    $height = $size[1];

/*
    if($editor_file)
        $fileurl = $dataUrl.'/'.$editor_file;
    else
        $fileurl = $dataUrl.'/file/'.$bo_table.'/'.$filename;
*/
    $img = '<img src="'.$dataUrl.'" alt="" width="'.$width.'" height="'.$height.'" class="draggable" style="position:relative;top:0;left:0;cursor:move;">';
} else {
    windowClose('파일이 존재하지 않습니다.');
}
?>





<!DOCTYPE html>
<html lang="ko">
<head>
	<title>KAIST 스마트구조시스템 연구실</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="user-scalable=yes, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
	<meta name="title" content="">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="subject" content="">
	<meta name="Description" content="">
	<meta name="classification" content="">

	<!-- 카카오톡 링크 보낼 때 뜨는 이미지와 텍스트 설정 -->
	<meta property="og:type" content="website">
	<meta property="og:title" content=""> <!-- 제목에 뜰 내용(굵은글씨) -->
	<meta property="og:url" content="">	<!-- 링크걸릴주소 -->
	<meta property="og:description" content=""> <!-- 제목아래쪽에 한줄 나오는 짧은 소개글 -->
	<meta property="og:image" content=""> <!-- 썸네일이미지 경로 -->

	<link type="text/css" href="../style/reset.css" rel="stylesheet">
	<link type="text/css" href="../style/response.css" rel="stylesheet">
	<link type="text/css" href="../style/common.css" rel="stylesheet">
	<link type="text/css" href="../style/common-ani.css" rel="stylesheet">
	<link type="text/css" href="../style/layerpopup.css" rel="stylesheet">
	<link type="text/css" href="../style/ebi.slider.css" rel="stylesheet">
	<link type="text/css" href="../style/layout.css" rel="stylesheet">
	<link type="text/css" href="../style/design.css" rel="stylesheet">
	<link type="text/css" href="../style/main.css" rel="stylesheet">
	<link type="text/css" href="../style/program.css" rel="stylesheet">
	<link type="text/css" href="../style/calendar.css" rel="stylesheet">


	<script src="../script/jquery.1.12.0.min.js"></script>
	<script src="../script/jquery-ui.1.10.1.js"> </script>
	<script src="../script/jquery.cookie.js"></script>
	<script src="../script/jquery.easing.1.3.js"></script>
	<script src="../script/ebi.default.js"></script>
	<script src="../script/ebi.layerpopup.js"></script>
	<script src="../script/ebi.slider.js"></script>
	<script src="../script/ebi.gnb.js"></script>
	<script src="../script/ebi.js"></script>
	<script src="../script/jquery.calendar.js"></script>

	<script src="../js/calendar_mobile.js"></script>
	<script src="../js/program.js"></script>


	<!--다음 도로명 주소 API START-->
	<?if($_SERVER["HTTPS"]=="on") {?>
		<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
	<?}else{?>
		<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
	<?}?>
	<!--다음 도로명 주소 API END-->


	<!--[if lt IE 9]>
		<link href="../style/ie.warning.css" rel="stylesheet" type="text/css">
	<![endif]-->
</head>

<body>

<div><?php echo $img ?></div>

<script>
var win_w = <?php echo $width ?>;
var win_h = <?php echo $height ?> + 70;
var win_l = (screen.width - win_w) / 2;
var win_t = (screen.height - win_h) / 2;

if(win_w > screen.width) {
    win_l = 0;
    win_w = screen.width - 20;

    if(win_h > screen.height) {
        win_t = 0;
        win_h = screen.height - 40;
    }
}

if(win_h > screen.height) {
    win_t = 0;
    win_h = screen.height - 40;

    if(win_w > screen.width) {
        win_w = screen.width - 20;
        win_l = 0;
    }
}

window.moveTo(win_l, win_t);
window.resizeTo(win_w, win_h);

$(function() {
    var is_draggable = false;
    var x = y = 0;
    var pos_x = pos_y = 0;

    $(".draggable").mousemove(function(e) {
        if(is_draggable) {
            x = parseInt($(this).css("left")) - (pos_x - e.pageX);
            y = parseInt($(this).css("top")) - (pos_y - e.pageY);

            pos_x = e.pageX;
            pos_y = e.pageY;

            $(this).css({ "left" : x, "top" : y });
        }

        return false;
    });

    $(".draggable").mousedown(function(e) {
        pos_x = e.pageX;
        pos_y = e.pageY;
        is_draggable = true;
        return false;
    });

    $(".draggable").mouseup(function() {
        is_draggable = false;
        return false;
    });

    $(".draggable").dblclick(function() {
        window.close();
    });
});
</script>

</body>
</html>