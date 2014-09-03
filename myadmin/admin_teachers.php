<?php

error_reporting(5);

$admin_teachers_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$records_per_page = 50;
$max_pages = 10;

$navbar = ${$mtype."_nav_bar"};

if ($mtype != "admin" AND $mtype != "manager")
{
	exit;
}

if ($_GET["doaction"] == "teachers_type")
{
  setcookie("teachers_type","$show");
  $teachers_type = $show;
}
else if ($_COOKIE["teachers_type"] != "")
  $teachers_type = $_COOKIE["teachers_type"];
else
{
  setcookie("teachers_type","Active");
  $teachers_type = "Active";
}

if ($_GET["doaction"] == "export")
{
  $sortby = $_GET["sortby"];
  $orderby = $_GET["orderby"];
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename=teachers.csv");
  print "\"ID#\",\"Name\",\"Email\",\"Phone\",\"Date Registered\",\"County\",\"School District\",\"School Name\",\"# of Active Requests\",\"Last Request Date\",\"Comments\"\r\n";
	$query = $DB_site->query("SELECT id,fname,lname,email,phone,school,account_status,comments,DATE(submit_time) FROM teachers WHERE account_status='$teachers_type' ORDER BY $sortby");
  while (list($teacher_id,$fname,$lname,$email,$phone,$school,$account_status,$comments,$submit_date) = $DB_site->fetch_array($query))
  {
    list($county,$district,$school_name) = $DB_site->query_first("SELECT county,district,school_name FROM schools WHERE id='$school'");
    list($active_requests) = $DB_site->query_first("SELECT count(*) FROM tars WHERE teacher_id='$teacher_id' AND tar_status='pending'");
    list($last_request_date) = $DB_site->query_first("SELECT DATE(submit_time) FROM tars WHERE teacher_id='$teacher_id' ORDER BY ID DESC LIMIT 1");
    $comments = eregi_replace("[\r|\n|\"]","",$comments);
    print "\"$teacher_id\",\"$fname $lname\",\"$email\",\"$phone\",\"$submit_date\",\"$county\",\"$district\",\"$school_name\",\"$active_requests\",\"$last_request_date\",\"$comments\"\r\n";
  }
  exit;
}



if ($_POST["doaction"] == "activate_teacher")
{
	$targetuser = urldecode($targetuser);
  $comments = sql_safe($comments);
  $new_comments = date("F j, Y, g:i a") . " Admin added:\n" . $comments . "\n--------------\n\n";
  
  $result = $DB_site->query("UPDATE teachers SET account_status='Active',comments = CONCAT(comments,'$new_comments') WHERE id='$targetuser'");
  $msg = "Teached Reactivated successfully.";
}

