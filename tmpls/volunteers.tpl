<html>
<head>
<title>$website_title - Volunteer Registration / Login</title>
[TEMPLATE]meta[/TEMPLATE] 
[TEMPLATE]css[/TEMPLATE] 
</head> 
<body id="home_page"> 
<div id="main"> <font size="2" face="Arial, Helvetica, sans-serif"> 
  <!-- header  -->
  [TEMPLATE]header[/TEMPLATE] 
  <!-- top nav bar -->
  [TEMPLATE]top_nav_bar[/TEMPLATE] 
  <!-- content -->
  </font> 
  <div id="content"> 
    <h2><font size="4" face="Arial, Helvetica, sans-serif">Engineers Volunteering 
      to Visit Classrooms</font></h2>
    <p><font size="2" face="Arial, Helvetica, sans-serif">Engineers - if you would 
      like to speak to K-12 students and teachers about the challenges and rewards 
      of the <br>
      engineering profession, please register and you will be able to browse the 
      teacher requests for classroom visits. <br>
      </font></p>
    <div id="loginBox"> 
      <form method="post" action="$website_volunteers" name="login_form" id="login_form">
        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="hidden" name="doaction">
        </font><font color="#FF6600" size="3" face="Arial, Helvetica, sans-serif"><br>
        <strong>Returning volunteers, please log in.</strong></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
        <strong> Enter your username and password below.<br>
        </strong></font><font face="Arial, Helvetica, sans-serif"><font size="2"><strong><i><font color="red">$login_msg<br>
        </font></i><font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Username:</strong></font></font><font color="red"> 
        <i> <br>
        </i></font></strong> 
        <input name="email" type="text" value="$email" size="50">
        <br>
        </font><font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1">(your 
        email address)</font><br>
        <font face="Arial, Helvetica, sans-serif"><strong>Password:</strong></font> 
        </font><font size="2"> <br />
        <input name="password" type="password" size="50">
        <input type="button" name="button" value="Log in" onClick="document.getElementById('login_form').doaction.value='volunteers_login';document.getElementById('login_form').submit();return false;">
        <br />
        </font><font size="2" face="Arial, Helvetica, sans-serif"><a href="#" onClick="document.getElementById('login_form').doaction.value='volunteers_password';document.getElementById('login_form').submit();return false;"><font size="1">I 
        forgot my password. Email it to me</font></a><font size="1">.</font></font><font size="2"><br />
        <br />
        </font></font> 
      </form>
    </div>
    <hr size="1">
    <div id="signupBox"> 
      <form method="post" action="$website_volunteers" name="signup_form" id="signup_form">
        <p><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="hidden" name="doaction" >
          </font><font color="#FF6600" size="3" face="Arial, Helvetica, sans-serif"><br>
          <strong>New volunteers, please register first.</strong></font><font size="2" face="Arial, Helvetica, sans-serif"><br>
          <strong> Create a username and password.<br>
          </strong></font><font size="2"><strong><i><font color="red" face="Arial, Helvetica, sans-serif">$register_msg</font></i></strong> 
          <font face="Arial, Helvetica, sans-serif"><strong><i><font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
          </strong></font></font></i><font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Username: 
                      *</strong></font><i><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
                      </strong></font></i></font></strong></font></font> 
          <input name="email" type="text" value="$email" size="50">
          <br>
          <font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1">(must 
          be your email address)</font></font></font><br>
        <font size="2"><font face="Arial, Helvetica, sans-serif"><strong><font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Confirm Username: 
        *</strong></font><i><font size="2" face="Arial, Helvetica, sans-serif"><strong><br>
        </strong></font></i></font></strong></font></font>
          <input name="remail" type="text" value="$remail" size="50">
          <br>
          <font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1">(must 
        be same as your above email address)</font><br>
          <font face="Arial, Helvetica, sans-serif"><strong>Password </strong>(minimum 
          5 characters)<strong>:</strong></font> </font></font>*<br />
          <input name="password" type="password" size="50">
          <br>
          <font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><strong>Confirm 
            password: </strong></font></font>*<br />
          <input name="rpassword" type="password" size="50">
          <br />
          <font face="Arial, Helvetica, sans-serif"><font size="2"> 
          </font></font></p>
        <font face="Arial, Helvetica, sans-serif"><font size="2">
        <p><strong> <font size="3"><br>
          Contact Information</font></strong></p>
        </font></font> 
        <p><font face="Arial, Helvetica, sans-serif"><font size="2"><font face="Arial, Helvetica, sans-serif"><strong>First 
          name</strong></font></font>: *<br />
          <input name="fname" type="text" value="$fname" size="40">
          <strong><font size="2"><br>
          </font></strong> <font size="2"><font face="Arial, Helvetica, sans-serif"><strong>Last 
          name</strong></font></font>: *<br />
          <input name="lname" type="text" value="$lname" size="40">
          </font></p>
        <p><font face="Arial, Helvetica, sans-serif"><font size="2"><strong>Company 
          (or former company)</strong></font>: *<br />
		  $company_dropdown <br>
          <font size="2"><strong>Other</strong></font>:
          <input name="other_company" type="text" value="$other_company" size="40"><br>
          
          <strong><font face="Arial, Helvetica, sans-serif"><font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1">(if company not listed above)</font></font></font><font size="2"><br><br>
          </font></strong> <font size="2"><strong>Title</strong></font>:<br />
          <input name="title" type="text" value="$title" size="40">
          <strong><font size="2"><br>
          </font></strong> <font size="2"><strong>Industry</strong></font>:<br />
          <input name="industry" type="text" value="$industry" size="40">
          <strong><font size="2"><br>
          </font></strong> <font size="2"><strong>Daytime 
          phone #</strong></font>:<br />
          <input name="phone" type="text" value="$phone" size="40">
          <br>
          <font size="2" face="Arial, Helvetica, sans-serif"><font color="#666666" size="1">Your 
          phone number will not be published.</font></font></font></p>
        <p><font face="Arial, Helvetica, sans-serif"><font size="2"><strong>Address</strong></font>:<br />
          <textarea name="address" cols="35" rows="3" id="address">$address</textarea>
          </font></p>
        <p><font face="Arial, Helvetica, sans-serif"><font size="2"><strong>Background 
          (education &amp; experience)</strong></font>: <br />
          <textarea name="details" cols="35" rows="5" id="details">$details</textarea>
          <br />
          <font size="2"> 
          <input type="button" name="button" value="Register" onClick="document.getElementById('signup_form').doaction.value='volunteers_signup';document.getElementById('signup_form').submit();return false;">
          <br />
          <br />
          </font></font> </p>
      </form>
    </div>
  </div>
  <font size="2" face="Arial, Helvetica, sans-serif"> 
  <!-- footer -->
  [TEMPLATE]footer[/TEMPLATE] </font></div>	
</body>
</html>
