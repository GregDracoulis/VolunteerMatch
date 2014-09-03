<?php 
include "global.php";

mysql_connect("localhost", "cacthub_svec", "svec2011") or die(mysql_error()); 
mysql_select_db("cacthub_vmdbsvec") or die(mysql_error());
	
$sess=sql_safe($_GET["sess"]);
$tarid=sql_safe($_GET["tarid"]);
$message=sql_safe($_GET["message"]);
$other_volunteers = sql_safe($_GET["other_volunteers"]);

//Get email of the current user
$sql = "SELECT email,member_id FROM sessions WHERE session_id = '$sess'";
$result = mysql_query($sql); 
$row = mysql_fetch_assoc($result);
$email = $row["email"];
$volunteerid = $row["member_id"];
//Get teacher email
$sql2 = "SELECT email FROM teachers WHERE id = (SELECT teacher_id FROM tars WHERE id=$tarid)";
$result2 = mysql_query($sql2); 
$row2 = mysql_fetch_assoc($result2);
$teacher_email = $row2["email"];

//Add email to database
mysql_query("UPDATE tars SET email_status='In-Progress' WHERE id='$tarid'");

//Get tar information
$sql2 = "SELECT * FROM tars WHERE id = $tarid";
$result2 = mysql_query($sql2); 
$row2 = mysql_fetch_assoc($result2);
$teacher_id = $row2["teacher_id"];
$county_name = $row2["county_name"];
$district_name = $row2["district_name"];
$school_name = $row2["school_name"];
$school_city = $row2["school_city"];
$school_zip = $row2["school_zip"];
$subject = $row2["subject"];
$grades = $row2["grades"];
$students = $row2["students"];
$months = $row2["months"];
$category = $row2["category"];
$category_name = $row2["category_name"];
$best_times = $row2["best_times"];
$details = $row2["details"];
$submit_time = $row2["submit_time"];
$teacher_fname = $row2["teacher_fname"];
$teacher_lname = $row2["teacher_lname"];

$sql3 = "SELECT address FROM schools WHERE school_name = '$school_name' AND city = '$school_city'";
$result3 = mysql_query($sql3); 
$row3 = mysql_fetch_assoc($result3);
$school_address = $row3["address"];

$sql4 = "SELECT * FROM volunteers WHERE id = '$volunteerid'";
$result4 = mysql_query($sql4); 
$row4 = mysql_fetch_assoc($result4);
$fname = $row4["fname"];
$lname = $row4["lname"];
$volunteer_phone = $row4["phone"];
$title = $row4["title"];
$company = $row4["company"];
$industry = $row4["industry"];
$volunteer_details = $row4["details"];
$other_emailed_volunteers = $other_volunteers;

//Change status of tar in database
srand((double)microtime()*1000000);
$rand_id = md5(uniqid(rand()));
$rand_id = addslashes($rand_id);
$result = $DB_site->query("INSERT INTO tars_emails (tar_id,teacher_id,county_name,district_name,school_name,school_city,school_zip,subject,grades,students,months,category,category_name,best_times,details,submit_time,email_message,email_status,email_dated,volunteer,rand_id,other_volunteers)
VALUES('$tarid','$teacher_id','$county_name','$district_name','$school_name','$school_city','$school_zip','$subject','$grades','$students','$months','$category','$category_name','$best_times','$details','$submit_time','$message','pending',now(),'$volunteerid','$rand_id','$other_volunteers')
");

//This email was requested by Lynn to be the sender for the emails to volunteers
$send_from_email = "mvmartin@ccsf.edu";

eval("\$message = \"".gettemplate('tar_email_volunteer')."\";");

// Send email to teacher
send_email($email,$teacher_email,"New Volunteering Request",$message,'text/html');
//Send email to volunteer that submitted
send_email($send_from_email,$email,"Copy: New Volunteering Request",$message,'text/html');
//Send email to other_volunteers listed
$separatedemails = explode(",",$other_volunteers);
foreach($separatedemails as $value) {
	send_email($send_from_email,$value,"Copy: New Volunteering Request",$message,'text/html');
}
// Write email to file
/*if ($fp = fopen("$website_email_path/$rand_id","w"))
{
	fwrite($fp,$message);
	fclose($fp);
}*/

?>