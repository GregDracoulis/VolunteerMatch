<?php

error_reporting(5);

$admin_companies_class = "class='selected_link'";

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

if ($_GET["doaction"] == "volunteers_type")
{
  setcookie("volunteers_type","$show");
  $volunteers_type = $show;
}
else if ($_COOKIE["volunteers_type"] != "")
  $volunteers_type = $_COOKIE["volunteers_type"];
else
{
  setcookie("volunteers_type","Active");
  $volunteers_type = "Active";
}

// add records
if ($_POST["doaction"] == "add" && $_POST["company_name"] != "")
{
	$company_name = sql_safe($_POST["company_name"]);
	$result = $DB_site->query("INSERT INTO companies (company_name,submit_time) VALUES('$company_name',now())");
	$msg = "Company Added.";
	load_page("$website_admin_companies?PHPSESSID=$PHPSESSID",1,$msg);
}
// update records
if ($_POST["doaction"] == "update" && $_POST["cid"] != "" && $_POST["company_name"] != "")
{
	$cid = $_POST["cid"];
	$company_name = sql_safe($_POST["company_name"]);
	$result = $DB_site->query("UPDATE companies SET company_name='$company_name' WHERE id='$cid'");
	$msg = "Company Updated.";
	load_page("$website_admin_companies?PHPSESSID=$PHPSESSID",1,$msg);
}

if ($_GET["doaction"] == "delete" && $_GET["cid"] != "")
{
	$cid = $_GET["cid"];
	list($company_name) = $DB_site->query("DELETE FROM companies WHERE id='$cid'");
	$msg = "Company Deleted.";
	load_page("$website_admin_companies?PHPSESSID=$PHPSESSID",1,$msg);
}	
	
if ($_GET["doaction"] == "edit" && $_GET["cid"] != "")
{
	$cid = $_GET["cid"];
	list($company_name) = $DB_site->query_first("SELECT company_name FROM companies WHERE id='$cid'");
?>
<!DOCTYPE html>
<html><head>
<title><?php echo $website_title?> Edit Companies</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align='left' width="800">
    <div align="center"><strong><?php echo $msg?></strong></div>
	<form method="post" action="<?php echo $website_admin_companies?>" id="company_form">
	<input type="hidden" name="PHPSESSID" value="<?php echo $PHPSESSID?>">
	<input type="hidden" name="cid" value="<?php echo $cid?>">
	<input type="hidden" name="doaction" value="update">
	<table width="100%" border="0">
	<tr><td colspan="2"><h2>Edit Company</h2></td></tr>
	<tr><td>Company: </td><td><input type="text" name="company_name" size="50" value="<?php echo $company_name?>"> <input type=submit name="Submit" value="Update Company"></td></tr>
	</table>
	</form>
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
	$jump_query = " company_name LIKE '%$field_value%'";
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
	$listquery = " WHERE  companies.company_name REGEXP '^$list'  ";
	if ($jump_query != '') {$listquery.= "AND $jump_query";}
	$sort_string .= "&list=$list&";
}
elseif ($jump_query != '') {$listquery = "WHERE  $jump_query  ";}
else {$listquery = " ";}

$field = trim($field);
$text = trim($text);

