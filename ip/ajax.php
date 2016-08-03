<?php
require_once('include/db_login.php');

$connect = mysql_connect($db_host, $db_username, $db_password);
if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}
$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

// Retrieve data from query string
$ip = $_GET['ip_addr'];

// Escape user input to help prevent SQL injection
$ip = mysql_real_escape_string($ip);

// Build query
$query = "SELECT * FROM master WHERE ip_addr = '$ip'";
	
//Execute query
$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$id = $row["ip_id"];
$ip = $row["ip_addr"];
$ip_type = $row["ip_type"];
$ip_avail = $row["ip_avail"];
$device_type = $row["device_type"];
$model = $row["model"];
$serial = $row["serial"];
$asset = $row["asset"];
$campus = $row["campus"];
$bldg = $row["bldg"];
$location = $row["location"];
$dns = $row["dns"];
$mac = $row["mac_addr"];
$dept = $row["dept"];
$uname = $row["uname"];
$notes = $row["notes"];
}

echo ($ip . ',' . $ip_type . ',' . $ip_avail . ',' . $device_type . ',' . $model . ',' . $serial . ',' . $asset . ',' . $campus . ',' . $bldg . ',' . $location . ',' . $dns . ',' . $mac . ',' . $dept . ',' . $uname . ',' . $notes);

// Close the connection
mysql_close($connect);

?>
