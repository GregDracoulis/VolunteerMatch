<?php

include "global.php"; // Get Configuration
include "hashing_security.php";
require_once('recaptchalib.php');

getsettings();

$publickey = "6LdPHu4SAAAAALllMxN7Heb8nyoY1pb_lWW3uWMt"; // get this from the recaptcha signup page
$privatekey = "6LdPHu4SAAAAAKualkPRNQiKCJmEjMkAqYelrhgI"; // recaptcha private key
$recaptcha_html = recaptcha_get_html($publickey);

if ($_POST["doaction"] == "teachers_login") {
	$email = sql_safe($_POST["email"]);
	$password = sql_safe($_POST["password"]);
	$flag = 0;
	$login_msg = "<strong>Following error(s) occurred during Login:</strong><br />";
	
	if ($email && $password) {
		list($member_id,$member_email,$member_password) = $DB_site->query_first("SELECT id,email,password FROM teachers WHERE email='$email' AND account_status ='Active'");
			
		// Check if the password is hashed
		$hash_sections = explode(":", $member_password);
	    if(($hash_sections != HASH_SECTIONS) && ($hash_sections[HASH_ALGORITHM_INDEX] != PBKDF2_HASH_ALGORITHM) && ($hash_sections[HASH_ITERATION_INDEX] != PBKDF2_ITERATIONS)) {
	    	$correct_hash = create_hash($member_password);
			$DB_site->query("UPDATE teachers SET password='$correct_hash' WHERE id='$member_id' AND email='$member_email'");
		} else {
			$correct_hash = $member_password;
		}
		
		if ($member_id && (validate_password($password, $correct_hash))) {
			init_session($member_id, $member_email, 'teacher', $session_logout);
		} else {
        	$flag = 1;
        	$login_msg.="<strong>If you're not registered, please register below. If registered, please check and try again.</strong><br />";
      	}
	} else {
      $flag = 1;
      $login_msg.="<strong>Please enter both email and password to login.</strong><br />";
	}
	
	if ($flag == 0) {
		header("Location: teachers_assistancerequest.php") ;
	}
}

if ($_POST["doaction"] == "teachers_password") {
  $email = sql_safe($_POST["email"]);
  if ($email) {
    list($teacher_id,$teacher_fname,$teacher_lname) = $DB_site->query_first("SELECT id,fname,lname FROM teachers WHERE email='$email'");
	if ($teacher_id) {
		$time_now = gettimeofday();
		$hash = create_hash($email.$time_now[sec].$time_now[usec]);
		$hash_sections = explode(":", $hash);
		$activation_code = urlencode($hash_sections[HASH_PBKDF2_INDEX]);
		$DB_site->query("UPDATE teachers SET activation_code='$activation_code', account_status='Inactive' WHERE id='$teacher_id'");	
		$forgot_password_message = "Dear $teacher_fname $teacher_lname,

We recently received a password reset request for your account. 

Please reset your password by clicking the following link: $website_dir/forgot_password.php?$activation_code

Regards,
The Volunteer Match Team";
		$headers = "From: $do_not_reply_email\r\n";
		mail($email,"Volunteer Match Password Reset",$forgot_password_message,$headers);
		$login_msg = '<strong><span style="color:green;">Login information has been sent to your email address. Please check your inbox.</strong><br />';
	} else {
		$login_msg = "<strong>This e-mail address is not registered.</strong><br />";
	}
  }
  else
    $login_msg = "Please enter your Email address to recover password.<br />";
}

