<?php

error_reporting(5);

$admin_categories_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$records_per_page = 25;
$max_pages = 10;

$navbar = ${$mtype."_nav_bar"};

//if ($mtype != "admin" AND $mtype != "manager")
//{
//	exit;
//}

if ($_GET["doaction"] == "Deactivate")
{
	$targetuser = urldecode($targetuser);
  $result = $DB_site->query("UPDATE categories SET is_active='N' WHERE id='$targetuser'");
  $msg = "Category Deactivated successfully.";
}

if ($_GET["doaction"] == "Activate")
{
	$targetuser = urldecode($targetuser);
  $result = $DB_site->query("UPDATE categories SET is_active='Y' WHERE id='$targetuser'");
  $msg = "Category Activated successfully.";
}

if ($_GET["doaction"] == "order_up")
{
	$targetuser = urldecode($targetuser);
	list($up_id,$up_order) = $DB_site->query_first("SELECT id,display_order FROM categories WHERE display_order < $display_order ORDER BY display_order DESC LIMIT 1");
	if ($up_id != "")
	{
    $result = $DB_site->query("UPDATE categories SET display_order='$up_order' WHERE id='$targetuser'");
    $result = $DB_site->query("UPDATE categories SET display_order='$display_order' WHERE id='$up_id'");
    $msg = "Category Moved Up successfully.";
  }
}

if ($_GET["doaction"] == "order_down")
{
	$targetuser = urldecode($targetuser);
	list($up_id,$up_order) = $DB_site->query_first("SELECT id,display_order FROM categories WHERE display_order > $display_order ORDER BY display_order ASC LIMIT 1");
	if ($up_id != "")
	{
    $result = $DB_site->query("UPDATE categories SET display_order='$up_order' WHERE id='$targetuser'");
    $result = $DB_site->query("UPDATE categories SET display_order='$display_order' WHERE id='$up_id'");
    $msg = "Category Moved Down successfully.";
  }
}

