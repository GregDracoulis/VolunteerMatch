<!DOCTYPE html>
<html>
<?php

include "global.php"; // Get Configuration
error_reporting(5);
getsettings();


?>

<script type="text/javascript">

var school_array = <?php echo json_encode(getSchools());?>;
var tar_array = <?php echo json_encode(getTars());?>;
var vars = <?php echo $_GET['vars']; ?>;
var sess = "<?php echo $_SESSION['session_id']; ?>";
var mapcenterlat = 37.338901;
var mapcenterlong = -121.893521;
var map_zoom = 11;
//If this is a specific location vars will be equal to 1, therefore set these variables
if( vars == 1) {
	map_zoom = 14;
	tarid = <?= (isset($_GET['tarid'])?$_GET['tarid'] : -1); ?>;
	mapcenterlat = -1;
	mapcenterlong = -1;
	var found = false;
	var i=0;
	var sname = "null";
	while(!found) {
		var tar = tar_array[i];
		if(tar.id == tarid){
			sname = tar.school_name;
			found = true;
		}
		i++;
	}
	found = false;
	i = 0;
	while(!found) {
		var school = school_array[i];
		if(school.school_name == sname){
			mapcenterlat = school.lat;
			mapcenterlong = school.lng;
			found = true;
		}
		i++;
	}
}



</script>


<head>
<link rel="stylesheet" type="text/css" href="eweek.css" />
</head>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQy4jXQecBOUl58rQCUaQEv5XaIqJsj5M&sensor=false"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta charset="utf-8">
<style>
	#map_canvas {
	margin-left: 20px;
	padding: 0px;
	border: 1px solid #C0C0C0;
	width: 700px;
	height: 600px;
	float:right;
	}
	
	#map_search {
		width: 300px;
	}
	#main {
		width:1020px;
		padding-top: 5px;
	}
	#pagetitle {
		padding-left:10px;
	}
	#map_panel {
		width: 300px;
		padding-left:10px;
	}
	.checklist {
		float:left;
		clear: left;
		padding:0px
		margin:0px;
	}
	.paneldiv {
		float: left;
		width: 250px;
	}
	#map_info {	
		
		//border-bottom: 1px solid gray;
		height: 600px;
		clear:left;
	}
	#info_title {
		border-bottom: 1px solid gray;
		width:275px;
	}
	.smalltitle {
		width: 275px;
	}
	.largetitle {
		width: 275px;
		font-size: 23px;
		font-weight: bold;
	}
	.opp_div1 {
		width: 270px;
		background-color: #fbfaf6;
	}
	.opp_div2 {
		width: 270px;
		background-color: #eeebdc;
	}
	#Options {
		margin-top: 10px;
	}
	#map_opps {
		height:545px;
		overflow-y: scroll;
		overflow-x: hidden;
	}
	#infowindowtitle {
		font-size: 20px;
		font-weight: bold;
	}
	#mask {
		position:absolute;
		z-index:9000;
		background-color:#000;
		display:none;
	}
	#modalwindow {
		position:fixed;
		width:440px;
		display:none;
		z-index:9999;
		padding: 5px;
		background-color: white;
	}
	#close {
		float: right;
		color: blue;
		text-decoration: underline;
		cursor: pointer;
	}
	#tarinfo {
		margin: 15px;
		margin-top: 25px;
		border: 1px solid #E0DCF5;
	}
	#tarbuttondiv {
		width: 50%;
		margin: 0px auto;
	}
	#tarvolunteer {
		margin: 15px;
	}
	.textbox {
		height: 100px;
		width: 400px;
	}
	#tarmessage {
		margin: 15px;
		border: 1px solid #E0DCF5;
	}
	#message {
		//width: 80%;
		//height: 20%;
	}
	
