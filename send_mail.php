<?php
include "global.php"; // Get Configuration
getsettings();

$info_email = "volunteermatch@californiatechedresources.org";
$subject = "Engineering Week Volunteer Match";

$request = filter_var($_POST["request"], FILTER_SANITIZE_STRING);
$message = $_POST["message"];
$cclist = $_POST["cc"];
$volunteer_email = $_POST["email"];

$ccarray = explode(",", $cclist);
$tocc = array_map('trim', $ccarray);
$ccstring = implode(",", $tocc);

list($teacher_email) = $DB_site->query_first("SELECT teachers.email FROM tars JOIN teachers ON tars.teacher_id=teachers.id WHERE tars.id='$request'");
list($volunteer_id,$fname,$lname,$title,$company) = $DB_site->query_first("SELECT id,fname,lname,title,company FROM volunteers WHERE email='$volunteer_email'");

$formatted_msg = "Message From: $fname $lname" . ($company ? (" (" .($title ? ($title . ", ") : "") . $company . ")") : "") . "
-------------------------------------------------------
$message";

$headers = "From: $info_email" . "\r\n";
$headers .= "Reply-To: $volunteer_email" . "\r\n";
if ($ccstring) {
	$ccheaders = $headers . "CC: $ccstring" . "\r\n";
	$ccheaders .= "X-Mailer: PHP/" . phpversion();
}
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($teacher_email, $subject, $formatted_msg, $headers);
if($sent){
	$sent = mail($volunteer_email, $subject, $formatted_msg, ($ccstring ? $ccheaders : $headers));
}

if($sent){
	$DB_site->query("INSERT INTO tars_emails (tar_id,submit_time,email_message,email_status,email_dated,volunteer)
		VALUES('$request',now(),'".sql_safe($formatted_msg)."','pending',now(),'$volunteer_id')");
	$DB_site->query("UPDATE tars SET email_status='In-Progress', volunteer='$volunteer_id' WHERE id='$request'");
	echo "Message sent!";
} else {
	echo "Error sending message, please try again.";
}

?>