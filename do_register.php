<?php 

include "global.php"; // Get Configuration
include "hashing_security.php";
require_once('recaptchalib.php');
getsettings();

session_start();

$privatekey = "6LdPHu4SAAAAAKualkPRNQiKCJmEjMkAqYelrhgI"; // recaptcha private key

if (isset($_POST["register"]))
{
	$email = sql_safe($_POST["email"]);
	$password = sql_safe($_POST["password"]);
	$rpassword = sql_safe($_POST["rpassword"]);
	$fname = sql_safe($_POST["fname"]);
	$lname = sql_safe($_POST["lname"]);
	$company = sql_safe($_POST["company"]);
	$other_company = sql_safe($_POST["other_company"]);
	$title = sql_safe($_POST["title"]);
	$industry = sql_safe($_POST["industry"]);
	$phone = sql_safe($_POST["phone"]);
	$address = sql_safe($_POST["address"]);
	$details = sql_safe($_POST["details"]);
	
	$hashed_password = create_hash($password);
	
	$flag = 0;
	$register_msg = 'The following error(s) occured during registration:<ul style="color:#580000;font-weight:normal;">';
	
	$resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$register_msg .= "<li>Catptcha not valid. Please try again.</li>";
		$flag = 1;
	}

	// Check password length
	if (strlen($password) < 5)
	{
		$register_msg .= "<li>Password too short, Please use at least a 5 character Password</li>";
		$flag = 1;
	}

	// Compare password and repeat password
	if ($password != $rpassword)
	{
		$register_msg .= "<li>Password and Confirmation don't match. Please try again.</li>";
		$flag = 1;
	}
	
	// Filter out Bad Emails
	if (!check_email_address($email))
	{
		$register_msg .= "<li>Invalid email address syntax.</li>";
		$flag = 1;
	}
	
	if ($other_company != "") // Other company given
	{
		$company_name = trim($other_company);
		if ($company_name != "") // add other to database if not there
		{
			list($check) = $DB_site->query_first("SELECT count(*) FROM companies WHERE company_name='$company_name'");
			if ($check <= 0)
			{
				$result = $DB_site->query("INSERT INTO companies (company_name,submit_time) VALUES('$company_name',now())");
			}
		}
	}
	else
	{
		list($company_name) = $DB_site->query_first("SELECT company_name FROM companies WHERE id='$company'");
	}
	if ($company_name == "")
	{
		$register_msg .= "<li>Please fill in the Company Name</li>";
		$flag = 1;
	}
	
	list($count) = $DB_site->query_first("SELECT count(*) FROM volunteers where email='$email'");
	if ($count > 0)
	{
		$register_msg.= '<li>Email already exists in the database. Please <a href="index.php">log in</a> instead.</li>';
		$flag = 1;
	}
	
	$register_msg .= "</ul>";
	
	if ($flag == 0)
	{
		$time_now = gettimeofday();
		$hash = create_hash($email.$time_now[sec].$time_now[usec]);
		$hash_sections = explode(":", $hash);
		$activation_code = urlencode($hash_sections[HASH_PBKDF2_INDEX]);

		$DB_site->query("INSERT INTO volunteers (password,email,fname,lname,phone,company,title,industry,address,details,activation_code,account_status,submit_time,comments)
			VALUES('$hashed_password','$email','$fname','$lname','$phone','$company_name','$title','$industry','$address','$details','$activation_code','Unconfirmed',now(),'')");
								
		$activation_message = "Welcome to Volunteer Match!

You, or someone using your email address, recently registered with us. You can complete the process by clicking the following link: $website_dir/activate.php?$activation_code

If this is an error, ignore this email and you will be removed from our mailing list.

Thanks!
The Volunteer Match Team";

		$headers = "From: $do_not_reply_email\r\n";
		mail($email,"Volunteer Match Registration--Action Required",$activation_message,$headers);
	} else {
		$_SESSION["register_msg"] = $register_msg;
		header("Location: registration.php") ;
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Volunteer Match Registration</title>
		<link rel="stylesheet" type="text/css" href="eweek.css" />
	</head>
	<body><?php require("header.php") ?>
		<div style="text-align:center;"><br /><br />
		<h1 style="font-size:x-large;font-weight:bold;">Thanks for Signing Up!</h1>
		<h2>The account for <?php echo $email ?> has been registered.</h2>
		<p>Check your email for the activation link that's on its way from <a href="mailto:<?php echo $do_not_reply_email ?>"><?php echo $do_not_reply_email ?></a></p>
		<br /><br />
		<?php require("footer.php") ?>
	</body>
</html>