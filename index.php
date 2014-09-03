<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
getsettings();

if (isset($_POST["login"]))
{
	$email = sql_safe($_POST["email"]);
	$password = sql_safe($_POST["password"]);
	$flag = 0;
	$login_msg = 'There were errors with your login:<ul style="color:#580000;font-weight:normal;">';
	
	if ($email && $password)
	{
		$result = $DB_site->query("SELECT id,email,password FROM volunteers WHERE email='$email' AND account_status ='Active'");
		
		$matched_members = array();
		while($row = $DB_site->fetch_array($result)) {
			$matched_members[] = $row;
		}
		
		foreach($matched_members as $matched_member) {
			list($member_id, $member_email, $member_password) = $matched_member;
		
			// Check if the password is hashed
			$hash_sections = explode(":", $member_password);
		    if(($hash_sections != HASH_SECTIONS) && ($hash_sections[HASH_ALGORITHM_INDEX] != PBKDF2_HASH_ALGORITHM) && ($hash_sections[HASH_ITERATION_INDEX] != PBKDF2_ITERATIONS)) {
		    	$correct_hash = create_hash($member_password);
				$DB_site->query("UPDATE volunteers SET password='$correct_hash' WHERE id='$member_id' AND email='$member_email'");
			} else {
				$correct_hash = $member_password;
			}
			
			if ($member_id && (validate_password($password, $correct_hash))) {
				$matched_id = $member_id;
				init_session($member_id,$member_email,'volunteer',$session_logout);
				break;
			}
	     }
	     
		 if(isset($matched_id)) {
			if(count($matched_members) > 1) {
				$DB_site->query("UPDATE tars SET volunteer='$matched_id' WHERE volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
				$DB_site->query("UPDATE tars_emails SET volunteer='$matched_id' WHERE volunteer=ANY( SELECT id FROM volunteers WHERE email='$email')");
				$DB_site->query("DELETE FROM volunteers WHERE email='$email' AND id!='$matched_id'");
			}
		 } else {
		 	$flag = 1;
	        $login_msg.="<li>If you’re not registered, please Register below. If registered, you may need to activate your account.</li>";
		 }
	}
	else
	{
      $flag = 1;
      $login_msg.="<li>Please enter both email and password to login.</li>";
	}
	
	if ($flag == 0)
	{
		header("Location: volunteers.php") ;
	}
} elseif (isset($_POST['reset_pw'])) {
	$email = sql_safe($_POST["email"]);
	if ($email) {
	    list($volunteer_id,$volunteer_fname,$volunteer_lname) = $DB_site->query_first("SELECT id,fname,lname FROM volunteers WHERE email='$email'");
		if ($volunteer_id) {
			$time_now = gettimeofday();
			$hash = create_hash($email.$time_now[sec].$time_now[usec]);
			$hash_sections = explode(":", $hash);
			$activation_code = urlencode($hash_sections[HASH_PBKDF2_INDEX]);
			$DB_site->query("UPDATE volunteers SET activation_code='$activation_code', account_status='Inactive' WHERE id='$volunteer_id'");	
			$forgot_password_message = "Dear $volunteer_fname $volunteer_lname,
		
We recently received a password reset request for your account. 
	
Please reset your password by clicking the following link: $website_dir/forgot_password.php?$activation_code
	
Regards,
The Volunteer Match Team";
			$headers = "From: $do_not_reply_email\r\n";
			mail($email,"Volunteer Match Password Reset",$forgot_password_message,$headers);
			$flag = 1;
			$login_msg = '<strong><span style="color:green;">Login information has been sent to your email address. Please check your inbox.</strong><br />';
		} else {
			$flag = 1;
			$login_msg = "<strong>This e-mail address is not registered.</strong><br />";
		}
	} else {
		$flag = 1;
		$login_msg = "<strong>Email address is required.</strong><br />";
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Volunteer Match Login</title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
    </head>
    <body>
	 <?php require("header.php"); ?>
	 <section>

        
        <form method="POST" id="login">
        	<?php if ($flag != 0) {echo($login_msg);} ?>
            <fieldset>
                 <input id="email" name="email" required type="email" placeholder="Email"/><br>
                 <input id="password" name="password" type="password" placeholder="Password" /> <br/>
                 <input type="submit" name="login" value="Login" />
                 <input type="submit" name="reset_pw" value="Forgot Password" />
             </fieldset>
			
            <a href="registration.php" style="float:left;">Register</a>
            <a href="teachers.php" style="float:right;">Teachers</a>		     
    </form> 
            
        </section>
       <?php require("footer.php"); ?>
    </body>
</html>