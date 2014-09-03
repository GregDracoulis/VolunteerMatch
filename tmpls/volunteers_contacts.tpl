<html>
<head>
<title>$website_title - Browsing of Teachers Contacted by you.</title>
[TEMPLATE]meta[/TEMPLATE]
[TEMPLATE]css[/TEMPLATE]
<script src="functions.js" type="text/javascript"></script>
</head>
<body id="home_page">
<div id="main">
	<!-- header  -->
	[TEMPLATE]header[/TEMPLATE]
	<!-- top nav bar -->
	[TEMPLATE]volunteers_nav_bar[/TEMPLATE]
	<!-- content -->
    <div id="content">
  <h2 align="center">Browsing of Teachers Contacted by you  </h2>
  <p>Welcome $fname $lname</p>
  <p>Below are the teachers you have contacted in the past</p>
  <hr size="1">
  <span class="msg">$msg</span>
  <table class="search" width="100%">
  <tr><td>
  	<form method=get action="$website_volunteers_contacts">
  	<input name='field_name' type=hidden value="county_name,school_name,school_city,school_zip,subject,months,details,grades">
  	<input name='field_value_all' type=text value="$field_value_all"> <input type=submit name='Submit' value='Search'> [<a href="$website_volunteers_overview">Clear search results</a>]
		</form></td>
    <td><form method=get action="$website_volunteers_contacts">
	<input type="hidden" name="field_name" value="category_name">
      Show requests with category: $category_dropdown 
       <input type=submit name='Submit' value='Search'></form></td>
  </tr>
  </table>
  <table class="results">
  <tr>
    <td colspan="10" ><span class="white center">You have contacted $total_count teachers in the past $string_pages</span></td>
  </tr>
  <tr class="tblheader">
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=teacher_id&orderby=$orderby">Teacher contacted</a></td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=school_name&orderby=$orderby">Date Contacted</a> </td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=school_city&orderby=$orderby">School</a> </td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=subject&orderby=$orderby">Subject</a> </td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=category_name&orderby=$orderby">Category</a></td>
    <td class="legend" width="50%"><a href="$website_volunteers_contacts$sort_string&sortby=grades&orderby=$orderby">Details</a></td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=months&orderby=$orderby">TimeFrame</a></td>
    <td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=students&orderby=$orderby"># of students</a> </td>
	<td class="legend"><a href="$website_volunteers_contacts$sort_string&sortby=id&orderby=$orderby">Email ID# (click to see email)</a> </td>
	<td class="legend">Status</td>
  </tr>
  $current_requests
</table>
    </div>
    <!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