if ($_GET["doaction"] == "Activate")
{
	$targetuser = urldecode($targetuser);
	list($teacher_id,$fname,$lname,$email,$school,$account_status,$comments,$submit_date) = $DB_site->query_first("SELECT id,fname,lname,email,school,account_status,comments,DATE(submit_time) FROM teachers where id='$targetuser'");
  
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Teachers</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h1><?php echo $website_title?> Teachers</h1></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="center" width="800">
  <form method=post action='<?php echo $website_admin_teachers?>'>
  <input type=hidden name='targetuser' value='<?php echo $targetuser?>'>
  <input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
  <input type=hidden name='doaction' value='activate_teacher'>

  <table border="0" cellspacing="0">
    <tr> 
      <td colspan="2"> 
        <div align="center"><font size="5" color="#FFFFFF">Reactivate Teacher</font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr size='1'>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">ID:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo $teacher_id?>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Name:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo "$fname $lname"?> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Email:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo $email?> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Add Comments:
      </td>
      <td>
        <font size="2" color="#000000"><textarea name='comments'  cols='40' rows='5'></textarea> 
      </td>
    </tr>

    <tr><td colspan="2"><hr size='1'></td></tr>
    <tr>
      <td>
        <font size="2" color="#000000">
      </td>
      <td>
        <font size="2" color="#000000"><input type=submit name='Submit' value='Confirm Reactivation'>
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

if ($_POST["doaction"] == "deactivate_teacher")
{
	$targetuser = urldecode($targetuser);
  $comments = sql_safe($comments);
  $new_comments = date("F j, Y, g:i a") . " Admin added:\n" . $comments . "\n---------------\n\n";
  
  $result = $DB_site->query("UPDATE teachers SET account_status='Inactive',comments = CONCAT(comments,'$new_comments') WHERE id='$targetuser'");
  $msg = "Teached Deactivated successfully.";
}

if ($_GET["doaction"] == "Deactivate")
{
	$targetuser = urldecode($targetuser);
	list($teacher_id,$fname,$lname,$email,$school,$account_status,$comments,$submit_date) = $DB_site->query_first("SELECT id,fname,lname,email,school,account_status,comments,DATE(submit_time) FROM teachers where id='$targetuser'");
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Teachers</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h1><?php echo $website_title?> Teachers</h1></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="center" width="800">
  <form method=post action='<?php echo $website_admin_teachers?>'>
  <input type=hidden name='targetuser' value='<?php echo $targetuser?>'>
  <input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
  <input type=hidden name='doaction' value='deactivate_teacher'>

  <table border="0" cellspacing="0">
    <tr> 
      <td colspan="2"> 
        <div align="center"><font size="5" color="#FFFFFF">Deactivate Teacher</font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr size='1'>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">ID:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo $teacher_id?>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Name:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo "$fname $lname"?> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Email:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo $email?> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Add Comments:
      </td>
      <td>
        <font size="2" color="#000000"><textarea name='comments' cols='40' rows='5'></textarea> 
      </td>
    </tr>

    <tr><td colspan="2"><hr size='1'></td></tr>
    <tr>
      <td>
        <font size="2" color="#000000">
      </td>
      <td>
        <font size="2" color="#000000"><input type=submit name='Submit' value='Confirm Deactivation'>
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

$sort_string = "";

if ($field_value != '')
{
  $jump_query = " (id LIKE '%$field_value%' OR 
				  email LIKE '%$field_value%' OR 
				  fname LIKE '%$field_value%' OR 
				  lname LIKE '%$field_value%' OR 
				  phone LIKE '%$field_value%' OR
				  school LIKE '%$field_value%' OR
				  comments LIKE '%$field_value%' OR 
				  details LIKE '%$field_value%') ";	
  $sort_string .= "field_name=$field_name&field_value=$field_value";	
}

$sort_array = array();
$query_array = array();
if ($sortby == "") {$sortby = "ID";}
if ($orderby == "") {$orderby = "ASC";}
$sort_query = "$sortby $orderby";
if (trim($sort_string) == "") {
	$sort_string = "?";
}
else {
	$sort_string = "?".$sort_string."&";
}

if (isset($list))
{
	$listquery = " WHERE lname REGEXP '^$list' AND account_status='$teachers_type' ";
	if ($jump_query != '') {$listquery.= "AND $jump_query";}
	$sort_string .= "&list=$list&";
}
elseif ($jump_query != '') {$listquery = "WHERE $jump_query AND account_status='$teachers_type' ";}
else {$listquery = " WHERE account_status='$teachers_type' ";}

$field = trim($field);
$text = trim($text);

// Get the Pages
list($total_count) = $DB_site->query_first("SELECT count(*) FROM teachers $listquery");

if ($total_count > $records_per_page)
{
	$pages = $total_count/$records_per_page;
	if (gettype($pages) == "double")
	{
		settype($pages,integer);
		$pages++;
	}
  // Show the First Page		
	if (!isset($page))
	{
		$page=1;
		$string_pages = ".Top $records_per_page are shown.You can Go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_teachers".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";

		for($count=1;$count<=$pages;$count++)
		{
		  // Show only max pages
		  if ($count == $max_pages)
		  {
        $string_pages.="...";
        continue;
      }
      if ($count > $max_pages) {continue;}
      
			if ($count != $page) {
				$string_pages.="<a href='$website_admin_teachers".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
			$string_pages.="</a>";
			}
		}
		$string_pages.="<a href='$website_admin_teachers".$sort_string."page=2&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		$string_pages.="<a href='$website_admin_teachers".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a>";
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,fname,lname,email,phone,school,account_status,comments,DATE(submit_time) FROM teachers $listquery ORDER BY $sort_query LIMIT $records_per_page");
		$string_pages.=" ]";
	}
	else // Show the Requested Page
	{
		$string_pages = " You can go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_teachers".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";
		if ($page > 1) {
			$string_pages.="<a href='$website_admin_teachers".$sort_string."page=".($page-1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;</a>&nbsp;&nbsp;";
		}

		for($count=1;$count<=$pages;$count++)
		{
		  // Show only max pages on either side of selected page
		  if ( (($page - $count) == $max_pages/2) || (($count - $page) == $max_pages/2) )
		  {
        $string_pages.="...";
        continue;
      }
		  if ( (($page - $count) > $max_pages/2) || (($count - $page) > $max_pages/2) )
		  {
        continue;
      }
      
			if ($count != $page) {
				$string_pages.="<a href='$website_admin_teachers".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
				$string_pages.="</a>";
			}
		}
		if ($page < $pages) {
			$string_pages.="<a href='$website_admin_teachers".$sort_string."page=".($page+1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		}
		$string_pages.="<a href='$website_admin_teachers".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a> ";
		$start_limit=($page-1)*$records_per_page;
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,fname,lname,email,phone,school,account_status,comments,DATE(submit_time) FROM teachers $listquery ORDER by $sort_query LIMIT $start_limit,$records_per_page");
		$string_pages.=" ]";
	}
}
else
{
	$page = $pages = 1;
	$string_pages = "";
	if (!isset($sortby)) {$sortby = "id ASC";}
	$result = $DB_site->query("SELECT id,fname,lname,email,phone,school,account_status,comments,DATE(submit_time) FROM teachers $listquery ORDER BY $sort_query");
}


$count1 = 0;
$color = 0;


if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

while (list($teacher_id,$fname,$lname,$email,$phone,$school,$account_status,$comments,$submit_date) = $DB_site->fetch_array($result))
{
	$flag = 1;
  $bgcolor = $colors[$current_color];
	$status_bgcolor = "";

  list($county,$district,$school_name) = $DB_site->query_first("SELECT county,district,school_name FROM schools WHERE id='$school'");
  list($active_requests) = $DB_site->query_first("SELECT count(*) FROM tars WHERE teacher_id='$teacher_id' AND tar_status='pending'");
  list($last_request_date) = $DB_site->query_first("SELECT DATE(submit_time) FROM tars WHERE teacher_id='$teacher_id' ORDER BY ID DESC LIMIT 1");

  $last_request_date = ($last_request_date  == "") ? "None" : $last_request_date;
  $county = ($county  == "") ? "NA" : $county;
  $district = ($district  == "") ? "NA" : $district;
  $school_name = ($school_name  == "") ? "NA" : $school_name;
  $active_requests = ($active_requests  == "") ? "0" : $active_requests;
  $comments = ($comments  == "") ? "None" : $comments;
  
  if ($account_status == "Active")
    $action_link = "<a href=\"$website_admin_teachers?PHPSESSID=$PHPSESSID&doaction=Deactivate&targetuser=$teacher_id\">Deactivate</a>";
  else
    $action_link = "<a href=\"$website_admin_teachers?PHPSESSID=$PHPSESSID&doaction=Activate&targetuser=$teacher_id\">Activate</a>";
    
	$current_records.="<tr bgcolor=\"$bgcolor\">
    	<td class=\"smallText\">$teacher_id</font></td>
		  <td class=\"smallText\">$lname, $fname</td>
		  <td class=\"smallText\">$email</td>
		  <td class=\"smallText\">$phone</td>
		  <td class=\"smallText\">$submit_date</td>
		  <td class=\"smallText\">$county</td>
		  <td class=\"smallText\">$district</td>
		  <td class=\"smallText\">$school_name</td>
		  <td class=\"smallText\"><a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&field_name=teacher_id&field_value=$teacher_id&field_type=exact&&doaction=tars_type&show=Pending&special_field=of+Teacher+$fname+$lname\">$active_requests</a></td>
		  <td class=\"smallText\">$last_request_date</td>
		  <td class=\"smallText\">$action_link</td>
     <!-- </tr>
     <tr bgcolor=\"$bgcolor\"> --><td colspan='10' class=\"smallText\">Comments: <pre>$comments</pre></td></tr>
     ";
  $current_color = !$current_color;
}

$list_string = "<TR><TD colspan=\"12\"> List by Teacher Last Name: ";
for($i=65;$i<=90;$i++)
{
	$list_string.="<a href='$website_admin_teachers?list=".chr($i)."&sortby=lname&orderby=".urlencode($orderby)."&PHPSESSID=$PHPSESSID'> ".chr($i)." </a>";
}
$list_string.="&nbsp;&nbsp;[<a href=\"$website_admin_teachers?PHPSESSID=$PHPSESSID\">List all Teachers</a>]</TD></TR>";

if ($teachers_type == "Active")
  $active_teachers_link = "<strong>Showing Active Teachers</strong>";
else
  $active_teachers_link = "<a href=\"$website_admin_teachers?PHPSESSID=$PHPSESSID&doaction=teachers_type&show=Active\">Show Active Teachers</a>";

if ($teachers_type == "Inactive")
  $inactive_teachers_link = "<strong>Showing Inactive Teachers</strong>";
else
  $inactive_teachers_link = "<a href=\"$website_admin_teachers?PHPSESSID=$PHPSESSID&doaction=teachers_type&show=Inactive\">Show Inactive Teachers</a>";
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Teachers</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="left" width="800">
    <div align="center"><strong><?php echo $msg?></strong></div>
    <table border='1' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="12"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current <?php echo $teachers_type?> Teachers</font> [<?php echo $active_teachers_link?> | <?php echo $inactive_teachers_link?>]</td></tr>
		<?php echo $list_string?>
		<form method=post action='<?php echo $website_admin_teachers?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<tr><td colspan="8">
          <input type=text name='field_value' value='<?php echo $field_value?>'> <input type=submit name='Submit' value='Search'> [<a href='<?php echo $website_admin_teachers?>'>Clear Search</a>]
        </td>
        <td colspan="4"><a href="<?php echo $website_admin_teachers?>?PHPSESSID=<?php echo $PHPSESSID?>&doaction=export&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>">Download CSV File</a></td>
        </tr>
		</form>
		<tr><td colspan="12" align="center"><font face="Georgia, Times New Roman, Times, serif" size="2" color="#FFFFFF">
		<form method=post action='<?php echo $website_admin_teachers?>'>
		<a href="<?php echo $website_admin_teachers?><?php echo $sort_string?>page=<?php echo $page?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/first.gif" border="0"></a> 
		<a href="<?php echo $website_admin_teachers?><?php echo $sort_string?>page=<?php echo (($page-1) > 1) ? ($page-1) : 1; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/previous.gif" border="0"></a> 
		Page <input type="text" size="3" name="page" id="page" value="<?php echo $page?>" onChange="window.location.href='<?php echo $website_admin_teachers?><?php echo $sort_string?>page=' + this.value + '&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>';"> of <?php echo $pages?> 
		<a href="<?php echo $website_admin_teachers?><?php echo $sort_string?>page=<?php echo (($page+1) < $pages) ? ($page+1) : $pages; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/next.gif" border="0"></a> 
		<a href="<?php echo $website_admin_teachers?><?php echo $sort_string?>page=<?php echo $pages?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/last.gif" border="0"></a> 
		</form>
		<!--There are <?php echo $total_count?> total <?php echo $teachers_type?> teachers<?php echo $string_pages?>-->
		
		</font></td></tr>
<?php
// Flip The Order	
if ($orderby == "ASC") 
{
	${"sort_" . $sortby} = "<img src='../images/asc.gif' border='0' title='Ascending' />";
	$orderby = "DESC";
}
elseif ($orderby == "DESC") 
{
	${"sort_" . $sortby} = "<img src='../images/desc.gif' border='0' title='Descending' />";
	$orderby = "ASC";
}	
?>		
    <tr>
		<td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_teachers?><?php echo $sort_string?>sortby=id&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>ID</a> <?php echo $sort_id?></b></td>
		<td width='15%' class="mediumText"><b><a href='<?php echo $website_admin_teachers?><?php echo $sort_string?>sortby=lname&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Name</a> <?php echo $sort_lname?></b></td>
        <td width='15%' class="mediumText"><b><a href='<?php echo $website_admin_teachers?><?php echo $sort_string?>sortby=email&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Email</a> <?php echo $sort_email?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_teachers?><?php echo $sort_string?>sortby=phone&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Phone</a> <?php echo $sort_phone?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_teachers?><?php echo $sort_string?>sortby=submit_time&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Date Registered</a> <?php echo $sort_submit_time?></b></td>
        <td width='10%' class="mediumText"><b>County </b></td>
        <td width='10%' class="mediumText"><b>School District</b></td>
        <td width='10%' class="mediumText"><b>School Name</b></td>
        <td width='5%' class="mediumText"><b># of Active Requests</b></td>
        <td width='10%' class="mediumText"><b>Last Request Date</b></td>
        <td width='5%' class="mediumText"><b>Action</b></td>
        <td width='5%' class="mediumText"><b>Comments</b></td>
     </tr>
	<?php echo $current_records?>
	</table>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
