<?php

error_reporting(5);

include "global.php";

if ($Submit == "Resend Lost Password" AND $user != "")
{
		list($admin_email,$admin_password)=$DB_site->query_first("SELECT email,password FROM admin WHERE username='" . mysql_real_escape_string($user) . "'");
    if ($admin_email != "")
    {
		  mail($admin_email,"Lost Password Request","Lost Password you had requested is $admin_password","From: $admin_email\nX-Mailer: PHP/" . phpversion());
		  $msg = "Password has been sent to your email.";
    }
    else
      $msg = "No such user.";
}
if ($Submit == "Login" AND $user != "" AND $pass != "")
{
		$test=$DB_site->query_first("SELECT id,mtype FROM admin WHERE username='" . mysql_real_escape_string($user) . "' AND password='" . mysql_real_escape_string($pass) . "'");
		if ($test[0])
		{
			srand((double)microtime()*1000000);
			$session_id = md5(uniqid(rand()));
			$session_id = addslashes($session_id);
			$DB_site->query("DELETE FROM admin_sessions WHERE username='" . mysql_real_escape_string($user) . "'");
			$update =$DB_site->query("INSERT INTO admin_sessions (username,password,mtype,session_id,logout_time) VALUES('" . mysql_real_escape_string($user) . "','" . mysql_real_escape_string($pass) . "','$test[1]','$session_id',DATE_ADD(now(), INTERVAL $session_logout MINUTE) )");
			if (!$update) {die("Cannot insert sessions into database");}
			session_id($session_id);
			session_start();
		}
		else
		{
			show_form($user,$pass,"Invalid Username/Password. Please check and try again");
			exit;
		}
?>
<!DOCTYPE html>
<html>
<head>
<title>Loading..Please wait.</title>
<meta http-equiv="Refresh" CONTENT="0; URL=<?php echo $website_admin_home?>?PHPSESSID=<?php echo $session_id?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="1">Loading..Please 
        wait.. <a href="<?php echo $website_admin_home?>?PHPSESSID=<?php echo $session_id?>">Click here</a> if nothing happens</font></div>
    </td>
  </tr>
</table>
</body>
</html>
<?php
}
else
{
	show_form($user,$pass,"Please Login..");
	exit;
}

function show_form($user,$pass,$msg)
{
  extract($GLOBALS); 
  $ip = $_SERVER["REMOTE_ADDR"];
   
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Admin Login</title>
<script language="JavaScript">
<!--
function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_validateForm() { //v3.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (val!=''+num) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } 
re=/user/i;
errors=errors.replace(re,"Admin Username");
re=/pass/i;
errors=errors.replace(re,"Admin Password");

if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');

}
//-->
</script>
</HEAD>
<BODY bgcolor='#FFFFFF'>
<div align='center'>
<?php echo $msg?>
</div>
<FORM METHOD="POST" ACTION="<?php echo $website_admin_login?>">
  <div align='center'>
    <table width='50%' border='0'>
    <tr><td colspan="2" align="center">Please Enter Username and Password</td></tr>
    <tr>
      <td align="right" width="40%">Username: </td>
      <td><INPUT TYPE=TEXT name="user" value="<?php echo $user?>"> </td>
      </tr>
      <tr>
        <td align="right">Password: </td>
        <td><INPUT TYPE=password name="pass" value="<?php echo $pass?>"></td>
      </tr>
      <tr>
        <td colspan='2' align='center'><INPUT TYPE=SUBMIT NAME='Submit' VALUE='Login' onClick="MM_validateForm('user','','R','pass','','R');return document.MM_returnValue"> 
          <INPUT TYPE=reset name=reset value='Cancel'> <br><INPUT TYPE=submit name='Submit' value='Resend Lost Password' onClick="MM_validateForm('user','','R');return document.MM_returnValue"> </td>
      </tr>
    </table>
  </div>
</FORM>
<div align='center'><hr size='1'>
Copyright &copy; <?php echo $website_title?>, All Rights Reserved</div>

</body></html>

<?php
}
?>
