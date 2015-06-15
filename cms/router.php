<?php
/**
 * ADMIN LOGIN PROCESS
 * @author Ron D. White rwhite@october-design.com <rwhite@october-design.com>
 * @version 5.0, 2015-05-27
 * @since 1.0, 2015
 *
 *@param string $user_name, $_POST var, admins user name
 *@param string $user_pass, $_POST var, admins password
 *@param int $parent, hidden $_POST var used for building path to xml content
 *@param string $token, hidden $_POST var, defined in CMS_CONF string hash for form authentication
 *
 *@param string $errorfile, defined in GAL_CONF, path to custom error reporting.  
 * reports errors back to the web user without exposing system information.  
 * error reporting script was not included with project distro, either create one 
 * or comment out the header line (L53, 57) and handle failed checks your way.
 *
 *@param array $_SESSION[staff][{}], carries admin credentials for user check
 * prototype - array(staff(uname=>string, uid=>int, token=>string))
 *
 *@return void
*/
session_start();
error_reporting(E_ERROR | E_PARSE);
/* 
* --- LOCAL VARS ---
*/
include 'CMS_CONF.php';
/*
* has a token for router login only
*/
$date = date('Y-m-d',time());
$parentdir = $_POST['parent'];
$loginsecstr = $date.strtoupper($parentdir);
$rrtoken = hash('md5', $loginsecstr); //do not assign to session
/*
* --- LOCAL FUNCTIONS ---
*/
/* 
* function testmatch ($uname[string], $upass[string], $qresult[array])
* test login credentials against user db records without exposing form input to database. 
* return array $passed, prototype array(name=user_name,uid=user_id) assigned to session 
*/
function testmatch($uname,$upass,$qresult) {
	$passed = array('name'=>null,'uid'=>null);
	for($i=0;$i<=count($qresult);$i++) {
		if ( $qresult[$i]['u_name'] == $uname ) $passed['name'] = $uname;
		if ( $qresult[$i]['u_pwd'] == $upass ) $passed['uid'] = $qresult[$i]['u_id'];
	}
	return $passed;
}
/* 
* --- CHECK USER INPUT BEFORE PROCEEDING --- 
* break point - if post vars are empty or token doesn't match, send to error page
* get var page send used back sub section index page.
*/
if (empty($_POST['user_name']) || empty($_POST['user_pass']))  { 
	$erno = 5;
	header("Location: ../$errorfile?erno=$erno&page=index.php"); 
	exit();
} elseif ($_POST['token']!=$rrtoken) {
	$erno = 3;
	header("Location: ../$errorfile?erno=$erno&page=index.php");
	exit();
} else {  //passed, filter input - removes special characters
	$user_name = filter_var($_POST['user_name'],FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW);
	$user_pass = filter_var($_POST['user_pass'],FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW);
	$parent = filter_var($_POST['parent'],FILTER_SANITIZE_SPECIAL_CHARS,FILTER_FLAG_STRIP_LOW);
	unset($_POST['token']);
/* 
* --- DATA PULL --- 
*/
try {
	$dbh = new PDO(DB_HOST, DB_UNAME, DB_UPWORD);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	print("Error on PDO Connect: ".$e->getMessage()."<br>\n");
	die();
} 
/* 
* pull all active staff records and assign result to array.
* for security purpose, no user input is required for this query.
*
* The user table and column labels will most likely require changing. 
* change the SELECT statement to your tables structure.
*/
try {
	$staffar = array();
	$q = "SELECT u_id,u_name,u_pwd FROM users WHERE u_active='Y'";
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) { $staffar[] = $row; } 
} catch (PDOException $e) {
	print "Error!: ".$e->getMessage()."<br>";
	die();
}
/*
* define the parent directory name.  Parent directory is a subsection 
* with text content stored in an XML file.  There can be more than one 
* sub directory managed by the cms solution.
*
* Each sub section with XML content will require its own switch case.  
* @param string $parent, sub directory name, required for path to content
*/
switch ($parent) {
	case "aboutme":
		$ppath = $parent;
	break;
	case "example":
		$ppath = $parent;
	break;
	default:
		$ppath = "../index.html";
}
/* 
* break point - if test failed because input didn't match any database records, 
* send to error page and exit 
*/ 
$usercheck = testmatch($user_name,$user_pass,$staffar);
if ( $usercheck['name'] == null || $usercheck['uid'] == null ) {
 	$erno = 1;
	header("Location: ../$errorfile?erno=$erno&page=index.php"); 
	exit();
}
/*
* --- ASSIGN STAFFAR TO SESSION --- 
* param string [name], authenticated user name
* param int [uid], authenticated user id
* param string [token], hash for subsiquent form post validation
*/
if ($usercheck['name']!=null)
$_SESSION['staff']['uname'] = $usercheck['name'];
$_SESSION['staff']['uid'] = $usercheck['uid'];
$_SESSION['staff']['parent'] = $ppath;
$_SESSION['staff']['token'] = $sessiontoken; //hashed in CMS_CONF
/*
* -- ROUTE USER TO SUBSECTION ADMIN HOME --
* return process to subsection viewer
*/
?>
    <script language="javascript">
      location.replace('<?php echo "../cms/index.admin.php"; ?>');
    </script>
<?php
	exit();
}
?>