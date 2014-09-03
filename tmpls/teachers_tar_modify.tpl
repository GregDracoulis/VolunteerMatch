<html>
<head>
<title>$website_title -Teachers Assistance Requests</title>
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
	[TEMPLATE]teachers_nav_bar[/TEMPLATE]
	<!-- content -->
    <div id="content">
  <h2 align="center">Teacher Assistance Requests</h2>
  <p>Welcome $fname $lname</p>
  <p>School: $school_name</p>
  <hr size="1">
  <div id="loginBox">
	  <form method="post" action="$website_teachers_overview" name="tar_form" id="tar_form">
	  <input type="hidden" name="doaction">
	  <input type="hidden" name="tid" id="tid" value="$tid">
	  <h2>Modify Request</h2>
	  <strong><i><font color="red">$tar_msg</font></i></strong>
	  
	  <p><input type="text" name="subject" value="$subject"> Subject Taught *<br />
	  <select id="grades" name="grades"><option value="K" $grades_K>K</option><option value="1" $grades_1>1</option><option value="2" $grades_2>2</option><option value="3" $grades_3>3</option><option value="4" $grades_4>4</option><option value="5" $grades_5>5</option><option value="6" $grades_6>6</option><option value="7" $grades_7>7</option><option value="8" $grades_8>8</option><option value="9" $grades_9>9</option><option value="10" $grades_10>10</option><option value="11" $grades_11>11</option><option value="12" $grades_12>12</option></select>
	  Grades taught (if different grades, put in lowest grade) *<br />
	  <input type="text" name="students" value="$students"> Number of Students<br /></p>
	  Desired Month(s): * <br />
	  <input name="months[]" type="checkbox" value="Any" $months_Any> ANY during the school year , or (check all that apply) <br />
	  <input name="months[]" type="checkbox" value="Jan" $months_Jan> Jan <input name="months[]" type="checkbox" value="Feb" $months_Feb> Feb <input name="months[]" type="checkbox" value="Mar" $months_Mar> Mar <input name="months[]" type="checkbox" value="Apr" $months_Apr> Apr <input name="months[]" type="checkbox" value="May" $months_May> May <input name="months[]" type="checkbox" value="Jun" $months_Jun> Jun <input name="months[]" type="checkbox" value="Jul" $months_Jul> Jul <input name="months[]" type="checkbox" value="Aug" $months_Aug> Aug <input name="months[]" type="checkbox" value="Sep" $months_Sep> Sep <input name="months[]" type="checkbox" value="Oct" $months_Oct> Oct <input name="months[]" type="checkbox" value="Nov" $months_Nov> Nov <input name="months[]" type="checkbox" value="Dec" $months_Dec> Dec <br /><br />
	  <h3>Volunteer Needed For: * </h3>
	  $category_options
	  <br />
	  
<div id="comments_text">Please provide us details on what you would like to happen during the volunteer’s <br />
visit, and what sort of information they should bring (<a href="#" onClick="MM_openBrWindow('$website_teachers_overview?doaction=show_examples','example','scrollbars=yes,resizable=yes,width=600,height=600');return false;">examples</a>): *</div><br />
<textarea name="details" cols="50" rows="5" id="details">$details</textarea>
<br />
<input type="button" name="button" value="Update Request" onClick="document.getElementById('tar_form').doaction.value='modify_tar';document.getElementById('tar_form').submit();return false;"> &nbsp; <input type="button" name="button" value="Cancel Modification" onClick="document.getElementById('tar_form').doaction.value='cancel_modify';document.getElementById('tar_form').submit();return false;">
<br />
	  </form>
	  </div>
  
  </div>
	<!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
