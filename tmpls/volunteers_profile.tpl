<html>
<head>
<title>$website_title - Volunteers Home</title>
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
  <div id="loginBox">
	  <form method="post" action="$website_volunteers_profile" name="profile_form" id="profile_form">
	  <input type="hidden" name="doaction">
	  <h2 align="center">Volunteer Profile </h2>
	  <hr size="1">
	  <strong><i><font color="red">$update_msg</font></i></strong>
	  <p><strong>Update Password</strong><br />
	  <br />
	  <input name="old_password" type="password" id="old_password"> 
	  Old Password<br />
	  <input name="new_password" type="password" id="new_password"> 
	  New Password<br />
	  <input name="new_rpassword" type="password" id="new_rpassword"> 
	  Confirm New Password<br /></p>
	  <p><strong>Update Contact Information</strong><br /><br />
	  <input type="text" name="fname" value="$fname"> First Name <input type="text" name="lname" value="$lname"> 
	  Last Name<br />
	  $company_dropdown Company (or former company) <br />
	  <input type="text" name="other_company" value="$other_company"> Other (if company not listed above) <br /><br />
	  <input type="text" name="title" value="$title"> Title<br />
	  <input type="text" name="industry" value="$industry"> Industry<br />
	  <input name="phone" type="text" value="$phone"> Daytime phone#<br />
	  <textarea name="address" cols="30" rows="4" id="address">$address</textarea>
	  Address<br />
	  <textarea name="details" cols="40" rows="5" id="details">$details</textarea>
	  Your Background (Education and Experience)<br />
	  </p>
	 <input type="button" name="button" value="Update Profile" onClick="document.getElementById('profile_form').doaction.value='profile_update';document.getElementById('profile_form').submit();return false;"> &nbsp;&nbsp;<input type="button" name="button" value="Cancel Update" onClick="document.getElementById('profile_form').doaction.value='cancel_update';document.getElementById('profile_form').submit();return false;">
	 <br />
	 <br />
	  </form>
	  </div>
  
  </div>
	<!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
