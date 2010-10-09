var displayTime = 10000;

function growBar() {

	jQuery("#bar").stop(true, true);
	jQuery("#bar").css("width","0%");
	jQuery("#bar").animate({
		width: '100%',
	}, displayTime, 'linear');

}

function changeBanner() {

	var bannerNo = jQuery("#post-ads a").size();
	var cur = parseInt(jQuery("#post-ads a.opaque").attr("id").replace("banner-", ""));
	var nex = (cur+1)%bannerNo;

	jQuery("#post-ads a").removeClass("opaque");
	jQuery("#post-ads #banner-"+nex).addClass("opaque");
	
	jQuery("#chooser li").removeClass("displaying");
	jQuery("#chooser .to-"+nex).addClass("displaying");

	growBar();

}

jQuery(document).ready(function() {

	growBar();
	var bannerNo = jQuery("#post-ads a").size();
	var bannerTimer = setInterval (changeBanner, displayTime );

	jQuery("#controls li").click(function() {
		
		jQuery("#post-ads a").removeClass("opaque");
		jQuery("#chooser li").removeClass("displaying");

		var imageToShow = parseInt(jQuery(this).attr("class").replace("to-", ""));

 		if ( jQuery(this).attr("id") ) { var adjust = parseInt(jQuery(this).attr("id").replace("a", "")); }
 		else { var test = null; }

 		jQuery("#adjuster li").attr("class",function() {
 			if ( !test ) { adjust = parseInt(jQuery(this).attr("id").replace("a", "")); to = imageToShow; }
 			else { var to = parseInt(jQuery(this).attr("class").replace("to-", "")); }
 			return "to-"+((to+adjust)%bannerNo);
 		});

		jQuery("#post-ads #banner-"+imageToShow).addClass("opaque");
		jQuery("#chooser .to-"+imageToShow).addClass("displaying");

	});

	jQuery("#post-ads").hover(
		function() {
			clearInterval(bannerTimer);
			jQuery("#bar").stop(true, true);
			jQuery("#bar").css("width","0%");
		},
		function() {
			bannerTimer = setInterval(changeBanner, displayTime);
			growBar();
		}
	);
});
