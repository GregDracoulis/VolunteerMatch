<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
require_once('recaptchalib.php');
getsettings();

session_start();

$publickey = "6LdPHu4SAAAAALllMxN7Heb8nyoY1pb_lWW3uWMt"; // get this from the recaptcha signup page

$company_dropdown = "<select name='company' id='company'>";
$query = $DB_site->query("SELECT id,company_name FROM companies ORDER BY company_name ASC");
while (list($cid,$company_name) = $DB_site->fetch_array($query))
{
	$company_dropdown.= "<option value='$cid'>$company_name</option>";
}
$company_dropdown.= "</select>";

$register_msg = $_SESSION["register_msg"];
unset($_SESSION["register_msg"]);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Volunteer Match Registration</title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
        <script type="text/javascript">
        	var RecaptchaOptions = {
			    theme : 'white'
			 };
        </script>
    </head>
    <body>
        <?php require("header.php"); ?>
		<div style="font-weight: bold;margin-top: 10px;margin-left:auto;margin-right:auto;width:100%;text-align: center;"> <label><?php echo($register_msg); ?></label></div>
        <form method="POST" action="do_register.php">
        <section id="left">
          <fieldset id="reg1" class="inblk">
		
            <legend>Contact Information</legend>
              <div><label for="fname"> First Name:* </label>
               <input type="text" id="fname" name="fname" required="required"/>
              </div>
                   <div><label for="lname"> Last Name:* </label>
                    <input type="text" id="lname" name="lname" required="required"/>
                  </div>
                  <div>
                    <label for="company"> Company:* </label>
					<?php echo ($company_dropdown); ?>
                    (or former company) 
                  </div>
                   <div>
                        <label for="other_company"> Other: </label>
                        <input type="text" id="other_company" name="other_company"/>
                        (if company not listed above)
                  </div>
             <div>
                    <label for="address"> Address: </label>
                    <input id="address" name="address" type="text"/>
                  </div> 
                  <div>
                    <label for="phone"> Phone: </label>
                    <input type="text" id="phone" name="phone"/>
                  </div> 
                  
            </fieldset>
                           
            <fieldset id="reg3" class="inblk">
                <legend>Create account</legend>
                    <div>
                       <label for="email" class="uname"> Email:* </label>
                       <input id="email" name="email" required type="email" placeholder="mymail@mail.com"/> 
                        
                    </div>
                    <div>
                       <label for="password" class="passwd"> Password:* </label>
                      <input id="password" name="password" required type="password" placeholder="eg. X8df!90EO" /> 
                    </div>
                    <div>
                       <label for="password" class="passwd"> Confirm Password:* </label>
                      <input id="password" name="rpassword" required type="password" placeholder="eg. X8df!90EO" />
                    </div>
                  
                   
                       
            </fieldset> 
                <div id="register">
                    <input type="submit" name="register" value="Register" />
                    <label>Already registered? </label> <a href="index.php">Login here</a>
                </div>
        </section> 
        <section id="right" >
            
                <fieldset class="inblk">
                    <legend>Other information</legend>
                <div>
                        <label for="title"> Title: </label>
                        <input type="text" id="title" name="title"/>
                </div>
                
                    <div>
                       <label for="industry"> Industry: </label>
                       <input id="industry" name="industry" type="text"/> 
                        
                    </div>
                   
                    <div>
                       <label for="details"> Background : </label>
                      <textArea rows="5" id="details" name="details" > 
                      </textArea>
                    </div>
				</fieldset>
				<fieldset class="inblk">
					<legend>Verification</legend>
					<div>
						<?php echo recaptcha_get_html($publickey);?>
					</div>
				</fieldset>
        </section>
       </form>
   
       <?php require("footer.php"); ?>
    </body>
</html>