if ($_POST["doaction"] == "teachers_signup")
{
	$email = sql_safe($_POST["email"]);
	$remail = sql_safe($_POST["remail"]);
	$password = sql_safe($_POST["password"]);
	$rpassword = sql_safe($_POST["rpassword"]);
	$fname = sql_safe($_POST["fname"]);
	$lname = sql_safe($_POST["lname"]);
	$phone = sql_safe($_POST["phone"]);
	$county = sql_safe($_POST["county"]);
	$school = sql_safe($_POST["school"]);
	$school_not_listed = sql_safe($_POST["school_not_listed"]);
	$school_district = sql_safe($_POST["school_district"]);
	$school_name = sql_safe($_POST["school_name"]);
	$school_zip = sql_safe($_POST["school_zip"]);
	//echo "$school_county,$other_school_county<br>";
	
	$hashed_password = create_hash($password);
	
	$flag = 0;
	$register_msg = "Following error(s) occurred during registration:<br />";
	
	$resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $_POST["recaptcha_challenge_field"],
	                                $_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		$register_msg .= "Catptcha not valid. Please try again.<br />";
		$flag = 1;
	}

	if ($school_not_listed == "Y") {
		if (!$school_district || !$school_name || !$school_zip) {
			$register_msg .= "Please fill in all the school fields.<br />";
			$flag = 1;
		} else {
			list($school, $check_lat, $check_lon) = $DB_site->query_first("SELECT id,lat,lon FROM schools WHERE district='$school_district' AND zip='$school_zip' AND school_name='$school_name'");
			if (!$check_lat || !$check_lon) {
				// Geocode the school
				
				$geocode_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($school_name . ' ' . $school_zip)."&sensor=false";
		
				$ch = curl_init();
				$timeout = 5; // set to zero for no timeout
				curl_setopt ($ch, CURLOPT_URL, $geocode_url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		        $geocode_response = curl_exec($ch);
				curl_close($ch);
				
				$geocode_result = json_decode($geocode_response, true);
				if($geocode_result['status'] == "OK") {
					$lat = $geocode_result['results'][0]['geometry']['location']['lat'];
					$lon = $geocode_result['results'][0]['geometry']['location']['lng'];
					
					$geocoded_address = array();
					foreach($geocode_result['results'][0]['address_components'] as $address_component) {
						foreach($address_component['types'] as $type) {
							switch ($type) {
								case 'establishment':
									$geocoded_address['name'] = $address_component['long_name'];
									break;
								case 'street_number':
									$geocoded_address['street_number'] = $address_component['long_name'];
									break;
								case 'route':
									$geocoded_address['street_name'] = $address_component['long_name'];
									break;
		    					case 'locality':
		        					$geocoded_address['city'] = $address_component['long_name'];
		        					break;
								case 'administrative_area_level_2':
									$geocoded_address['county'] = $address_component['long_name'];
		        					break;
								case 'administrative_area_level_1':
		        					$geocoded_address['state'] = $address_component['short_name'];
		        					break;
								case 'postal_code':
		        					$geocoded_address['zip'] = $address_component['short_name'];
		        					break;
							}
						}
					}
					
					$school_name = $geocoded_address['name'];
					$school_address = $geocoded_address['street_number'] . ' ' . $geocoded_address['street_name'];
					$school_city = $geocoded_address['city'];
					$school_county = $geocoded_address['county'];
					$school_state = $geocoded_address['state'];
					$school_zip = $geocoded_address['zip'];
				
					$result = $DB_site->query("INSERT INTO schools (school_name,address,district,city,county,state,zip,lat,lon)
											VALUES('$school_name','$school_address','$school_district','$school_city','$school_county','$school_state','$school_zip',$lat,$lon)
											ON DUPLICATE KEY UPDATE lat=$lat,lon=$lon");
					list($school) = $DB_site->query_first("SELECT id FROM schools WHERE school_name='$school_name' AND address='$school_address' AND zip='$school_zip'");
					eval("\$message = \"".gettemplate('new_school_request_email')."\";");
					//echo $message;
					send_email($do_not_reply_email,$admin_email,"New School Addition ",$message);
				} else {
					// Geocode failed
					$register_msg .= "Could not locate school. Check name and zip code.<br />";
					$flag = 1;
				}
			}
		}
	}
	
	// Check password length
	if (strlen($password) < 5)
	{
		$register_msg .= "Password too short, Please use at least 5 character Password<br />";
		$flag = 1;
	}

	// Compare password and repeat password
	if ($password != $rpassword)
	{
		$register_msg .= " - Password and Confirm Password don't match. Please try again.<br />";
		$flag = 1;
	}
	
	// Compare password and repeat password
	if ($email != $remail)
	{
		$register_msg .= " - Username and Confirm Username [email] don't match. Please try again.<br />";
		$flag = 1;
	}
	
	if (!$fname OR !$lname OR !$school)
	{
		$register_msg .= "Please fill all the variables and try again.<br />";
		$flag = 1;
	}
	
	// Filter out Bad Emails
	if (!check_email_address($email))
	{
		$email = "";
		$register_msg .= "Invalid address syntax.<br />";
		$flag = 1;
	}

	list($count) = $DB_site->query_first("SELECT count(*) FROM teachers where email='$email'");
	if ($count > 0)
	{
		$register_msg.= "Email already exists in the database. Please enter another email.<br />";
		$flag = 1;
	}
	
	if ($flag == 0)
	{
		$time_now = gettimeofday();
		$hash = create_hash($email.$time_now[sec].$time_now[usec]);
		$hash_sections = explode(":", $hash);
		$activation_code = urlencode($hash_sections[HASH_PBKDF2_INDEX]);

		$DB_site->query("INSERT INTO teachers (password,email,fname,lname,phone,school,details,activation_code,account_status,submit_time,comments)
			VALUES('$hashed_password','$email','$fname','$lname','$phone','$school','$details','$activation_code','Unconfirmed',now(),'')");
								
		$activation_message = "Welcome to Volunteer Match!

You, or someone using your email address, recently registered with us. You can complete the process by clicking the following link: $website_dir/activate.php?$activation_code

If this is an error, ignore this email and you will be removed from our mailing list.

Thanks!
The Volunteer Match Team";

		$headers = "From: $do_not_reply_email\r\n";
		mail($email,"Volunteer Match Registration--Action Required",$activation_message,$headers);
		$register_msg = '<span style="color:green;">Your account has been registered<br />Check your email for the activation link that\'s on its way from </span><a href="mailto:'.$do_not_reply_email.'">'.$do_not_reply_email.'</a>';
	}
}

$county_dropdown = "";
$school_dropdown = "";
$cquery = $DB_site->query("SELECT id,county FROM schools GROUP BY county ORDER BY ID ASC");
while (list($cid,$county) = $DB_site->fetch_array($cquery))
{
  $school_found = 0;
  $squery = $DB_site->query("SELECT id,district,school_name,address,city FROM schools WHERE county='$county' ORDER BY school_name ASC");
  while (list($sid,$district,$school,$address,$city) = $DB_site->fetch_array($squery))
  {
    $address=preg_replace('/[^\w| ]+/i',' ',$address);
    $city=preg_replace('/[^\w| ]+/i',' ',$city);  
    $school_found++;
    $school_dropdown.= "arrItems1[$sid] = \"$school    ($address,$city)\"; arrItemsGrp1[$sid] = $cid;\n";
  }
  if ($school_found)
    $county_dropdown.= "<option value='$cid'>$county</option>";
}

$state_dropdown = "";
$query = $DB_site->query("SELECT state,abbrev FROM states ORDER BY state ASC");
while (list($state_name,$state_abbrev) = $DB_site->fetch_array($query))
{
	$state_dropdown.= "<option value='$state_abbrev'>$state_name</option>";
}

eval("dooutput(\"".gettemplate("teachers")."\");");
exit;

?>