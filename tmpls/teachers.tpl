<html>
<head>
<title>$website_title - Teachers Registration / Login</title>
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
		document.getElementById('new_school_div').style.display='block';
	}
	else
	{
		document.getElementById('signup_form').county.disabled= false;
		document.getElementById('signup_form').school.disabled= false;
		document.getElementById('new_school_div').style.display='none';
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
<script type="text/javascript">
        	var RecaptchaOptions = {
			    theme : 'white'
			 };
</script>
<script type="text/javascript">
<!--

	// sort function - ascending (case-insensitive)
	function sortFuncAsc(record1, record2) {
		var value1 = record1.optText.toLowerCase();
		var value2 = record2.optText.toLowerCase();
		if (value1 > value2) return(1);
		if (value1 < value2) return(-1);
		return(0);
	}

	// sort function - descending (case-insensitive)
	function sortFuncDesc(record1, record2) {
		var value1 = record1.optText.toLowerCase();
		var value2 = record2.optText.toLowerCase();
		if (value1 > value2) return(-1);
		if (value1 < value2) return(1);
		return(0);
	}

	function sortSelect(selectToSort, ascendingOrder) {
		if (arguments.length == 1) ascendingOrder = true;    // default to ascending sort

		// copy options into an array
		var myOptions = [];
		for (var loop=0; loop<selectToSort.options.length; loop++) {
			myOptions[loop] = { optText:selectToSort.options[loop].text, optValue:selectToSort.options[loop].value };
		}

		// sort array
		if (ascendingOrder) {
			myOptions.sort(sortFuncAsc);
		} else {
			myOptions.sort(sortFuncDesc);
		}

		// copy sorted options from array back to select box
		selectToSort.options.length = 0;
		for (var loop=0; loop<myOptions.length; loop++) {
			var optObj = document.createElement('option');
			optObj.text = myOptions[loop].optText;
			optObj.value = myOptions[loop].optValue;
			selectToSort.options.add(optObj);
		}
	}
//-->
</script></head>
<body id="home_page"> 
<div id="main"> <font size="2" face="Arial, Helvetica, sans-serif"> 
  </font> 
  <div id="content"> 
    <h2><font size="4" face="Arial, Helvetica, sans-serif">Teachers Requesting 
      Volunteers to Visit Their Classrooms</font></h2>
    <p><font size="2" face="Arial, Helvetica, sans-serif">We invite you to participate 
      in the SFUSD &quot;volunteers in the classroom&quot; program. This site 
      allows you, as a teacher, to request volunteers to come visit your class. 
      Once you register and put in your request(s), volunteers will be able to 
      view your request and get in contact with you. While we can't guarantee 
      that we'll find a match for every teacher request, we hope that this Volunteer 
      Match resource will make it much easier to get industry volunteers involved 
      in education.</font></p>
    <p><font size="2" face="Arial, Helvetica, sans-serif">In order to ensure appropriate 
      requests from California teachers (vs. random submittals from web surfers), 
      <br>
      we ask first-time users to REGISTER below. If you've already registered, 
      please LOGIN. </font></p>
    <div id="loginBox"> 
      <form method="post" action="$website_teachers" name="login_form" id="login_form">
        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="hidden" name="doaction">
        </font> 
        <h2><font color="#FF6600" size="3" face="Arial, Helvetica, sans-serif">Returning 
          teachers, please log in.</font><font size="2" face="Arial, Helvetica, sans-serif"><br>
          <strong> Enter your username and password below.<br>
          <i><font color="red">$login_msg</font></i></strong> <br>
          <strong>Username: *</strong><br>
          <input name="email" type="text" value="$email" size="50">
          <br>
          <font size="1" color="#666666">(your email address)</font><br>
          <strong>Password: *</strong> <br />
          <input name="password" type="password" size="50">
          <input type="button" name="button" value="Log in" onClick="document.getElementById('login_form').doaction.value='teachers_login';document.getElementById('login_form').submit();return false;">
          <br></font><font size="1">
          <a href="#" onClick="document.getElementById('login_form').doaction.value='teachers_password';document.getElementById('login_form').submit();return false;">I 
          forgot my password. Email me a reset link</a>.<br />
          </font> </h2>
      </form>
    </div>
    <hr size="1">
    <div id="signupBox"> 
      <form method="post" action="$website_teachers" name="signup_form" id="signup_form">
        <p><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="hidden" name="doaction">
          </font><font color="#FF6600" size="2" face="Arial, Helvetica, sans-serif"><br>
          <strong><font size="3">New users, please register first.</font></strong></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
          <strong> Create a username and password.<br>
          <i><font color="red">$register_msg<br>
          </font></i>Email Address:</strong> * <br />
          <input name="email" type="text" value="$email" size="50">
          <br/></font>
          <font size="2" face="Arial, Helvetica, sans-serif"><strong>Confirm Email:</strong> * <br />
            <input name="remail" type="text" value="$remail" size="50">
            <br>
            <font size="1" color="#666666">(must be same as your above email address)</font></font><br>
          <font size="2" face="Arial, Helvetica, sans-serif"><strong>Password</strong> 
          (minimum 5 characters): *<br />
          <input name="password" type="password" size="50">
          <br>
          <strong>Confirm password: </strong>*<br />
          <input name="rpassword" type="password" size="50">
          <br />
          </font> </p>
        <p><font size="3" face="Arial, Helvetica, sans-serif"><strong> Contact 
          Information</strong></font></p>
        <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>First name: 
          *</strong><br />
          <input name="fname" type="text" value="$fname" size="40">
          <strong><br>
          </strong> <strong>Last name: *</strong><br />
          <input name="lname" type="text" value="$lname" size="40">
          <strong><br>
          </strong> <strong>Phone number:</strong><br />
          <input name="phone" type="text" value="$phone" size="40">
          <br>
          <font size="1" color="#666666">Your phone number will not be published.</font></font></p>
        <p><font size="2" face="Arial, Helvetica, sans-serif"><strong>County your 
          school is in: *</strong><br />
          <select id="county" name="county" onChange="selectChange(signup_form.county, signup_form.school, '', arrItems1, arrItemsGrp1);sortSelect(signup_form.school, true);" >
            <option value="">- select -</option>
	  $county_dropdown 
	      </select>
          <strong><br><br>
          </strong> <strong>School name: </strong>*<br />
          <select id="school" name="school" >
          </select>
          <br>
          <font size="1" color="#666666">Choose after selecting county</font> </font></p>
        
        <p><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="checkbox" name="school_not_listed" id="school_not_listed" value="Y" onClick="checkboxAction();">
          <strong>School not listed? Please add your school below</strong><br>
		  <div id="new_school_div" style="display:none">
          <br />
		  <strong>School District</strong>: *
		  <input type="text" name="school_district" value="$school_district"><br /><br />
		  <strong>School Name</strong>: *
		  <input type="text" name="school_name" value="$school_name"><br /><br />
	      <strong>Zip</strong>: * 
		  <input type="text" name="school_zip" value="$school_zip" size="10"><br />
		  <br />
          </div></font>
		  
		  <font size="2" face="Arial, Helvetica, sans-serif">
		  <strong>Verify: </strong>*<br />
          $recaptcha_html
          <br />
          <input type="button" name="button2" value="Register" onClick="document.getElementById('signup_form').doaction.value='teachers_signup';document.getElementById('signup_form').submit();return false;">
          </font> </p>
	  </form>
      
    </div>
  </div>
  <font size="2" face="Arial, Helvetica, sans-serif"> 
  <!-- footer -->
  [TEMPLATE]footer[/TEMPLATE] </font></div>	
</body>
</html>
