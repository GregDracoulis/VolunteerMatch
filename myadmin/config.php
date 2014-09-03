<?php

/////////////////////////////////////////////////////////////
// Please note that if you get any errors when connecting, //
// that you will need to email your host as we cannot tell //
// you what your specific values are supposed to be        //
/////////////////////////////////////////////////////////////

// type of database running
// (only mysql is supported at the moment)
$dbservertype='mysql';

// hostname or ip of server
$servername='YOUR_HOSTNAME';

// username and password to log onto db server
$dbusername='YOUR_DB_USER';
$dbpassword='YOUR_DB_PASS';

// name of database
$dbname='YOUR_DB_NAME';

// allow password viewing / editing in control panel
// 0 = not visible or editable
// 1 = not visible, but can be edited
// 2 = visible and can be edited
$pwdincp=0;

// technical email address - any error messages will be emailed here
$technicalemail='YOUR_TECHNICAL_EMAIL';

// use persistant connections to the database
// 0 = don't use
// 1 = use
$usepconnect=1;

// Logout time in minutes
$session_logout = 480;

?>
