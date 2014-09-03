<?php

include "global.php"; // Get Configuration
getsettings();

$result = $DB_site->query("UPDATE tars,teachers SET tars.teacher_fname=teachers.fname, tars.teacher_lname=teachers.lname  WHERE tars.teacher_id=teachers.id;");

echo "Done";

?>
