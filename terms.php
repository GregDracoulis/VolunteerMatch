<?php
include "global.php"; // Get Configuration
getsettings();



error_reporting(5);
####### Checking the website Status ############ //
if ($website_active != '1') {
eval("dooutput(\"".gettemplate("closed_page")."\");");

exit;
}
?>
<!DOCTYPE html>
	<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="eweek.css" />
    </head>
    <body>

	<?php require ("header.php") ?>
	<?php require ("nav.php") ?>
	<section id="mainContent">
	    
			<h2 align="center">Terms of Usage  </h2>
			<hr size="1">
			<p>Volunteer Match was developed by the California Centers for Applied Competitive Technologies (CACT) using State of California CTE Hub SB70 funds. This program is free if used for non-profit educational purposes. Please contact Mark Martin (mvmartin@ccsf.edu) at City College of San Francisco for information.</p>
			<hr size="1">
	</section>
		<?php require ("footer.php") ?>
	</body>
	</html>