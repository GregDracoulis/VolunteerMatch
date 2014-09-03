<html>
<head>
<title>$website_title - Browsing of Teacher Assistance Requests</title>
[TEMPLATE]meta[/TEMPLATE]
[TEMPLATE]css[/TEMPLATE]
<script src="functions.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
function checkboxAction()
{
	if (document.getElementById('signup_form').school_not_listed.checked == true)
	{
		document.getElementById('signup_form').county.disabled= true;
		document.getElementById('signup_form').school.disabled= true;
	}
	else
	{
		document.getElementById('signup_form').county.disabled= false;
		document.getElementById('signup_form').school.disabled= false;
	}	
}
// -->
</script>

<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Revised by: DeWayne Whitaker :: http://www.aecdfw.com
Original by: Andrew Berry */


var arrItems1 = new Array();
var arrItemsGrp1 = new Array();

$school_dropdown

function selectChange(control, controlToPopulate, selectedItem, ItemArray, GroupArray) {
  var myEle ;
  var x ;
  // Empty the second drop down box of any choices
  for (var q=controlToPopulate.options.length;q>=0;q--) controlToPopulate.options[q]=null;

  // ADD Default Choice - in case there are no values
  myEle = document.createElement("option") ;
  myEle.setAttribute('value','');
  var txt = document.createTextNode("- select -");
  myEle.appendChild(txt)
  controlToPopulate.appendChild(myEle)
  
  // Now loop through the array of individual items
  // Any containing the same child id are added to
  // the second dropdown box
  for ( x = 0 ; x < ItemArray.length  ; x++ ) {
    if ( GroupArray[x] == control.value ) {
      myEle = document.createElement("option") ;
      myEle.setAttribute('value',x);
      if(myEle.value == selectedItem) { myEle.setAttribute('selected','1'); }
      var txt = document.createTextNode(ItemArray[x]);
      myEle.appendChild(txt)
      controlToPopulate.appendChild(myEle)
    }
  }
}

// -->
</script>
</head>
<body id="home_page">
<div id="main">
	<!-- header  -->
	[TEMPLATE]header[/TEMPLATE]
	<!-- top nav bar -->
	[TEMPLATE]volunteers_nav_bar[/TEMPLATE]
	<!-- content -->
    <div id="content">
  <h2 align="center">Browsing of Teacher Assistance Requests </h2>
  <p>Welcome $lname, $fname </p>
    <p>Thanks for offering to visit a local classroom. Below is a list of requests 
      from teachers. Please look through it and email teachers whose classrooms 
      you would like to visit (click on their name to send an email). </p>
    <p>We ask that you contact only one or two teachers at a time. It may take 
      a few days for a teacher to get back to you.<br>
      <br>
      To reduce the size of the list, you may search by zip code, school name, 
      teacher first name, teacher last name, or other keywords. </p>
    <p>We very much appreciate your willingness to help in the education of our 
      students. </p>
    <hr size="1">
  <span class="msg">$msg</span>
  <p><font size="4" face="Arial, Helvetica, sans-serif"><a href="$website_map?vars=0&sess=$sess">View map of all requests </a> </font></p>
  <table class="search" width="100%">
  <tr><td>
  	<form method=get action="$website_volunteers_overview">
  	<input name='field_name' type=hidden value="teacher_fname,teacher_lname,county_name,school_name,school_city,school_zip,subject,months,details,grades">
  	<input name='field_value_all' type=text value="$field_value_all"> <input type=submit name='Submit' value='Search'> [<a href="$website_volunteers_overview">Clear search results</a>]
		</form></td>
    <td><form method=get action="$website_volunteers_overview">
	<input type="hidden" name="field_name" value="category_name">
      Show requests with category: $category_dropdown 
       <input type=submit name='Submit' value='Search'></form></td>
  </tr>
  </table>
  
  <table class="results">
  <tr>
    <td colspan="9"><span class="medium_text white">Teacher Requests</span></td>
  </tr>
  <tr>
    <td colspan="9" ><span class="white center">There are $total_count total teacher assistance requests $string_pages</span></td>
  </tr>
  <tr class="tblheader">
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=county_name&orderby=$orderby">County</a></td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=school_name&orderby=$orderby">School</a> </td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=school_city&orderby=$orderby">City</a> </td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=teacher_lname&orderby=$orderby">Teacher</a> </td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=subject&orderby=$orderby">Subject</a> </td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=category_name&orderby=$orderby">Category</a></td>
    <td class="legend" width="50%"><a href="$website_volunteers_overview$sort_string&sortby=grades&orderby=$orderby">Details</a></td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=months&orderby=$orderby">TimeFrame</a></td>
    <td class="legend"><a href="$website_volunteers_overview$sort_string&sortby=students&orderby=$orderby"># of students in class</a> </td>
	<td class="legend">Map</td>
  </tr>
  $current_requests
</table>

    </div>
    <!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
