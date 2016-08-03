<?php
function post_db($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $x10, $x11, $x12, $x13, $x14, $x15, $x16, $x17, $x18, $x19, $x20, $x21, $x22, $x23, $x24, $x25, $x26, $x27, $x28) {

// Connect to and select database
require_once('/Sites/idsearch.cca.edu/documents/inventory/include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

// Post values to the database from form
$query = "INSERT INTO master (uname, fname, lname, dept, campus, bldg, floor, room, mach_type, platform, model, other_model, asset_tag, serial, service_tag, proc_speed, proc_type, ram, disk_size, optical_drive, mach_name, ip_addr, mac_addr, display_model, display_size, display_asset, display_serial, notes) VALUES ('$x1', '$x2', '$x3', '$x4', '$x5', '$x6', '$x7', '$x8', '$x9', '$x10', '$x11', '$x12', '$x13', '$x14', '$x15', '$x16', '$x17', '$x18', '$x19', '$x20', '$x21', '$x22', '$x23', '$x24', '$x25', '$x26', '$x27', '$x28')";

$result = mysql_query($query);
if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<h3>Entry added to CCA Database!</h3>");

//Close the connection
mysql_close($connect);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ETS Inventory</title>
<script type="text/javascript" language="javascript" charset="utf-8" src="/inventory/include/inventory2.js"></script>
<link rel="stylesheet" href="/inventory/include/inventory.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="netsys.php">Netsys Computing Inventory Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="netsys.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td>
</tr>
</table></center>
</div>
<div id="addstrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add a record</strong>
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

if ($uname != NULL || $fname != NULL || $lname != NULL || $dept != NULL || $campus != NULL || $bldg != NULL || $floor != NULL || $room != NULL || $mtype != NULL || $platform != NULL || $model != NULL || $othermodel != NULL || $asset != NULL || $serial != NULL || $service != NULL || $processor != NULL || $proc_type != NULL || $ram != NULL || $disk != NULL || $optical != NULL || $mname != NULL || $ip != NULL || $mac != NULL || $display_model != NULL || $display_size != NULL || $display_asset != NULL || $display_serial != NULL || $notes != NULL) {
post_db($uname, $fname, $lname, $dept, $campus, $bldg, $floor, $room, $mtype, $platform, $model, $othermodel, $asset, $serial, $service, $processor, $proc_type, $ram, $disk, $optical, $mname, $ip, $mac, $display_model, $display_size, $display_asset, $display_serial, $notes);
}
else {
echo ('<form name="inventory" action="'.$_SERVER["PHP_SELF"].'" method="POST">');
echo ('<table class="form"><tr><td valign=top><h4>User</h4></td><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>Network</h4></td><td valign=top><h4>Display</h4></td></tr>
<tr><td valign=top><strong>Username: </strong><br><input type="text" name="username" size="20" maxlength="50" /><br><br>
<strong>First Name: </strong><br><input type="text" name="firstname" size="20" maxlength="50" /><br><br>
<strong>Last Name: </strong><br><input type="text" name="lastname" size="20" maxlength="50" /><br><br>
<strong>Department:</strong><br>
	<select name="department">
	<option value="">Select department</option>
	<option value="Academic Affairs">Academic Affairs</option>
	<option value="Advancement">Advancement</option>
	<option value="Business Office">Business Office</option>
	<option value="Center for Art in Public LIfe">CAPL</option>
	<option value="Communications">Communications</option>
	<option value="Educational Technology Services">ETS</option>
	<option value="Enrollment Services">Enrollment Services</option>
	<option value="Extended Education">Extended Ed</option>
	<option value="Facilities">Facilities</option>
	<option value="Financial Aid">Financial Aid</option>
	<option value="First Year Office">First Year Office</option>
	<option value="Graduate Offices">Graduate Offices</option>
	<option value="Graduate Program Chairs">Graduate Program Chairs</option>
	<option value="Human Resources">Human Resources</option>
	<option value="Libraries">Libraries</option>
	<option value="Presidents Office">Presidents Office</option>
	<option value="Public Safety">Public Safety</option>
	<option value="Purchasing">Purchasing</option>
	<option value="Shipping & Receiving">Shipping & Receiving</option>
	<option value="Student Affairs">Student Affairs</option>
	<option value="Student Records">Student Records</option>
	<option value="Studio Managers">Studio Managers</option>
	<option value="Studio Program Chairs">Studio Program Chairs</option>
	<option value="Wattis">Wattis</option>
	</select></td>
<td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" value="OAK" onclick="bldgSelect(1)"/>Oakland<br>
	<input type="radio" name="campus" value="SF" onclick="bldgSelect(2)"/>San Francisco<br><br>
<strong>Building: </strong><br>
	<select name="bldg">
	<option value="">Select building (must first select a campus)</option></select><br><br>
<strong>Floor: </strong><br>
	<input type="text" name="floor" size="1" maxlength="50" /><br><br>
<strong>Room: </strong><br>
	<input type="text" name="room" size="10" maxlength="50" /></td>
<td valign=top><strong>Device Type: </strong><br>
<select name="mtype">
	<option value="">Select type</option>
	<option value="Computer">Computer</option>
	<option value="Monitor">Monitor</option>
	<option value="Scanner">Scanner</option>
	<option value="Undistributed Computer">Undistributed Computer</option>
	<option value="Undistributed Monitor">Undistributed Monitor</option>
	<option value="Undistributed Scanner">Undistributed Scanner</option>
	</select><br><br>
<strong>Platform: </strong><br>
	<input type="radio" name="platform" value="Mac" onclick="modelSelect(1)"/>Mac<br>
	<input type="radio" name="platform" value="PC" onclick="modelSelect(2)"/>PC<br>
	<input type="radio" name="platform" value="NULL" />Neither<br><br>
<strong>Computer Model: </strong><br>
	<select name="model">
	<option value="">Select model (must first select platform)</option></select><br><br>
<strong>Printer/Scanner Model: </strong><br>
	<select name="othermodel">
	<option value="">Select model</option>
	<option value="HP LJ1022N">HP LJ1022N</option>
	<option value="HP LJ1200">HP LJ1200</option>
	<option value="HP LJ1300N">HP LJ1300N</option>
	<option value="HP LJ2015DN">HP LJ2015DN</option>
	<option value="HP LJ2100TN">HP LJ2100TN</option>
	<option value="HP LJ2200DN">HP LJ2200DN</option>
	<option value="HP LJ2300DN">HP LJ2300DN</option>
	<option value="HP LJ2420DN">HP LJ2420DN</option>
	<option value="HP LJ2430DTN">HP LJ2430DTN</option>
	<option value="HP LJ3500">HP LJ3500</option>
	<option value="HP LJ4000">HP LJ4000</option>
	<option value="HP LJ4050N">HP LJ4050N</option>
	<option value="HP LJ4100N">HP LJ4100N</option>
	<option value="HP LJ4240N">HP LJ4240N</option>
	<option value="HP LJ4350DTN">HP LJ4350DTN</option>
	<option value="HP LJ5">HP LJ5</option>
	<option value="HP LJ6P">HP LJ6P</option>
	<option value="HP LJ8150DN">HP LJ8150DN</option>
	<option value="Other">Other</option>
	</select><br><br>
<strong>Asset Tag: </strong><br>
	<input type="text" name="asset" size="5" maxlength="50" /><br><br>
<strong>Serial Number: </strong><br>
	<input type="text" name="serial" size="20" maxlength="50" value="no value"/><br><br>
<strong>Service Tag: </strong><br>
	<input type="text" name="service" size="20" maxlength="50" /><br><br>
<strong>Processor Type: </strong><br>
	<select name="proc_type">
	<option value="">Select processor (must first select platform)</option></select><br><br>
<strong>Processor Speed: </strong><br>
	<select name="processor">
	<option value="">Select processor speed</option>
	<option value="0 - 1 GHz">0 - 1 GHz</option>
	<option value="1.01 - 1.5 GHz">1.01 - 1.5 GHz</option>
	<option value="1.51 - 2 GHz">1.51 - 2 GHz</option>
	<option value="2.01 - 2.5 GHz">2.01 - 2.5 GHz</option>
	<option value="2.51 - 3 GHz">2.51 - 3 GHz</option>
	<option value="3.01 - 3.5 GHz">3.01 - 3.5 GHz</option>
	</select><br><br>
<strong>RAM: </strong><br>
	<select name="ram">
	<option value="">Select RAM</option>
	<option value="256 MB">256 MB</option>
	<option value="512 MB">512 MB</option>
	<option value="1 GB">1 GB</option>
	<option value="1.5 GB">1.5 GB</option>
	<option value="2 GB">2 GB</option>
	<option value="3 GB">3 GB</option>
	<option value="4 GB">4 GB</option>
	</select><br><br>
<strong>Hard drive: </strong><br>
	<select name="hdd">
	<option value="">Select disk size</option>
	<option value="0 - 40 GB">0 - 40 GB</option>
	<option value="41 - 80 GB">41 - 80 GB</option>
	<option value="81 - 120 GB">81 - 120 GB</option>
	<option value="121 - 160 GB">121 - 160 GB</option>
	<option value="161 - 200 GB">161 - 200 GB</option>
	<option value="201 - 240 GB">201 - 240 GB</option>
	<option value="241 - 280 GB">241 - 280 GB</option>
	<option value="281 - 320 GB">281 - 320 GB</option>
	</select><br><br>
<strong>Optical Drive: </strong><br>
	<select name="optical">
	<option value="">Select functionality</option>
	<option value="Reads CDs">Reads CDs</option>
	<option value="Reads CDs/Reads DVDs">Reads CDs/Reads DVDs</option>
	<option value="Burns CDs">Burns CDs</option>
	<option value="Burns CDs/Reads DVDs">Burns CDs/Reads DVDs</option>
	<option value="Burns CDs/Burns DVDs">Burns CDs/Burns DVDs</option>
	</select></td>
<td valign=top><strong>Machine Name: </strong><br><input type="text" name="mname" size="22" maxlength="50" /><br><br>
<strong>IP Address: </strong><br><input type="text" name="ip" size="20" maxlength="50" /><br><br>
<strong>MAC Address: </strong><br><input type="text" name="mac" size="20" maxlength="50" /></td>
<td valign=top><strong>Display Model: </strong><br><input type="text" name="display_model" size="20" maxlength="50" value="" /><br><br>
<strong>Display Size: </strong><br><input type="text" name="display_size" size="10" maxlength="50" /><br><br>
<strong>Display Asset: </strong><br><input type="text" name="display_asset" size="20" maxlength="50" /><br><br>
<strong>Display Serial: </strong><br><input type="text" name="display_serial" size="20" maxlength="50" /><br><br>
<h4>Notes</h4>
<textarea class="messagebox" rows="7" cols="35" style="font-size:11px" name="notes"></textarea></td></tr></table><br>
<hr><input type="submit" value="Post to Database" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
}
?>
</div>
</body>
</html>