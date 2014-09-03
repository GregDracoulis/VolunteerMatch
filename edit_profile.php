<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
getsettings();

list($member_id,$email,$sess) = check_session("volunteer");
list($fname,$lname,$password,$title,$company,$industry,$phone,$address,$details) = $DB_site->query_first("SELECT fname,lname,password,title,company,industry,phone,address,details FROM volunteers WHERE id='$member_id' AND email='$email'");

if (isset($_POST["update"]))
{
	$old_password = sql_safe($_POST["old_password"]);
	$new_password = sql_safe($_POST["new_password"]);
	$new_rpassword = sql_safe($_POST["new_rpassword"]);
	$fname = sql_safe($_POST["fname"]);
	$lname = sql_safe($_POST["lname"]);
	$title = sql_safe($_POST["title"]);
	$company = sql_safe($_POST["company"]);
	$other_company = sql_safe($_POST["other_company"]);
	$industry = sql_safe($_POST["industry"]);
	$phone = sql_safe($_POST["phone"]);
	$address = sql_safe($_POST["address"]);
	$details = sql_safe($_POST["details"]);
 
	if ($other_company != "") // Other company given
	{
		$company_name = trim($other_company);
		if ($company_name != "") // add other to database if not there
		{
			list($check) = $DB_site->query_first("SELECT count(*) FROM companies WHERE company_name='$company_name'");
			if ($check <= 0)
			{
				$result = $DB_site->query("INSERT INTO companies (company_name,submit_time) VALUES('$company_name',now())");
			}
		}
	}
	else {
		list($company_name) = $DB_site->query_first("SELECT company_name FROM companies WHERE id='$company'");
	}
	
	$error_flag = false;
	
	if ($old_password && $new_password && $new_rpassword) {
		if (!validate_password($old_password, $password)) {
			$update_msg.= "<strong>Old Password doesn't match.</strong><br />";
			$error_flag = true;
		}

		if ($new_password != $new_rpassword)
		{
			$update_msg.= "<strong>New Password doesn't match Repeat New Password.</strong><br />";
			$error_flag = true;
		}

		if ($error_flag == false)
		{
			$new_hash = create_hash($new_password);
			$DB_site->query("UPDATE volunteers SET password='$new_hash' WHERE id='$member_id'");
			$update_msg.= "<strong>Password updated successfully.</strong><br />";
		}
	}
	
	if ($fname != "" AND $lname != "") {
		$result = $DB_site->query("UPDATE volunteers SET fname='$fname',lname='$lname',title='$title',company='$company_name',industry='$industry',phone='$phone',address='$address',details='$details' WHERE id='$member_id'");
		$update_msg.= "<strong>Profile updated successfully.</strong><br />";
	}
	
	if ($error_flag == false) {
		header("Location: volunteers.php");
		exit;
	}
}

$company_dropdown = "<select name='company' id='company'>";
$query = $DB_site->query("SELECT id,company_name FROM companies ORDER BY company_name ASC");
while (list($cid,$company_name) = $DB_site->fetch_array($query))
{
	if ($company_name == $company)
		$company_dropdown.= "<option value='$cid' selected>$company_name</option>";
	else
		$company_dropdown.= "<option value='$cid'>$company_name</option>";
	
}
$company_dropdown.= "</select>";




?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
    </head>
    <body>
        <?php require("header.php"); ?>
		<?php require("nav.php"); ?>
 <form method="POST">
        <section id="left">
          <fieldset id="reg1" class="inblk">
		
            <legend>Update Contact Information</legend>
              <div><label for="fname"> First Name: </label>
               <input type="text" id="fname" name="fname" value="<?php echo($fname); ?>" required="required"/>
              </div>
                   <div><label for="lname"> Last Name: </label>
                    <input type="text" id="lname" name="lname" value="<?php echo($lname); ?>" required="required"/>
                  </div>
                  <div>
                    <label for="company"> Company: </label>
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
                    <input id="address" name="address" type="text" value="<?php echo($address); ?>"/>
                  </div> 
                  <div>
                    <label for="phone"> Phone: </label>
                    <input type="text" id="phone" name="phone" value="<?php echo($phone); ?>" />
                  </div> 
                  
            </fieldset>
                           
            <fieldset id="reg3" class="inblk">
                <legend>Update Password</legend>
                    <div>
                       <label for="old_password" class="passwd"> Old Password: </label>
                      <input id="old_password" name="old_password" type="password" placeholder="eg. X8df!90EO"/> 
                    </div>
					 <div>
                       <label for="new_password" class="uname"> New Password: </label>
                       <input id="new_password" name="new_password" type="password" placeholder="eg. X8df!90EO"/> 
                        
                    </div>
                    <div>
                       <label for="new_rpassword" class="passwd"> Confirm Password: </label>
                      <input id="new_rpassword" name="rpassword" type="password" placeholder="eg. X8df!90EO" />
                    </div>
                  
                   
                       
            </fieldset> 
                <div id="register">
                    <input type="submit" name="update" value="Update" />
                </div>
        </section> 
			<section id="right" >
            
                <fieldset class="inblk">
                    <legend>Update Other information</legend>
                <div>
                        <label for="title"> Title: </label>
                        <input type="text" id="title" name="title" value="<?php echo($title); ?>"/>
                </div>
                
                    <div>
                       <label for="industry"> Industry: </label>
                       <input id="industry" name="industry" type="text" value="<?php echo($industry); ?>"/> 
                        
                    </div>
                   
                    <div>
                       <label for="details"> Background : </label>
                      <textArea rows="5" id="details" name="details" value="<?php echo($details); ?>"> 
                      </textArea>
                    </div>   
                    
				</fieldset>
				<?php echo($update_msg); ?>
        </section>
       </form>
   
       <?php require("footer.php"); ?>
    </body>
</html>

