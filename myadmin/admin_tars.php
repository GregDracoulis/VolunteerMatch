<?php

error_reporting(5);

$admin_tars_class = "class='selected_link'";

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

if ($_GET["doaction"] == "tars_type")
{
  setcookie("tars_type","$show");
  $tars_type = $show;
}
else if ($_POST["doaction"] == "tars_type")
{
  setcookie("tars_type","$show");
  $tars_type = $show;
}
else if ($_COOKIE["tars_type"] != "")
  $tars_type = $_COOKIE["tars_type"];
else
{
  setcookie("tars_type","Pending");
  $tars_type = "Pending";
}

if ($_GET["doaction"] == "export")
{
  $sortby = $_GET["sortby"];
  $orderby = $_GET["orderby"];
  header("Content-type: text/plain");
  
  header("Content-Disposition: attachment; filename=tars-" . $tars_type . ".csv");
 // print "ID#,Teacher Name,Email,County,School District,School Name,Category of Request,Desired Months,Best Days and Times,Description,Subject,Grade Level,# of Students,Date Submitted,# of Emails for this request\r\n";
  print "\"ID#\",\"Teacher Name\",\"Daytime Phone\",\"Email\",\"County\",\"School District\",\"School Name\",\"School Street Address\",\"School City\",\"School Zip\",\"Category of Request\",\"Desired Months\",\"Best Days and Times\",\"Description\",\"Subject\",\"Grade Level\",\"# of Students\",\"Date Submitted\",\"Status\",\"Volunteer\",\"Rating\",\"Complete Date\",\"Comments\"\r\n";
	$query = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,school_city,school_zip,subject,category_name,best_times,months,details,grades,students,DATE(submit_time),tar_status,volunteer,rating,DATE(complete_time),comments,email_status FROM tars WHERE tar_status='$tars_type' ORDER BY $sortby");
  while (list($tar_id,$teacher_id,$county_name,$district_name,$school_name,$school_city,$school_zip,$subject,$category_name,$best_times,$months,$details,$grades,$students,$submit_date,
$tar_status,$volunteer,$rating,$complete_date,$comments,$email_status) = $DB_site->fetch_array($query))
  {
    $volunteer_name = $volunteer_email = "";
    list($teacher_name,$teacher_email,$teacher_phone,$school_id) = $DB_site->query_first("SELECT CONCAT(fname,' ',lname),email,phone,school FROM teachers WHERE id='$teacher_id'");
    list($emails_count) = $DB_site->query_first("SELECT count(*) FROM tars_emails WHERE tar_id='$tar_id'");
	list($school_address) = $DB_site->query_first("SELECT address FROM schools WHERE id='$school_id'");
    if ($volunteer > 0)
      list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer'");
    
    $details = eregi_replace("[\r|\n|\"]","",$details);
    $comments = eregi_replace("[\r|\n|\"]","",$comments);
    
    //print "$tar_id,$teacher_name,$teacher_email,$county_name,$district_name,$school_name,$category_name,$months,$best_times,$details,$subject,$grades,$students,$submit_date,$emails_count\r\n";
    print "\"$tar_id\",\"$teacher_name\",\"$teacher_phone\",\"$teacher_email\",\"$county_name\",\"$district_name\",\"$school_name\",\"$school_address\",\"$school_city\",\"$school_zip\",\"$category_name\",\"$months\",\"$best_times\",\"$details\",\"$subject\",\"$grades\",\"$students\",\"$submit_date\",\"$email_status\",\"$volunteer_name\",\"$rating\",\"$complete_date\",\"$comments\"\r\n";
  }
  exit;
}

if ($_GET["doaction"] == "Delete")
{
	$targetuser = urldecode($targetuser);
  $result = $DB_site->query("UPDATE tars SET tar_status='admin deleted' WHERE id='$targetuser'");
  $msg = "TAR Deleted successfully.";
}

if ($_GET["doaction"] == "modify_email_status")
{
	$targetuser = urldecode($targetuser);
	$result = $DB_site->query("UPDATE tars SET email_status='$email_status' WHERE id='$targetuser'");
	$msg = "TAR Updated successfully.";
}

