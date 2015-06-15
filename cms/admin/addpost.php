<?php
include 'addpost.control.php'; //date and rrtoken vars
?>
<!DOCTYPE html>
<head lang="en">
<meta charset="UTF-8">
<title>Add New Entry</title>
<link rel="stylesheet" href="../css/cmsStyle.css" type="text/css" media="screen">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
 	//client side validation before submit, sanitizer handled server side
 	$(document).ready(function() {
 		$("form").submit(function() {
 		if ( $("input:first").val()=='') {
 			alert("You need a valid date!");
 			return false;
 		} else if ( $('input[name="thead"]').val()=='' ) {
 			alert ("headline field is required!");
 			return false;
 		} else if ( $('textarea[name="tpost"]').val()=='' ) {
 			alert ("Text field is required!");
 			return false;
 		} else {
 			alert("Your form was submitted");
 			return true;
 		};
 		});
 	});
</script>
</head>
<body>
<div style="border-top: 6pt solid <?php echo $bg_color?>;border-bottom: 10pt solid <?php echo $bg_color?>;">
<table class="plist">
 <tr>
	 <th colspan="2">Add New Entry Form</th></tr>
 <tr>
	 <form action="addpost.control.php" method="post">
		 <input type="hidden" name="token" value="<?php echo $rrtoken?>">
		 <tr><th class="colhead">Post Date:</th><td class="cells"><input type="date" size=40 name="tdate" value="<?php echo $date?>"></td></tr>
		 <tr><th class="colhead">Headline:</th><td class="cells"><input type="text" size=40 name="thead"></td></tr>
		 <tr><th class="colhead">Text:</th><td class="cells"><textarea cols=35 rows=6 name="tpost"></textarea></td></tr>
		 <tr><th colspan=2><input type="submit" value="Submit"></th></tr>
	</form>
</table>
<br>
<div id="fbutton"><button class="button buttontxt" type="button"><a href="dashboard.php">Close</a></button></div>
<!--ends fbutton division-->
</div>
</body>
</html>