</style>
<script type="text/javascript">
	var currenttar = -1;
	$(document).ready(function () {
		if(vars == 1) {
			setMapInfoPanel(sname);
			$("#tarz" + tarid).css("border","1px solid rgb(109,154,249)");
		}
	});
	
	function getXMLHttp() {
		  var xmlHttp;

		  try
		  {
			//Firefox, Opera 8.0+, Safari
			xmlHttp = new XMLHttpRequest();
		  }
		  catch(e)
		  {
			//Internet Explorer
			try
			{
			  xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e)
			{
			  try
			  {
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			  }
			  catch(e)
			  {
				alert("Your browser does not support AJAX!")
				return false;
			  }
			}
		  }
		  return xmlHttp;
	}
	
	function MakeRequest() {
		var xmlHttp = getXMLHttp();
		  
		xmlHttp.onreadystatechange = function() {
			if(xmlHttp.readyState == 4) {
			  HandleResponse(xmlHttp.responseText);
			}
		  }
		 var message = $("#message").val();;
		 var other = $("#emails").val();;
		 xmlHttp.open("GET", "tarsubmit.php?sess="+sess+"&"+"message="+message+"&tarid="+currenttar+"&other_volunteers="+other, true); 
		 xmlHttp.send(null);
	}	
	
	function HandleResponse(response) {
		
		//Succesful submission
		if (response == "") {
			alert("Succesfully submitted");
			$("#message").val('');
			$("#emails").val('');
			$('#tarbuttondiv').show();
			$('#tarmessage').hide();
			hidemodal();
			//remove the tar that was just submitted for
			$('#tarz'+currenttar).hide();
		}
		
		else {
			alert(response);
			alert("Error Submitting.  If problem persists email us at: mvmartin@ccsf.edu");
		}
   }
		
	
	//hidemodal function
		function hidemodal() {
			$('#modalwindow').hide();
			$('#mask').hide();
		}
	
	 function initialize() {
		$("#close").click(hidemodal);
		 //Show when "Volunteer for this Opportunity!" button is clicked
		$("#tarbutton").click(function showvolunteer() {
			$('#tarbuttondiv').hide();
			$('#tarmessage').show();
		});
		$("#tarcancel").click(function hidevolunteer() {
			$('#tarbuttondiv').show();
			$('#tarmessage').hide();
		});
		
		
		
		
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
			var infowindowhtml = '<div id="infowindowtitle">' + school.school_name + '</div>' +
			'<div>'+ school.address +'</div>' +
			'<div>'+ school.city +', '+school.state + ' '+ school.zip +'</div>' +
			'<div><a href="http://maps.google.com/maps?saddr=&daddr='+school.address+','+school.city+','+school.state+','+school.zip+'" target="_blank">Directions</a></div>';
			infowindow.setContent(infowindowhtml);
			google.maps.event.addListener(marker, 'click', function() {
				
				if ( activeInfoWindow == this ) {
					return;
				}
				if ( activeInfoWindow ) {
					activeInfoWindow.close();
				}
				activeInfoWindow = infowindow;
				infowindow.open(map, marker);
				
				//Function call to populate the multiple opportunities on side of page here
				setMapInfoPanel(marker.title);
			});
			//Close click removes the data in the map opps div and changes title
			google.maps.event.addListener(infowindow,'closeclick',function(){
				document.getElementById("info_title").innerHTML = "Select a school on map";
				document.getElementById("map_opps").innerHTML = "";
			});
		}
      }
	  
	  function setMapInfoPanel(school_name) {
		document.getElementById("info_title").innerHTML = school_name + "<br/ > <p style='font-size: 15px; font-weight:bold'>Click on an opportunity below </p>";
	    var html = '';
		var divnum=1;
	    for(var i=0; i<tar_array.length; i++) {
			tar = tar_array[i];
			if (tar.school_name == school_name) {
			  html = html + '<div id="tarz'+tar.id +'" class="opp_div' + divnum + '"' + 'onclick=extraInfo('+ tar.id +');' + 
			  //'mouseenter=function changeColor(){$(".opp_div1").css("background-color", "red");};'+
			  '>' +
			  'Grade: ' + tar.grades + '<br/>' + 
			  'Class Size: ' + tar.students + '<br/>' +
			  'Description: ' + tar.details + '<br/>' +
			  '</div>';
			  //alternating colors
			  if ( divnum == 1) {
				divnum=2;
			  }
			  else
				divnum=1;
			}
		}
		document.getElementById("map_opps").innerHTML = html;
		//Hover color change
		$('.opp_div1').mouseenter(function () {
			$(this).css("background-color", "#6d9af9");
			$(this).css("cursor", "pointer");
		});
		$('.opp_div2').mouseenter(function () {
			$(this).css("background-color", "#6d9af9");
			$(this).css("cursor", "pointer");
		});
		$('.opp_div1').mouseleave(function () {
			$(this).css("background-color", "#fbfaf6");
		});
		$('.opp_div2').mouseleave(function () {
			$(this).css("background-color", "#eeebdc");
		});
	  }
	  
	  function changeColor(divid) {
			var mydiv = document.getElementById(divid);
			$(mydiv).css("background-color", "red");
			alert();
			return;
			//mydiv.background = "#AA0000";
	  }
	  
	  function extraInfo(tarid) {
	    //Set height and width to mask to fill up the whole screen
		var winH = $(window).height();
        var winW = $(window).width();
		$('#mask').css({'width':winW,'height':winH});
        $('#mask').fadeTo("200",0.9); 
		
		//set location of modal popup to center
		var modal = document.getElementById("modalwindow");
		$(modal).css('top',  100);
        $(modal).css('left', winW/2-$(modal).width()/2);
		
		//Get the tar
		var tar;
		for(var i=0; i<tar_array.length; i++) {
			tar = tar_array[i];
			if (tar.id == tarid)
				i = tar_array.length;
		}
		//set html for specific tar
		$('#tarschool').html(tar.school_name);
		$('#tarteacher').html(tar.teacher_fname + " " + tar.teacher_lname);
		$('#tarsubject').html(tar.subject);
		$('#targrades').html(tar.grades);
		$('#tarstudents').html(tar.students);
		$('#tarcategory').html(tar.category);
		$('#tarbest_times').html(tar.best_times);
		$('#tardetails').html(tar.details);
		
		currenttar = tarid;
		
		//Hide data entry for vonlunteering
		$('#tarbuttondiv').show();
		$('#tarmessage').hide();
		
		$(modal).fadeIn(1);
		
		$('#mask').click(hidemodal); 
	  }

	
	 //Do when window is resized
	$(document).ready(function () {
		$(window).resize(function () {
  
        var box = $('#modalwindow');
  
        //Get the screen height and width
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();
       
        //Set height and width to mask to fill up the whole screen
        $('#mask').css({'width':maskWidth,'height':maskHeight});
                
        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();
  
        //Set the popup window to center
        box.css('top',  100);
        box.css('left', winW/2 - box.width()/2);
		});
	});
	  
	 

      google.maps.event.addDomListener(window, 'load', initialize);
