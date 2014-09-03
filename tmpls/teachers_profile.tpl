<html>
<head>
<title>$website_title -Teachers Profile</title>
[TEMPLATE]meta[/TEMPLATE] 
[TEMPLATE]css[/TEMPLATE] 
<script type="text/javascript">
<!--
function checkboxAction()
{
	if (document.getElementById('profile_form').school_not_listed.checked == true)
	{
		document.getElementById('profile_form').county.disabled= true;
		document.getElementById('profile_form').school.disabled= true;
	}
	else
	{
		document.getElementById('profile_form').county.disabled= false;
		document.getElementById('profile_form').school.disabled= false;
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
  <div id="content"> 
    <div id="loginBox"> 
      <form method="post" action="$website_teachers_profile" name="profile_form" id="profile_form">
        <font size="3" face="Arial, Helvetica, sans-serif"> 
        <input type="hidden" name="doaction">
        </font><font face="Arial, Helvetica, sans-serif"> 
        <h2 align="left"><font size="4">Teacher Profile </font></h2>
        </font> 
        <hr size="1">
        <font size="3" face="Arial, Helvetica, sans-serif"><strong><i><font color="red">$update_msg</font></i></strong> 
        <strong><br>
        Update Password</strong><br />
        <font size="2"><br />
        <input name="old_password" type="password" id="old_password">
        Old Password<br>
        <br />
        <input name="new_password" type="password" id="new_password">
        New Password<br />
        <input name="new_rpassword" type="password" id="new_rpassword">
        New Password (confirm)<br />
        <br />
        <strong><font size="3">Update Contact Information</font></strong><br />
        <br />
        <input type="text" name="fname" value="$fname">
        First Name 
        <input type="text" name="lname" value="$lname">
        Last Name<br />
        <input type="text" name="phone" value="$phone">
        Phone<br />
        <br />
        School: $school_name, $school_address, $school_city, $school_state, $school_zip<br />
        $change_school_html<br />
        <br />
        <input type="button" name="button" value="Update Profile" onClick="document.getElementById('profile_form').doaction.value='profile_update';document.getElementById('profile_form').submit();return false;">
        &nbsp; 
        <input type="button" name="button" value="Cancel Update" onClick="document.getElementById('profile_form').doaction.value='cancel_update';document.getElementById('profile_form').submit();return false;">
        <br />
        <br />
        </font></font> 
      </form>
    </div>
  </div>
</div>	
</body>
</html>
