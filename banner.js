$j = jQuery.noConflict();
var displayTime = 10000;

function growBar() {

	$j("#bar").stop(true, true);
	$j("#bar").css("width","0%");
	$j("#bar").animate({
		width: '100%',
	}, displayTime, 'linear');

}

function changeBanner() {

	var bannerNo = $j("#post-ads a").size();
	var cur = parseInt($j("#post-ads a.opaque").attr("id").replace("banner-", ""));
	var nex = (cur+1)%bannerNo;

	$j("#post-ads a").removeClass("opaque");
	$j("#post-ads #banner-"+nex).addClass("opaque");
	
	$j("#chooser li").removeClass("displaying");
	$j("#chooser .to-"+nex).addClass("displaying");

	growBar();

}

$j(document).ready(function() {

	growBar();
	var bannerNo = $j("#post-ads a").size();
	var bannerTimer = setInterval (changeBanner, displayTime );

	$j("#controls li").click(function() {
		
		$j("#post-ads a").removeClass("opaque");
		$j("#chooser li").removeClass("displaying");

		var imageToShow = parseInt($j(this).attr("class").replace("to-", ""));

 		if ( $j(this).attr("id") ) { var adjust = parseInt($j(this).attr("id").replace("a", "")); }
 		else { var test = null; }

 		$j("#adjuster li").attr("class",function() {
 			if ( !test ) { adjust = parseInt($j(this).attr("id").replace("a", "")); to = imageToShow; }
 			else { var to = parseInt($j(this).attr("class").replace("to-", "")); }
 			return "to-"+((to+adjust)%bannerNo);
 		});

		$j("#post-ads #banner-"+imageToShow).addClass("opaque");
		$j("#chooser .to-"+imageToShow).addClass("displaying");

	});

	$j("#post-ads").hover(
		function() {
			clearInterval(bannerTimer);
			$j("#bar").stop(true, true);
			$j("#bar").css("width","0%");
		},
		function() {
			bannerTimer = setInterval(changeBanner, displayTime);
			growBar();
		}
	);
});
