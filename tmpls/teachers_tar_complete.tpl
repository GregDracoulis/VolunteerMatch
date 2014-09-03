<html>
<head>
<title>$website_title -Teachers Assistance Requests</title>
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
<div id="main"> <font size="2" face="Arial, Helvetica, sans-serif"> 
  <!-- header  -->
  [TEMPLATE]header[/TEMPLATE] 
  <!-- top nav bar -->
  [TEMPLATE]teachers_nav_bar[/TEMPLATE] 
  <!-- content -->
  </font> 
  <div id="content"> 
    <h2 align="left"><font size="4" face="Arial, Helvetica, sans-serif">Teacher 
      Assistance Requests</font></h2>
    <p><font size="2" face="Arial, Helvetica, sans-serif">Welcome $fname $lname</font></p>
    <p><font size="2" face="Arial, Helvetica, sans-serif">School: $school_name</font></p>
    <hr size="1">
    <div id="loginBox"> 
      <form method="post" action="$website_teachers_overview" name="tar_form" id="tar_form">
        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="hidden" name="doaction">
        <input type="hidden" name="tid" id="tid" value="$tid">
        </font><font face="Arial, Helvetica, sans-serif">
        <h2><font size="3">Mark this Request as Completed </font></h2>
        <font size="2"><strong><i><font color="red">$tar_msg</font></i></strong> 
        <p>*Volunteer Completing Request : $volunteers_dropdown<br />
          *Rating (1-5): 
          <select id="rating" name="rating">
            <option value="" selected> - Select -</option>
            <option value="1">1 - Excellent job</option>
            <option value="2">2</option>
            <option value="3">3 - Neutral</option>
            <option value="4">4</option>
            <option value="5">5 - Poor job</option>
          </select>
          <br />
          Comment on the Rating: <br />
          <textarea name="comments" cols="40" rows="5"></textarea>
        </p>
        <input type="button" name="button" value="Mark Request as Completed" onClick="document.getElementById('tar_form').doaction.value='complete_tar';document.getElementById('tar_form').submit();return false;">
        &nbsp; 
        <input type="button" name="button" value="Return to request without updating" onClick="document.getElementById('tar_form').doaction.value='cancel_request';document.getElementById('tar_form').submit();return false;">
        <br />
        ----------------------- Request Details ---------------------<br />
        Subject: $subject<br />
        Grades taught : $grades<br />
        Number of Students: $students<br />
        <br />
        Desired Month(s): $months<br />
        <br />
        Category: $category_name<br />
        Details: $details<br />
        -------------------------------------------------------------<br />
        </font></font> 
      </form>
    </div>
  </div>
  <font size="2" face="Arial, Helvetica, sans-serif"> 
  <!-- footer -->
  [TEMPLATE]footer[/TEMPLATE] </font></div>	
</body>
</html>
