<?php

error_reporting(5);

$admin_tars_emails_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$records_per_page = 25;
$max_pages = 10;

$navbar = ${$mtype."_nav_bar"};

if ($mtype != "admin" AND $mtype != "manager")
{
	exit;
}

if ($_GET["doaction"] == "export")
{
  $sortby = $_GET["sortby"];
  $orderby = $_GET["orderby"];
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename=tars_emails.csv");
  print "\"ID#\",\"Email Sent By\",\"Email Message\",\"Date Submitted\",\"Teacher Name\",\"Email\",\"County\",\"School District\",\"School Name\",\"Category of Request\",\"Desired Months\",\"Best Days and Times\",\"Description\",\"Subject\",\"Grade Level\",\"# of Students\"\r\n";
	$query = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(submit_time),email_status,email_message,volunteer,rating,DATE(complete_time) FROM tars_emails ORDER BY $sortby");
  while (list($email_id,$teacher_id,$county_name,$district_name,$school_name,$subject,$category_name,$best_times,$months,$details,$grades,$students,$submit_date,
$email_status,$email_message,$volunteer,$rating,$complete_date) = $DB_site->fetch_array($query))
  {
    list($teacher_name,$teacher_email) = $DB_site->query_first("SELECT CONCAT(fname,' ',lname),email FROM teachers WHERE id='$teacher_id'");
    list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer'");
    $details = eregi_replace("[\r|\n|,]"," ",$details);
    $email_message = eregi_replace("[\r|\n|,]"," ",$email_message);
    print "\"$email_id\",\"$volunteer_name\",\"$email_message\",\"$submit_date\",\"$teacher_name\",\"$teacher_email\",\"$county_name\",\"$district_name\",\"$school_name\",\"$category_name\",\"$months\",\"$best_times\",\"$details\",\"$subject\",\"$grades\",\"$students\"\r\n";
  }
  exit;
}

$sort_string = "";

if ($field_name != '' AND $field_value != '')
{
  if ($field_type == "exact")
	 $jump_query = " $field_name='$field_value'";
	else
	 $jump_query = " $field_name LIKE '%$field_value%'";
  $sort_string .= "field_name=$field_name&field_value=$field_value";	
}
elseif ($field_value != '')
{
  $jump_query = " (id LIKE '%$field_value%' OR 
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
				  email_message LIKE '%$field_value%' OR
				  other_volunteers LIKE '%$field_value%' OR
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
	$listquery = " WHERE school_name REGEXP '^$list' ";
	if ($jump_query != '') {$listquery.= "AND $jump_query";}
	$sort_string .= "&list=$list&";
}
elseif ($jump_query != '') {$listquery = "WHERE $jump_query  ";}
else {$listquery = "  ";}

$field = trim($field);
$text = trim($text);

// Get the Pages
list($total_count) = $DB_site->query_first("SELECT count(*) FROM tars_emails $listquery");

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
		$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";

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
				$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
			$string_pages.="</a>";
			}
		}
		$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=2&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a>";
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(email_dated),email_status,email_message,volunteer,rating,DATE(complete_time),other_volunteers FROM tars_emails $listquery ORDER BY $sort_query LIMIT $records_per_page");
		$string_pages.=" ]";
	}
	else // Show the Requested Page
	{
		$string_pages = " You can go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";
		if ($page > 1) {
			$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=".($page-1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;</a>&nbsp;&nbsp;";
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
				$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
				$string_pages.="</a>";
			}
		}
		if ($page < $pages) {
			$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=".($page+1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		}
		$string_pages.="<a href='$website_admin_tars_emails".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a> ";
		$start_limit=($page-1)*$records_per_page;
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(email_dated),email_status,email_message,volunteer,rating,DATE(complete_time),other_volunteers FROM tars_emails $listquery ORDER by $sort_query LIMIT $start_limit,$records_per_page");
		$string_pages.=" ]";
	}
}
else
{
	$page = $pages = 1;
	$string_pages = "";
	if (!isset($sortby)) {$sortby = "id ASC";}
	$result = $DB_site->query("SELECT id,teacher_id,county_name,district_name,school_name,subject,category_name,best_times,months,details,grades,students,DATE(email_dated),email_status,email_message,volunteer,rating,DATE(complete_time),other_volunteers FROM tars_emails $listquery ORDER BY $sort_query");
}


$count1 = 0;
$color = 0;


if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

