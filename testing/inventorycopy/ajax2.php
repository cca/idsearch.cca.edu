<?php
require_once('/Sites/idsearch.cca.edu/documents/inventorycopy/include/db_login.php');

$connect = mysql_connect($db_host, $db_username, $db_password);
if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}
$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

// Retrieve data from query string
$userid = $_GET['user_id'];

// Build query
$query = "SELECT * FROM master_copy WHERE user_id = '$userid'";
	
//Execute query
$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$userid = $row["user_id"];
$username = $row["uname"];
$firstname = $row["fname"];
$lastname = $row["lname"];
$department = $row["dept"];
$campus = $row["campus"];
$building = $row["bldg"];
$floor = $row["floor"];
$room = $row["room"];
$mtype = $row["mach_type"];
$platform = $row["platform"];
$model = $row["model"];
$othermodel = $row["other_model"];
$asset = $row["asset_tag"];
$serial = $row["serial"];
$service = $row["service_tag"];
$proc_speed = $row["proc_speed"];
$proc_type = $row["proc_type"];
$ram = $row["ram"];
$disk = $row["disk_size"];
$optical = $row["optical_drive"];
$mname = $row["mach_name"];
$ip = $row["ip_addr"];
$mac = $row["mac_addr"];
$disp_model = $row["display_model"];
$disp_size = $row["display_size"];
$disp_asset = $row["display_asset"];
$disp_serial = $row["display_serial"];
$notes = $row["notes"];
}

echo ($username . ',' . $firstname . ',' . $lastname . ',' . $department . ',' . $campus . ',' . $building . ',' . $floor . ',' . $room . ',' . $mtype . ',' . $platform . ',' . $model . ',' . $othermodel . ',' . $asset . ',' . $serial . ',' . $service . ',' . $proc_speed . ',' . $proc_type . ',' . $ram . ',' . $disk . ',' . $optical . ',' . $mname . ',' . $ip . ',' . $mac . ',' . $disp_model . ',' . $disp_size . ',' . $disp_asset . ',' . $disp_serial . ',' . $notes);

// Close the connection
mysql_close($connect);

?>