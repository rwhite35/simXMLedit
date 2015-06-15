<?php
/**
* DATABASE CONNECTION AND QUERY CLASS
* @version 1.0, 2015-05-13
* @since 1.0, 2015-05
* 
* make db connection and run queries.
* extended by classes that require db interaction
* file name and class name are the same, not using 
* namespace for this implementation
*/
class dbmanager {
	protected $query;
	protected $result;
	protected $bind;
	/*
	* connection method
	* return object $DB, db connection
	*/
	protected function conn() {
	  require '../CMS_CONF.php';
	  
	  try {
		$DB = new PDO(DB_HOST, DB_UNAME, DB_UPWORD);
		$DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  } catch( Exception $e ) {
		$mes = "Error:L24 db_class.php Caught Exception ---"."\n";
		$mes .= $e->getMessage()."\n";
		error_log($mes);  
	  }
	  return $DB;
   }
   /*
   * query method
   * param string $query, query statement passed from publicAccess method
   * param mixed $bind, array or scalar contains placeholder values,
   * !!important, note the order of fields and placeholders.
   * return object $result,  
   */
   protected function query($query,$bind) {
	$DB = (!isset($DB)) ? $this->conn() : $DB;
	try {
		$stmt = $DB->prepare($query);
		
		if ($bind!=null) {
		  $cnt = count($bind);
		  if ($cnt>1) { //mulitple binders
		    $t=1;
		    for($i=0;$i<$cnt;$i++) {
			    $stmt->bindParam($t,$bind[$i]);
			    $t++;
		    }
		  } else { //single binder
			  $stmt->bindParam(1,$bind);
		  }
		}
		
		if($stmt->execute()) {
		  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	        $this->result[] = $row;
		  }
		  return $this->result;
		} else { 
			throw new Exception("L63: Error on dbmanage::query execution."); 
		}
	} catch ( Exception $e ) {
		error_log("Error on query method: ".$e->getMessage());
	}
		unset($stmt);
		unset($this->result);
   }
}
?> 