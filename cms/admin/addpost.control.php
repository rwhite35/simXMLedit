<?php
/**
* ADD POST CONTROLLER
* @version 1.5, 2015-05-28
* @since 1.0, 2015
*
* @uses editor::addNewPost()
* @params array $params, required for editor::addNewPost
* prototype array([fpath]=>string path to target, [formpost]=> array(
*				  [tdate]=>date added, [thead]=>string headline text, [tpost]=>string text area))
*
*/
session_start();
error_reporting(E_ALL ^ E_NOTICE);
include '../CMS_CONF.php';
/* 
* USER CHECK --	
*/
if ( !isset($_SESSION['staff']['uid']) ) {
  $erno = 3;  //session expired
  header("Location: ../../$errorfile?erno=$erno&page=$homepage");
  exit();
}
/*
* CLASS LOADER --
*/
include '../cms.class.loader.php';
/*
* PROCESS POST --
*/
if ( !empty($_POST['token']) && $_POST['token'] === $_SESSION['staff']['token'] ) {
  foreach( $_POST as $k=>$v ) {
	$fvalue = filter_var($v, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_HIGH);
	if($fvalue!=false) {
		$clean[$k] = $fvalue;
	} else {
		$err = 1;
		return $err;
		exit();
	}
  }
  unset($clean['token']); // not storing token in xml
  $params['fpath'] = $_SESSION['staff']['fpath'];
  $params['formpost'] = $clean;
  /*
  * if made it this far, append the post to the target xml
  */
  $xmlObj = new editor($params);
  $result = $xmlObj->addNewPost();
  
  if($result != 0 || $result == false) { //UI feedback on success or fail
	echo '<span class="successfeedback">Entry was successfully added with '.$result.' bytes written.<br><a href="../index.admin.php" target="_self">Go Back</a> to Admin Control Panel.</span>';
  } else {
	echo '<span class="failedfeedback">Something didn&#39;t go as expected.  Check the file path or target file permissions and make sure bother are correct.<br><a href="../index.admin.php" target="_self">Go Back</a> to Admin Control Panel.</span>';
  }
} else {
	/*
	* used to validate form post is authentic, 
	* on intial addpost load, hashes a token for token input field
	* on form submit, uses token to validate form post
	*/
	$date = date('Y-m-d',time());
	$dirname = basename(dirname(__DIR__));
	$secstr = $date.strtoupper($dirname);
	$rrtoken = hash('md5', $secstr);
}
?>