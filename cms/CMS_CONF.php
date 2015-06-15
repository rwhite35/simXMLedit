<?php
/**
* 
* DISTRO SPECIFIC CONFIGURATION SETTINGS
* local variables for application configuration. You may want to move some of these
* variables outside the gallery root directory, specifically database credentials.
*/
/*
* homepage is used for navigation and sets the websites root director
* name. homepage can be the fully qualified domain - http://www.examp.com
*/
$homepage = "index.html";
/*
* THIS SCRIPT IS NOT INCLUDED in the project distro, its up to 
* the developer to create their own error reporting mechanism.  
* required in router.php (L53), index_admin.php (L23), dashboard.php (L19)
*/
$errorfile = "error.php";
/*
* THIS SCRIPT IS NOT INCLUDED in the project distro, its up to 
* the developer to create their own logout mechanism.  
* required in dashboard.php (L50)
*/
$logoutfile = "logout.php";
/*
* DB CREDENTIALS
* this is the database user, not an unprivileged user account (admin_joe).
* DB_UNAME privileged superuser with grant permissions
* DB_UPWORD privileged superuser with grant permissions
* BD_HOST string is set for mysql::PDO driver
*/
define("DB_NAME","gallery_dev");
define("DB_UNAME","mydbsupuser");
define("DB_UPWORD","mydummypass123");
define("DB_HOST","mysql:host=localhost;dbname=".DB_NAME);
/*
*
* GALLERY CONFIGURATION VARS
* These constant variables define the location of resources and 
* sets parameters for image file size and resolution.
*/
/* 
* CONST define the image file size allowed for form and php.ini reference
*/
define("CMS_PARENT_DIR",__DIR__);
/*
* SECURITY TOKEN unique to each session, ensures form post
* is valid and prevents cross-site forgeries, called on each 
* controller that processes form input.
*/
$date = date('Y-m-d',time());
$secstr = $date.strtoupper(basename(CMS_PARENT_DIR));
$sessiontoken = hash('md5', $secstr);
/*
* CONST defines dev log text file, path to client dev root
* text file used for debugging installations, comment out if
* not using. 
*/
define("DEV_LOG", CMS_PARENT_DIR . "/dev_mes.txt");
?>