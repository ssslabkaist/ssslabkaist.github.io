// Core object start
if (typeof window != "undefined") {
	if (typeof window.hosting == "undefined") {
		window.mobileGo = {};
	}
} else {
	if (!mobileGo) {
		mobileGo = {};
	}
}

//모바일 판단 클래스
mobileGo.moble_checker = function () {

	this.mobile_domain = "";
	this.mobile_index = "/mobile/main/main.html";

	this.checkClient = function() {
		if ( this.isMobileDomain() || this.isMobile() ) {
			var url = "http://" + this.mobile_domain + this.mobile_index;
			//window.alert(url);
			location.href = url;
		}
	}

	this.isMobileDomain = function () {
		//window.alert(location.host.toLowerCase());
		
		if (this.mobile_domain.toLowerCase() == location.host.toLowerCase()) {
			return true;
		} else {
			return false;
		}
	}

	this.isMobile = function(){

		if( navigator.userAgent.match(/Android|BlackBerry|Opera Mini|IEMobile|iPhone|iPad|iPod/i) ) {
			return true;
		}
		else{
			return false;
		}

	}

}