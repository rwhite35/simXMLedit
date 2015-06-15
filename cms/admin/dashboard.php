<?php
/**
* ADMIN CONTROL PANEL
* @version 2.2 2015-05-28
* @since 1.0 2015
*
* sets the target xml file path for all CMS class methods
* displays Entries List and Add Entry buttons 
*/
session_start();
error_reporting(E_ALL ^ E_NOTICE);
include '../CMS_CONF.php';
/*
* USER CHECK --
*/
if(empty($_SESSION['staff']['uid'])) {
  $erno = 3;  //session expired
  header("Location: ../../$errorfile?erno=$erno&page=$homepage");
  exit();
}
/*
* LOCAL VARS --
*/
$subsection = $_SESSION['staff']['parent'];
/*
* SET PATH TO TARGET XML -- ie. aboutus/copy/text.xml 
*/
$targetfile = $subsection.DIRECTORY_SEPARATOR."copy".DIRECTORY_SEPARATOR."text.xml";
if ( file_exists( "../../" . $targetfile) ) {
	$_SESSION['staff']['fpath'] = $targetfile;
} else {
	throw new Exception("L33: dashboard error, file doesn't exist, check the path.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Control Panel</title>
<link href="../css/cmsStyle.css" rel="stylesheet" type="text/css" media="screen">
<!-- VIEWPORT force mobile devices to use device-width instead
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" media="only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)" href="../css/tablet.css" />
-->
</head>
<body>
<div id="wrapper">
	<p>Manage Page Content For <strong><?php print strtoupper($subsection)?></strong></p>
	<div id="modNav">
	  <div id="dash_row">
		<div id="dash_left"><a href="entrieslist.php?view=<?php print $subsection?>" target="_self">
			<img src="../img/mgallery.png" alt="Manage Gallery" title="Manage Gallery Properties" border=0/><br>Entries List</a></div>
		<div id="dash_right"><a href="addpost.php?view=<?php print $subsection?>" target="_self">
			<img src="../img/aimage.png" alt="Add Images" title="Add Images to Galleries" border=0/><br>Add Entry</a></div>
	  </div>
	</div>
	<p>What to do from here:<br>
	<ul id="definitions">
		<li>Entry List - Select past entries from storage</li>
		<li>Add Entry - Add new entries to public page</li>
		<li>&nbsp;</li>
		<li><button class="button buttontxt" type="button" onclick=window.parent.location.href='../../<?php echo $logoutfile?>?bdir=<?php echo $subsection?>' target='_parent'>Log Out</button></li>
	</ul>
</div>
</body>
</html>