// Get the Pages
list($total_count) = $DB_site->query_first("SELECT count(*) FROM companies $listquery");

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
		$string_pages.="<a href='$website_admin_companies".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";

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
				$string_pages.="<a href='$website_admin_companies".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
			$string_pages.="</a>";
			}
		}
		$string_pages.="<a href='$website_admin_companies".$sort_string."page=2&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		$string_pages.="<a href='$website_admin_companies".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a>";
		if (!isset($sortby)) {$sortby = "companies.id ASC";}
		$result = $DB_site->query("SELECT companies.id,companies.company_name,DATE(companies.submit_time) AS submit_time,count(volunteers.id) AS count FROM companies LEFT JOIN volunteers ON (volunteers.company=companies.company_name) $listquery GROUP BY companies.company_name ORDER BY $sort_query LIMIT $records_per_page");
		$string_pages.=" ]";
	}
	else // Show the Requested Page
	{
		$string_pages = " You can go directly to PAGES: [";
		$string_pages.="<a href='$website_admin_companies".$sort_string."page=1&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;&lt;</a>&nbsp;&nbsp;";
		if ($page > 1) {
			$string_pages.="<a href='$website_admin_companies".$sort_string."page=".($page-1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&lt;</a>&nbsp;&nbsp;";
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
				$string_pages.="<a href='$website_admin_companies".$sort_string."page=$count&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>";
			}
			$string_pages.="$count&nbsp;&nbsp;";
			if ($count != $page) {
				$string_pages.="</a>";
			}
		}
		if ($page < $pages) {
			$string_pages.="<a href='$website_admin_companies".$sort_string."page=".($page+1)."&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;</a>&nbsp;&nbsp;";
		}
		$string_pages.="<a href='$website_admin_companies".$sort_string."page=$pages&PHPSESSID=$PHPSESSID&sortby=$sortby&orderby=$orderby'>&gt;&gt;</a> ";
		$start_limit=($page-1)*$records_per_page;
		if (!isset($sortby)) {$sortby = "companies.id ASC";}
		$result = $DB_site->query("SELECT companies.id,companies.company_name,DATE(companies.submit_time) AS submit_time,count(volunteers.id) AS count FROM companies LEFT JOIN volunteers ON (volunteers.company=companies.company_name) $listquery GROUP BY companies.company_name ORDER by $sort_query LIMIT $start_limit,$records_per_page");
		$string_pages.=" ]";
	}
}
else
{
	$page = $pages = 1;
	$string_pages = "";
	if (!isset($sortby)) {$sortby = "companies.id ASC";}
	$result = $DB_site->query("SELECT companies.id,companies.company_name,DATE(companies.submit_time) AS submit_time,count(volunteers.id) AS count FROM companies LEFT JOIN volunteers ON (volunteers.company=companies.company_name) $listquery GROUP BY companies.company_name ORDER BY $sort_query");
}


$count1 = 0;
$color = 0;

if ($total_count == "") $total_count = "0";
$current_records = "";

$colors = Array("#FFCCCC","");
$current_color = 0;

while (list($company_id,$company_name,$submit_date) = $DB_site->fetch_array($result))
{
	$flag = 1;
	$bgcolor = $colors[$current_color];
	$status_bgcolor = "";

	list($volunteers) = $DB_site->query_first("SELECT count(*) FROM volunteers WHERE company='$company_name'");

	$volunteers = ($volunteers  == "") ? "0" : $volunteers;
    
	$current_records.="<tr bgcolor=\"$bgcolor\">
		<td class=\"smallText\">$company_id</font></td>
		<td class=\"smallText\">$company_name</td>
		<td class=\"smallText\">$submit_date</td>
		<td class=\"smallText\">$volunteers</td>
		<td class=\"smallText\"><a href=\"$website_admin_companies?PHPSESSID=$PHPSESSID&doaction=edit&cid=$company_id\">Edit</a> - 
		<a href=\"$website_admin_companies?PHPSESSID=$PHPSESSID&doaction=delete&cid=$company_id\" onClick=\"Confirm();return document.CC_returnValue\" >Delete</a></td>
		</tr>";
	$current_color = !$current_color;
}

