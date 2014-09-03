<?php

include "global.php"; // Get Configuration
getsettings();

error_reporting(0);

list($member_id,$email,$sess) = check_session("teacher");
list($fname,$lname,$school) = $DB_site->query_first("SELECT fname,lname,school FROM teachers WHERE id='$member_id' AND email='$email'");
list($county_name,$district_name,$school_name,$school_city,$school_zip) = $DB_site->query_first("SELECT county,district,school_name,city,zip FROM schools WHERE id='$school'");

if ($_GET["doaction"] == "show_examples")
{
    eval("dooutput(\"".gettemplate("teachers_examples")."\");");
    exit;	
}

if ($_POST["doaction"] == "delete_tar")
{
  $tid = sql_safe($_POST["tid"]);
  $result = $DB_site->query("UPDATE tars SET tar_status='teacher deleted' WHERE teacher_id='$member_id' AND id='$tid'");
  $tar_msg = "TAR deleted successfully.<br />";
}

if ($_POST["doaction"] == "complete_tar")
{
  $tid = sql_safe($_POST["tid"]);
  $volunteer = sql_safe($_POST["volunteer"]);
  $rating = sql_safe($_POST["rating"]);
  $comments = sql_safe($_POST["comments"]);
  
  if ($tid != "" AND $volunteer != "" AND $rating != "")
  {
    $result = $DB_site->query("UPDATE tars SET volunteer='$volunteer',rating='$rating',tar_status='complete',complete_time=now(),comments='$comments' WHERE teacher_id='$member_id' AND id='$tid'");
    $tar_msg = "TAR marked as completed.<br />";
  }
  else
  {
    $tar_msg = "Please fill in all the variables and try again.<br />";
    $tar_complete_error = 1;
  }
}

if ($_POST["doaction"] == "update_tar_rating")
{
  $tid = sql_safe($_POST["tid"]);
  $rating = sql_safe($_POST["rating"]);
  $comments = sql_safe($_POST["comments"]);
  
  if ($tid != "" AND $rating != "")
  {
    $result = $DB_site->query("UPDATE tars SET rating='$rating',complete_time=now(),comments='$comments' WHERE teacher_id='$member_id' AND id='$tid'");
    $tar_msg = "TAR rating updated.<br />";
  }
  else
  {
    $tar_msg = "Please fill in all the variables and try again.<br />";
    $tar_rating_update_error = 1;
  }
}

