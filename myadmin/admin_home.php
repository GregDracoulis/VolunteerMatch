<?php

error_reporting(5);

$admin_home_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();

list($username,$password,$mtype) = check_session();

$navbar = ${$mtype."_nav_bar"};

list($active_teachers) = $DB_site->query_first("SELECT count(*) from teachers where account_status='active'");
list($inactive_teachers) = $DB_site->query_first("SELECT count(*) from teachers where account_status != 'active'");

list($active_volunteers) = $DB_site->query_first("SELECT count(*) from volunteers where account_status='active'");
list($inactive_volunteers) = $DB_site->query_first("SELECT count(*) from volunteers where account_status != 'active'");

list($pending_tars) = $DB_site->query_first("SELECT count(*) from tars WHERE tar_status='pending'");
list($completed_tars) = $DB_site->query_first("SELECT count(*) from tars WHERE tar_status='complete'");

list($tar_emails) = $DB_site->query_first("SELECT count(*) from tars_emails");

 
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Admin Home</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?> </h2></td></tr>
<tr><td valign='top'><?php echo $navbar?></td><td valign='top'>
      <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tr> 
          <td colspan="3"> 
            <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"><b>Welcome 
              to <?php echo $website_title?> Admin Area</b></font></div>
          </td>
        </tr>
        <tr> 
          <td colspan="3"><font face="Arial, Helvetica, sans-serif" size="2">Please 
            select the adminstrative function from the navigation bar </font></td>
        </tr>
        <tr> 
          <td colspan="3"><font face="Arial, Helvetica, sans-serif" size="2"><b><?php echo $website_title?> Overview</b></font></td>
        </tr>
    	  <tr><td colspan='3'><?php echo $new_messages_alert?></td></tr>
    	  <tr><td colspan='3'><hr size='1'></td></tr>
        <tr bgcolor='#FFCCCC'> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Active Teachers</td>
          <td width="59%"><?php echo $active_teachers?></td>
        </tr>
        <tr> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Inactive Teachers</td>
          <td width="59%"><?php echo $inactive_teachers?></td>
        </tr>
        <tr bgcolor='#FFCCCC'> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Active Volunteers</td>
          <td width="59%"><?php echo $active_volunteers?></td>
        </tr>
        <tr> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Inactive Volunteers</td>
          <td width="59%"><?php echo $inactive_volunteers?></td>
        </tr>
        <tr bgcolor='#FFCCCC'> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Pending TARs</td>
          <td width="59%"><?php echo $pending_tars?></td>
        </tr>
        <tr> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Completed TARs</td>
          <td width="59%"><?php echo $completed_tars?></td>
        </tr>
        <tr bgcolor='#FFCCCC'> 
          <td width="7%">&nbsp;</td>
          <td width="34%">Total TAR Emails</td>
          <td width="59%"><?php echo $tar_emails?></td>
        </tr>
	<tr><td colspan='3'><hr size='1'></td></tr>
      </table>
</td></tr></table>
<div align='center'><hr size='1'>
Copyright &copy; All Rights Reserved</div>
</body></html>
