<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Update CCA Printer/Network Database</title>
<link rel="stylesheet" href="/ipcopy/include/ip.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr><td id="logo"><a href="index.php">CCA Printer/Network Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td></tr>
</table></center>
</div>
<div id="content">

<?php
$id = $_POST["ip_id"];
$ip = $_POST["ipaddr"];
$ip_type = $_POST["ip_type"];
$avail = $_POST["avail"];
$device_type = $_POST["device_type"];
$model = $_POST["model"];
$serial = $_POST["serial"];
$asset = $_POST["asset"];
$campus = $_POST["campus"];
$bldg = $_POST["bldg"];
$location = $_POST["location"];
$dns = $_POST["dns"];
$mac = $_POST["mac"];
$dept = $_POST["department"];
$uname = $_POST["username"];
$notes = $_POST["notes"];

// Connect to and select database
require_once('/Sites/idsearch.cca.edu/documents/ip/include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

$query = "UPDATE master_copy SET ip_addr='$ip', ip_type='$ip_type', ip_avail='$avail', device_type='$device_type', model='$model', serial='$serial', asset='$asset', campus='$campus', bldg='$bldg', location='$location', dns='$dns', mac_addr='$mac', dept='$dept', uname='$uname', notes='$notes' WHERE ip_addr='$ip'";

$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<h3>Entry updated in the CCA Printer/Network Database!</h3>");

//Close the connection
mysql_close($connect);

?>
</div>
</body>
</html>