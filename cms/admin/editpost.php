<?php
include 'editpost.control.php'; //instance params and loads classes
$bytes = ($_GET['bw']) ? $_GET['bw'] : null; //returned from updateFormPost
$mes = "<br>Success, entry updated! ".$bytes." bytes written.";
?>
<!DOCTYPE html>
<head lang="en">
<meta charset="UTF-8">
<title>Edit Post Entry Form</title>
<link rel="stylesheet" href="../css/cmsStyle.css" type="text/css" media="screen">
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
  /* disable submit if posted 
  $(document).ready(function() {
  $('input[type=submit]',this).attr("disabled","disabled");
  	return false;
  });
  */
</script>
</head>
<body>
<div style="border-top: 6pt solid <?php echo $bg_color?>;border-bottom: 10pt solid <?php echo $bg_color?>;">
<table class="glist">
 <tr>
   <th colspan="2">Edit Post Entry
	   <?php if( $bytes > 0 ) echo $mes?>
   </th></tr>
 <tr>
	<form action="editpost.control.php" method="post">
		<?php
			$fObj = new editor($params);
			$fObj->getFormPost();
		?>
	<tr><th colspan=2><input type="submit" value="Submit"></th></tr>
  	</form>
 </table>
 <br>
 <div id="fbutton"><button class="button buttontxt" type="button"><a href="dashboard.php">Close</a></button></div>
 <!--ends fbutton division-->
</div>
</body>
</html>