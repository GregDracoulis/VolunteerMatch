<?php

include "global.php"; // Get Configuration
getsettings(); // Get Settings Variables

error_reporting(5);

list($username,$email,$sess) = check_session();

if ($sess != "")
{
	$result = $DB_site->query("DELETE FROM sessions WHERE session_id='$sess'");
	if (!$result) {echo "Unable to remove traces from database";}
  setcookie("sess","");
  setcookie("cid","");
}

header("Location: $website_home");
exit;
?>
