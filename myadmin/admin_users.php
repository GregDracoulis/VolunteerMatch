<?php

error_reporting(5);

$admin_users_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$navbar = ${$mtype."_nav_bar"};

if ($mtype != "admin" AND $mtype != "manager")
{
	exit;
}
elseif ($mtype == "admin")
{
	$filter_string = "AND mtype != 'admin' ";
	$select_type = "<select name='newmtype'><option value='manager'>Manager</option><option value='user'>User</option></select>";

}
elseif ($mtype == "manager")
{
	$filter_string = "AND mtype != 'admin' AND mtype != 'manager' ";
	$select_type = "<select name='newmtype'><option value='user'>User</option></select>";
}


if ($Submit == "Delete" AND $uid != "")
{
	$DB_site->query("DELETE FROM admin WHERE ID='$uid' $filter_string");
	$msg = "User Deleted Successfully.";
}

if ($Submit == "Add New User" AND $newusername != "" AND $newpassword != "" AND $newmtype != "")
{
	$flag = 0;
	if ($mtype != "admin")
	{
		if ($mtype == "manager" AND $newmtype == "admin") {$flag = 1;}
		elseif ($mtype == "user") {$flag = 1;}
	}
	elseif ($mtype == "admin" AND $newmtype == "admin") {$flag = 1;}
	if ($flag == 0)
	{
		$DB_site->query("INSERT INTO admin (username,password,mtype) VALUES('$newusername','$newpassword','$newmtype')");
		$msg = "User Added Successfully.";
	}
	else
	{
		$msg = "User Addition Failed. Permission Denied.";
	}
}

$current_users = "<TABLE width='100%' border='0'>
<TR bgcolor='FFCCCC'><TD colspan='4' align='center'><font size='4'><b>Existing Users</b></font></TD></TR>
<TR bgcolor='FFCCCC'><TD colspan='4' align='center'><font size='4'><b>$msg</b></font></TD></TR>
<TR bgcolor='CCCCCC'><TD>Username</TD><TD>Password</TD><TD>Type</TD><TD>Action</TD></TR>";
$query = $DB_site->query("SELECT id,username,password,mtype FROM admin WHERE 1=1 $filter_string ORDER BY ID ASC");
while ($array = $DB_site->fetch_array($query))
{
	$current_users.= "<TR><TD>$array[1]</TD><TD>$array[2]</TD><TD>$array[3]</TD><TD><a href='$website_admin_users?PHPSESSID=$PHPSESSID&Submit=Delete&uid=$array[0]'>Delete</a></TD></TR>";
}
$current_users.= "</TABLE>";
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Admin Users</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="left" width="800">
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr> 
	  <td>
		<?php echo $current_users?>
          </td>
        </tr>
        <tr> 
	  <td>
		<hr size='1'>
          </td>
        </tr>
        <tr> 
	  <td>
		<form method=post action='<?php echo $website_admin_users?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<TABLE width='100%' border='0'>
			<TR bgcolor='FFCCCC'><TD colspan='2' align='center'><font size='4'><b>Add New User</b></font></TD></TR>
			<TR><TD>Username</TD><TD><input type=text name='newusername'></TD></TR>
			<TR><TD>Password</TD><TD><input type=password name='newpassword'></TD></TR>
			<TR><TD>Type (Manager/User)</TD><TD><?php echo $select_type?></TD></TR>
			<TR><TD colspan='2' align='center'><input type=submit name='Submit' value='Add New User'></TD></TR>
		</TABLE>
		</form>
          </td>
        </tr>
      </table>
<?php echo $string?>
</td></tr></table>
<div align='center'><hr size='1'>
Copyright &copy; All Rights Reserved</div>
</body></html>
