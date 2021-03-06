<?php
/**
* TEXT EDITOR CLASS
* @package
* @author Ron D. White rwhite@october-design.com,ronwhite562@gmail.com
* @version Version 3.5, 2015-06-15 
* @since AB 4.5
* @inherited from blog_lite_class
* @see reference projects/cmsmanager/editor_class_doc.pages for api usage
*
* Package Has seven public methods, 
* 1 getAllPost : public method, lists all xml post in decending order
* 2 getFormPost : public method, load individual post by blog id in to form to edit
* 3 updateFormPost : public method, update/replaces target entry with getFormPost edits
* 4 addNewPost : public method, appends new entry to target xml list, last entry added
* 5 deletePost : public method, deletes target entry by removing blog child by id
*
* 6 getPostList : not enabled 2015-06-15
* 7 getLastPost: not enabled 2015-06-15
*
* @params array $params, see each method docBlock for definition
* @usage, $objVar = new editor($params);
* @usage, $objVar->[method name];
* @return mixed, see each method docBlock for return value
*/	
class editor {
	//class properties
	protected $params;
	public function __construct( $p ) { $this->params = $p; }
/**
*
* @api
* PUBLIC METHOD getAllPost
* returns all previous post in decending order, called from entrieslist viewer
* @uses method createXMLobj, requires file path to web site sub section xml file
* @param string $params[fpath], relative path to file, defined in dashboard
* @usage $objVar->getAllPost();
* @return string HTML, all xml[blog] entries in a list format
*/
public function getAllPost() {
	if( !empty( $this->params['fpath'] ) ) { $xmlPostArrObj = $this->createXMLobj( "../../" . $this->params['fpath'] ); } 
	else { throw new Exception("L40 editor::getAllPost failed to create xml object, check path!"); }
	if (isset($xmlPostArrObj)){	$xmlPostArrObj = array_reverse($xmlPostArrObj); }
	foreach($xmlPostArrObj as $key=>$value) { // output HTML
	  $postid = $xmlPostArrObj[$key]->attributes()->id; // assign xml entry id
	  $ped = explode("_", $postid);
	  echo '<div id="entrylist"><div class="dtable-row">'."\n";
	  printf('<a href="editpost.php?pid=%s"><img src="../img/edit_icon.png" height="20px" title="Edit Entry" alt="Edit Entry"></a>&nbsp;&nbsp;'."\n",$postid);
	  printf('<a href="delete.control.php?pid=%s" class="deleteme"><img src="../img/delete_icon.png" height="20px" title="Delete Entry" alt="Delete Entry"></a>&nbsp;&nbsp;'."\n",$postid);
	  printf('<span class="headline">%s&nbsp;(posted %s)</span></div>'."\n",$value->thead,$value->tdate);
	  printf('<p class="sdstyle">%s</p>',$value->tpost);
	  echo '</div>'."\n";
   }
}
/**
*
* @api
* PUBLIC METHOD getFormPost
* outputs individual post in edit text form, called from editpost viewer
* @uses method createXMLobj, requires file path to web site sub section xml file
* @param string $params[fpath], relative file path defined in dashboard and assigned to session 
* @param int, $params[postid] : xml attribute id <blog @id=id_n>
* @usage $objVar->getFormPost();
* @return string HTML, pre populated HTML form
*/
public function getFormPost() {
	$pid = "id_".$this->params['postid'];
	if( !empty( $this->params['fpath'] ) ) { 
		 $xmlPostArrObj = $this->createXMLobj( "../../" . $this->params['fpath'] ); 
	} else { throw new Exception("L69 editor::getFormPost failed to create xml array object, check path!"); }
	if ( is_array($xmlPostArrObj) ) {
	  for ( $i=1; $i<=count($xmlPostArrObj); $i++ ) {
	   if ( $xmlPostArrObj[$i]->attributes()==$pid ) {  // if blog id and pid match, output node in form
		 $validmatch = $pid;
		 foreach( $xmlPostArrObj[$i] as $key=>$value ) {
	       ${$key} = array();
	       ${$key}[]=$key;		// form field name is same as xml[blog] child-node name
	       ${$key}[]=$value;	// current child node value
		 } // close foreach
		 printf ('<input type="hidden" name="pid" value=%s>'."\n",$validmatch);
		 printf ('<input type="hidden" name="token" value="%s">'."\n",$this->params['token']);
		 echo '<tr><th class="colhead">Post Date:</th><td class="cells">'."\n";
		 printf ('<input type="date" size=40 name="%s" value="%s"></td></tr>',$tdate[0],$tdate[1]);
		 echo '<tr><th class="colhead">Headline:</th><td class="cells">'."\n";
		 printf ('<input type="text" size=40 name="%s" value="%s"></td></tr>',$thead[0],$thead[1]);
		 echo '<tr><th class="colhead">Text:</th><td class="cells">'."\n";
		 printf ('<textarea cols=75 rows=10 name="%s">%s</textarea></td></tr>',$tpost[0],$tpost[1]);
	   } // close if	  
	  } // close for
	} else { throw new Exception("L93 editor::getFormPost error on id match, no object created."); }
}
/**
*
* @api
* PUBLIC METHOD updateFormPost
* update target xml text from getFormPost method, called from editpost.control 
* @uses method generateTempXML, requires blog id and array of edit form input
* @uses PHP Class DOMDocument, writes new xml with updated content
* @uses PHP Class DOMXPath, allows process to query xml document by id attri
* @param string $params[fpath], defined in dashboard, relative path to target xml
* !! NOTE: backslashes "../../" added at code block level, not globally !!
* @param int $params[postid] : <blogger><blog @id=id_n> id attribute, id attri must start with alpha char
* @param array $params[formpost] : proto: array(pid=>int,token=>string,tdate=>date,thead=>string,tpost=>string)
* @usage $objVar->updateFormPost();
* @return mixed, bytes written on success, false on fail
*/
public function updateFormPost() {
	$fpath = "../../" . $this->params['fpath'];
	$inputArr = $this->params['formpost'];
	$pid = $inputArr['pid'];
	if( !empty($inputArr) ) { //create xml document object
	  $xml = new DOMDocument('1.0');
	  $xml->validateOnParse = true;
	  $xml->formatOutput = true;
	  $xml->preserveWhiteSpace = false;
	  $xml->load(realpath($fpath)); //realpath() support for windows file systems
	  /* 
	  * get correct node to edit using $pid 
	  */
	  $searchNodes = $xml->getElementsByTagName( "blog" );
	  foreach( $searchNodes as $target ) {  //returns array of blog[@ids]
	  	$attrIDarr[] = $target->getAttribute('id');
	  } 
	  $key = array_search($pid, $attrIDarr);
	  if ( $key === false ) { error_log("L129 editor::updateFormPost no key found, exiting here"); exit(); } 
	  $context = '//blogger/blog[@id="'.$attrIDarr[$key].'"]';
	  $xpathObj = new DOMXPath($xml);
	  $entry = $xpathObj->query($context);
	  //echo $entry->item(0)->nodeValue; debugging to see current value of node
	  $previouspost = $entry->item(0);
	  /*
	  * generate new xml from users input
	  * requires editor::generateTempXML method 
	  */
	  $newxml = $this->generateTempXML($pid,$inputArr);
	  $newedit = $xml->importNode($newxml->documentElement, true); //DOMDoc method
	  $previouspost->parentNode->replaceChild($newedit,$previouspost);	  
	  $byteswritten = $xml->save($fpath); //DOMDoc method
	  return $byteswritten;
	} else {
	  throw new Exception("L145 editor::updateFormPost no input array for updating.");
	  return false;
	}
}
/**
* 
* @api
* PUBLIC METHOD addNewPost
* add new entry to end of blog nodes by writting new xml to target file.
* @uses method generateTempXML, requires blog id attri and add form input
* @uses PHP Class DOMDocument, writes new xml with updated content
* @uses PHP Class DOMXPath, query xml document by blog id attri
* @param string $params[fpath], reletive path to target xml defined in dashboard
* !! NOTE: backslashes "../../" added at code block level, not globally !!
* @param int, $params[postid] : xml attribute id <blog @id=id_n>, must start with alpha char
* @param array $params['formpost] : proto : array(token=>string,tdate=>date,thead=>string,tpost=>string)
* @usage $objVar->updateFormPost();
* @return mixed, bytes written on success, false on fail
*/
public function addNewPost() {
  $fpath = "../../" . $this->params['fpath'];
  $inputArr = $this->params['formpost'];
  $pid = "";
  if ( isset($inputArr) && !empty($inputArr) ) {
	$xml = new DOMDocument('1.0');
	$xml->validateOnParse = true;
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false;
	$xml->load($fpath);
	$rootnode = $xml->getElementsByTagName('blogger')->item(0);
	$parentnode = $xml->getElementsByTagName('blog');
	$i=0;
	foreach($parentnode as $value){
		$lastpostid = $parentnode->item($i)->getAttribute('id');
		$i++;
	}
	$lastpostid = explode("_", $lastpostid); // array([0]=>string,[1]=>int)
	$lastpostid[1]+=1;
	$pid = implode("_", $lastpostid);
	/*
	* generate the new xml from client input
	*/
	$tempxml = $this->generateTempXML($pid,$inputArr);
	$handle = $xml->importNode($tempxml->documentElement, true);
	$rootnode->appendChild($handle);
	$byteswritten = $xml->save($fpath);
	return $byteswritten;
  }else{
	echo '<br>&nbsp;<span class="newtitle">L195: addNewPost: Array not passed to class, zero bytes written.</span>';
	return false;
  }
}
/**
*
* @api
* PUBLIC METHOD deletePost
* remove blog nodes by cutting blog entries from target file
* @uses PHP Class DOMDocument, writes new xml with updated content
* @uses PHP Class DOMXPath, query xml document by blog id attri
* @param string $param[fpath], defined in dashboard, reletive path to target xml
* !! NOTE: backslashes "../../" added at code block level, not globally !!
* @param int, $params[postid] : xml attribute id <blog @id=id_n>, must start with alpha char
* @usage $objVar->deletePost();
* @return bool, true on success, false on fail
*/
public function deletePost() { 
  $fpath = "../../" . $this->params['fpath'];
  $pid = $this->params['postid']; // this would be the target entry
  $xml = new DOMDocument('1.0');
  $xml->load($fpath);
  $xml->formatOutput = true;
  $xml->preserveWhiteSpace = false;
  $xpath = new DOMXpath($xml);
  $tempxml = $xml->documentElement;
  // loop through parent nodes, if id/pid match, delete that parent/child
  $pnode = $xml->getElementsByTagName('blog');
  foreach($pnode as $key) {
   if ($key->getAttribute('id')==$pid) { $oldxml = $tempxml->removeChild($key); }
  }
  if ($xml->save($fpath)) { return true; exit(); }
  else { return false; exit(); }
}
/* ##### PROTECTED HELPER METHODS ##### */
/**
* 
* CLASS METHOD createXMLobj
* protected - only available to editor class
* loads target xml in a formatted object, called in getAllPost, getPostList
* usage $this->createXMLarray();
* @return array, $xmlPostArrObj
* xml simple object
* xml[blog] decendent structure, tdate(date posted), thead(headline), tpost(content)
* xml[blog id=n] has one attribute 'id'
*/ 
protected function createXMLobj( $fpath ) {
	$xml = simplexml_load_file( $fpath );
	if ($xml!=false) {
	  $num=0;				  
	  foreach ($xml->blog as $value){
		 $num++;
		 $xmlPostArrObj{$num} = $value;
	  }
	} else {
		throw new Exception("L259: editor::createXMLobj returned false, check path.");
	}
	return $xmlPostArrObj;
}
/**
* 
* CLASS METHOD generateTempXML
* protected - only available to editor class
* creates temporary formated xml object, called in updateFormPost, addNewPost
* @usage $this->generateNewXML($pid,$array);
* @param int, $pid : blog node id attribute
* @param array, $array : cleaned $_POST array of user input
* @return obj, $tempxml : newly created xml fragment 
*/
protected function generateTempXML($pid,$array) {
	$tempxml = new DOMDocument();
	$tmp_node = $tempxml->createElement("blog");
	$tmp_node_attr = $tempxml->createAttribute('id');
	$tmp_node_attr->value = $pid; 
	$tmp_node->appendChild($tmp_node_attr);
	//append the additional children nodes
	$tmp_node->appendChild($tempxml->createElement("tdate",$array['tdate']));
	$tmp_node->appendChild($tempxml->createElement("thead",htmlentities(trim($array['thead']))));
	$tmp_node->appendChild($tempxml->createElement("tpost",htmlentities(trim($array['tpost']))));
	$tempxml->appendChild($tmp_node); //assign newly completed blog entry to newxml object
	//error_log($tempxml->saveHTML()); works as expected 2015-05-20
	return $tempxml;
}
/* ##### MAY BE DEFINED AT A LATER DATE ##### */
/**
* method usage: $objVar->getLastPost();
* @since v2.5, 2014-01-11
*
* public function getLastPost() { }
*/
/**
* method usage: $objVar->getPostList();
*
* public function getPostList() { }
*/
}
?>