</script>

<body id="home_page">
<?php require("header.php"); ?>
<?php require("nav.php"); ?>
<div id="boxes">
	
	<div id="modalwindow">
		<div id="close">[X]</div>
		<div id="tarinfo">
			<div><b>School: </b><span id="tarschool"></span></div>
			<div><b>Teacher: </b><span id="tarteacher"></span></div>
			<div><b>Subject: </b><span id="tarsubject"></span></div>
			<div><b>Grade: </b><span id="targrades"></span></div>
			<div><b>Class Size: </b><span id="tarstudents"></span></div>
			<div><b>Category: </b><span id="tarcategory"></span></div>
			<div><b>Best Times: </b><span id="tarbest_times"></span></div>
			<div><b>Details: </b><span id="tardetails"></span></div>
		</div>
		<div id="tarbuttondiv"><button type="button" id="tarbutton">Volunteer for this Opportunity!</button></div>
		
		<div id ="tarmessage" >
		
		<label for="message"> Your Message </br>(include times available, and contact info [email and phone #]): </label></br>
		<textarea id="message" name="message" class="textbox"></textarea></br></br>
		<label for="emails">Copy other volunteers for this class (separate emails with comma):</label></br>
		<textarea id="emails" name="emails" class="textbox"></textarea></br>
		
		<button type="button" id="tarsubmit" onclick='MakeRequest();' >Send Email</button>
		<button type="button" id="tarcancel">Cancel Email</button>
		
		</div>
	
	</div>
    <!-- Used to mask screen during modal window --> 
    <div id="mask"></div>
</div>
<div id="main">
		<h1 id="pagetitle">Eweek Opportunity Map</h1>
		<div id="map_canvas"></div>
		<div id="map_panel">
		
		<div id="map_info" >
			<div id="info_title" class="largetitle">Select a school on map</div>
			<div id="map_opps"></div>
		</div>
		<!--
		<div id="Options">
		<div class="largetitle">Search Options</div>
		<div class="paneldiv">
		<div class="smalltitle">Grades:</div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;">
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
			<div class="smalltitle">Volunteer Type:</div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			<li><input type="checkbox" checked="true">Hour Lecture</input></li>
			<li><input type="checkbox" checked="true">Career Speaker</input></li>
			</ul></div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			<li><input type="checkbox" checked="true">Advisor Board</input></li>
			<li><input type="checkbox" checked="true">Other</input></li>
			</ul></div>
		</div>
		<div class="paneldiv">
			<div class="smalltitle">Subject:</div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;" >
			<li><input type="checkbox" checked="true">Multiple</input></li>
			<li><input type="checkbox" checked="true">Science</input></li>
			</ul></div>
			<div><ul type="none" style="float:left; padding:0px; margin:0px;">
			<li><input type="checkbox" checked="true">Math</input></li>
			<li><input type="checkbox" checked="true">Life Science</input></li>
			</ul></div>
		</div>	
		
	</div> -->
	
	</div>
	
	
	<!-- footer -->
	
</div>	
 <?php require("footer.php"); ?>
 
</body>
</html>




