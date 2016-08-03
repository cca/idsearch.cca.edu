<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Update CCA ETS Inventory</title>
<link rel="stylesheet" href="/inventorycopy/include/inventory.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="netsys.php">CCA ETS Inventory Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="netsys.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a></td>
</tr>
</table></center>
</div>
<div id="content">

<?php
$uname = $_POST["username"];
$fname = $_POST["firstname"];
$lname = $_POST["lastname"];
$dept = $_POST["department"];
$campus = $_POST["campus"];
$bldg = $_POST["bldg"];
$floor = $_POST["floor"];
$room = $_POST["room"];
$mtype = $_POST["mtype"];
$platform = $_POST["platform"];
$model = $_POST["model"];
$othermodel = $_POST["othermodel"];
$asset = $_POST["asset"];
$serial = $_POST["serial"];
$service = $_POST["service"];
$proc_type = $_POST["proc_type"];
$processor = $_POST["processor"];
$ram = $_POST["ram"];
$disk = $_POST["hdd"];
$optical = $_POST["optical"];
$mname = $_POST["mname"];
$ip = $_POST["ip"];
$mac = $_POST["mac"];
$display_model = $_POST["display_model"];
$display_size = $_POST["display_size"];
$display_asset = $_POST["display_asset"];
$display_serial = $_POST["display_serial"];
$notes = $_POST["notes"];

// Connect to and select database
require_once('/Sites/idsearch.cca.edu/documents/inventorycopy/include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

$query = "UPDATE master_copy SET uname='$uname', fname='$fname', lname='$lname', dept='$dept', campus='$campus', bldg='$bldg', floor='$floor', room='$room', mach_type='$mtype', platform='$platform', model='$model', other_model='$othermodel', asset_tag='$asset', serial='$serial', service_tag='$service', proc_speed='$processor', proc_type='$proc_type', ram='$ram', disk_size='$disk', optical_drive='$optical', mach_name='$mname', ip_addr='$ip', mac_addr='$mac', display_model='$display_model', display_size='$display_size', display_asset='$display_asset', display_serial='$display_serial', notes='$notes' WHERE uname='$uname'";

$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<h3>Entry updated in the CCA Inventory Database!</h3>");

//Close the connection
mysql_close($connect);

?>
</div>
</body>
</html>