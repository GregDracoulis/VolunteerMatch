<?php
function getsettings()
{
	global $DB_site;
	$settings = $DB_site->query("SELECT varname,value FROM settings ORDER BY settingid ASC");
	if (!$settings) {Error("Cannot grab settings from database");}
	while($array = $DB_site->fetch_array($settings))
	{
		global $$array[0];
		$$array[0] = $array[1];
	}	
}
?>