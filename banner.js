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
	var nex = "banner-"+((cur+1)%bannerNo);

	$j("#post-ads a").removeClass("opaque");
	$j("#post-ads #"+nex).addClass("opaque");

	growBar();

}

$j(document).ready(function() {

	growBar();
	var bannerNo = $j("#post-ads a").size();
	var bannerTimer = setInterval (changeBanner, displayTime );

	$j("#controls span").click(function() {
		
		$j("#post-ads a").removeClass("opaque");

		var imageToShow = parseInt($j(this).attr("id").replace("to-", ""));
 		var adjust = parseInt($j(this).attr("class").replace("a", ""));

 		$j("#controls span").attr("id",function() {
 			var to = parseInt($j(this).attr("id").replace("to-", ""));
 			return "to-"+((to+adjust)%bannerNo);
 		});

		$j("#post-ads #banner-"+imageToShow).addClass("opaque");

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
