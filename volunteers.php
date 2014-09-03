<?php

include "global.php"; // Get Configuration
getsettings();

list($member_id,$email,$sess) = check_session("volunteer");
list($fname,$lname,$title,$company,$industry,$address,$volunteer_details,$volunteer_phone,$city,$state,$zip,$lat,$lon) = $DB_site->query_first("SELECT fname,lname,title,company,industry,address,details,phone,city,state,zip,lat,lon FROM volunteers WHERE id='$member_id' AND email='$email'");

// Default latitude and longitude to center the map on
$centerlat = "37.338";
$centerlon = "-121.893";
//$search_distance = 25;
$unit = 'mi';

if ($address) {
	// The user has entered an address
	
	if (!$city || !$state || !$zip || !$lat || !$lon) {
		// We haven't geocoded their address
		$geocode_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";
		
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
						case 'street_number':
							$street_number = $address_component['long_name'];
							break;
						case 'route':
							$street_name = $address_component['long_name'];
							break;
    					case 'locality':
        					$city = $address_component['long_name'];
        					break;
						case 'administrative_area_level_1':
        					$state = $address_component['short_name'];
        					break;
						case 'postal_code':
        					$zip = $address_component['short_name'];
        					break;
					}
				}
			}
			$street_address = $street_number . ' ' . $street_name;
			
			$DB_site->query("UPDATE volunteers SET address='$street_address',city='$city',state='$state',zip='$zip',lat=$lat,lon=$lon WHERE id='$member_id' AND email='$email'");
		}
	}

	// Override the defaults for map center
	$centerlat = $lat;
	$centerlon = $lon;
	//$search_distance = 50;
}

// Radius of earth. The earth is not perfectly spherical, but this is the 'mean radius'
if ($unit == 'km') $radius = 6371.009; // in kilometers
elseif ($unit == 'mi') $radius = 3958.761; // in miles

// Latitude boundaries
$minLat = $centerlat - rad2deg($search_distance / $radius);
$maxLat = $centerlat + rad2deg($search_distance / $radius);

// Longitude boundaries (longitude gets smaller when latitude increases)
$minLon = $centerlon - rad2deg($search_distance / $radius / cos(deg2rad($centerlat)));
$maxLon = $centerlon + rad2deg($search_distance / $radius / cos(deg2rad($centerlat)));

// Get results ordered by distance (approx)
//$result = $DB_site->query("SELECT *
//    FROM schools 
//	WHERE (lat BETWEEN $minLat AND $maxLat) AND (lon BETWEEN $minLon AND $maxLon) 
//	ORDER BY ABS(lat - $centerlat) + ABS(lon - $centerlon) ASC");
	
