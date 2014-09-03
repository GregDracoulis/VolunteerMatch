<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
require_once('recaptchalib.php');
getsettings();

$publickey = "6LdPHu4SAAAAALllMxN7Heb8nyoY1pb_lWW3uWMt"; // get this from the recaptcha signup page
$privatekey = "6LdPHu4SAAAAAKualkPRNQiKCJmEjMkAqYelrhgI"; // recaptcha private key

$key = $_SERVER['QUERY_STRING'];

if (isset($_POST["set_pw"])) {
	$password = sql_safe($_POST["password"]);
	$confirm = sql_safe($_POST["confirm"]);
	$key = sql_safe($_POST["key"]);
	
	$hashed_password = create_hash($password);
	
	$flag = 0;
	$msg = 'The following error(s) occured:<ul style="color:#580000;font-weight:normal;">';
	
	list($volunteer_id,$volunteer_email) = $DB_site->query_first("SELECT id,email FROM volunteers WHERE activation_code='$key' AND account_status='Inactive'");
	list($teacher_id,$teacher_email) = $DB_site->query_first("SELECT id,email FROM teachers WHERE activation_code='$key' AND account_status='Inactive'");
	
	$resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$flag = 1;
		$msg .= "<li>Catptcha not valid. Please try again.</li>";
	}
	
	if(!($password && $confirm)) {
		$flag = 1;
		$msg .= "<li>Please fill in all the fields</li>";
	} elseif($password != $confirm) {
		$flag = 1;
		$msg .= "<li>Password and confirmation must match</li>";
	}
	
	// Check password length
	if (strlen($password) < 5)
	{
		$flag = 1;
		$register_msg .= "<li>Password too short, Please use at least a 5 character Password</li>";
	}
	
	if($flag == 0){
		if($volunteer_id) {
			$DB_site->query("UPDATE volunteers SET account_status='Active', activation_code=NULL, password='$hashed_password' WHERE id='$volunteer_id'");
			init_session($volunteer_id,$volunteer_email,'volunteer',$session_logout);
			Header("Location: volunteers.php");
			exit;
		} elseif ($teacher_id) {
			$DB_site->query("UPDATE teachers SET account_status='Active', activation_code=NULL, password='$hashed_password' WHERE id='$teacher_id'");
			init_session($member_id,$teacher_email,'teacher',$session_logout);
			Header("Location: teachers.php");
			exit;
		} else {
			$flag = 1;
			$msg .= '<li>There seems to be a problem. Please check your password reset link.</li>';
		}
	}
	
	$msg .= "</ul>";
}


?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Volunteer Match Password Reset</title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
        <script type="text/javascript">
        	var RecaptchaOptions = {
			    theme : 'white'
			 };
        </script>
    </head>
	<body>
		<?php require("header.php"); ?>
        <form method="POST" style="margin:50px 30%;text-align:center;">
        	<?php if ($flag != 0) {echo($msg);} ?>
            <fieldset>
                 <input id="password" name="password" required type="password" placeholder="Password" style="margin-left:auto;margin-right:auto;"/><br>
                 <input id="password" name="confirm" required type="password" placeholder="Confirm Password" style="margin-left:auto;margin-right:auto;"/> <br/>
                 <?php echo recaptcha_get_html($publickey);?>
                 <input type="hidden" name="key" value="<?php echo $key ?>" />
                 <input type="submit" name="set_pw" value="Set Password" />
             </fieldset>
    	</form> 
		<?php require("footer.php"); ?>
	</body>
</html>