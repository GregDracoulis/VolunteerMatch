<?php

error_reporting(5);

$admin_schools_class = "class='selected_link'";

include "global.php";
include "sessions.php";
getsettings();
list($username,$password,$mtype) = check_session();

$records_per_page = 50;
$max_pages = 10;

$navbar = ${$mtype."_nav_bar"};

//if ($mtype != "admin" AND $mtype != "manager")
//{
//	exit;
//}

if ($_GET["doaction"] == "export")
{
  $sortby = $_GET["sortby"];
  $orderby = $_GET["orderby"];
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename=schools.csv");
  print "\"ID#\",\"County\",\"School District Name\",\"School Name\",\"Street Address\",\"City\",\"State\",\"Zip\"\r\n";
	$query = $DB_site->query("SELECT id,county,district,school_name,address,city,state,zip FROM schools ORDER BY $sortby");
  while (list($school_id,$county,$district,$school_name,$school_address,$school_city,$school_state,$school_zip) = $DB_site->fetch_array($query))
  {
    $school_address = eregi_replace("[\r|\n|,]"," ",$school_address);
    print "\"$school_id\",\"$county\",\"$district\",\"$school_name\",\"$school_address\",\"$school_city\",\"$school_state\",\"$school_zip\"\r\n";
  }
  exit;
}

if ($_POST["doaction"] == "update_school")
{
  $targetuser = $_POST["targetuser"];
  if ($targetuser != "" AND $county != "" AND $district != "" AND $school_name != "" AND $city != "" AND $zip != "")
  {
    $result = $DB_site->query("UPDATE schools SET county='$county',district='$district',school_name='$school_name',address='$address',city='$city',state='$state',zip='$zip' WHERE id='$targetuser'");
    $msg = "School updated successfully.";
  }
}

if ($_POST["doaction"] == "add_schools")
{
	// ACTIVE ICON FILE SECTION //
	if ($_FILES['school_file']['name'] != "")
	{
		if ($_FILES['school_file']['error'] == UPLOAD_ERR_OK) // Check If File is Uploaded
		{
			$found_csv_file = 0;
			$schools_inserted = 0;
			if ($_FILES['school_file']['tmp_name'] != FALSE)
			{
				if ($fp = fopen($_FILES['school_file']['tmp_name'],"rw"))
				{
				  $found_csv_file = 1;
				  while (!feof($fp))
				  {
					$buffer = fgets($fp);
					$buffer = trim($buffer);
					//echo "$buffer\n";
					$csv_array = explode(",",$buffer);
					//echo "<br />" . sizeof($csv_array)."<br />";
					if (sizeof($csv_array) != 7)
					  continue;
					
					$county = sql_safe(trim($csv_array[0]));
					$district = sql_safe(trim($csv_array[1]));
					$school = sql_safe(trim($csv_array[2]));
					$address = sql_safe(trim($csv_array[3]));
					$city = sql_safe(trim($csv_array[4]));
					$state = sql_safe(trim($csv_array[5]));
					$zip = sql_safe(trim($csv_array[6]));
					$result = $DB_site->query("INSERT INTO schools (county,district,school_name,address,city,state,zip) VALUES('$county','$district','$school','$address','$city','$state','$zip')");
					$schools_inserted++;
				  }
				  fclose($fp);
				  $msg.= "$schools_inserted Schools Inserted.";
				}
				else
					$msg.= "Unable to open file.<br />";
			}
			else
				$msg = "Error uploading file. Please try again.<br />";
		}
	}  
}

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

if ($_POST["doaction"] == "Delete")
{
	$targetuser = urldecode($targetuser);
	$result = $DB_site->query("DELETE FROM schools WHERE id='$targetuser'");
	$msg = "School deleted successfully.";
}