if ($_GET["doaction"] == "Complete")
{
	$tid = $targetuser;
	
	if ($tid != "")
	{
		list($subject,$grades,$students,$months,$category,$best_times,$details,$submit_date) = $DB_site->query_first("SELECT subject,grades,students,months,category,best_times,details,DATE(submit_time) FROM tars WHERE id='$tid' ");
		list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");

		$volunteers_dropdown = "<select name=\"volunteer\" id=\"volunteer\">";
		$query = $DB_site->query("SELECT volunteer FROM tars_emails WHERE tar_id='$tid' GROUP BY volunteer ORDER BY ID ASC");
		while (list($volunteer_id) = $DB_site->fetch_array($query))
		{
			list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer_id'");
			$volunteers_dropdown.= "<option value='$volunteer_id'>$volunteer_name [$volunteer_email]</option>";
		}
		$volunteers_dropdown .= "<option value='0'>Not Listed</option></select>";

?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> TARs</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
<script language="JavaScript" src="functions.js"></script>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="left" width="800">
	<form method="post" action="<?php echo $website_admin_tars?>" name="tar_form" id="tar_form">
	<input type=hidden name='PHPSESSID' value="<?php echo $PHPSESSID?>">
	  <input type="hidden" name="doaction">
	  <input type="hidden" name="tid" id="tid" value="<?php echo $tid?>">
	  <h2>Mark this  Request as Completed </h2>
	  <p>*Volunteer Completing Request : <?php echo $volunteers_dropdown?><br /><br />
	  *Rating (1-5): 
	    <select id="rating" name="rating"><option value="" selected> - Select -</option><option value="1">1 - Excellent job</option><option value="2">2</option><option value="3">3 - Neutral</option><option value="4">4</option><option value="5">5 - Poor job</option></select><br /><br />
	  Comment on the Rating: <br /><textarea name="comments" cols="40" rows="5"></textarea></p>
<input type="button" name="button" value="Mark Request as Completed" onClick="document.getElementById('tar_form').doaction.value='complete_tar';document.getElementById('tar_form').submit();return false;"> &nbsp; <input type="button" name="button" value="Cancel this Request" onClick="document.getElementById('tar_form').doaction.value='cancel_request';document.getElementById('tar_form').submit();return false;">
<br />	  
	  ----------------------- Request Details ---------------------<br />
	  Subject: <?php echo $subject?><br /><br />
	  Grades taught : <?php echo $grades?><br /><br />
	  Number of Students: <?php echo $students?><br /><br />
	  Desired Month(s): <?php echo $months?><br /><br />
	  Category: <?php echo $category_name?><br /><br />
	  Details: <?php echo $details?><br />
	  -------------------------------------------------------------<br />

	  </form>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>	  
<?php
		exit;
	}
}

if ($_POST["doaction"] == "complete_tar")
{
    $result = $DB_site->query("UPDATE tars SET volunteer='$volunteer',rating='$rating',tar_status='complete',complete_time=now(),comments='$comments' WHERE id='$tid'");
    $tar_msg = "TAR marked as completed.<br />";
}

$sort_string = "";

if ($field_name != '' AND $field_value != '')
{
  if ($field_type == "exact")
	 $jump_query = " $field_name='$field_value'";
	else
	 $jump_query = " $field_name LIKE '%$field_value%'";
	
  $sort_string .= "field_name=$field_name&field_value=$field_value&field_type=$field_type";	
}
elseif ($field_value != '')
{
  $jump_query = " (id LIKE '%$field_value%' OR 
				  teacher_fname LIKE '%$field_value%' OR 
				  teacher_lname LIKE '%$field_value%' OR 
				  county_name LIKE '%$field_value%' OR 
				  district_name LIKE '%$field_value%' OR
				  school_name LIKE '%$field_value%' OR
				  school_city LIKE '%$field_value%' OR 
				  school_zip LIKE '%$field_value%' OR 
				  subject LIKE '%$field_value%' OR
				  category_name LIKE '%$field_value%' OR
				  best_times LIKE '%$field_value%' OR
				  months LIKE '%$field_value%' OR
				  grades LIKE '%$field_value%' OR
				  comments LIKE '%$field_value%' OR
				  details LIKE '%$field_value%') ";
  $sort_string .= "field_name=$field_name&field_value=$field_value&field_type=$field_type";	
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
	$listquery = " WHERE school_name REGEXP '^$list' AND tar_status='$tars_type' ";
	if ($jump_query != '') {$listquery.= "AND $jump_query";}
	$sort_string .= "&list=$list&";
}
elseif ($jump_query != '') {$listquery = "WHERE $jump_query AND tar_status='$tars_type' ";}
else {$listquery = " WHERE tar_status='$tars_type' ";}

