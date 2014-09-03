<?php

error_reporting(5);

$admin_templates_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$navbar = ${$mtype."_nav_bar"};

if ($mtype != "admin" AND $mtype != "manager" AND $mtype != "designer")
{
	exit;
}

// Update Template
if (ereg("$website_admin_templates",$_SERVER['HTTP_REFERER']) AND $submit == "Update Template")
{
	if (get_magic_quotes_gpc()) {
		$template_data = stripslashes($template_data);
	}
	if ($fp = fopen("$website_template_path/$tpl.tpl","w"))
	{
    fwrite($fp,$template_data);
    fclose($fp);
    $msg = "Template Updated";
  }
	else
	{
    $msg = "Permission Denied.";
  }
}

if (ereg("$website_admin_templates",$_SERVER['HTTP_REFERER']) AND isset($tpl) AND $action == 'revert')
{
    $template_data = implode("",file("$website_template_path/backup/$tpl.tpl"));
    
    if ($fp = fopen("$website_template_path/$tpl.tpl","w"))
    {
      fwrite($fp,$template_data);
      fclose($fp);
      $msg = "Template Restored";
    }
    else
    {
      $msg = "Permission Denied.";
    }
}
elseif (ereg("$website_admin_templates",$_SERVER['HTTP_REFERER']) AND isset($tpl) AND $action == 'replace')
{
    $template_data = implode("",file("$website_template_path/$tpl.tpl"));
    
    if ($fp = fopen("$website_template_path/backup/$tpl.tpl","w"))
    {
      fwrite($fp,$template_data);
      fclose($fp);
      $msg = "Template Replaced";
    }
    else
    {
      $msg = "Permission Denied.";
    }
}
elseif ($action == "view" && isset($tpl))
{
  $template_data = implode("",file("$website_template_path/$tpl.tpl"));
	$template_data = eregi_replace(">","&gt;",$template_data);
	$template_data = eregi_replace("<","&lt;",$template_data);
	$template_data = eregi_replace("\"","&quot;",$template_data);
  
	$string = "<form method='post' action='$website_admin_templates'>";
	$string.= "<input type=hidden name='PHPSESSID' value='$PHPSESSID'>";
	$string.= "<input type=hidden name='tpl' value='$tpl'>";
	$string.= "<TABLE width='100%' border='0'>";
	$string.= "<TR><TD colspan='2'>Please Update the template and Press Update Template</TD></TR>";
	$string.= "<TR><TD colspan='2'>Template Title : $tpl</td></tr>";
	$string.= "<TR><TD colspan='2'><TEXTAREA name='template_data' cols='100' rows='25'>$template_data</TEXTAREA></td></tr>";
	$string.= "<TR><TD colspan='2'><input type=submit name='submit' value='Update Template'><input type=reset name=reset value='Reset'><input type=submit name=submit value='Cancel'></TD></TR>";
	$string.= "</TABLE>";
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Templates</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'>
<?php echo $navbar?>
</td>
<td><pre><?php echo $string?></pre></td></tr></table>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
<?php
exit;
}

$current_templates = "<TABLE border='1'>"; 
$current_templates.= "<TR><TD colspan='2'>$msg</TD></TR>";
$current_templates.= "<TR class='tblhead'><TD colspan='2'>Here are the available templates</TD></TR>";

$dir_array = Array();

if ($handle=opendir("$website_template_path"))
{
	while (($file = readdir($handle))!==false) 
	{
		if (ereg("(.+)\.tpl",$file,$regs))
		{
			$template_title = $regs[1];
			array_push($dir_array,$template_title);
		}
	}
	closedir($handle);
	
	sort($dir_array);
	
	for ($i = 0; $i < sizeof($dir_array); $i++)
	{
      $flag = 1;
      $bgcolor = $colors[$current_color];
      
      $template_title = $dir_array[$i];
      $current_templates.= "<tr bgcolor=\"$bgcolor\">
                                  <td><span class='gc'>$template_title</span></td>
                                  <td><a href=\"$website_admin_templates?tpl=$template_title&PHPSESSID=$PHPSESSID&action=view\">View / Edit</a></td>
                                  </tr>";
      $current_color = !$current_color;
    }
}
$current_templates.="</TABLE>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Templates</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'>
<?php echo $navbar?>
</td>
<td><pre><?php echo $current_templates?></pre></td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