$result = $DB_site->query("SELECT *
    FROM schools
	ORDER BY ABS(lat - $centerlat) + ABS(lon - $centerlon) ASC");
		
function getDistance($lat1, $lng1, $lat2, $lng2, $unit='mi') {
	// radius of earth; @note: the earth is not perfectly spherical, but this is considered the 'mean radius'
	if ($unit == 'km') $radius = 6371.009; // in kilometers
	elseif ($unit == 'mi') $radius = 3958.761; // in miles

	// convert degrees to radians
	$lat1 = deg2rad((float) $lat1);
	$lng1 = deg2rad((float) $lng1);
	$lat2 = deg2rad((float) $lat2);
	$lng2 = deg2rad((float) $lng2);
		
	// great circle distance formula
	return $radius * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
}
	
$nearby_schools = array();

while($row = $DB_site->fetch_array($result)) {
	// Only emit the results that are within the circle with specified radius
	//if (getDistance($lat, $lon, $row['lat'], $row['lon'], $unit) <= $search_distance) {
		$nearby_schools[] = $row;
	//}
}

$nearby_opportunities = array();

foreach($nearby_schools as $key => $school){
	$number_of_opportunities = 0;
	$school_id = $school["id"];
	$school_name = $school["school_name"];
	$school_zip = $school["zip"];
	$result = $DB_site->query("SELECT tars.id, schools.id AS school_id, schools.school_name, schools.address, schools.city, schools.state, schools.zip, teachers.fname, teachers.lname, tars.subject, categories.category_name, tars.details, tars.grades, tars.students FROM tars JOIN schools ON (tars.school_id=schools.id) OR (tars.school_name=schools.school_name AND tars.school_zip=schools.zip) JOIN teachers ON tars.teacher_id=teachers.id JOIN categories ON tars.category=categories.id WHERE ((tars.school_id='$school_id') OR (tars.school_name='$school_name' AND tars.school_zip='$school_zip')) AND tars.email_status='Open'");
	while($row = $DB_site->fetch_array($result)) {
		if(is_null($row["school_id"])){
			$row_id = $row["id"];
			$DB_site->query("UPDATE tars SET school_id='$school_id',school_name=NULL,county_name=NULL,district_name=NULL,school_city=NULL,school_zip=NULL,teacher_fname=NULL,teacher_lname=NULL,category_name=NULL WHERE id='$row_id'");
		}
		$nearby_opportunities[] = $row;
		$number_of_opportunities = $number_of_opportunities + 1;
	}
	if ($number_of_opportunities > 0) {
		$nearby_schools[$key]["opportunities"] = $number_of_opportunities;
	} else {
		unset($nearby_schools[$key]);
	}
}

// Get all open correspondence
$result = $DB_site->query("SELECT tars.id AS tar_id, tars_emails.id AS email_id,
	schools.school_name, schools.address, schools.city, schools.state, schools.zip,
	teachers.fname, teachers.lname, tars.subject, categories.category_name, tars.details, tars.grades, tars.students,
	tars_emails.email_message, tars_emails.email_dated 
	FROM tars_emails JOIN tars ON tars_emails.tar_id = tars.id
	JOIN teachers ON tars.teacher_id = teachers.id
	JOIN schools ON teachers.school = schools.id
	JOIN categories ON tars.category = categories.id
	WHERE tars_emails.volunteer = ANY ( SELECT id FROM volunteers WHERE email='$email' )
	AND tars_emails.email_status = 'pending'
	ORDER BY tars_emails.email_dated DESC");
	
$open_arrangements = array();

while($row = $DB_site->fetch_array($result)) {
	$open_arrangements[] = $row;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
        <link rel="stylesheet" type="text/css" href="bootstrap-tokenfield.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
        <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=YOUR_MAPS_KEY&sensor=false"></script>
        <script type="text/javascript">
        var map;
        var infowindow = new google.maps.InfoWindow({maxWidth: 350});
        
        google.maps.event.addListener(infowindow, 'closeclick', function() {  
		    $( "tbody > tr" ).show();
		});  
		
		function dropMarker(location, map, info, school_id) {
	        var marker = new google.maps.Marker({
	        	position: location,
	            map: map,
	            animation: google.maps.Animation.DROP
	        });
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,this);
				infowindow.setContent(info);
				$( "tbody > tr" ).hide();
				$( "tbody > tr" ).filter(function( index ) {
    				return $( this ).attr("data-school") == school_id;
  				}).show();
			});
		}
	    
	    nearby_schools = <?php echo json_encode($nearby_schools); ?>;
	    nearby_opportunities = <?php echo json_encode($nearby_opportunities); ?>;
	    
	    function dropSchools(school_list) {
	    	for (var key in school_list) {
	    		var info = '<div class="inner-infowindow">' + school_list[key]["school_name"] + "<br />" +
	    			((school_list[key]["address"].trim().length > 0) ? (school_list[key]["address"] + "<br />") : "") +
	    			school_list[key]["city"] + ", " + school_list[key]["state"] + " " + school_list[key]["zip"] + "<br />" +
	    			"<span style=\"color: green;\">" + school_list[key]["opportunities"] + ((school_list[key]["opportunities"] > 1) ? " opportunities</span>" : " opportunity</span>") +
	    			"</div>";
				var loc = new google.maps.LatLng(parseFloat(school_list[key]['lat']), parseFloat(school_list[key]['lon']));
				dropMarker(loc,map,info,school_list[key]["id"]);
			}
	    }
	    
	    function initialize() {
			var mapOptions = {
			center: new google.maps.LatLng(parseFloat(<?php echo $centerlat; ?>), parseFloat(<?php echo $centerlon; ?>)),
			zoom: 10
        	};
		map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		
		google.maps.event.addListenerOnce(map, 'idle', function(){
		    //this part runs when the mapobject is created and rendered
		    dropSchools(nearby_schools);
		});
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		</script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
		<script src="js/bootstrap-tokenfield.js" type="text/javascript"></script>
	    <script>
	    $(function() {
	    	$( document ).tooltip();
	    	//$( "#addl_emails" ).on('afterCreateToken', function (e) {
			    // Über-simplistic e-mail validation
			//    var re = /\S+@\S+\.\S+/
			//    var valid = re.test(e.token.value)
			//    if (!valid) {
			//      $(e.relatedTarget).addClass('invalid')
			//    }
			//  }).tokenfield();
	    	$( "#email_form" ).dialog({
				autoOpen: false,
				height: "auto",
				width: 725,
				modal: true,
				buttons: {
					"Send": function() {
						$( "#email_form" ).dialog( "close" );
						$.ajax({
							type: 'POST',
							cache: false,
							url: 'send_mail.php',
							data: $('form[name=volunteer]').serialize(),
							success: function(msg){
								alert(msg);
								location.reload();
					        }
					    });
					}
				}
			});
		
			$( ".volunteer" ).button().click(function() {
			 	$( "#request_id" ).val($(this).val());
				$( "#email_form" ).dialog( "open" );
			});
		
			$( ".complete_arrangement" ).button().click(function() {
			 	$.get( "do_arrangement.php", { id: $(this).val(), action: "complete"}, function( data ) {
  					alert( data );
  					location.reload();
				});
			});
			
			$( ".cancel_arrangement" ).button().click(function() {
			 	$.get( "do_arrangement.php", { id: $(this).val(), action: "cancel"}, function( data ) {
  					alert( data );
  					location.reload();
				});
			});
			
		});
		
		
        $(document).ready(function(){
		  $("#results").dataTable(
		  	{ 	// Disable sorting on the no-sort class
				"aoColumnDefs" : [ {
    				"bSortable" : false,
    				"aTargets" : [ "no-sort" ]
				} ]
			});
		});
		
		$(function() {
			$( "#open_correspondence" ).accordion({
				collapsible: true
			});
		});
	   </script>

    </head>
    <body>
         <?php require("header.php"); ?>
         <?php require("nav.php"); ?>
         <div style="float:right;">Welcome <?php echo ($fname." ".$lname); ?><br /><?php echo ($email); ?></div>

    <section id="mainContent" style="clear:both;margin-top:60px;">

	<div style="width:70%;float:left;">
	<div id="map-canvas" style="height:400px;min-width:100%;"></div>
		<table id="results">
			<thead>
				<tr>
					<th class="no-sort"></th>
					<th>School</th>
					<th>City</th>
					<th>Teacher</th>
					<th>Subject</th>
					<th>Category</th>
					<th>Details</th>
					<th>Grade</th>
					<th>Students</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				foreach($nearby_opportunities as $opportunity) {
					echo '<tr data-opportunity="'.$opportunity["id"].'" data-school="'.$opportunity["school_id"].'">';
					echo '<td><button class="volunteer" type="button" value="'.$opportunity["id"].'">Volunteer</button></td>';
					echo '<td><b>'.$opportunity["school_name"].'</b><br />'.$opportunity["address"].'</td>';
					echo '<td>'.$opportunity["city"].", ".$opportunity["state"].'</td>';
					echo '<td>'.$opportunity["fname"]." ".$opportunity["lname"].'</td>';
					echo '<td>'.$opportunity["subject"].'</td>';
					echo '<td>'.$opportunity["category_name"].'</td>';
					echo '<td>'.$opportunity["details"].'</td>';
					echo '<td>'.$opportunity["grades"].'</td>';
					echo '<td>'.$opportunity["students"].'</td>';
					echo '</tr>';
				}
			?>
			</tbody>
   		</table>
   	</div>
   	
   	<div style="width:30%;float:right;text-align:center;">
   		<span style="font-weight:bold;">Open Arrangements</span>
   		<div id="open_correspondence" style="font-size:12px;padding:10px;text-align:left;">
		<?php 
			foreach($open_arrangements as $open_arrangement) {
				$trimmed_address = trim($open_arrangement["address"]);
				$address_string = (!empty($trimmed_address) ? $open_arrangement["address"].', ' : '').$open_arrangement["city"].', '.$open_arrangement["state"].' '.$open_arrangement["zip"];
				echo '<h3>'.$open_arrangement["fname"]." ".$open_arrangement["lname"].'</b> ('.$open_arrangement["school_name"].')<br />';
				echo $open_arrangement["email_dated"].'</h3>';
				echo '<div style="padding:0;">';
				echo '<div style="background:#f5f5ff;padding:10px;border-bottom:1px solid rgb(170,170,170);">';
				echo '<a title="Get directions (opens in new window)" href="https://maps.google.com/maps?saddr=current+location&daddr='.urlencode($open_arrangement["school_name"].', '.$address_string).'" target="_blank">'.$address_string.'</a><br />';
				echo $open_arrangement["subject"].': '.$open_arrangement["category_name"].'--Grade '.$open_arrangement["grades"].', '.$open_arrangement["students"].' students<br />';
				echo $open_arrangement["details"];
				echo '</div>';
				echo '<div style="margin:10px;">';
				echo '<pre style="white-space:pre-wrap;">'.$open_arrangement["email_message"].'</pre>';
				echo '<br /><button class="complete_arrangement" type="button" value="'.$open_arrangement["tar_id"].'">Mark as Complete</button><button class="cancel_arrangement" type="button" value="'.$open_arrangement["tar_id"].'">Cancel</button>';
				echo '</div>';
				echo '</div>';
			}
		?>
		</div>
   	</div>
    </section>
    
<div id="email_form" title="Volunteer">
	<form name="volunteer" style="font-size: 12px;margin: 0px;text-align: center;">
		<fieldset style="margin: 0px;padding:10px 40px;">
			<label for="message">Message</label><br />
			<textarea id="message_textarea" name="message" cols="80" rows="8" autofocus="autofocus" required="required"
				placeholder="Get in contact with the teacher! Include some information about yourself and the dates and times you are available."></textarea>
			<br /><label for="cc">CC: </label><input id="cc" name="cc" type="text" placeholder="separate addresses with commas" style="background:white;width:400px;"></textarea>
			<br />We'll also send a copy to you at <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
			<input id="request_id" type="hidden" name="request" />
			<input type="hidden" name="email" value="<?php echo $email; ?>" />
		</fieldset>
	</form>
</div>
        
    <?php require("footer.php"); ?>
       
 	<script src="js/geoPosition.js" type="text/javascript" charset="utf-8"></script>
 	<script type="text/javascript" charset="utf-8">
 		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++) 
			  {
			  var c = ca[i].trim();
			  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
			  }
			return "";
		}
 	
 		$(document).ready(function() {
 			permission = getCookie("locate");
 			if (permission !== "false") {
 				if (geoPosition.init()) {
				  geoPosition.getCurrentPosition(geoSuccess, geoError);
				}
 			}
		});
		
		function geoSuccess(loc) {
		    var latitude = parseFloat(loc.coords.latitude);
		    var longitude = parseFloat(loc.coords.longitude);
			var ctr = new google.maps.LatLng(latitude, longitude);
			map.setCenter(ctr);
			map.setZoom(14);
		}
		
		function geoError() {
			document.cookie="locate=false";
		}
 	</script>
    </body>
</html>