$field = trim($field);
$text = trim($text);

// Get the Pages
list($total_count) = $DB_site->query_first("SELECT count(*) FROM tars $listquery");

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
		$string_pages.="<a href='$website_admin_tars".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";

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
				$string_pages.="<a href='$website_admin_tars".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
			$string_pages.="</a>";
			}
		}
		$string_pages.="<a href='$website_admin_tars".$sort_string."page=2&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		$string_pages.="<a href='$website_admin_tars".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a>";
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(submit_time),tar_status,volunteer,rating,DATE(complete_time),comments,email_status FROM tars $listquery ORDER BY $sort_query LIMIT $records_per_page");
		$string_pages.=" ]";
	}
	else // Show the Requested Page
	{
		$string_pages = " You can go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_tars".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";
		if ($page > 1) {
			$string_pages.="<a href='$website_admin_tars".$sort_string."page=".($page-1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;</a>&nbsp;&nbsp;";
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
				$string_pages.="<a href='$website_admin_tars".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
				$string_pages.="</a>";
			}
		}
		if ($page < $pages) {
			$string_pages.="<a href='$website_admin_tars".$sort_string."page=".($page+1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		}
		$string_pages.="<a href='$website_admin_tars".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a> ";
		$start_limit=($page-1)*$records_per_page;
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(submit_time),tar_status,volunteer,rating,DATE(complete_time),comments,email_status FROM tars $listquery ORDER by $sort_query LIMIT $start_limit,$records_per_page");
		$string_pages.=" ]";
	}
}
else
{
	$page = $pages = 1;
	$string_pages = "";
	if (!isset($sortby)) {$sortby = "id ASC";}
	$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(submit_time),tar_status,volunteer,rating,DATE(complete_time),comments,email_status FROM tars $listquery ORDER BY $sort_query");
}


$count1 = 0;
$color = 0;

if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

