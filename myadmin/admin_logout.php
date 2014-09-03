<?php
error_reporting(5);

include "global.php";
getsettings();
if (isset($PHPSESSID))
{
	$cleanup = $DB_site->query("DELETE FROM admin_sessions where session_id='$PHPSESSID'");
	if ($cleanup) 
	{
		session_start();
		session_destroy();
		header("Location: $website_admin_login");
		exit;
	}
	else
	{
		echo "Unable to logout";
	}
}
?>