while (list($email_id,$teacher_id,$county_name,$district_name,$school_name,$subject,$category_name,$best_times,$months,$details,$grades,$students,$submit_date,
$email_status,$email_message,$volunteer,$rating,$complete_date,$other_volunteers) = $DB_site->fetch_array($result))
{
	$flag = 1;
  $bgcolor = $colors[$current_color];
	$status_bgcolor = "";

  list($teacher_name,$teacher_email) = $DB_site->query_first("SELECT CONCAT(lname,', ',fname),email FROM teachers WHERE id='$teacher_id'");
  list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(lname,', ',fname),email FROM volunteers WHERE id='$volunteer'");

  $emails_count = ($emails_count  == "") ? "0" : $emails_count;
  $details = ($details  == "") ? "None" : $details;
  $best_times = ($best_times  == "") ? "None" : $best_times;
    
	$current_records.="<tr bgcolor=\"$bgcolor\">
    	<td class=\"smallText\">$email_id</td>
 		  <td class=\"smallText\">$volunteer_name</td>
		  <td class=\"smallText\">$email_message</td>
		  <td class=\"smallText\">$submit_date</td>
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
		  <td class=\"smallText\">$other_volunteers</td>
     </tr>";   
  $current_color = !$current_color;
}

$list_string = "<TR><TD colspan=\"17\"> List by School Name: ";
for($i=65;$i<=90;$i++)
{
	$list_string.="<a href='$website_admin_tars_emails?list=".chr($i)."&sortby=school_name&orderby=".urlencode($orderby)."&PHPSESSID=$PHPSESSID'> ".chr($i)." </a>";
}
$list_string.="&nbsp;&nbsp;[<a href=\"$website_admin_tars_emails?PHPSESSID=$PHPSESSID\">List all Tars Emails</a>]</TD></TR>";

?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> TARs Emails</title>
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
		<tr><td colspan="17" ><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current <?php echo $tars_type?> TARs Emails <?php echo $special_field?></font> </td></tr>
		<?php echo $list_string?>
		<form method=post action='<?php echo $website_admin_tars_emails?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<tr><td colspan="12">
          <input type=text name='field_value' value='<?php echo $field_value?>'> <input type=submit name='Submit' value='Search'> [<a href='<?php echo $website_admin_tars_emails?>'>Clear Search</a>]
        </td>
        <td colspan="5"><a href="<?php echo $website_admin_tars_emails?>?PHPSESSID=<?php echo $PHPSESSID?>&doaction=export&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>">Download CSV File</a></td>
        </tr>
		</form>
		<tr><td colspan="17" align="center"><font face="Georgia, Times New Roman, Times, serif" size="2" color="#FFFFFF">
		<form method=post action='<?php echo $website_admin_tars_emails?>'>
		<a href="<?php echo $website_admin_tars_emails?><?php echo $sort_string?>page=<?php echo $page?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/first.gif" border="0"></a> 
		<a href="<?php echo $website_admin_tars_emails?><?php echo $sort_string?>page=<?php echo (($page-1) > 1) ? ($page-1) : 1; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/previous.gif" border="0"></a> 
		Page <input type="text" size="3" name="page" id="page" value="<?php echo $page?>" onChange="window.location.href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>page=' + this.value + '&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>';"> of <?php echo $pages?> 
		<a href="<?php echo $website_admin_tars_emails?><?php echo $sort_string?>page=<?php echo (($page+1) < $pages) ? ($page+1) : $pages; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/next.gif" border="0"></a> 
		<a href="<?php echo $website_admin_tars_emails?><?php echo $sort_string?>page=<?php echo $pages?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/last.gif" border="0"></a> 
		</form>			
		<!--There are <?php echo $total_count?> total <?php echo $tars_type?> tars emails<?php echo $string_pages?>-->
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
		<td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=id&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>ID</a> <?php echo $sort_id?></b></td>
        <td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=volunteer&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Volunteer sending email</a> <?php echo $sort_volunteer?></b></td>
        <td width='25%' class="mediumText"><b>Email Message</b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=submit_time&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Date Sent</a> <?php echo $sort_submit_time?></b></td>
		<td width='10%' class="mediumText"><b>Teacher Sent to</td>
        <td width='10%' class="mediumText"><b>Email</b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=county_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>County</a> <?php echo $sort_county_name?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=district_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>School District</a> <?php echo $sort_district_name?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=school_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>School Name</a> <?php echo $sort_school_name?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=category_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Category of Request</a> <?php echo $sort_category_name?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=months&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Desired Months</a> <?php echo $sort_months?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=best_times&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Best Days and Times</a> <?php echo $sort_best_times?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=details&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Description</a> <?php echo $sort_details?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=subject&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Subject</a> <?php echo $sort_subject?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=grades&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Grade Level</a> <?php echo $sort_grades?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=students&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'># of Students</a> <?php echo $sort_students?></b></td>
        <td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_tars_emails?><?php echo $sort_string?>sortby=other_volunteers&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Other Volunteers</a> <?php echo $sort_other_volunteers?></b></td>
		</tr>
		  <tr><?php echo $current_records?>
		 </table>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
