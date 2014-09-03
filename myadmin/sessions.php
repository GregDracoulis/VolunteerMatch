<?php
function check_session()
{
	global $DB_site,$website_admin_login,$PHPSESSID;
	if (isset($PHPSESSID))
	{
		$check = $DB_site->query_first("SELECT username,password,mtype from admin_sessions where session_id='$PHPSESSID' AND logout_time > now() ");
		if (!$check) {header("Location: $website_admin_login");exit;}
		$confirm = $DB_site->query_first("SELECT count(*) FROM admin WHERE username='$check[0]' AND password='$check[1]' AND mtype='$check[2]'");
		if (!$confirm) {header("Location: $website_admin_login");exit;}
		return $check;
	}
	else
	{
		header("Location: $website_admin_login");
		exit;
	}
}
?>