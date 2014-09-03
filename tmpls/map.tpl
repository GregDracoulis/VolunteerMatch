
<head>
<title>$website_title - Terms of Usage</title>
[TEMPLATE]meta[/TEMPLATE]
[TEMPLATE]css[/TEMPLATE]
</head>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<style>
	#map_canvas {
	margin-left: 10px;
	padding: 0px;
	border: 1px solid #C0C0C0;
	width: 700px;
	height: 600px;
	float:right;
	margin-bottom: 10px;
	}
	
	#text {
        width: 600px;
        overflow: auto;
      }
	#map_search {
		width: 150px;
	}
	#main {
		width:1000px;
		padding-top: 5px;
	}
	#map_panel {
		width: 100px;
	}
	.checklist {
		float:left;
		clear: left;
		padding:0px
		margin:0px;
	}
	.paneldiv {
		float: left;
		clear: left;
		width: 250px;
	}
	#map_info {
		float: left;
		clear: left;
	}
	
</style>
<script type="text/javascript">
	
	
	/*
	TESTTEST
	TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST
	TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST
	TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST
	TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST
	TESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTESTTEST
	*/
	 function initialize() {
		//Set Map options
        var mapOptions = {
          scaleControl: true,
          center: new google.maps.LatLng(mapcenterlat, mapcenterlong),
          zoom: map_zoom,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		
		//Map all the schools
		mapSchools(school_array);//school_array is on map.php file
		//Function to draw all schools on map
		function mapSchools(schoolarray) {
			for(var i=0; i<schoolarray.length;i++){
				createMarker(schoolarray[i]);
			}
		}
		
		//Function to dynamically create map markers
		var activeInfoWindow;
		function createMarker(school) {
			//Create marker
			var marker = new google.maps.Marker({
			  map: map,
			  title: school.school_name,
			  position: new google.maps.LatLng(school.lat, school.lng)
			});
			//Create infowindow for this marker
			var infowindow = new google.maps.InfoWindow();
			var infowindowhtml = '<h2>' + school.school_name + '</h2>' +
			'<div>'+ school.address +'</div>' +
			'<div>'+ school.city +', '+school.state + ' '+ school.zip +'</div>' +
			'<div><a href="http://maps.google.com/maps?saddr=&daddr='+school.address+','+school.city+','+school.state+','+school.zip+'" target="_blank">Directions</a></div>';
			infowindow.setContent(infowindowhtml);
			google.maps.event.addListener(marker, 'click', function() {
				////////////////
				//Function call to populate the multiple opportunities on side of page here
				if ( activeInfoWindow == this ) {
					return;
				}
				if ( activeInfoWindow ) {
					activeInfoWindow.close();
				}
				activeInfoWindow = infowindow;
				infowindow.open(map, marker);
			});
		}
      }

      google.maps.event.addDomListener(window, 'load', initialize);
</script>

<body id="home_page">
<div id="main">
	<!-- header  -->
	[TEMPLATE]header[/TEMPLATE]
	<!-- top nav bar -->
	[TEMPLATE]top_nav_bar[/TEMPLATE]
	<!-- content -->
	<div id="main">
		<h1>Eweek Opportunity Map</h1>
		<div id="map_canvas"></div>
		<div id="map_panel">
		
		<div id="Options">
		<h2>Search Options</h2>
		<div class="paneldiv">
			<div><ul type="none" style="float:left; padding:0px; margin:0px;">
			Grades
			<li><input type="checkbox" checked="true">Kindergarten </input></li>
			<li><input type="checkbox" checked="true">1st Grade </input></li>
			<li><input type="checkbox" checked="true">2nd Grade </input></li>
			<li><input type="checkbox" checked="true">3rd Grade </input></li>
			<li><input type="checkbox" checked="true">4th Grade </input></li>
			<li><input type="checkbox" checked="true">5th Grade </input></li>
			<li><input type="checkbox" checked="true">6th Grade </input></li>
			</ul></div>
			<div><ul type="none" style="padding:0px; margin:0px;">
			<li><input type="checkbox" checked="true">7th Grade </input></li>
			<li><input type="checkbox" checked="true">8th Grade </input></li>
			<li><input type="checkbox" checked="true">9th Grade </input></li>
			<li><input type="checkbox" checked="true">10th Grade </input></li>
			<li><input type="checkbox" checked="true">11th Grade </input></li>
			<li><input type="checkbox" checked="true">12th Grade </input></li>
			</ul></div>
		</div>
		<div class="paneldiv">
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			Volunteer Type:
			<li><input type="checkbox" checked="true">Give an hour lecture</input></li>
			<li><input type="checkbox" checked="true">Career Speaker</input></li>
			</ul></div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			<li><input type="checkbox" checked="true">Advisor Board</input></li>
			<li><input type="checkbox" checked="true">Other</input></li>
			</ul></div>
		</div>
		<div class="paneldiv">
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			Subject:
			<li><input type="checkbox" checked="true">Multiple</input></li>
			<li><input type="checkbox" checked="true">Science</input></li>
			</ul></div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;">
			<li><input type="checkbox" checked="true">Math</input></li>
			<li><input type="checkbox" checked="true">Life Science</input></li>
			</ul></div>
		</div>			
	</div>
	<div id="map_info" >
		<h2>Selected Opportunity</h2>
		<div><b>School:</b>Luther Burbank School 4 Wabash Ave San Jose 95128</div>
		<div><b>County:</b>Santa Clara</div>
		<div><b>Teacher:</b> Ysenia Villarreal</div>
		<div><b>Category:</b>Give an hour lecture</div>
		<div><b>Subject:</b>Multiple</div>
		<div><b>Details:</b>Grade 3: A general description of what engineers do. Examples of how the profession helps us in our everyday life. They are 3rd graders and do not have much experience with the subject, so the basic foundation would be great.</div>
		<div><button type="button" onclick="alert('You are now signed up for this!')">I want to Volunteer for this</button></div>
	</div>
	</div>
	</div>
	
	<!-- footer -->
	[TEMPLATE]footer[/TEMPLATE]
</div>	
</body>
</html>