while (list($tar_id,$teacher_id,$county_name,$district_name,$school_name,$subject,$category_name,$best_times,$months,$details,$grades,$students,$submit_date,
$tar_status,$volunteer,$rating,$complete_date,$comments,$email_status) = $DB_site->fetch_array($result))
{
	$flag = 1;
  $bgcolor = $colors[$current_color];
	$status_bgcolor = "";

  list($teacher_name,$teacher_email) = $DB_site->query_first("SELECT CONCAT(lname,', ',fname),email FROM teachers WHERE id='$teacher_id'");
  list($emails_count) = $DB_site->query_first("SELECT count(*) FROM tars_emails WHERE tar_id='$tar_id'");

  $emails_count = ($emails_count  == "") ? "0" : $emails_count;
  $details = ($details  == "") ? "None" : $details;
  $best_times = ($best_times  == "") ? "None" : $best_times;
  
  $volunteer_name = "";
  $volunteer_email="";  

  if ($tar_status == "pending")
  {
    $action_link = "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=Complete&targetuser=$tar_id\" onClick='Confirm();return document.CC_returnValue;'>COMPLETED</a> - 
	<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=Delete&targetuser=$tar_id\" onClick='Confirm();return document.CC_returnValue;'>DELETE</a>";
  }
  else if ($volunteer > 0)
  {
    $action_link = "NA";
    list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer'");
    //$action_link = "NA</font></td></tr>
    //                <tr bgcolor='#FFFFF0'><td colspan=\"16\" align=\"right\" class=\"smallText\">Completed By: $volunteer_name [$volunteer_email], Date Completed: $complete_date, Rating: $rating, Comments: $comments";
  }
  else if ($tar_status == "complete" AND $volunteer == 0)
  {
    $action_link = "NA";
    //$action_link = "NA</font></td></tr>
    //                <tr bgcolor='#FFFFF0'><td colspan=\"16\" align=\"right\" class=\"smallText\">Completed By: Unlisted Volunteer, Date Completed: $complete_date, Rating: $rating, Comments: $comments";
  }
  else
  {
    $action_link = "NA";
  }

	$current_records.="<tr bgcolor=\"$bgcolor\">
    	<td class=\"smallText\">$tar_id</td>
		  <td class=\"smallText\">$teacher_name</td>
		  <td class=\"smallText\">$teacher_email</td>
		  <td class=\"smallText\">$county_name</td>
		  <td class=\"smallText\">$district_name</td>
		  <td class=\"smallText\">$school_name</td>
		  <td class=\"smallText\">$category_name</td>
		  <td class=\"smallText\">$months</td>
		  <td class=\"smallText\">$best_times</td>
		  <td class=\"smallText\">$details</td>
		  <td class=\"smallText\">$subject</td>
		  <td class=\"smallText\">$grades</td>
		  <td class=\"smallText\">$students</td>
		  <td class=\"smallText\">$submit_date</td>
 		  <td class=\"smallText\">$email_status</a></td>
 		  <td class=\"smallText\">$volunteer_name </td>
		  <td class=\"smallText\">$rating</td>
		  <td class=\"smallText\">$complete_date</td>
		  <td class=\"smallText\">$comments</td>
		  <td>";

	($email_status == "Open") ? $current_records.= "" : $current_records.= "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=modify_email_status&email_status=Open&targetuser=$tar_id\" onClick='Confirm();return document.CC_returnValue;'>Open</a> / ";
	($email_status == "In-Progress") ? $current_records.= "" : $current_records.= "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=modify_email_status&email_status=In-Progress&targetuser=$tar_id\" onClick='Confirm();return document.CC_returnValue;'>In-Progress</a> / ";
	($email_status == "Scheduled") ? $current_records.= "" : $current_records.= "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=modify_email_status&email_status=Scheduled&targetuser=$tar_id\" onClick='Confirm();return document.CC_returnValue;'>Scheduled</a> ";
		  
	$current_records.="</td><td class=\"smallText\">$action_link</td>
		  
     </tr>";   
  $current_color = !$current_color;
}

$list_string = "<TR><TD colspan=\"21\"> List by School Name: ";
for($i=65;$i<=90;$i++)
{
	$list_string.="<a href='$website_admin_tars?list=".chr($i)."&sortby=school_name&orderby=".urlencode($orderby)."&PHPSESSID=$PHPSESSID'> ".chr($i)." </a>";
}
$list_string.="&nbsp;&nbsp;[<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID\">List all Tars</a>]</TD></TR>";

if ($tars_type == "Pending")
  $pending_tars_link = "<strong>Showing Pending TARs</strong>";
else
  $pending_tars_link = "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=tars_type&show=Pending\">Show Pending TARs</a>";

if ($tars_type == "Complete")
  $completed_tars_link = "<strong>Showing Completed TARs</strong>";
else
  $completed_tars_link = "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=tars_type&show=Complete\">Show Completed TARs</a>";

if ($tars_type == "Admin Deleted")
  $admin_deleted_tars_link = "<strong>Showing Admin Deleted TARs</strong>";
else
  $admin_deleted_tars_link = "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=tars_type&show=Admin+Deleted\">Show Admin Deleted TARs</a>";

if ($tars_type == "Teacher Deleted")
  $teacher_deleted_tars_link = "<strong>Showing Teacher Deleted TARs</strong>";
