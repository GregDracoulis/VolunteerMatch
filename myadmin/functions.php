<?php

require('Swift/Swift.php');
require('Swift/Swift/Connection/SMTP.php');


// ###################### Start gettemplate #######################
function gettemplate($templatename,$escape=1,$gethtmlcomments=1) {
  // gets a template from the db or from the local cache
  global $templatecache,$DB_site,$templatesetid,$addtemplatename,$website_template_path;
  //echo "Getting $templatename\n";
  if (isset($templatecache[$templatename])) {
    $template=$templatecache[$templatename];
  } else {
    //echo "$website_template_path/$templatename.tpl<br>";
    $template = implode("",file("$website_template_path/$templatename.tpl"));
    $template = preg_replace("/\[TEMPLATE\](.+?)\[\/TEMPLATE\]/e", "getnestedtemplate('\\1')", $template); // Any Nested Templates???
    //echo $template;
    //$gettemp=$DB_site->query_first("SELECT template FROM template WHERE title='".addslashes($templatename)."' ORDER BY templatesetid DESC LIMIT 1");
    //$template=$gettemp[template];
    $templatecache[$templatename]=$template;
 }

  if ($escape==1) {
    $template=addslashes($template);
    $template=str_replace("\\'","'",$template);
  }
  if ($gethtmlcomments and $addtemplatename) {
    return "<!-- BEGIN TEMPLATE: $templatename -->\n$template\n<!-- END TEMPLATE: $templatename -->";
  }
  return $template;
}

function getnestedtemplate($templatename)
{
  //eval('global $' . join(',$', array_keys($GLOBALS)) . ';');

  //eval("\$new_data = \"".gettemplate($templatename)."\";");
  $new_data = gettemplate($templatename,0,0);
  //$template=addslashes($template);
  //$template=str_replace("\\'","'",$template);


  return $new_data;
}

function getmessagetemplate($templatename,$activation_code,$escape=1,$gethtmlcomments=1) {
  // gets a template from the db or from the local cache
  global $templatecache,$DB_site,$templatesetid,$addtemplatename,$website_alert_templates_dir;
  //echo "Getting $templatename\n";
  if (isset($templatecache[$templatename])) {
    $template=$templatecache[$templatename];
  } else {
    //echo "$website_template_path/$templatename.tpl<br>";
    $template = implode("",file("$website_alert_templates_dir/$activation_code/$templatename.tpl"));
    //echo $template;
    //$gettemp=$DB_site->query_first("SELECT template FROM template WHERE title='".addslashes($templatename)."' ORDER BY templatesetid DESC LIMIT 1");
    //$template=$gettemp[template];
    $templatecache[$templatename]=$template;
 }

  if ($escape==1) {
    $template=addslashes($template);
    $template=str_replace("\\'","'",$template);
  }
  if ($gethtmlcomments and $addtemplatename) {
    return "<!-- BEGIN TEMPLATE: $templatename -->\n$template\n<!-- END TEMPLATE: $templatename -->";
  }
  return $template;
}


// ###################### Start dooutput #######################
function dooutput($vartext,$sendheader=1) {

  global $pagestarttime,$query_count,$showqueries,$querytime;

  if ($showqueries) {
    $pageendtime=microtime();

    $starttime=explode(" ",$pagestarttime);
    $endtime=explode(" ",$pageendtime);

    $totaltime=$endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];

    $vartext.="<!-- Page generated in $totaltime seconds with $query_count queries -->";
  }

  if (!$showqueries) {
    echo dovars($vartext,$sendheader);
    flush;
  } else {
    $output=dovars($vartext,$sendheader);
    echo "\n<b>Page generated in $totaltime seconds with $query_count queries,\nspending $querytime doing MySQL queries and ".($totaltime-$querytime)." doing PHP things.</b></pre>";
    flush;
  }
}

// ###################### Start dovars #######################
function dovars($newtext,$sendheader=1) {
  // parses replacement vars

  global $DB_site,$replacementsetid,$gzipoutput,$gziplevel,$newpmmsg;
  static $vars;

  if (connection_status()) {
    exit;
  }

  /*if (!isset($vars)) {
    $vars=$DB_site->query("SELECT findword,replaceword FROM replacement WHERE replacementsetid IN(-1,'$replacementsetid') ORDER BY replacementsetid DESC,replacementid DESC");
  } else {
    $DB_site->data_seek(0,$vars);
  }

  while ($var=$DB_site->fetch_array($vars)) {
    if ($var['findword']!="") {
      $newtext=str_replace($var['findword'],$var['replaceword'],$newtext);
    }
  }*/

  global $PHP_SELF;

  if ($gzipoutput and !headers_sent()) {
    $newtext=gzipoutput($newtext,$gziplevel);
  }

  if ($sendheader) {
    @header("Content-Length: ".strlen($newtext));
  }

  return $newtext;
}