if ($_POST["doaction"] == "Edit")
{
	$targetuser = urldecode($targetuser);
	list($county,$district, $school_name, $school_address, $school_city, $school_state, $school_zip) = $DB_site->query_first("SELECT `county`, `district`, `school_name`, `address`, `city`, `state`, `zip` FROM `schools` where id='$targetuser'");
  
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Schools</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h1><?php echo $website_title?> Schools</h1></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="center" width="800">
  <form method=post action='<?php echo $website_admin_schools?>'>
  <input type=hidden name='targetuser' value='<?php echo $targetuser?>'>
  <input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
  <input type=hidden name='doaction' value='update_school'>

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
        <font size="2" color="#000000">ID:
      </td>
      <td>
        <font size="2" color="#000000"><?php echo $targetuser?>
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">County:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='county' value='<?php echo $county?>' size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">District:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='district' value='<?php echo $district?>' size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">School Name:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='school_name' value='<?php echo $school_name?>' size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">Address:
      </td>
      <td>
        <font size="2" color="#000000"><textarea name='address' cols='40' rows='5'><?php echo $school_address?></textarea> 
      </td>
    </tr>
    
    <tr>
      <td>
        <font size="2" color="#000000">City:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='city' value='<?php echo $school_city?>' size="50"> 
      </td>
    </tr>
    <tr>
      <td>
        <font size="2" color="#000000">State:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='state' value='<?php echo $school_state?>' size="50"> 
      </td>
    </tr>
   
    <tr>
      <td>
        <font size="2" color="#000000">Zip:
      </td>
      <td>
        <font size="2" color="#000000"><input type=text name='zip' value='<?php echo $school_zip?>' > 
      </td>
    </tr>

    <tr><td colspan="2"><hr size='1'></td></tr>
    <tr>
      <td>
        <font size="2" color="#000000">
      </td>
      <td>
        <font size="2" color="#000000"><input type=submit name='Submit' value='Update School'>
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
				  county LIKE '%$field_value%' OR 
				  district LIKE '%$field_value%' OR 
				  school_name LIKE '%$field_value%' OR 
				  address LIKE '%$field_value%' OR
				  city LIKE '%$field_value%' OR
				  state LIKE '%$field_value%' OR 
				  zip LIKE '%$field_value%') ";
  $sort_string .= "field_name=$field_name&field_value=$field_value";	
}

//$sortby = $_GET["sortby"];
//$orderby = $_GET["orderby"];

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
elseif ($jump_query != '') {$listquery = "WHERE $jump_query ";}


$field = trim($field);
$text = trim($text);

// Get the Pages
list($total_count) = $DB_site->query_first("SELECT count(*) FROM schools $listquery");

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
		$string_pages.="<a href='$website_admin_schools".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";

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
				$string_pages.="<a href='$website_admin_schools".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
			$string_pages.="</a>";
			}
		}
		$string_pages.="<a href='$website_admin_schools".$sort_string."page=2&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		$string_pages.="<a href='$website_admin_schools".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a>";
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,county,district,school_name,address,city,state,zip FROM schools $listquery ORDER BY $sort_query LIMIT $records_per_page");
		$string_pages.=" ]";
	}
	else // Show the Requested Page
	{
		$string_pages = " You can go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_schools".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";
		if ($page > 1) {
			$string_pages.="<a href='$website_admin_schools".$sort_string."page=".($page-1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;</a>&nbsp;&nbsp;";
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
				$string_pages.="<a href='$website_admin_schools".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
				$string_pages.="</a>";
			}
		}
		if ($page < $pages) {
			$string_pages.="<a href='$website_admin_schools".$sort_string."page=".($page+1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		}
		$string_pages.="<a href='$website_admin_schools".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a> ";
		$start_limit=($page-1)*$records_per_page;
		if (!isset($sortby)) {$sortby = "id ASC";}
		$result = $DB_site->query("SELECT id,county,district,school_name,address,city,state,zip FROM schools $listquery ORDER by $sort_query LIMIT $start_limit,$records_per_page");
		$string_pages.=" ]";
	}
}
else
{
	$page = 1;
	$pages = 1;
	$string_pages = "";
	if (!isset($sortby)) {$sortby = "id ASC";}
	$result = $DB_site->query("SELECT id,county,district,school_name,address,city,state,zip FROM schools $listquery ORDER BY $sort_query");
}


