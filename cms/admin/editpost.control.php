<?php
/**
* EDIT POST CONTROLLER
* @version 1.5, 2015-05-28
* @since 2015
*
* @uses editor::updateFormPost()
* @params array $params, required for editor::updateFormPost
* prototype array([fpath]=>string path to target, [token]=>string validates form post, [postid]=>string blog node id attribute,
*          		  [formpost]=> array([tdate]=>date added, [thead]=>string headline text, [tpost]=>string text area))
* @return string html, edit post form with post content in input fields
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
* START PROCESSING INPUT --
*/
$params = array();
$params['fpath'] = $_SESSION['staff']['fpath'];
/*
* validate form post is authentic, run on both intial addpost load
* and again on addpost form submit.
*/
$date = date('Y-m-d',time());
$dirname = basename(dirname(__DIR__));
$secstr = $date.strtoupper($dirname);
$rrtoken = hash('md5', $secstr);
$params['token'] = $rrtoken;
/*
* run code block on initial page load with GET[pid] as trigger
* $params[postid] required for getFormPost() method
*/
if( isset($_GET) && !empty($_GET['pid']) ) {
	$postid = filter_var($_GET['pid'],FILTER_SANITIZE_NUMBER_INT);
	$params['postid'] = $postid;
	unset($_GET['pid']);
/*
* run code block on form submit with POST[pid] as trigger
* $params[formpost] array required for updateFormPost() method
*/	
} else if( isset($_POST) && !empty($_POST['pid']) ) {
	$params['postid'] = filter_var($_POST['pid'],FILTER_SANITIZE_NUMBER_INT);
	
	if ( $_POST['token'] != $_SESSION['staff']['token'] ) {
		error_log("L56: editpost.control tokens do not match.");
		exit();
	}
	unset($_POST['token']);
	
	if ( !empty($_POST['tpost']) ) {
			foreach ($_POST as $key=>$value) {
				$params['formpost']{$key} = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES);
			}
			//var_dump($params);
			$objVar = new editor($params);
			$return = $objVar->updateFormPost();
			if(  $return > 0 ) {
				$pid = $params['postid'];
				header("Location: editpost.php?pid=$pid&bw=$return");
				exit();
			} else {
				throw new Exception("L62: editpost.control Error on editor::updateFormPost returned false.");
			}
	} else {
		throw new Exception("L64: editpost.control Post input value empty.");
	} 
} else {
	throw new Exception("L67: editpost.control process failed on loading.");
}
?>