// ###################### Start gettextareawidth #######################
function gettextareawidth() {
	// attempts to fix idiotic Nutscrape textarea width problems
	global $HTTP_USER_AGENT;

	if (eregi("MSIE",$HTTP_USER_AGENT)) { // browser is IE
		return "70";

	} elseif (eregi("Mozilla/5.0",$HTTP_USER_AGENT)) { // browser is NS 6
		return "40";

	} elseif (eregi("Mozilla/4.",$HTTP_USER_AGENT)) { // browser is NS4
		return "50";

	} else { // unknown browser - stick in a sensible value
		return 60;

	}

}

function form_element($variable,$value,$optioncode)
{
	if ($optioncode == "yesno")
	{
		if (!(isset($value) OR $value=="")) {$value= '1';}
		$formelement = "Yes <INPUT TYPE='radio' name='$variable' value='1'";
		$formelement.= ($value=="1")?"checked":"";
		$formelement.= "> No <INPUT TYPE=radio name='$variable' value='0'";
		$formelement.= ($value=="0")?"checked":"";
		$formelement.= ">";
	}
	elseif ($optioncode == "textarea")
	{
		$formelement = "<TEXTAREA NAME='$variable' rows='7' cols='".gettextareawidth()."'>$value</TEXTAREA>";
	}
	else
	{
		$formelement = "<INPUT TYPE='text' name='$variable' value='$value' size='50'>";
	}
	return $formelement;
}

function sql_addslashes($a_string = '', $is_like = FALSE)
{
        if ($is_like) {
            $a_string = str_replace('\\', '\\\\\\\\', $a_string);
        } else {
            $a_string = str_replace('\\', '\\\\', $a_string);
        }
        $a_string = str_replace('\'', '\\\'', $a_string);

        return $a_string;
} // end of the 'sql_addslashes()' function

function send_email($from,$to,$subject,$body,$type='text/plain')
{
	$headers = "From: $from" . "\r\n" .
    "Reply-To: $from" . "\r\n" .
    "X-Mailer: PHP/" . phpversion();
	mail($to, $subject, $body, $headers);
}

//function send_email($from,$to,$subject,$body,$type='text/plain')
//{
  //Specify secure TLS when we make the connection
//  $swift = new Swift(new Swift_Connection_SMTP('localhost'));
//
//  if (!$swift->hasFailed())
//  {
//      $swift->send(
//          "$to",
//          "$from",
//          "$subject",
//          "$body",
//          "$type"
//      );
//      $swift->close();
//      //print "Mail Sent to $to.";
//  }
//  else
//  {
//     //print "Failed to send mail.";
//  }
//}

function sql_safe($value)
{
  // Stripslashes
  if (get_magic_quotes_gpc())
    $value = stripslashes($value);
  // Remove \r
  $value = eregi_replace("\r\n","\n",$value);
  // Strip tags
  $value = strip_html_tags(trim($value));
  // Encode special html entities
  $value = htmlspecialchars($value, ENT_QUOTES);
  // Escape the string
  $value = mysql_real_escape_string($value);

  return $value;
}

function check_email_address($email)
{
 // First, we check that there's one @ symbol, and that the lengths are right
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
  {
    // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++)
  {
    if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
    {
      return false;
    }
  }
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
  { // Check if domain is IP. If not, it should be valid domain name
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2)
    {
      return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++)
    {
      if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i]))
      {
        return false;
      }
    }
  }
  return true;
}
function strip_html_tags( $text )
{
    $text = preg_replace(
        array(
          // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
          // Add line breaks before and after blocks
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',
        ),
        array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",
        ),
        $text );
    return strip_tags( $text );
}

// Filtering Function

function filterBadWords($str,$badWordsFile) {
  $badFlag = 0;
  if(!is_file($badWordsFile)) {
    echo "ERROR missing  bw file: " . $badWordsFile;
    exit;
  }
  else {
    $badWordsFH = fopen($badWordsFile,"r");
    $badWordsArray = explode("\n", fread($badWordsFH, filesize($badWordsFile)));
    fclose($badWordsFH);
  }
  foreach ($badWordsArray as $badWord) {
    if(!$badWord) continue;
    else {
    	$mybadWord = trim($badWord);
      $regexp = "/\b".$mybadWord."\b/i";
      if(preg_match($regexp,$str)) $badFlag = 1;
      //echo "<hr>$regexp searched in $str - Result $badFlag<hr>";
    }
  }
    if(preg_match("/\[url/",$str)) $badFlag = 1;
  return $badFlag;
}