$list_string = "<TR><TD colspan=\"5\"> List by Company Name: ";
for($i=65;$i<=90;$i++)
{
	$list_string.="<a href='$website_admin_companies?list=".chr($i)."&sortby=company_name&orderby=".urlencode($orderby)."&PHPSESSID=$PHPSESSID'> ".chr($i)." </a>";
}
$list_string.="&nbsp;&nbsp;[<a href=\"$website_admin_companies?PHPSESSID=$PHPSESSID\">List all Companies</a>]</TD></TR>";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head>
<title><?php echo $website_title?> Companies</title>
<LINK href="./cp.css" rel=stylesheet type=text/css>
<script language="Javascript">
<!--
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
-->
</script>
</head>
<body>
<table width='100%' border='0'>
<tr><td colspan='2'><h2><?php echo $website_title?></h2></td></tr>
<tr><td valign='top' width="150">
<?php echo $navbar?>
</td>
<td valign='top' align='left' width="800">
    <div align="center"><strong><?php echo $msg?></strong></div>
    <table border='1' cellpadding='2' cellspacing='0' width='100%' bordercolor="<?php echo $Color5?>">
		<tr><td colspan="5" align="center"><font face="Georgia, Times New Roman, Times, serif" size="4" color="#FFFFFF">Current Companies</font></td></tr>
		<?php echo $list_string?>
		<form method=post action='<?php echo $website_admin_companies?>'>
		<input type=hidden name='PHPSESSID' value='<?php echo $PHPSESSID?>'>
		<tr><td colspan="5">
          <input type=text name='field_value' value='<?php echo $field_value?>'> <input type=submit name='Submit' value='Search'> [<a href='<?php echo $website_admin_companies?>'>Clear Search</a>]
        </td>
        </tr>
		</form>
		<tr><td colspan="5" align="center"><font face="Georgia, Times New Roman, Times, serif" size="2" color="#FFFFFF">
		<form method=post action='<?php echo $website_admin_companies?>'>
		<a href="<?php echo $website_admin_companies?><?php echo $sort_string?>page=<?php echo $page?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/first.gif" border="0"></a> 
		<a href="<?php echo $website_admin_companies?><?php echo $sort_string?>page=<?php echo (($page-1) > 1) ? ($page-1) : 1; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/previous.gif" border="0"></a> 
		Page <input type="text" size="3" name="page" id="page" value="<?php echo $page?>" onChange="window.location.href='<?php echo $website_admin_companies?><?php echo $sort_string?>page=' + this.value + '&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>';"> of <?php echo $pages?> 
		<a href="<?php echo $website_admin_companies?><?php echo $sort_string?>page=<?php echo (($page+1) < $pages) ? ($page+1) : $pages; ?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/next.gif" border="0"></a> 
		<a href="<?php echo $website_admin_companies?><?php echo $sort_string?>page=<?php echo $pages?>&PHPSESSID=<?php echo $PHPSESSID?>&sortby=<?php echo $sortby?>&orderby=<?php echo $orderby?>"><img src="../images/last.gif" border="0"></a> 
		</form>
		<!--There are <?php echo $total_count?> total companies <?php echo $string_pages?>-->
		
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
			<td width='5%' class="mediumText"><b><a href='<?php echo $website_admin_companies?><?php echo $sort_string?>sortby=id&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>ID</a> <?php echo $sort_id?></b></td>
			<td width='65%' class="mediumText"><b><a href='<?php echo $website_admin_companies?><?php echo $sort_string?>sortby=company_name&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Company</a> <?php echo $sort_company_name?></b></td>
			<td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_companies?><?php echo $sort_string?>sortby=submit_time&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'>Date Entered</a> <?php echo $sort_submit_time?></b></td>
			<td width='10%' class="mediumText"><b><a href='<?php echo $website_admin_companies?><?php echo $sort_string?>sortby=count&orderby=<?php echo $orderby?>&PHPSESSID=<?php echo $PHPSESSID?>'># of Volunteers</a> <?php echo $sort_count?></b></td>
			<td width='10%' class="mediumText"><b>Action</b></td>
		</tr>
		<tr><?php echo $current_records?>
	</table>
	<hr size="1">
	<form method="post" action="<?php echo $website_admin_companies?>" id="company_form">
	<input type="hidden" name="PHPSESSID" value="<?php echo $PHPSESSID?>">
	<input type="hidden" name="cid" value="<?php echo $cid?>">
	<input type="hidden" name="doaction" value="add">
	<table width="100%" border="1">
	<tr><td colspan="2"><h2>Add New Company</h2></td></tr>
	<tr><td>Company: </td><td><input type="text" name="company_name" size="50" value="<?php echo $company_name?>"> <input type=submit name="Submit" value="Add Company"></td></tr>
	</table>
	</form>
</td></tr></table>
<hr size='1'>
<div align='center'>
Copyright &copy; All Rights Reserved</div>
</body></html>
