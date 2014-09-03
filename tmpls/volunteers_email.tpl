<html>
<head>
<title>$website_title - Volunteers Email</title>
[TEMPLATE]meta[/TEMPLATE]
[TEMPLATE]css[/TEMPLATE]
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
  <h2>Browsing of Teacher Assistance Requests</h2>
  <p>Welcome $fname $lname</p>
  <hr size="1">
  <form method="post" action="$website_volunteers_overview" name="email_form" id="email_form">
	  <input type="hidden" name="doaction">
	  <input type="hidden" name="tid" value="$tid">
	  <h2>Email Teacher</h2>
	  <strong><i><font color="red">$msg</font></i></strong>
	  <p><strong>To</strong>: $teacher_fname $teacher_lname [$teacher_email] <br />
	  $school_name ($school_address, $school_city) <br />
	    <strong>Subject</strong>: Volunteering at your School ($category_name)<br />
	  $months, $best_times<br />
	  $details<br />
	  <strong>From</strong>: $fname $lname [$email] [Ph.$volunteer_phone]<br />
	  <strong>Volunteer Information</strong>: $title, $company, $industry, $volunteer_details<br />
	  <br />
	  <strong>Your message</strong> (include times available if known, and contact info [email and phone #])<br />
	  <textarea name="message" cols="80" rows="6" id="message">$message</textarea></p>
	  <br /><br />
	  Copy other volunteers for this class (one email address per line):<br /> 
	  <textarea name="other_volunteers" cols="40" rows="3" id="other_volunteers">$other_volunteers</textarea>
	  </p>
	  <br />
	  <br />
	  <input type="button" name="button" value="Send Email" onClick="document.getElementById('email_form').doaction.value='send_email';document.getElementById('email_form').submit();return false;"> &nbsp;&nbsp;<input type="button" name="button" value="Cancel Email" onClick="document.getElementById('email_form').doaction.value='cancel_email';document.getElementById('email_form').submit();return false;">
	  <br />
	  </form>
  <hr size="1">
  </div>
    <!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
