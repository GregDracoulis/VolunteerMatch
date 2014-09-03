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
			<h2 align="center">Our Privacy Policy </h2>
			<hr size="1">
			<p>The information we obtain on this site will be used for matching teachers and volunteers. It will not be shared with anyone except for this use.</p>
			<hr size="1">
	</section>
	<?php require ("footer.php") ?>
	</body>
	</html>