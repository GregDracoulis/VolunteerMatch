<?php
	include "global.php"; // Get Configuration
	getsettings();

	list($member_id,$email,$sess) = check_session("volunteer");

	$tid = $_GET["id"];
	$action = $_GET["action"];
	
	if ($action == "complete"){
		$DB_site->query("UPDATE tars SET tar_status='complete',email_status='Scheduled' WHERE id='$tid' AND volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
		$DB_site->query("UPDATE tars_emails SET email_status='complete', complete_time=now() WHERE tar_id='$tid' AND volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
		echo "Marked as Complete";
	} elseif ($action == "cancel") {
		$DB_site->query("UPDATE tars SET tar_status='pending',email_status='Open',volunteer=NULL WHERE id='$tid' AND volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
		$DB_site->query("UPDATE tars_emails SET email_status='canceled', complete_time=now() WHERE tar_id='$tid' AND volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
		echo "Canceled";
	}
?>