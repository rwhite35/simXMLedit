<?php
/**
* CONNECT TO SUBSECTION FLAT FILE
*/
class texthandler {
	protected $fpath;
	
	function __construct( $filePath ) {
		$this->fpath = $filePath;
	}
	
	public function getAllText() {
		$xml = simplexml_load_file( $this->fpath );
		$num=0;				  
		foreach ( $xml->blog as $value ) {
		 $num++;
		 $textArray{$num} = $value;
		}
	
		if ( isset($textArray) ) { 
		  $textArray = array_reverse($textArray);
		  return $textArray;
	  	} else {
		  throw new RuntimeException('Text Array was not set.');
	  	}
	}
}