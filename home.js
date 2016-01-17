var numNavTabs = 2;
var url = window.location.href;
//alert("here");
$(document).ready(function(){
	//alert("here");
	resizeNav();
});
$(window).resize(function() {
	resizeNav();
});

function resizeNav() {
	if(url.match("home.php$")) {
		//alert("home");
		$("#nav2").css("background-color", "#7A7A7A");
		$("#nav2").hover(function() {
			$(this).css("background-color", "#525252");
		},
		function() {
			$(this).css("background-color", "#7A7A7A");
		});
	}
	else {
		//alert("assignments");
		$("#nav1").css("background-color", "#7A7A7A");
		$("#nav1").hover(function() {
			$(this).css("background-color", "#525252");
		},
		function() {
			$(this).css("background-color", "#7A7A7A");
		});
	}
	
	//alert("here");
	var winWidth = $(window).width();
	var navPad = winWidth / 8;//1260
	var tabWidth = (winWidth - (navPad * 2) - (3 * (numNavTabs - 1))) / numNavTabs;
	$(".topNav").css("width", tabWidth);
	for (var i = 1; i <= numNavTabs; i++) {
		var pixLeft = (i - 1) * (tabWidth + 3) + navPad;
		$("#nav" + i).css("left", pixLeft + "px");		
	}
	//alert(winWidth);
	if (winWidth < 490) {
		//alert("small");
		$("#nameText").css("font-size", "35px");
		$("#nameText").css("top", "25px");
	}	
	else {
		$("#nameText").css("font-size", "60px");
		$("#nameText").css("top", "13px");
	}
	
	if(url.match("home.php$")) {
		var textW = winWidth - $("#wedPic").width() - 40;
		$("#aboutMe").css("width", textW + "px");
		var textL = $("#wedPic").width() + 20;
		$("#aboutMe").css("left", textL + "px");
	}
	
	//alert(url);
}