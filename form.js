$j = jQuery.noConflict();

function addFormField() {

	var FieldNo = $j('#sbt-container:first li').size();

	var fields = "<li id=\"sbt-" + FieldNo + "\"><h3>Banner " + (FieldNo+1) + "<span onClick=\"removeFormField(" + FieldNo + ")\">&#215;</span></h3>" +
		"<p><label for=\"sbt-image-" + FieldNo + "\">Image URL:</label>" +
		"<input type=\"text\" id=\"sbt-image-" + FieldNo + "\" name=\"banner-" + FieldNo + "[image]\" /></p>" +
		"<p><label for=\"sbt-post-" + FieldNo + "\">Post URL:</label>" +
		"<input type=\"text\" id=\"sbt-post-" + FieldNo + "\" name=\"banner-" + FieldNo + "[post]\" /></p></li>";

	$j("#sbt-container:first-child").append(fields);

}
function updateAttr(ele,attr) {

	attr = ele.attr(attr);

	if ( attr.substr(-7) == '[image]' ) return 'banner-'+ele.parent().parent().index()+'[image]';
	else if ( attr.substr(-6) == '[post]' ) return 'banner-'+ele.parent().parent().index()+'[post]';
	else if ( attr.substr(0,8) == 'sbt-post' ) return 'sbt-post-'+ele.parent().parent().index();
	else return 'sbt-image-'+ele.parent().parent().index();

}
function updateForm() {
	$j('#sbt-container:first-child li').attr("id",function(){return 'sbt-'+$j(this).index();});
	$j('#sbt-container:first-child h3').text(function(){return 'Banner '+($j(this).parent().index()+1);});
	$j('#sbt-container:first-child h3').append(function(){return "<span onClick=\"removeFormField("+$j(this).parent().index()+")\">&#215;</span>";});
	$j('#sbt-container:first-child li label').attr("for",function(){return updateAttr($j(this),"for");});
	$j('#sbt-container:first-child li input').attr("id",function(){return updateAttr($j(this),"id");});
	$j('#sbt-container:first-child li input').attr("name",function(){return updateAttr($j(this),"name");});
}
function removeFormField(FieldNo) {

	$j("#sbt-"+FieldNo).remove();
	$j("#sbt-"+FieldNo).animate({height: 0}, 250, 'linear', function(){
		$j(this).remove();
		updateForm();
	});

}

$j(document).ready(function(){ 

	$j("ul#sbt-container").sortable({ opacity: 0.6, placeholder: 'sbt-placeholder', forcePlaceholderSize: true, update: function(){updateForm();}});

});
