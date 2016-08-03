<?php

function post_db($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $x10, $x11, $x12, $x13, $x14, $x15) {

// Connect to and select database
require_once('include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

// Post values to the database from form
$query = "INSERT INTO master (ip_addr, ip_type, ip_avail, device_type, model, serial, asset, campus, bldg, location, dns, mac_addr, dept, uname, notes) VALUES ('$x1', '$x2', '$x3', '$x4', '$x5', '$x6', '$x7', '$x8', '$x9', '$x10', '$x11', '$x12', '$x13', '$x14', '$x15')";

$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<h3>Entry added to CCA IP Database!</h3>");

//Close the connection
mysql_close($connect);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Add entry to CCA Printer/Network Database</title>
<meta http-equiv="content-type" content="text/html; charset="utf-8" />
<meta name="description" content="" />
<script src="include/ip.js" type="text/javascript"></script>
<link rel="stylesheet" href="include/ip.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr><td id="logo"><a href="index.php">CCA Printer/Network Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td></tr>
</table></center>
</div>
<div id="addstrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add a record</strong>
</div>

<div id="content">
<?php
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

if ($ip != NULL || $ip_type != NULL || $avail != NULL || $device_type != NULL || $model != NULL || $serial != NULL || $asset != NULL || $campus != NULL || $bldg != NULL || $location != NULL || $dns != NULL || $mac != NULL || $dept != NULL || $uname != NULL || $notes != NULL) {
post_db($ip, $ip_type, $avail, $device_type, $model, $serial, $asset, $campus, $bldg, $location, $dns, $mac, $dept, $uname, $notes);
}
else {
echo ('<form name="iptrack" action="'.$_SERVER["PHP_SELF"].'" method="POST">');
echo ('<table class="form"><tr><td valign=top><h4>Network</h4></td><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>User</h4></td><td valign=top><h4>Notes</h4></td></tr>
<tr><td valign=top><strong>IP Address:</strong><br>
<input type="text" name="ipaddr" size="20" /><br><br>
<strong>IP Type:</strong><br>
<input type="radio" name="ip_type" id="ipstatic" value="Static">Static  <input type="radio" name="ip_type" id="ipdynamic" value="Dynamic">Dynamic<br><br>
<strong>IP Availability:</strong><br>
<input type="radio" name="avail" id="ipused" value="Used">Used  <input type="radio" name="avail" id="ipavail" value="Available">Available<br><br>
<strong>DNS Name:</strong><br>
<input type="text" name="dns" size="20" /><br><br>
<strong>Mac Address:</strong><br>
<input type="text" name="mac" size="20" />
</td>
<td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" value="Oakland" id="oakradio" onclick="bldgSelect(1)"/>Oakland<br>
	<input type="radio" name="campus" value="San Francisco" id="sfradio" onclick="bldgSelect(2)"/>San Francisco<br>
	<input type="radio" name="campus" value="%" onclick="bldgSelect(3)"/>Both<br><br>
<strong>Building: </strong><br>
	<select name="bldg">
	<option value="">Select building (must first select a campus)</option></select><br><br>
<strong>Department:</strong><br>
	<select name="department">
	<option value="">Select department</option>
	<option value="Academic Affairs">Academic Affairs</option>
	<option value="Advancement">Advancement</option>
	<option value="Business Office">Business Office</option>
	<option value="Center for Art in Public LIfe">CAPL</option>
	<option value="Communications">Communications</option>
	<option value="Academic Computing">ETS - Academic Computing</option>
	<option value="Administrative Computing">ETS - Administrative Computing</option>
	<option value="Media Center">ETS - Media Center</option>
	<option value="Networks and Systems">ETS - Networks and Systems</option>
	<option value="Enrollment Services">Enrollment Services</option>
	<option value="Extended Education">Extended Ed</option>
	<option value="Facilities">Facilities</option>
	<option value="Financial Aid">Financial Aid</option>
	<option value="First Year Office">First Year Office</option>
	<option value="Graduate Offices">Graduate Offices</option>
	<option value="Graduate Program Chairs">Graduate Program Chairs</option>
	<option value="Human Resources">Human Resources</option>
	<option value="Libraries">Libraries</option>
	<option value="Presidents Office">President\'s Office</option>
	<option value="Public Safety">Public Safety</option>
	<option value="Purchasing">Purchasing</option>
	<option value="Shipping & Receiving">Shipping & Receiving</option>
	<option value="Student Affairs">Student Affairs</option>
	<option value="Student Records">Student Records</option>
	<option value="Studio Managers">Studio Managers</option>
	<option value="Studio Program Chairs">Studio Program Chairs</option>
	<option value="Wattis">Wattis</option>
	</select><br><br>
	<strong>Location:</strong><br>
	<input type="text" name="location" size="20" />
	</td>
<td valign=top><strong>Device Type:</strong><br>
	<select name="device_type">
	<option value="">Select type</option>
	<option value="Computer">Computer</option>
	<option value="Firewall">Firewall</option>
	<option value="Networking Device">Networking Device</option>
	<option value="Packeteer">Packeteer</option>
	<option value="Printer">Printer</option>
	<option value="Router">Router</option>
	<option value="Server">Server</option>
	<option value="Switch">Switch</option>
	<option value="Wireless Access Point">Wireless Access Point</option>
	</select><br><br>
	<strong>Model Number:</strong><br>
	<input type="text" name="model" size="20" /><br><br>
	<strong>Serial Number:</strong><br>
	<input type="text" name="serial" size="20" /><br><br>
	<strong>Asset Tag:</strong><br>
	<input type="text" name="asset" size="20" />
	</td>
<td valign=top><strong>Username: </strong><br>
<input type="text" name="username" size="20" maxlength="50" /></td>
<td valign=top><strong>Notes:</strong><br>
<textarea class="messagebox" rows="10" cols="35" style="font-size:12px" name="notes"></textarea>
</td></tr></table><br><hr>
<input type="submit" value="Add to Database" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
}
?>
</div>
</body>
</html>