if ($_POST["doaction"] == "add_category")
{
  $comments_text = sql_safe($comments_text);
  if ($category_name != "")
  {
    list($display_order) = $DB_site->query_first("SELECT display_order FROM categories ORDER BY display_order DESC LIMIT 1");
    $display_order++;
    $result = $DB_site->query("INSERT INTO categories (category_name,best_times,comments_text,is_active,display_order) 
                                          VALUES('$category_name','$best_times','$comments_text','$is_active','$display_order')
                              ");
    $msg = "Category Added successfully.";
  }
}

if ($_POST["doaction"] == "update_category")
{
	$targetuser = urldecode($targetuser);
  $comments_text = sql_safe($comments_text);
 
  $result = $DB_site->query("UPDATE categories SET category_name='$category_name',best_times='$best_times',comments_text='$comments_text',is_active='$is_active' WHERE id='$targetuser'");
  $msg = "Category Updated successfully.";
}

if ($_GET["doaction"] == "View")
{
	$targetuser = urldecode($targetuser);
	list($category_id,$category_name,$best_times,$comments_text,$is_active) = $DB_site->query_first("SELECT id,category_name,best_times,comments_text,is_active FROM categories where id='$targetuser'");
  ${"best_times_" . $best_times} = "checked";
  ${"is_active_" . $is_active} = "checked";
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Categories</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top''>
<?php echo $navbar?>
</td>
<td align='left'>
  <form method=post action='<?php echo $website_admin_categories?>'>
  <input type=hidden name='targetuser' value='<?php echo $targetuser?>'>
  <input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
  <input type=hidden name='doaction' value='update_category'>

  <table border="0" cellspacing="0">
    <tr> 
      <td colspan="2"> 
        <div align="center"><font size="5" color="#FFFFFF">Update Category</font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr size='1'>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Name:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name="category_name" value="<?php echo $category_name?>" size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">"Best Times & Days" Box included?
      </td>
      <td>
        <font size="2" color="#000000"><input type=radio name="best_times" value="Y" <?php echo $best_times_Y ?> > Yes <input type=radio name="best_times" value="N" <?php echo $best_times_N ?> > No 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Comments Text:
      </td>
      <td>
        <font size="2" color="#000000"><textarea name='comments_text' cols='40' rows='3'><?php echo $comments_text?></textarea> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Active? :
      </td>
      <td>
        <font size="2" color="#000000"><input type=radio name="is_active" value="Y" <?php echo $is_active_Y?> > Yes <input type=radio name="is_active" value="N" <?php echo $is_active_N?> > No
      </td>
    </tr>
    <tr><td colspan="2"><hr size='1'></td></tr>
    <tr>
      <td>
        <font size="2" color="#000000">
      </td>
      <td>
        <font size="2" color="#000000"><input type=submit name='Submit' value='Update Category'>
      </td>
    </tr>
  </table>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
<?php
exit;
}

$count1 = 0;
$color = 0;

if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

$result = $DB_site->query("SELECT id,category_name,best_times,comments_text,is_active,display_order FROM categories ORDER BY display_order ASC");
while (list($category_id,$category_name,$best_times,$comments_text,$is_active,$display_order) = $DB_site->fetch_array($result))
{
	$flag = 1;
  $bgcolor = $colors[$current_color];
	$status_bgcolor = "";

  if ($is_active == "Y")
    $action_link = "<a href=\"$website_admin_categories?PHPSESSID=$PHPSESSID&doaction=Deactivate&targetuser=$category_id\">Deactivate</a>";
  else
    $action_link = "<a href=\"$website_admin_categories?PHPSESSID=$PHPSESSID&doaction=Activate&targetuser=$category_id\">Activate</a>";
    
  $comments_text = ($comments_text == "") ? "-" : $comments_text;
	$current_records.="<tr bgcolor=\"$bgcolor\">
		  <td><font face='$Font1' size='2'><a href=\"$website_admin_categories?PHPSESSID=$PHPSESSID&targetuser=$category_id&doaction=View\">$category_name</a></font></td>
		  <td><font face='$Font1' size='2'>$best_times</font></td>
		  <td><font face='$Font1' size='2'>$comments_text</font></td>
		  <td><font face='$Font1' size='2'>$action_link</font></td>
		  <td><font face='$Font1' size='2'><a href=\"$website_admin_categories?PHPSESSID=$PHPSESSID&targetuser=$category_id&doaction=order_up&display_order=$display_order\">Up</a> - <a href=\"$website_admin_categories?PHPSESSID=$PHPSESSID&targetuser=$category_id&doaction=order_down&display_order=$display_order\">Down</a></font></td>
     </tr>";
  $current_color = !$current_color;
}

  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Categories</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top'>
<?php echo $navbar?>
</td>
<td valign='top' align='left'>
    <div align="center"><strong><?php echo $msg?></strong></div>
    <table border='1' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="5" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current TAR Categories</font> </td></tr>
    <tr>
		    <td width='20%'><b><font face="<?php echo $Font1?>" size='1'>Category</font></b></td>
        <td width='15%'><b><font face="<?php echo $Font1?>" size='1'>�Best Days & Times� box included?</font></b></td>
        <td width='40%'><b><font face="<?php echo $Font1?>" size='1'>Text next to comment box</font></b></td>
        <td width='10%'><b><font face="<?php echo $Font1?>" size='1'>Action</font></b></td>
        <td width='10%'><b><font face="<?php echo $Font1?>" size='1'>Re-Order</font></b></td>
      </tr>
		  <tr><?php echo $current_records?>
		 </table>
		 <hr size="1">
     <form method="post" action="<?php echo $website_admin_categories?>">
      <input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
      <input type=hidden name='doaction' value='add_category'>
     
    <table border='1' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="2" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Add New TAR Category</font> </td></tr>
    <tr><td>Category Name:</td><td><input type="text" name="category_name" size="50"></td></tr>
    <tr><td>�Best Days & Times� box included?</td><td><input type=radio name="best_times" value="Y" checked>Yes <input type=radio name="best_times" value="N">No </td></tr>
    <tr><td>Text next to comment box:</td><td><textarea name="comments_text" rows="3" cols="50"></textarea></td></tr>
    <tr><td>Is Active?</td><td><input type=radio name="is_active" value="Y" checked>Yes <input type=radio name="is_active" value="N">No </td></tr>
    <tr><td colspan="2" align="center"><input type=submit name=submit value="Add New Category"></td></tr>
      </tr>
		 </table>
     </form>     
     		 
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
