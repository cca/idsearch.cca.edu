<?php
function query_db($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $x10) {

// Connect to and select database
require_once('/Sites/idsearch.cca.edu/documents/ipcopy/include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}


switch ($x3)
{
case 0:
	$query = "SELECT * FROM master_copy WHERE ip_addr like '%$x1%' AND ip_addr like '%$x2%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 1:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '10.4%' AND '10.5%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 2:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '209.40.82%' AND '209.40.84%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 3:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '209.40.84%' AND '209.40.86%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 4:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '209.40.86%' AND '209.40.88%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 5:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '209.40.92%' AND '209.40.94%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
case 6:
	$query = "SELECT * FROM master_copy WHERE ip_addr BETWEEN '209.40.94%' AND '209.40.95%' AND ip_type like '%$x4%' AND ip_avail like '%$x5%' AND device_type like '%$x6%' AND campus like '%$x7%' AND bldg like '%$x8%' AND dept like '%$x9%' AND uname like '%$x10%'";
	$result = mysql_query($query);
	break;
}

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo('<h2>Search results</h2>&bull;&nbsp;To edit record - click IP address<br><br><table cellpadding="0px" cellspacing="0" class="sortable" id="iptable">
<tr>
<th id ="results"><a href="#"><b>#</b></a></th>
<th id="results"><a href="#"><b>IP Address</b></a></th>
<th id ="results"><a href="#"><b>Type</b></a></th>
<th id ="results"><a href="#"><b>Availability</b></a></th>
<th id ="results"><a href="#"><b>Device Type</b></a></th>
<th id ="results"><a href="#"><b>Model</b></a></th>
<th id ="results"><a href="#"><b>Serial</b></a></th>
<th id ="results"><a href="#"><b>Asset Tag</b></a></th>
<th id ="results"><a href="#"><b>Campus</b></a></th>
<th id ="results"><a href="#"><b>Building</b></a></th>
<th id ="results"><a href="#"><b>Location</b></a></th>
<th id ="results"><a href="#"><b>DNS Name</b></a></th>
<th id ="results"><a href="#"><b>Mac Address</b></a></th>
<th id ="results"><a href="#"><b>Department</b></a></th>
<th id ="results"><a href="#"><b>Username</b></a></th>
<th id ="results"><a href="#"><b>Notes</b></a></th>
<th id ="results"><a href="#"><b>Last Updated</b></a></th>
</tr>');

// Display database results
$rowclass = 1;
$i = 1;
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
//$dns = $row["dns"];
$dns = gethostbyaddr($ip);
$mac = $row["mac_addr"];
$dept = $row["dept"];
$uname = $row["uname"];
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
echo ('<td id="results"><a href="update2.php?ip_id=' . $id . '">' . $ip . '</a></td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($ip_type != NULL){
echo ('<td id="results">' . $ip_type .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($ip_avail != NULL){
echo ('<td id="results">' . $ip_avail .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($device_type != NULL){
echo ('<td id="results">' . $device_type .'</td>');
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

if ($serial != NULL){
echo ('<td id="results">' . $serial .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

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

if ($dns != NULL){
echo ('<td id="results">' . $dns .'</td>');
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

if ($dept != NULL){
echo ('<td id="results">' . $dept .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($uname != NULL){
echo ('<td id="results">' . $uname .'</td>');
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

if ($last != NULL){
echo ('<td id="results">' . $last .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

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
<title>CCA Printer/Network Database</title>
<meta http-equiv="content-type" content="text/html; charset="utf-8" />
<meta name="description" content="" />
<link rel="stylesheet" href="/ipcopy/include/ip.css" />
<script src="/ipcopy/include/ip.js" type="text/javascript"></script>

<script type='text/javascript' src='common.js'></script>
<script type='text/javascript' src='css.js'></script>
<script type='text/javascript' src='standardista-table-sorting.js'></script>
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="index.php">CCA Printer/Network Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td>
</tr>
</table></center>
</div>

<div id="content">
<?php
$ipaddr = $_GET["ipaddr"];
$subnet = $_GET["subnet"];
$range = $_GET["range"];
$ip_type = $_GET["ip_type"];
$avail = $_GET["avail"];
$device_type = $_GET["device_type"];
$campus = $_GET["campus"];
$bldg = $_GET["bldg"];
$dept = $_GET["department"];
$uname = $_GET["username"];

if ($ipaddr != NULL || $subnet != NULL || $range != NULL || $ip_type != NULL || $avail != NULL || $device_type != NULL || $campus != NULL || $bldg != NULL || $dept != NULL || $uname) {
query_db($ipaddr, $subnet, $range, $ip_type, $avail, $device_type, $campus, $bldg, $dept, $uname);
}
else {
echo ('<form name="iptrack" action="'.$_SERVER["PHP_SELF"].'" method="GET">');
echo ("<h3>Search by any combination:</h3><hr>");
echo ('<table class="form"><tr><td valign=top><h4>Network</h4></td><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>User</h4></td></tr>
<tr><td valign=top><strong>IP Address:</strong><br>
<input type="text" name="ipaddr" id="ip_addr" size="20" /><br>
<font color="red">OR</font><br>
<strong>Subnet:</strong><br>
<select name="subnet">
	<option value="">Select subnet</option>
	<option value="10.4.21.">Private 21 subnet</option>
	<option value="10.4.42.">Private 42 subnet</option>
	<option value="209.40.80.">80 subnet</option>
	<option value="209.40.81.">81 subnet</option>
	<option value="209.40.83.">83 subnet</option>
	<option value="209.40.84.">84 subnet</option>
	<option value="209.40.85.">85 subnet</option>
	<option value="209.40.86.">86 subnet</option>
	<option value="209.40.87.">87 subnet</option>
	<option value="209.40.90.">90 subnet</option>
	<option value="209.40.92.">92 subnet</option>
	<option value="209.40.93.">93 subnet</option>
	<option value="209.40.94.">94 subnet</option>
	<option value="209.40.95.">95 subnet</option>
</select><br>
<font color="red">OR</font><br>
<strong>Range:</strong><br>
<select name="range">
	<option value="0">Select range</option>
	<option value="1">10.4.x.x (Oak Administrative Network)</option>
	<option value="2">209.40.82.1 - 209.40.83.255 (Oak Wireless Network)</option>
	<option value="3">209.40.84.1 - 209.40.85.255 (SF Academic Network)</option>
	<option value="4">209.40.86.1 - 209.40.87.255 (Oak Academic Network)</option>
	<option value="5">209.40.92.1 - 209.40.93.255 (SF Wireless Network)</option>
	<option value="6">209.40.94.1 - 209.40.94.255 (SF Administrative Network)</option>
</select><br><br>
<strong>IP Type:</strong><br>
<input type="radio" name="ip_type" value="Static">Static  <input type="radio" name="ip_type" value="Dynamic">Dynamic<br><br>
<strong>IP Availability:</strong><br>
<input type="radio" name="avail" value="Used">Used  <input type="radio" name="avail" value="Available">Available</td>
<td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" value="Oakland" onclick="bldgSelect(1)"/>Oakland<br>
	<input type="radio" name="campus" value="San Francisco" onclick="bldgSelect(2)"/>San Francisco<br>
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
	</select></td>
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
	</select></td>
<td valign=top><strong>Username: </strong><br>
<input type="text" name="username" size="20" maxlength="50" /></td></tr>
</table><br><hr>
<input type="submit" value="Search Database" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
}
?>
</div>
</body>
</html>