else
  $teacher_deleted_tars_link = "<a href=\"$website_admin_tars?PHPSESSID=$PHPSESSID&doaction=tars_type&show=Teacher+Deleted\">Show Teacher Deleted TARs</a>";
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> TARs</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
<script language="JavaScript" src="functions.js"></script>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td class="left_nav">
<?php echo $navbar?>
</td>
<td valign='top' align="left">
    <div align="center"><strong><?php echo $msg?></strong></div>
    <table border='1' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="21"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current <?php echo $tars_type?> TARs <?php echo $special_field?></font></td></tr>
		<tr><td colspan="21">[<?php echo $pending_tars_link?> | <?php echo $completed_tars_link?> | <?php echo $admin_deleted_tars_link?> | <?php echo $teacher_deleted_tars_link?>]</td></tr>
		<?php echo $list_string?>
		<form method=post action='<?php echo $website_admin_tars?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<tr><td colspan="14">
          <input type=text name='field_value' value='<?php echo $field_value?>'> <input type=submit name='Submit' value='Search'> [<a href='<?php echo $website_admin_tars?>'>Clear Search</a>]
        </td>
        <td colspan="7"><a href="<?php echo $website_admin_tars?>?PHPSESSID=<?php echo $PHPSESSID?>&doaction=export&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>">Download CSV File</a></td>
        </tr>
		</form>
		<tr><td colspan="21" align="center"><font face="Georgia, Times New Roman, Times, serif" size="2" color="#FFFFFF">
		<form method=post action='<?php echo $website_admin_tars?>'>
		<a href="<?php echo $website_admin_tars?><?php echo $sort_string?>page=<?php echo $page?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/first.gif" border="0"></a> 
		<a href="<?php echo $website_admin_tars?><?php echo $sort_string?>page=<?php echo (($page-1) > 1) ? ($page-1) : 1; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/previous.gif" border="0"></a> 
		Page <input type="text" size="3" name="page" id="page" value="<?php echo $page?>" onChange="window.location.href='<?php echo $website_admin_tars?><?php echo $sort_string?>page=' + this.value + '&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>';"> of <?php echo $pages?> 
		<a href="<?php echo $website_admin_tars?><?php echo $sort_string?>page=<?php echo (($page+1) < $pages) ? ($page+1) : $pages; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/next.gif" border="0"></a> 
		<a href="<?php echo $website_admin_tars?><?php echo $sort_string?>page=<?php echo $pages?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/last.gif" border="0"></a> 
		</form>		
		<!--There are <?php echo $total_count?> total <?php echo $tars_type?> tars<?php echo $string_pages?>-->
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
		    <td width='2%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=id&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>ID</a> <?php echo $sort_id?></b></td>
		    <td width='5%' class="mediumText"><b>Teacher Name</b></td>
        <td width='5%' class="mediumText"><b>Email</b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=county_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>County</a> <?php echo $sort_county_name?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=district_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>School District</a> <?php echo $sort_district_name?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=school_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>School Name</a> <?php echo $sort_school_name?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=category_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Category of Request</a> <?php echo $sort_category_name?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=months&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Desired Months</a> <?php echo $sort_months?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=best_times&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Best Days and Times</a> <?php echo $sort_best_times?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=details&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Description</a> <?php echo $sort_details?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=subject&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Subject</a> <?php echo $sort_subject?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=grades&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Grade Level</a> <?php echo $sort_grades?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=students&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'># of Students</a> <?php echo $sort_students?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=submit_time&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Date Submitted</a> <?php echo $sort_submit_time?></b></td>
        <td width='2%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=email_status&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Status</a> <?php echo $sort_email_status?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=volunteer&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Volunteer</a> <?php echo $sort_volunteer?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=rating&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Rating</a> <?php echo $sort_rating?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=complete_time&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Completion Date</a> <?php echo $sort_complete_time?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars?><?php echo $sort_string?>sortby=comments&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Comments</a> <?php echo $sort_comments?></b></td>
        <td width='5%' class="mediumText"><b>Change Scheduling Status</b></td>
        <td width='2%' class="mediumText"><b>Action</b></td>
      </tr>
		  <tr><?php echo $current_records?>
		 </table>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
