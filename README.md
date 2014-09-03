# Volunteer Match
## Installing and Upgrading

> ####Warning: This file needs updating is **NOT CURRENT**

>If you are upgrading a previous install, remember to **backup** your installation before proceeding. The values in your configuration files can be inspected and re-used for the new install.
>Remember to **backup** any databases you are upgrading as well.

The database structure is new for 20101102 install.  The following tables may be imported from a previous install:
 - teachers
 - volunteers
 - schools
 - categories
New database tables
 - companies
 - news_subs
 - states
Modified database tables
 - tars

If the data is to be preserved from the previous install, install the _SQL/vol_install.sql SQL file into MySQL, then import over
the necessary tables from the previous install, for example:  teachers, volunteers, schools, categories.  This is a manual step
and not part of any automated install script.


INSTALL NOTES:


#####Unzip the SVE_Match.20101102.zip file

#####Create a MySQL database on your hosting provider, take note of the following:

 - MySQL server name
 - MySQL database name
 - MySQL login 
 - MySQL password

Import the file: _SQL/vol_install.sql  into the newly created database.

####With a text editor, such as notepad, edit `myadmin/config.php` and set the following values, collected from step 2:

	// hostname or ip of server
	$servername='YOUR_DB_HOSTNAME';

	// username and password to log onto db server
	$dbusername='YOUR_DB_USER';
	$dbpassword='YOUR_DB_PASS';

	// name of database
	$dbname='YOUR_DB_NAME';


####With a text editor, edit `files.php` and update the following values, according to your hosting provider setup:
>DO NOT include a trailing slash on these variables.

	$website_dir = "http://YOUR_HOSTNAME/YOUR_DIRECTORY";
	$website_base_dir = "PATH/TO/DIRECTORY";
	$website_template_path = "PATH/TO/DIRECTORY/tmpls";
	$bad_word_filter_file = "PATH/TO/badwordfilter.txt";
 

####With a text editor, edit `myadmin/admin_files.php` and update the following values according to your hosting >DO NOT include a trailing slash on these variables.
	
	$website_admin_dir = "http://YOUR_HOSTNAME/YOUR_ADMIN_DIRECTORY";
	$website_dir = "PATH/TO/DIRECTORY";
	$website_base_dir = "PATH/TO/DIRECTORY";
	$website_template_path = "PATH/TO/DIRECTORY/tmpls";

Step 6) With a hosting control panel, or FTP, change the permissions on the following files to `666`

type	|filename
--------|--------------
file	|`myadmin/badwordfilter.txt`
file	|`myadmin/badwordfilter.inc`
dir		|`tmpls/`
files	|`tmpls/*`

>Request assistance from your hosting provider on how to do this, as the seutp can vary. Many FTP clients have this ability, simply right click on the file, and change permission by issuing the command:

	chmod 666 <filename>

####Edit newsletter/config.php with the correct database configuration and credentials.

####Upload the files, via FTP to the site.

####Access the admin page via the web:

	http://domain.com/directory/myadmin/

	login: superadmin , pass: apples5
	login: admin	  , pass: grape4

####Debugging:
For debugging issues, if the site does not work, check the error logs with your PHP log, or hosting provider logs, for clues.
Verify the values in `files.php` and `myadmin/admin_files.php`
Double check the file permissions

####Success!