class schoolObj{}
function getSchools(){
	//These are for when pushed to real server - USE IN MAIN ENVIRONMENT
	mysql_connect("localhost", "cacthub_svec", "svec2011") or die(mysql_error()); 
	mysql_select_db("cacthub_vmdbsvec") or die(mysql_error());
	
	//These are for dynamic values - NOT WORKING YET
	//mysql_connect("$servername", "$dbusername", "$dbpassword") or die(mysql_error()); 
	//mysql_select_db("$dbname") or die(mysql_error());
	
	//These are for when running on local computer
	//mysql_connect("localhost", "root", "") or die(mysql_error()); 
    //mysql_select_db("eweek") or die(mysql_error()); 
	//Selects only the schools which have a request for a volunteer
	$sql = "SELECT DISTINCT schools.* FROM  schools INNER JOIN tars ON tars.school_name = schools.school_name and tars.email_status = 'Open'";
	$result = mysql_query($sql);
	if(!$result)
	echo "Query failed";
	
	$schoolarray = Array();
	while ($row = mysql_fetch_assoc($result)) {
		$school = new schoolObj();
		$school->id = $row["id"];
		$school->school_name = $row["school_name"];
		$school->state = $row["state"];
		$school->zip = $row["zip"];
		$school->city = $row["city"];
		$school->lat = $row["lat"];
		$school->lng = $row["lng"];
		$school->address = $row["address"];
		
		if(is_null($school->lat) == true OR is_null($school->lng) == true){
			$address = $school->school_name . ", " . $school->address . ", " . $school->city . ", " . $school->zip;
			//$prepAddr = str_replace(' ','+',$address);

			$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
			 
			$output= json_decode($geocode);
			 
			$lat = $output->results[0]->geometry->location->lat;
			$lng = $output->results[0]->geometry->location->lng;
			$school->lat = $lat;
			$school->lng =  $lng;
			//Add these new values to the database
			$schoolid = $school->id;
			mysql_query("UPDATE schools SET lat='$lat',lng='$lng' WHERE id='$schoolid'");
		}

		array_push($schoolarray, $school);
  }
  return $schoolarray;
}

class tarObj{}
function getTars() {
	//These are for when pushed to real server - USE IN MAIN ENVIRONMENT
	mysql_connect("localhost", "cacthub_svec", "svec2011") or die(mysql_error()); 
	mysql_select_db("cacthub_vmdbsvec") or die(mysql_error());
	
	//These are for dynamic values - NOT WORKING YET
	//mysql_connect("$servername", "$dbusername", "$dbpassword") or die(mysql_error()); 
	//mysql_select_db("$dbname") or die(mysql_error());
	
	//These are for when running on local computer
	//mysql_connect("localhost", "root", "") or die(mysql_error()); 
   	// mysql_select_db("eweek") or die(mysql_error()); 
	$sql = "SELECT * FROM tars WHERE email_status = 'Open'";
	$result = mysql_query($sql);
	if(!$result)
	echo "Query failed";
	
	$tararray = Array();
	while ($row = mysql_fetch_assoc($result)) {
		$tar = new tarObj();
		$tar->id = $row["id"];
		$tar->teacher_id = $row["teacher_id"];
		$tar->teacher_fname = $row["teacher_fname"];
		$tar->teacher_lname = $row["teacher_lname"];
		$tar->county_name = $row["county_name"];
		$tar->district_name = $row["district_name"];
		$tar->school_name = $row["school_name"];
		$tar->school_city = $row["school_city"];
		$tar->school_zip = $row["school_zip"];
		$tar->subject = $row["subject"];
		$tar->category = $row["category"];
		$tar->best_times = $row["best_times"];
		$tar->months = $row["months"];
		$tar->details = $row["details"];
		$tar->grades = $row["grades"];
		$tar->students = $row["students"];
		$tar->submit_time = $row["submit_time"];
		$tar->tar_status = $row["tar_status"];
		$tar->volunteer = $row["volunteer"];
		$tar->rating = $row["rating"];
		$tar->complete_time = $row["complete_time"];
		$tar->comments = $row["comments"];
		$tar->email_status = $row["email_status"];
		array_push($tararray, $tar);
	}
	return $tararray;
}

function load_page($page_url,$delay_secs,$display_msg)
{
?>
<html>
<head>
<title>Loading..Please wait.</title>
<meta http-equiv="Refresh" CONTENT="<?php echo $delay_secs?>; URL=<?php echo $page_url?>">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="3"><strong><?php echo $display_msg?></strong></font></div>
    </td>
  </tr>
  <tr>
    <td>
      <div align="center"><font face="Arial, Helvetica, sans-serif" size="1">Loading..Please 
        wait.. <a href="<?php echo $page_url?>">Click here</a> if nothing happens</font></div>
    </td>
  </tr>
</table>
</body>
</html>
<?php
exit; 
}

?>