$count1 = 0;
$color = 0;

if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

while (list($school_id,$county,$district,$school_name,$school_address,$school_city,$school_state,$school_zip) = $DB_site->fetch_array($result))
{
	$flag = 1;
	$bgcolor = $colors[$current_color];
	$status_bgcolor = "";

	$current_records.="<tr bgcolor=\"$bgcolor\">
    	<td><font face='$Font1' size='2'>$school_id</font></td>
		  <td><font face='$Font1' size='2'>$county</font></td>
		  <td><font face='$Font1' size='2'>$district</font></td>
		  <td><font face='$Font1' size='2'>$school_name</font></td>
		  <td><font face='$Font1' size='2'>$school_address</font></td>
		  <td><font face='$Font1' size='2'>$school_city</font></td>
		  <td><font face='$Font1' size='2'>$school_state</font></td>
		  <td><font face='$Font1' size='2'>$school_zip</font></td>
		  <td><form method='post' action='$website_admin_schools'>
		  <input type=hidden name='PHPSESSID' value='$PHPSESSID'>
		  <input type=hidden name='targetuser' value='$school_id'>
		  <input type=submit name='doaction' value='Edit'>
		  <input type=submit name='doaction' value='Delete' onclick='Confirm();return document.CC_returnValue;'>
		  </form></td>
     </tr>";
  $current_color = !$current_color;
}

