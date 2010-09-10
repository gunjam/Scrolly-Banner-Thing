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

	var cur = $j("#post-ads a.opaque").attr("id").replace("image-", "");
	var nex = "image-"+((cur+1)%3);
	$j("#post-ads a").removeClass("opaque");
	$j("#post-ads #"+nex).addClass("opaque");

	growBar();

}

$j(document).ready(function() {

	growBar();
	var bannerTimer = setInterval (changeBanner, displayTime );

	$j("#controls span").click(function() {
		
		$j("#post-ads a").removeClass("opaque");

		var imageToShow = $j(this).attr("id").replace("to-", "");
 		var adjust = $j(this).attr("class").replace("a", "");

 		$j("#controls span").attr("id",function() {
 			return "to-"+(($j(this).attr("id").replace("to-", "")+adjust)%3);
 		});

		$j("#post-ads #image-"+imageToShow).addClass("opaque");

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
