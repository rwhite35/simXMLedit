<?php
/**
* ENTRIES CONTROLLER
* version 1.0, 2015-05-28
* since 1.0, 2014-07-28
*
* @return string $subsection, parent directory name
* @return array $params, assigns session fpath to $params[fpath] element, defined in dashboard.
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
$subsection = ( !empty($_GET['view']) ) ? filter_var( $_GET['view'], FILTER_SANITIZE_STRING ) : null;
$params['fpath'] = $_SESSION['staff']['fpath'];
/*
* assignSortOrder([th_field_id]) called through AJAX post event user clicks a list column label.
* param string th_filed_id, each th tag has a dynamic class name, JQUERY captures that name and
* initializes an AJAX request
* return string $sortByCol, column name appended to table field prefix i.e. ct_name. 
*
function assignSortOrder($labelClick) {
	$lpart = array();
	$th_id = "";
	$sortByCol = "";
	
	if(!empty($labelClick)) {
		$lpart = explode("_", $labelClick);
		$th_id = $lpart[1]; //id appended to label_
	}
	//todo 2014-06-08: column names are specific to media center
	//table structure. these should be dynamic  	
	switch($th_id) {
	  case 1:
		$sortByCol="id";
	  break;
	  case 2:
		$sortByCol="name";
	  break;
	  case 3:
		$sortByCol="date";
	  break;
	  default:
	  	$sortByCol="id";
	}
	return $sortByCol;
}
$collabel = ucwords($parent) . "Sub Section"; //sub section name
*/
?>