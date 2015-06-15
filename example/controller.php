<?php
/**
* SUBSECTION CONTROLLER
*
* each subsection requires the controller script to load classes,
* aquire an xml object with the text file content
*/
include '../cms/cms.class.loader.php';
$fpath = __DIR__ . "/copy/text.xml";

$txtObj = new texthandler($fpath);
$textArray = $txtObj->getAllText();

$content = $textArray[0]->tpost;
?>