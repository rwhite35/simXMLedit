<?php
include 'entrieslist.control.php';
include '../cms.class.loader.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Entries</title>
<link href="../css/cmsStyle.css" rel="stylesheet" type="text/css" media="screen">
<!-- VIEWPORT force mobile devices to use device-width instead
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" media="only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)" href="../../css/tablet.css" />
-->
<!-- JQUERY/JAVASCRIPT-->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<!-- popup 4.x -->
<script type="text/javascript">
//Edit record pop up function
function openpopup(url, w, h) {
	winpop = window.open(url,
	'_blank',
	'top=200,screenx=25,left=200,screeny=25,width=' + w + ',height=' + h + ',scrollbars=yes,location=no,menubar=no,resizable=yes,status=no,toolbar=no');
}
function closepopup() {
	if(false==winpop.closed) {
		winpop.close();
	} else {
		alert('Window already closed!');
	}
}
</script>
<!-- Alert user delete operation in progress, each id must be unique -->
<script type="text/javascript">
$(document).ready(function() {
  $("a.deleteme").each(function() { 
	//var ahrefID = $(this).attr('id');
    $(this).click(function() { alert("This action will delete the post. Do you want to continue?"); });
  });
});
</script>
</head>
<body>
<div id="wrapper">
 <h2 class="subhead"><?php echo ucwords($subsection)?> List&nbsp;&nbsp;click Edit icon to popup change form.</span></h2>
 <div id="catlist">
 <?php
 	$cObj = new editor($params);
 	$cObj->getAllPost();
 	unset($params);
 	unset($cObj);
 ?>
 </div>
 <a href="addpost.php" target="_self">
 <div id="navbtn" class="button buttontxt" type="button"><span class="navbtntxt">Add Entry</span></div></a>&nbsp;&nbsp;
 <!--<a href="list_viewer.php?active=<?php echo $actstate?>&view=<?php echo $parent?>" target="_self">
 <div id="navbtn" class="button buttontxt" type="button"><span class="navbtntxt">View <?php echo ucwords($actbtn)?></span></div></a>&nbsp;&nbsp;-->
 <a href="dashboard.php" target="_self">
 <div id="navbtn" class="button buttontxt" type="button"><span class="navbtntxt">Admin Home</span></div></a>
</div>
</body>
</html>