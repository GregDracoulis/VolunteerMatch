<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
getsettings();

list($member_id,$email,$sess) = check_session("teacher");
list($fname,$lname,$school,$password,$phone) = $DB_site->query_first("SELECT fname,lname,school,password,phone FROM teachers WHERE id='$member_id' AND email='$email'");
list($county_name,$district_name,$school_name,$school_address,$school_city,$school_state,$school_zip) = $DB_site->query_first("SELECT county,district,school_name,address,city,state,zip FROM schools WHERE id='$school'");

if ($_POST["doaction"] == "cancel_update")
{
    header("Location: $website_teachers_overview");
    exit;
}
if ($_POST["doaction"] == "profile_update")
{
  $old_password = sql_safe($_POST["old_password"]);
  $new_password = sql_safe($_POST["new_password"]);
  $new_rpassword = sql_safe($_POST["new_rpassword"]);
  $fname = sql_safe($_POST["fname"]);
  $lname = sql_safe($_POST["lname"]);
  $school = sql_safe($_POST["school"]);
  $phone = sql_safe($_POST["phone"]);
  
  $hashed_password = create_hash($new_password);
  
  $error_flag = 0;

  if ($old_password && $new_password && $new_rpassword)
  {
    if (!validate_password($old_password,$password))
    {
      $update_msg.= "<strong>Old Password doesn't match.</strong><br />";
      $error_flag = 1;
    }
    
    if ($new_password != $new_rpassword)
    {
      $update_msg.= "<strong>New Password doesn't match Repeat New Password.</strong><br />";
      $error_flag = 1;
    }
    
    if ($error_flag == 0)
    {
      $result = $DB_site->query("UPDATE teachers SET password='$hashed_password' WHERE id='$member_id'");
      $update_msg.= "<strong>Password updated successfully.</strong><br />";
    }
  }
  if ($fname != "" AND $lname != "" AND $error_flag == 0)
  {
      $result = $DB_site->query("UPDATE teachers SET fname='$fname',lname='$lname',phone='$phone' WHERE id='$member_id'");
      $update_msg.= "<strong>Contact Info updated successfully.</strong><br />";
  }
  if ($school != "")
  {
      $result = $DB_site->query("UPDATE teachers SET school='$school' WHERE id='$member_id'");
      $update_msg.= "<strong>School updated successfully.</strong><br />";
  }
  
  if ($error_flag == 0)
  {
    header("Location: $website_teachers_overview?msg=".urlencode($update_msg));
    exit;
  }
  list($fname,$lname,$school,$password) = $DB_site->query_first("SELECT fname,lname,school,password FROM teachers WHERE id='$member_id' AND email='$email'");
}

list($tar_count) = $DB_site->query("SELECT count(*) FROM tars WHERE teacher_id='$member_id'");
if ($tar_count <= 0) // No tar, so they can change school
{
  $county_dropdown = "";
  $school_dropdown = "";
  $cquery = $DB_site->query("SELECT id,county FROM schools GROUP BY county ORDER BY ID ASC");
  while (list($cid,$county) = $DB_site->fetch_array($cquery))
  {
    $school_found = 0;
    $squery = $DB_site->query("SELECT id,district,school_name,address,city FROM schools WHERE county='$county' ORDER BY ID ASC");
    while (list($sid,$district,$school,$address,$city) = $DB_site->fetch_array($squery))
    {
	    $address=preg_replace('/[^\w| ]+/i',' ',$address);
	    $city=preg_replace('/[^\w| ]+/i',' ',$city);
	    
      $school_found++;
      $school_dropdown.= "arrItems1[$sid] = \"$school    ($address,$city)\"; arrItemsGrp1[$sid] = $cid;\n";
    }
    if ($school_found)
      $county_dropdown.= "<option value='$cid'>$county</option>";
  }
  
  $change_school_html = "<strong>Update School</strong><br />
  <select id=\"county\" name=\"county\" onChange=\"selectChange(profile_form.county, profile_form.school, '', arrItems1, arrItemsGrp1);\" >
	  <option value=\"\">- select -</option>
	  $county_dropdown 
	  </select>
	  County your school is in *<br />
	  <select id=\"school\" name=\"school\" ></select> Choose your school after choosing County *";
}


eval("dooutput(\"".gettemplate("teachers_profile")."\");");
exit;

?>