$list_string = "<TR><TD colspan=\"9\"> List by School Name: ";
for($i=65;$i<=90;$i++)
{
	$list_string.="<a href='$website_admin_schools?list=".chr($i)."&sortby=school_name&orderby=".urlencode($orderby)."&PHPSESSID=$PHPSESSID'> ".chr($i)." </a>";
}
$list_string.=" &nbsp;&nbsp;[<a href=\"$website_admin_schools?PHPSESSID=$PHPSESSID\">List all Schools</a>]</TD></TR>";


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Schools</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
<script language="javascript">
function Confirm()
{
      if (!confirm("Are you sure you want to perform selected Action?")) 
  	  {
  		  document.CC_returnValue =  false;
      }
  	  else
  	  {
  		  document.CC_returnValue = true;
  	  }
}
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align="left" width="800">
    <div align="center"><strong><?php echo $msg?></strong></div>
    <form action="<?php echo $website_admin_schools?>" method="post" enctype="multipart/form-data" name="add_achool" id="add_school">
    <input type="hidden" name="PHPSESSID" value="<?php echo $PHPSESSID?>">
    <input type="hidden" name="doaction" value="add_schools">
    <table border='0' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
    <tr><td colspan="2" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Add New Schools</font><br><br></td></tr>
    <tr><td align="right">Upload Schools CSV File: </td><td><input name="school_file" type="file" id="school_file" /> <input type=submit name="Submit" value="Upload CSV">
		<input type="button" name="add_school" value="Add School Manually" onclick="MM_openBrWindow('add_school.php','','width=500,height=350')" >
	</td></tr>
    <tr><td colspan="2"><ul><li>File must be in same format as current school database  [<a href="<?php echo $website_admin_schools?>?PHPSESSID=<?php echo $PHPSESSID?>&doaction=export&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>">Download Current School Database as example</a>]</li>
                            <li>Only upload NEW schools you want to add. Do not upload the entire school database. </li>
                            <li>The program is not capable of knowing if duplicates are uploaded</li>
                        </ul></td></tr>
	</table>
	</form>
	<!--<hr size="1">
	<form action="<?php echo $website_admin_schools?>" method="post" enctype="multipart/form-data" name="add_achool" id="add_school">
    <input type="hidden" name="PHPSESSID" value="<?php echo $PHPSESSID?>">
    <input type="hidden" name="doaction" value="add_school">
    <table border='0' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
    <tr><td colspan="7" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Add New School</font><br><br></td></tr>
	<tr>
		<td width='10%'><b><font face="<?php echo $Font1?>" size='1'>County</font></b></td>
		<td width='10%'><b><font face="<?php echo $Font1?>" size='1'>District</font></b></td>
		<td width='20%'><b><font face="<?php echo $Font1?>" size='1'>School Name</font></b></td>
		<td width='20%'><b><font face="<?php echo $Font1?>" size='1'>Address</font></b></td>
		<td width='10%'><b><font face="<?php echo $Font1?>" size='1'>City</font></b></td>
		<td width='10%'><b><font face="<?php echo $Font1?>" size='1'>State</font></b></td>
		<td width='5%'><b><font face="<?php echo $Font1?>" size='1'>Zip</font></b></td>
	 </tr>
	<tr>
		<td width='10%'><input type="text" name="county" size="20" /></td>
		<td width='10%'><input type="text" name="district" size="20"/></td>
		<td width='20%'><input type="text" name="school" size="40"/></td>
		<td width='20%'><textarea name='address' cols="40" rows="2"></textarea></td>
		<td width='10%'><input type="text" name="city" size="20" /></td>
		<td width='10%'><input type="text" name="state" size="20" /></td>
		<td width='5%'><input type="text" name="zip" size="6" /></td>
	 </tr>
	<tr><td colspan="7" align="center"><input type="submit" name="submit" value="Add New School" /></td></tr> 
	</table>
	</form>-->
	<hr size="1">
    <table border='0' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="9" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current Schools</font><br><br></td></tr>
		<?php echo $list_string?>
		<form method=post action='<?php echo $website_admin_schools?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<tr><td colspan="6"><input type=text name='field_value' value='<?php echo $field_value?>'> <input type=submit name='Submit' value='Search'> [<a href='<?php echo $website_admin_schools?>'>Clear Search</a>]</td>
        <td colspan="3"><a href="<?php echo $website_admin_schools?>?PHPSESSID=<?php echo $PHPSESSID?>&doaction=export&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>">Download Current School Database</a></td></tr>
		</form>
		<tr><td colspan="9" align="center"><font face="Georgia, Times New Roman, Times, serif" size="2" color="#FFFFFF">
		<form method=post action='<?php echo $website_admin_schools?>'>
		<a href="<?php echo $website_admin_schools?><?php echo $sort_string?>page=<?php echo $page?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/first.gif" border="0"></a> 
		<a href="<?php echo $website_admin_schools?><?php echo $sort_string?>page=<?php echo (($page-1) > 1) ? ($page-1) : 1; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/previous.gif" border="0"></a> 
		Page <input type="text" size="3" name="page" id="page" value="<?php echo $page?>" onChange="window.location.href='<?php echo $website_admin_schools?><?php echo $sort_string?>page=' + this.value + '&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>';"> of <?php echo $pages?> 
		<a href="<?php echo $website_admin_schools?><?php echo $sort_string?>page=<?php echo (($page+1) < $pages) ? ($page+1) : $pages; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/next.gif" border="0"></a> 
		<a href="<?php echo $website_admin_schools?><?php echo $sort_string?>page=<?php echo $pages?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/last.gif" border="0"></a> 
		<!--There are <?php echo $total_count?> total schools<?php echo $string_pages?>-->
		</form></font>
		</td></tr>
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
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=id&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>ID</a> <?php echo $sort_id?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=county&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>County</a> <?php echo $sort_county?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=district&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>District</a> <?php echo $sort_district?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=school_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>School Name</a> <?php echo $sort_school_name?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=address&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Address</a> <?php echo $sort_address?></font> </b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=city&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>City</a> <?php echo $sort_city?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=state&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>State</a> <?php echo $sort_state?></font></b></td>
			<td width='12%'><b><font face="<?php echo $Font1?>" size='1'><a href='<?php echo $website_admin_schools?><?php echo $sort_string?>sortby=zip&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Zip</a> <?php echo $sort_zip?></font></b></td>
			<td width='10%'><b><font face="<?php echo $Font1?>" size='1'>Action</font></b></td>
		 </tr>
		 <tr><?php echo $current_records?>
		 </table>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
