<?php

// ###################### Start init #######################
//load config
require("./myadmin/config.php");
require("./files.php");
require("./sessions.php");
require("./myadmin/functions.php");
require("./myadmin/settings.php");

// init db **********************
// load db class
$dbclassname="./myadmin/db_$dbservertype.php";
require($dbclassname);

$DB_site=new DB_Sql_vb;

$DB_site->appname="Website";
$DB_site->appshortname="Website";
$DB_site->database=$dbname;
$DB_site->server=$servername;
$DB_site->user=$dbusername;
$DB_site->password=$dbpassword;

$DB_site->connect();

$dbpassword="";
$DB_site->password="";
// end init db

function Error($msg)
{
	echo "<h1>Oops..</h1><hr size=1><br>Looks like there is a slight problem at our site.<br>";
	echo "An Email has been sent to Administrator.<br>";
	echo "The problem will be taken care of as soon as possible.<br>";
	echo "Sorry for the inconvenience and thanks for your support";
	exit;
}
?>
