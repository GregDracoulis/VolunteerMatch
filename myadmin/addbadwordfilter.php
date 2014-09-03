<?php

error_reporting(5);

$admin_badword_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$navbar = ${$mtype."_nav_bar"};

if ($doaction == "preview_bad_word_list")
{
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Admin BadWord Filter</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'><?php echo $navbar?></td><td valign='top'>
<FORM name="form" action="<?php echo $website_admin_badword?>" method="POST" >
<input type=hidden name="PHPSESSID" value="<?php echo $PHPSESSID?>">
<input type=hidden name="doaction" value="update_bad_word_list">

	<table>
		<tr><td align="center"><b><font color="red"> Please do not use any Commas, Dashes - Q Mark ? Double Quotes<br> AND " ! _ ! @ # $ % ^ & * ( ) characters</font></b></td></tr>
		<tr><td align="center">Preview Bad Words List (One word per line)</td></tr>
		<tr><td align="center"><?php echo $msg ?></td></tr>
		<tr><td><textarea name="badwordfilterlist" cols=60 rows=10><?php echo $badwordfilterlist?></textarea></td></tr>
		</td></tr>
		<tr>
			<td align="center"><input type="submit" value="Save Bad Word List" ></td>
		</tr>
	</table>
</form>
</td></tr></table>
<div align='center'><hr size='1'>
Copyright &copy; All Rights Reserved</div>
</body></html>
<?php
	exit;
}

if ($doaction == "update_bad_word_list")
{
	$handle = fopen($badWordsFile, "w+");
	if (fwrite($handle, $badwordfilterlist) === FALSE) {
	   echo "Cannot write to file ($badWordsFile)<br>\n";
	} else {
		//echo "Created $badWordsFile<br>\n";
		$msg = "Bad Word file updated.";
	}
	fclose($handle);
}

$bad_words_list = implode("",file("$badWordsFile"));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Admin BadWord Filter</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'><?php echo $navbar?></td><td valign='top'>
<FORM name="form" action="<?php echo $website_admin_badword?>" method="POST" >
<input type=hidden name="PHPSESSID" value="<?php echo $PHPSESSID?>">
<input type=hidden name="doaction" value="preview_bad_word_list">

	<table>
		<tr><td align="center"><b><font color="red"> Please do not use any Commas, Dashes - Q Mark ? Double Quotes<br> AND " ! _ ! @ # $ % ^ & * ( ) characters</font></b></td></tr>
		<tr><td align="center">Bad Words List (One word per line)</td></tr>
		<tr><td align="center"><?php echo $msg ?></td></tr>
		<tr><td><textarea name="badwordfilterlist" cols=60 rows=10><?php echo $bad_words_list?></textarea></td></tr>
		</td></tr>
		<tr>
			<td align="center"><input type="submit" value="Preview Bad Word List" ></td>
		</tr>
	</table>
</form>
</td></tr></table>
<div align='center'><hr size='1'>
Copyright &copy; All Rights Reserved</div>
</body></html>

