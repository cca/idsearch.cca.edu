<?php
function query_db($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8) {

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

switch ($x8)
{
case 0:
	$query = "SELECT * FROM network WHERE campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND ip_addr like '%$x6%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 1:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '10.4%' AND '10.5%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 2:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '209.40.82%' AND '209.40.84%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 3:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '209.40.84%' AND '209.40.86%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 4:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '209.40.86%' AND '209.40.88%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 5:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '209.40.92%' AND '209.40.94%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
case 6:
	$query = "SELECT * FROM network WHERE ip_addr BETWEEN '209.40.94%' AND '209.40.95%' AND campus like '%$x1%' AND bldg like '%$x2%' AND device like '%$x3%' AND asset_tag like '%$x4%' AND mach_name like '%$x5%' AND mac_addr like '%$x7%'";
	$result = mysql_query($query);
	break;
}

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo('<h2>Search results</h2>&bull;&nbsp;To edit record - click serial number<br><br><table cellpadding="0px" cellspacing="0" class="sortable" id="inventorytable">
<tr>
<th id ="results"><a href="#"><b>#</b></a></th>
<th id="results"><a href="#"><b>IP Address</b></a></th>
<th id ="results"><a href="#"><b>Device Type</b></a></th>
<th id ="results"><a href="#"><b>Model</b></a></th>
<th id ="results"><a href="#"><b>Serial</b></a></th>
<th id ="results"><a href="#"><b>Asset Tag</b></a></th>
<th id ="results"><a href="#"><b>Campus</b></a></th>
<th id ="results"><a href="#"><b>Building</b></a></th>
<th id ="results"><a href="#"><b>Location</b></a></th>
<th id ="results"><a href="#"><b>Machine Name</b></a></th>
<th id ="results"><a href="#"><b>Mac Address</b></a></th>
<th id ="results"><a href="#"><b>Notes</b></a></th>
<th id ="results"><a href="#"><b>Last Updated</b></a></th>
</tr>');

// Display database results
$rowclass = 1;
$i = 1;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$record_id = $row["record_id"];
$ip = $row["ip_addr"];
$device = $row["device"];
$model = $row["model"];
$serial = $row["serial"];
$asset = $row["asset"];
$campus = $row["campus"];
$bldg = $row["bldg"];
$location = $row["location"];
$mname = $row["mach_name"];
$mac = $row["mac_addr"];
$notes = $row["notes"];
$last = $row["last_updated"];

if ($rowclass == 0){
	echo ('<tr class="row0">');
}
else{
	echo ('<tr class="row1">');
}
echo ('<td id="results">' . $i .'</td>');

if ($ip != NULL){
echo ('<td id="results">' . $ip .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($device != NULL){
echo ('<td id="results">' . $device .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($model != NULL){
echo ('<td id="results">' . $model .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

echo ('<td id="results"><a href="update2.php?record_id=' . $record_id . '">' . $serial . '</a></td>');

if ($asset != NULL){
echo ('<td id="results">' . $asset .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($campus != NULL){
echo ('<td id="results">' . $campus .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($bldg != NULL){
echo ('<td id="results">' . $bldg .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($location != NULL){
echo ('<td id="results">' . $location .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($mname != NULL){
echo ('<td id="results">' . $mname .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($mac != NULL){
echo ('<td id="results">' . $mac .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($notes != NULL){
echo ('<td id="results">' . $notes .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

echo ('<td id="results">' . $last_updated . '</td>');
echo ('</tr>');
$rowclass = (1 - $rowclass);
$i+=1;
}

echo ('</table>');

// Close the connection
mysql_close($connect);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ETS Inventory</title>
<script type="text/javascript" language="javascript" charset="utf-8" src="include/inventory.js"></script>
<script src="include/sorttable.js" type="text/javascript"></script>
<link rel="stylesheet" href="include/inventory.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="index.php">Network Inventory Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td>
</tr>
</table></center>
</div>

<div id="content">
<?php
$campus = $_GET["campus"];
$bldg = $_GET["bldg"];
$device = $_GET["device"];
$asset = $_GET["asset"];
$mname = $_GET["mname"];
$mac = $_GET["mac"];
$ip = $_GET["ip"];
$range = $_GET["range"];

if ($campus != NULL || $bldg != NULL || $device != NULL || $asset != NULL || $mname != NULL || $mac != NULL || $ip != NULL || $range != NULL) {
query_db($campus, $bldg, $device, $asset, $mname, $mac, $ip, $range);
}
else {
echo ('<form name="inventory" action="'.$_SERVER["PHP_SELF"].'" method="GET">');
echo ("<h3>Search by any combination:</h3>(Results best viewed using Firefox)<br><hr>");
echo ('<table class="form"><tr><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>Network</h4></td></tr>
<tr><td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" value="OAK" onclick="bldgSelect(1)"/>Oakland<br>
	<input type="radio" name="campus" value="SF" onclick="bldgSelect(2)"/>San Francisco<br>
	<input type="radio" name="campus" value="%" onclick="bldgSelect(3)"/>Both<br><br>
<strong>Building: </strong><br>
	<select name="bldg">
	<option value="">Select building (must first select a campus)</option></select></td>
<td valign=top><strong>Device Type: </strong><br>
<select name="device">
	<option value="">Select type</option>
	<option value="Firewall">Firewall</option>
	<option value="Networking Device">Networking Device</option>
	<option value="Packeteer">Packeteer</option>
	<option value="Router">Router</option>
	<option value="Server">Server</option>
	<option value="Switch">Switch</option>
	<option value="Wireless Access Point">Wireless Access Point</option>
	</select><br><br>
<strong>Asset Tag: </strong><br>
<input type="text" name="asset" size="5" maxlength="10" /></td>
<td valign=top><strong>Machine Name: </strong><br><input type="text" name="mname" size="22" maxlength="50" /><br><br>
<strong>MAC Address: </strong><br><input type="text" name="mac" size="20" maxlength="50" /><br><br>
<strong>IP Address: </strong><br><input type="text" name="ip" size="20" maxlength="50" /><br><br>
<strong>IP Range: </strong><br>
<select name="range">
	<option value="0">Select range</option>
	<option value="1">10.4.x.x (Oak Administrative Network)</option>
	<option value="2">209.40.82.1 - 209.40.83.255 (Oak Wireless Network)</option>
	<option value="3">209.40.84.1 - 209.40.85.255 (SF Academic Network)</option>
	<option value="4">209.40.86.1 - 209.40.87.255 (Oak Academic Network)</option>
	<option value="5">209.40.92.1 - 209.40.93.255 (SF Wireless Network)</option>
	<option value="6">209.40.94.1 - 209.40.94.255 (SF Administrative Network)</option>
</select><td></tr></table><br><hr>
<input type="submit" value="Search Database" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
}
?>
</div>
</body>
</html>
