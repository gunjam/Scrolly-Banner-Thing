$j = jQuery.noConflict();

function addFormField() {

	var FieldNo = $j('#sbt-container:first div').size();

	var fields = "<div id=\"sbt-" + FieldNo + "\"><p>Banner " + (FieldNo+1) + "</p>" +
		"<p><label for=\"sbt-image-" + FieldNo + "\">Image URL:</label>" +
		"<input type=\"text\" id=\"sbt-image-" + FieldNo + "\" name=\"banner-" + FieldNo + "[image]\" /></p>" +
		"<p><label for=\"sbt-post-" + FieldNo + "\">Post URL:</label>" +
		"<input type=\"text\" id=\"sbt-post-" + FieldNo + "\" name=\"banner-" + FieldNo + "[post]\" /></p></div>";

	$j("#sbt-container:first-child").append(fields);
	$j("#sbt-container:last-child").append(fields);

}

function removeFormField() {
	
	var FieldNo = $j("#sbt-container:last div").size()-1;
	
	$j("#sbt-"+FieldNo).remove();
	$j("#sbt-"+FieldNo).remove();

}
