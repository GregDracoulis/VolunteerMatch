<?php
function get_fingerprint()
{
	return base64_encode(hash("sha512", $_SERVER['SERVER_NAME'] . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']));
}

function init_session($member_id, $member_email, $member_type, $session_logout)
{
	session_start();
	global $DB_site;
	$session_id = base64_encode(mcrypt_create_iv(64, MCRYPT_DEV_URANDOM));
	$session_fingerprint = get_fingerprint();
	
	$result = $DB_site->query("DELETE FROM sessions WHERE email='$member_email'");
	$result = $DB_site->query("INSERT INTO sessions (member_id,email,member_type,session_id,session_fingerprint,logout_time) 
		VALUES('$member_id','$member_email','$member_type','$session_id','$session_fingerprint',DATE_ADD(now(), INTERVAL $session_logout MINUTE))");
	$_SESSION['session_id'] = $session_id;
} 

function check_session($member_type)
{
	session_start();
	global $website_home,$DB_site,$session_logout;
	$sess = $_SESSION['session_id'];
	$session_fingerprint = get_fingerprint();
	if (!($sess)) {header("Location: $website_home");}
	else
	{
		$array = $DB_site->query_first("SELECT member_id,email,session_id FROM sessions WHERE session_id='$sess' AND session_fingerprint='$session_fingerprint' AND member_type='$member_type'");
		if ($array)
		{
			return $array;
		}
		else
		{
			// unset the session variables and destroy the session	
			$_SESSION = array();
			session_destroy();
			
			$result = $DB_site->query("DELETE FROM sessions WHERE session_id='$sess'");
			$msg = "Login has expired. Please login again";
			header("Location: $website_home?msg=".urlencode($msg));
		}
	}	
}

?>