if ($_POST["doaction"] == "modify_tar")
{
  $tid = sql_safe($_POST["tid"]);
  $subject = sql_safe($_POST["subject"]);
  $grades = sql_safe($_POST["grades"]);
  $students = sql_safe($_POST["students"]);
  $months = $_POST["months"];
  $category = sql_safe($_POST["category"]);
  $best_times = sql_safe($_POST["best_times_" . $category]);
  $details = sql_safe($_POST["details"]);
  
  $bad_words = filterBadWords($details,"$badWordsFile"); 
  
  if ($subject != "" AND $grades != "" AND $students != "" AND $category != "" AND $bad_words == 0)
  {
    $month_array = Array();
    foreach ($months as $month)
    {
      array_push($month_array,$month);
      if ($month == "Any")
        break;
    }
    $month_options = implode("/",$month_array);

    list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");
    
    $result = $DB_site->query("UPDATE tars SET subject='$subject',grades='$grades',students='$students',months='$month_options',category='$category',
                                  category_name='$category_name',best_times='$best_times',details='$details' WHERE teacher_id='$member_id' AND id='$tid'
                                    ");
    $tar_msg.= "TAR updated successfully.<br />";
    unset($subject,$grades,$students,$months,$category,$best_times,$category,$details);
  }
  else if ($bad_words > 0)
  {
    $tar_modify_error = 1;
    $tar_msg.= "Bad words detected in the request. Please correct and try again.<br />";  
  }
  else
  {
    $tar_modify_error = 1;
    $tar_msg.= "Please fill in all the variables and try again.<br />";  
  }
}

if (isset($_POST["new_tar"]))
{
  $subject = sql_safe($_POST["subject"]);
  $grades = sql_safe($_POST["grades"]);
  $students = sql_safe($_POST["students"]);
  $months = $_POST["months"];
  $category = sql_safe($_POST["category"]);
  $best_times = sql_safe($_POST["best_times_" . $category]);
  $details = sql_safe($_POST["details"]);


  if ($subject != "" AND $grades != "" AND $students != "" AND $category != "" AND $details != "")
  {
    $month_array = Array();
    foreach ($months as $month)
    {
      array_push($month_array,$month);
      ${"months_" . $month} = " checked ";
      if ($month == "Any")
        break;
    }
    $month_options = implode("/",$month_array);

    list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");
    
    $result = $DB_site->query("INSERT INTO tars (teacher_id,teacher_fname,teacher_lname,county_name,district_name,school_name,school_city,school_zip,subject,grades,students,months,category,category_name,best_times,details,submit_time,comments)
                                    VALUES('$member_id','$fname','$lname','$county_name','$district_name','$school_name','$school_city','$school_zip','$subject','$grades','$students','$month_options','$category','$category_name','$best_times','$details',now(),'')
                                    ");
    $tar_msg.= "TAR added successfully.<br />";
	// Uncomment this to clear these posted variables
    //unset($subject,$grades,$students,$months,$category,$best_times,$category,$details);
    
    // Comment the below lines to clear the posted variables
    ${"grades_" . $grades} = " selected ";
  
	  if ($_POST["doaction"] == "new_tar") // Save & Exit
	  {
		unset($subject,$grades,$students,$months,$category,$best_times,$details);
	  }
	  else
	  {
		unset($students,$best_times);
	  }  
  }
  else if ($bad_words > 0)
  {
    $tar_msg.= "Bad words detected in the request. Please correct and try again.<br />";  
  }
  else
    $tar_msg.= "Please fill in all the variables and try again.<br />";  
}

if ($_GET["doaction"] == "modify" OR $tar_modify_error == 1)
{
  if ($_GET["tid"] != "")
    $tid = $_GET["tid"];
    
  if ($tid != "")
  {
    list($subject,$grades,$students,$months,$category,$tbest_times,$details) = $DB_site->query_first("SELECT subject,grades,students,months,category,best_times,details FROM tars WHERE teacher_id='$member_id' AND id='$tid' ");
    ${"grades_" . $grades} = "selected";
    $months_array = explode("/",$months);
    foreach ($months_array as $month)
      ${"months_" . $month} = "checked";
      
    $cquery = $DB_site->query("SELECT id,category_name,best_times,comments_text FROM categories WHERE is_active='Y' ORDER BY display_order ASC");
    while (list($cid,$catname,$best_times,$comments_text) = $DB_site->fetch_array($cquery))
    {
      if ($comments_text == "") $comments_text = "Please provide us with more information about your selection above.";
      $comments_text = trim($comments_text);
      if ($category == $cid)
        $category_options.= "<input type=\"radio\" name=\"category\" id=\"category\" value=\"$cid\" checked onClick=\"document.getElementById('comments_text').innerHTML='$comments_text';\"> $catname ";
      else
        $category_options.= "<input type=\"radio\" name=\"category\" id=\"category\" value=\"$cid\" onClick=\"document.getElementById('comments_text').innerHTML='$comments_text';\"> $catname ";
      if ($category == $cid)
      {
        if ($best_times == "Y") 
        {
          $category_options.= "<input type=\"text\" name=\"best_times_$cid\" id=\"best_times_$cid\" value=\"$tbest_times\"> Best Days and Times ";
        }
      }
      else
      {
        if ($best_times == "Y") 
        {
          $category_options.= "<input type=\"text\" name=\"best_times_$cid\" id=\"best_times_$cid\"> Best Days and Times ";
        }
      }
      $category_options.= "<br />";
    }
      
    eval("dooutput(\"".gettemplate("teachers_tar_modify")."\");");
    exit;
  }
}

if ($_GET["doaction"] == "delete" AND $_GET["tid"] != "")
{
  $tid = $_GET["tid"];
  list($subject,$grades,$students,$months,$category,$best_times,$details,$submit_date) = $DB_site->query_first("SELECT subject,grades,students,months,category,best_times,details,DATE(submit_time) FROM tars WHERE teacher_id='$member_id' AND id='$tid' ");
  list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");
  eval("dooutput(\"".gettemplate("teachers_tar_delete")."\");");
  exit;
}
if ($_GET["doaction"] == "complete" OR $tar_complete_error == 1)
{
  if ($_GET["tid"] != "")
    $tid = $_GET["tid"];
  if ($tid != "")
  {
    list($subject,$grades,$students,$months,$category,$best_times,$details,$submit_date) = $DB_site->query_first("SELECT subject,grades,students,months,category,best_times,details,DATE(submit_time) FROM tars WHERE teacher_id='$member_id' AND id='$tid' ");
    list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");
    
    $volunteers_dropdown = "<select name=\"volunteer\" id=\"volunteer\">";
    $query = $DB_site->query("SELECT volunteer FROM tars_emails WHERE tar_id='$tid' AND teacher_id='$member_id' GROUP BY volunteer ORDER BY ID ASC");
    while (list($volunteer_id) = $DB_site->fetch_array($query))
    {
      list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer_id'");
      $volunteers_dropdown.= "<option value='$volunteer_id'>$volunteer_name [$volunteer_email]</option>";
    }
    $volunteers_dropdown .= "<option value='0'>Not Listed</option></select>";
    
    eval("dooutput(\"".gettemplate("teachers_tar_complete")."\");");
    exit;
  }
}

if ($_GET["doaction"] == "modify_email_status" AND $_GET["email_status"] != "" AND $_GET["tid"] != "")
{
	$email_status = $_GET["email_status"];
	$tid = $_GET["tid"];
	$result = $DB_site->query("UPDATE tars SET email_status='$email_status' WHERE id='$tid'");
	$msg = "Scheduling Status updated.";
}

if ($_GET["doaction"] == "update_rating")
{
  if ($_GET["tid"] != "")
    $tid = $_GET["tid"];
  if ($tid != "")
  {
    list($subject,$grades,$students,$months,$category,$best_times,$details,$submit_date,$volunteer_id,$comments) = $DB_site->query_first("SELECT subject,grades,students,months,category,best_times,details,DATE(submit_time),volunteer,comments FROM tars WHERE teacher_id='$member_id' AND id='$tid' ");
    list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$category'");

    list($volunteer_name,$volunteer_email) = $DB_site->query_first("SELECT concat(fname,' ',lname),email FROM volunteers WHERE id='$volunteer_id'");
    
    eval("dooutput(\"".gettemplate("teachers_tar_update_rating")."\");");
    exit;
  }
}

if ($_POST["doaction"] == "clear_fields")
{
	unset($subject,$grades,$students,$months,$category,$best_times,$details);
}

${"grades_" . $grades} = " selected ";

// Pending Requests
$request_count = 0;
$current_color = 0;
$current_requests = "";
$tquery = $DB_site->query("SELECT id,subject,grades,students,months,category,best_times,details,DATE(submit_time),email_status FROM tars WHERE teacher_id='$member_id' AND tar_status='Pending' ORDER BY ID DESC");
while (list($tid,$tsubject,$tgrades,$tstudents,$tmonths,$tcategory,$tbest_times,$tdetails,$tsubmit_date,$email_status) = $DB_site->fetch_array($tquery))
{
  $tbest_times = ($tbest_times == "") ? "Any" : $tbest_times;
  list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$tcategory'");
  $current_requests.= "<tr class=\"trclass$current_color\">
                        <td class=\"results\"><input type=button name='mark' value='Mark Visit Complete' onClick=\"openURL('$website_teachers_overview?doaction=complete&tid=$tid');return false\" /></td>
						<td class=\"results\">$tsubmit_date [#$tid]</td>
                        <td class=\"results\">$category_name</td>
                        <td class=\"results\">$tsubject</td>
                        <td class=\"results\"><span class=\"small_text  strong\">Grade $tgrades:</span> <span class=\"small_text  black1\">$tdetails</span></td>
						<td class=\"results\">$tmonths $tbest_times</td>
                        <td class=\"results\">$tstudents</td>
						<td class=\"results\">$email_status</td>
						<td class=\"results\">";
	if ($email_status == "In-Progress") 
	{
		$vquery = $DB_site->query("SELECT volunteer FROM tars_emails WHERE tar_id='$tid' ORDER BY id DESC LIMIT 1");
		while (list($volunteer) = $DB_site->fetch_array($vquery))
		{
			list($volunteer_name,$volunteer_email,$volunteer_phone) = $DB_site->query_first("SELECT concat(fname,' ',lname),email,phone FROM volunteers WHERE id='$volunteer'");
			$current_requests.="<span class=\"small_text\">$volunteer_name<br />$volunteer_email<br />$volunteer_phone</span><br><br>";
		}
	}		
	$current_requests.="</td>
						<td class=\"results\">";	
						
	($email_status == "Open") ? $current_requests.= "" : $current_requests.= "<a href=\"$website_teachers_overview?doaction=modify_email_status&email_status=Open&tid=$tid\">Open</a> / ";
	($email_status == "In-Progress") ? $current_requests.= "" : $current_requests.= "<a href=\"$website_teachers_overview?doaction=modify_email_status&email_status=In-Progress&tid=$tid\">In-Progress</a> / ";
	($email_status == "Scheduled") ? $current_requests.= "" : $current_requests.= "<a href=\"$website_teachers_overview?doaction=complete&tid=$tid\">Completed</a> ";

    $current_requests.="</td>
						<td class=\"results\"><a href=\"$website_teachers_overview?doaction=modify&tid=$tid\">Modify Request</a></td>
                        <td class=\"results\"><a href=\"$website_teachers_overview?doaction=delete&tid=$tid\">Delete Request</a></td>
                      </tr>";
  $request_count++;
  $current_color = ($current_color == "0") ? "1" : "0";
}
if ($request_count == 0)
  $current_requests.= "<tr><td colspan=\"12\" align=\"center\">No Pending Requests</td></tr>";

// Completed Requests
$completed_request_count = 0;
$current_color = 0;
$completed_requests = "";
$tquery = $DB_site->query("SELECT id,subject,grades,students,months,category,best_times,details,DATE(submit_time),volunteer,DATE(complete_time),rating FROM tars WHERE teacher_id='$member_id' AND tar_status='Complete' ORDER BY ID DESC LIMIT 10");
while (list($tid,$tsubject,$tgrades,$tstudents,$tmonths,$tcategory,$tbest_times,$tdetails,$tsubmit_date,$volunteer,$complete_date,$rating) = $DB_site->fetch_array($tquery))
{
  list($volunteer_name,$volunteer_email,$volunteer_phone) = $DB_site->query_first("SELECT concat(fname,' ',lname),email,phone FROM volunteers WHERE id='$volunteer'");
  $tbest_times = ($tbest_times == "") ? "Any" : $tbest_times;
  list($category_name) = $DB_site->query_first("SELECT category_name FROM categories WHERE id='$tcategory'");
  $completed_requests.= "<tr class=\"trclass$current_color\">
                        <td class=\"results\">$tsubmit_date [#$tid]</td>
                        <td class=\"results\">$category_name</td>
                        <td class=\"results\">$tsubject</td>
                        <td class=\"results\"><span class=\"small_text  strong\">Grade $tgrades:</span> <span class=\"small_text  black1\">$tdetails</span></td>
						<td class=\"results\">$tmonths $tbest_times</td>
                        <td class=\"results\"><span class=\"small_text\">$volunteer_name <br />$volunteer_email<br />$volunteer_phone</span></td>
                        <td class=\"results\">$complete_date</td>
                        <td class=\"results\">$rating</td>
						<td class=\"results\"><a href='$website_teachers_overview?doaction=update_rating&tid=$tid'>Update Rating</a></td>
                      </tr>";
  $completed_request_count++;
  $current_color = ($current_color == "0") ? "1" : "0";
}
if ($completed_request_count == 0)
  $completed_requests.= "<tr><td colspan=\"9\" align=\"center\">No Completed Requests</td></tr>";
  
$cquery = $DB_site->query("SELECT id,category_name,best_times,comments_text FROM categories WHERE is_active='Y' ORDER BY display_order ASC");
while (list($cid,$catname,$best_times,$comments_text) = $DB_site->fetch_array($cquery))
{
  if ($comments_text == "") $comments_text = "Please provide us details on what you would like to happen during the volunteer\'s <br />visit, and what sort of information they should bring (<a href=\'#\' onClick=MM_openBrWindow(\'$website_teachers_overview?doaction=show_examples\',\'example\',\'scrollbars=yes,resizable=yes,width=600,height=600\');return false;>examples</a>):";
  $comments_text = trim($comments_text);
  if ($cid == $category) $checked = "checked";
  else $checked = "";
  $category_options.= "<input type=\"radio\" name=\"category\" id=\"category\" value=\"$cid\" $checked onClick=\"document.getElementById('comments_text').innerHTML='$comments_text';\"> $catname ";
  if ($best_times == "Y") $category_options.= "<input type=\"text\" name=\"best_times_$cid\" id=\"best_times_$cid\" > Best Days and Times ";
  $category_options.= "<br />";
}
eval("dooutput(\"".gettemplate("teachers_overview")."\");");
exit;

?>
