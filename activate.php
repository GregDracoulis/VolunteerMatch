<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
getsettings();

$key = $_SERVER['QUERY_STRING'];
list($volunteer_id,$volunteer_email) = $DB_site->query_first("SELECT id,email FROM volunteers WHERE activation_code='$key' AND account_status='Unconfirmed'");
list($teacher_id,$teacher_email) = $DB_site->query_first("SELECT id,email FROM teachers WHERE activation_code='$key' AND account_status='Unconfirmed'");

if($volunteer_id) {
	$DB_site->query("UPDATE volunteers SET account_status='Active', activation_code=NULL WHERE id='$volunteer_id'");
	init_session($volunteer_id,$volunteer_email,'volunteer',$session_logout);
	Header("Location: volunteers.php");
	exit;
} elseif ($teacher_id) {
	$DB_site->query("UPDATE teachers SET account_status='Active', activation_code=NULL WHERE id='$teacher_id'");
	init_session($member_id,$teacher_email,'teacher',$session_logout);
	Header("Location: teachers.php");
	exit;
} else {
	$msg = '<div style="text-align:center;"><br /><br />';
	$msg .= '<h1 style="font-size:x-large;font-weight:bold;">There was a problem activating your account.</h1>';
	$msg .= "<h2>Either your account is already active, we're experiencing difficulties, or you reached this page by mistake.</h2>";
	$msg .= "<p>Try logging in, or try clicking the link you used to reach this page again. If you copied and pasted this address into your web browser, or if you typed it manually, try that one more time-you may have missed a part of it.</p>";
	$msg .= '<br /><br /></div>';
}   

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Volunteer Match Activation</title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
    </head>
	<body>
		<?php require("header.php"); ?>
		<?php echo $msg ?>
		<?php require("footer.php"); ?>
	</body>
</html>
