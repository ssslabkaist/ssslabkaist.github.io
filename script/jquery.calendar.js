$(function(){
	$.datepicker.regional['ko'] = {
		closeText: '창닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘선택',
		monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
		'7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월',
		'7월','8월','9월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: ''
	};

	$.datepicker.setDefaults($.datepicker.regional['ko']);

	$('.calendar').datepicker({
		showOn: 'button',
		buttonImage: '../images/common/icon_date.png', //이미지 url
		imgW:'35',
		imgH:'35',
		buttonImageOnly: true,
		buttonText: "달력",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});
});