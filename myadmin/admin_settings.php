<?php

error_reporting(5);

$admin_settings_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$navbar = ${$mtype."_nav_bar"};

if ($mtype != "admin" AND $mtype != "manager")
{
	exit;
}

if (eregi("$website_admin_settings",$_SERVER['HTTP_REFERER']) AND $submit == "Update Settings")
{
	foreach ( $HTTP_POST_VARS as $key=>$value)
	{
		if ( ($key != "submit") AND ($key != "PHPSESSID") ) {
			//print "$key=$value<br>";
			$update = $DB_site->query("UPDATE settings SET value='$value' where varname='$key'");
			if (!$update) {echo "Unable to update $key";}
		}
	}
}


$options = $DB_site->query("SELECT settingid,settinggroupid,grouptitle,title,varname,value,description,optioncode,displayorder FROM settings ORDER BY settingid ASC");
if (!$options) {Error("Cannot grab settings from database");}
$string = "<form method='post' action='$website_admin_settings'><input type=hidden name='PHPSESSID' value='$PHPSESSID'>";
$string .= "<TABLE width='100%' border='0'>"; 
$string .= "<TR><TD colspan='2'><font face='Arial, Helvetica, sans-serif' size='2'><b>$website_title Settings</b></font></TD></TR>";
while($array = $DB_site->fetch_array($options))
{
	if (isset($groupid) AND $groupid != $array[1])
	{
		$string.= "</TABLE><br><TABLE width='100%' BORDER='0'>";
		$string.= "<TR class='tblhead'><TD colspan='2><span class='tblhead'>$array[2]</span></TD></TR>";
		$groupid = $array[1];
	}
	elseif (!(isset($groupid)))
	{
		$string.= "<TR><TD colspan='2'>$array[2]</TD></TR>";
		$groupid = $array[1];
	}
	else
	{
		$groupid = $array[1];
	}
	$string.= "<TR><TD width='40%'>$array[3]</TD><TD width='60%'>".form_element($array[4],$array[5],$array[7])."</TD></TR>";
}
$string.="<TR><TD colspan=2 align='center'><input type=submit name='submit' value='Update Settings'><input type=reset name=reset value='Cancel'></TD></TR>";
$string.="</TABLE></form>";

?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Admin Settings</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'><?php echo $navbar?></td><td valign='top'>
<?php echo $string?>
</td></tr></table>
<div align='center'><hr size='1'>
Copyright &copy; All Rights Reserved</div>
</body></html>
