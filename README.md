# Volunteer Match
## Installing and Upgrading

> ###Warning: This file is not up to date as of 05/2013

UPDATE 2010-11-02:

NOTE: IF YOU ARE UPGRADING A PREVIOUS INSTALL, PLEASE DOWNLOAD ALL OF THE PREVIOUS FILES TO A BACKUP ON YOUR PC.
      THE VALUES IN THE CONFIG FILES CAN THEN BE INSPECTED, AND RE-USED FOR THE NEW INSTALL.

NOTE2:  TAKE A FULL BACKUP OF YOUR PREVIOUS MYSQL DATABASE.  IF POSSIBLE, RENAME IT ON THE SERVER TO _OLD, TO CONTINUE TO USE DATA.

a) The database structure is new for 20101102 install.  The following tables may be imported from a previous install:
 - teachers, volunteers, schools, categories
b) New database tables: companies, news_subs, states
c) Modified database tables: tars

If the data is to be preserved from the previous install, install the _SQL/vol_install.sql SQL file into MySQL, then import over
the necessary tables from the previous install, for example:  teachers, volunteers, schools, categories.  This is a manual step
and not part of any automated install script.


INSTALL NOTES:


Step 1) Unzip the SVE_Match.20101102.zip file

Step 2) Create a MySQL database on your hosting provider, take note of the following:

	MySQL server name
	MySQL database name
 	MySQL login 
	MySQL password

	Import the file: _SQL/vol_install.sql  into the newly created database.

Step 3) With a text editor, such as notepad, edit the files:
	myadmin/config.php and set the following values, collected from step 2:

	// hostname or ip of server
	$servername='dbhost';

	// username and password to log onto db server
	$dbusername='mrjtvol';
	$dbpassword='mrjtvol';

	// name of database
	$dbname='mrjtvol';


Step 4) With a text editor, edit the file: files.php, and update the following values, according to your hosting provider setup:

	* Do not include a trailing slash on these variables.

	$website_dir = "http://www.mrjaytee.com/vol";
	$website_base_dir = "/data/wwwroot/mrjaytee.com/www/vol";
	$website_template_path = "/data/wwwroot/mrjaytee.com/www/vol/tmpls";
	$bad_word_filter_file = "/data/wwwroot/mrjaytee.com/www/vol/myadmin/badwordfilter.txt";
 

Step 5) With a text editor, edit the file: myadmin/admin_files.php, and update the following values according to your hosting provider setup:

	* Do not include a trailing slash on these variables.
	
	$website_admin_dir = "http://www.mrjaytee.com/vol/myadmin";
	$website_dir = "http://www.mrjaytee.com/vol";
	$website_base_dir = "/data/wwwroot/mrjaytee.com/www/vol";
	$website_template_path = "/data/wwwroot/mrjaytee.com/www/vol/tmpls";

Step 6) With a hosting control panel, or FTP, change the permissions on the following files to 666:

	file:  myadmin/badwordfilter.txt
	file:  myadmin/badwordfilter.inc
	dir:   tmpls/
	files: tmpls/*

	Request assistance from your hosting provider, on how to do this, as the seutp can vary.  Many
	Windows FTP clients have this ability, simply right click on the file, and change permission by issuing the command:

	chmod 666 <filename>

Step 7) Edit newsletter/config.php with the correct database configuration and credentials.

Step 7) Upload the files, via FTP to the site.

Step 8) Access the admin page via the web:

	http://domain.com/directory/myadmin/

	login: superadmin , pass: apples5
	login: admin	  , pass: grape4

DEBUG:

 - For debugging issues, if the site does not work, check the error logs with your PHP log, or hosting provider logs, for clues.
 - Ensure the values in files.php and myadmin/admin_files.php
 - Double check the file permissions for the steps in step 6

Success!
