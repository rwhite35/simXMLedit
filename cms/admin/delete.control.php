<?php
/**
* DELETE POST CONTROLLER
* @version 1.0, 2015-06-12
* @since 2015
*
* @uses editor::updateFormPost()
* @params array $params, required for editor::updateFormPost
* prototype array (size=2)
*  		'fpath' => string 'aboutme/copy/text.xml' (length=21)
*  		'postid' => string 'id_2' (length=4)
* @return true,
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
$dirname = explode("/", dirname($params['fpath']));
$parentdir = $dirname[0];
if( isset($_GET) && !empty($_GET['pid']) ) {
  $postid = filter_var($_GET['pid'],FILTER_SANITIZE_STRING);
  $params['postid'] = $postid; // id_n
  //delete the post
  $objVar = new editor($params);
  $return = $objVar->deletePost();
  if(  $return == true ) {
	header("Location: entrieslist.php?view=$parentdir");
	exit();
  } else {
	throw new Exception("L40: delete.control Error on editor::updateFormPost returned false.");
  }
} else {
	throw new Exception("L40: delete.control Post input value empty.");
}
?>