<?php

error_reporting(5);

$admin_schools_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

if ($_POST["doaction"] == "add_school")
{
 	$county = sql_safe($_POST["county"]);
	$district = sql_safe($_POST["district"]);
	$school = sql_safe($_POST["school"]);
	$address = sql_safe($_POST["address"]);
	$city = sql_safe($_POST["city"]);
	$state = sql_safe($_POST["state"]);
	$zip = sql_safe($_POST["zip"]);
	if ($county != "" AND $district != "" AND $school != "" AND $address != "" AND $city != "" AND $state != "" AND $zip != "")
	{
		$result = $DB_site->query("INSERT INTO schools (county,district,school_name,address,city,state,zip) VALUES('$county','$district','$school','$address','$city','$state','$zip')");
		$msg = "School added successfully";
	}
	else
	{
		$msg = "Insufficient parameters";
	}
}

?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Add School</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
  <form method=post action='add_school.php'>
  <input type=hidden name='doaction' value='add_school'>

  <table border="0" cellspacing="0">
    <tr> 
      <td colspan="2"> 
        <div align="center"><font size="5" color="#FFFFFF">School Details</font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <div align="center"><font size="3" color="#FFFFFF"><?php echo $msg?></font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"> 
        <hr size='1'>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">County:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='county'  size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">District:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='district' size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">School Name:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='school'  size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Address:
      </td>
      <td>
        <font size="2" color="#000000"><textarea name='address' cols='40' rows='5'></textarea> 
      </td>
    </tr>
    
    <tr>
      <td>
        <font size="2" color="#000000">City:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='city'  size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">State:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='state' size="50"> 
      </td>
    </tr>
   
    <tr>
      <td>
        <font size="2" color="#000000">Zip:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='zip'  > 
      </td>
    </tr>

    <tr><td colspan="2"><hr size='1'></td></tr>
    <tr>
      <td>
        <font size="2" color="#000000">
      </td>
      <td>
        <font size="2" color="#000000"><input type=submit name='Submit' value='Add School'>
      </td>
    </tr>
  </table>
  </form>
</body></html>
