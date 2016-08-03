<?php
function query_db($x1, $x2, $x3, $x4, $x5, $x6, $x7, $x8, $x9, $x10, $x11, $x12) {

// Connect to and select database
require ('/Sites/idsearch.cca.edu/documents/inventory/include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

$query = "SELECT * FROM acadcomp WHERE campus like '%$x1%' AND bldg like '%$x2%' AND mach_type like '%$x3%' AND platform like '%$x4%' AND model like '%$x5%' AND other_model like '%$x6%' AND asset_tag like '%$x7%' AND proc_type like '%$x8%' AND proc_speed like '%$x9%' AND ram like '%$x10%' AND disk_size like '%$x11%' AND optical_drive like '%$x12%'";

$result = mysql_query($query);

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo('<h2>Search results</h2>&bull;&nbsp;For detailed information - click username<br>&bull;&nbsp;To edit record - click serial number<br><br><table cellpadding="0px" cellspacing="0" class="sortable" id="inventorytable">
<tr>
<th id ="results"><a href="#"><b>#</b></a></th>
<th id ="results"><a href="#"><b>Campus</b></a></th>
<th id ="results"><a href="#"><b>Building</b></a></th>
<th id ="results"><a href="#"><b>Floor</b></a></th>
<th id ="results"><a href="#"><b>Machine Type</b></a></th>
<th id ="results"><a href="#"><b>Computer Platform</b></a></th>
<th id ="results"><a href="#"><b>Computer Model</b></a></th>
<th id ="results"><a href="#"><b>Printer|Scanner Model</b></a></th>
<th id ="results"><a href="#"><b>Asset</b></a></th>
<th id ="results"><a href="#"><b>Serial No</b></a></th>
<th id ="results"><a href="#"><b>Service Tag</b></a></th>
<th id ="results"><a href="#"><b>Processor</b></a></th>
<th id ="results"><a href="#"><b>Processor Speed</b></a></th>
<th id ="results"><a href="#"><b>RAM</b></a></th>
<th id ="results"><a href="#"><b>HDD</b></a></th>
<th id ="results"><a href="#"><b>Optical Drive</b></a></th>
<th id ="results"><a href="#"><b>Last Updated</b></a></th>
</tr>');

// Display database results
$rowclass = 1;
$i = 1;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$record_id = $row["record_id"];
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
$processor = $row["proc_speed"];
$proc_type = $row["proc_type"];
$ram = $row["ram"];
$disk = $row["disk_size"];
$optical = $row["optical_drive"];
$disp_model = $row["display_model"];
$disp_size = $row["display_size"];
$disp_asset = $row["display_asset"];
$disp_serial = $row["display_serial"];
$notes = $row["notes"];
$last_updated = $row["last_updated"];

if ($rowclass == 0){
	echo ('<tr class="row0">');
}
else{
	echo ('<tr class="row1">');
}
echo ('<td id="results">' . $i .'</td>');

if ($campus != NULL){
echo ('<td id="results">' . $campus .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($building != NULL){
echo ('<td id="results">' . $building .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($floor != NULL){
echo ('<td id="results">' . $floor .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($mtype != NULL){
echo ('<td id="results">' . $mtype .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($platform != NULL){
echo ('<td id="results">' . $platform .'</td>');
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

if ($othermodel != NULL){
echo ('<td id="results">' . $othermodel .'</td>');
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

echo ('<td id="results"><a href="update2.php?user_id=' . $user_id . '">' . $serial . '</a></td>');

if ($service != NULL){
echo ('<td id="results">' . $service .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($proc_type != NULL){
echo ('<td id="results">' . $proc_type .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($processor != NULL){
echo ('<td id="results">' . $processor .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($ram != NULL){
echo ('<td id="results">' . $ram .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($disk != NULL){
echo ('<td id="results">' . $disk .'</td>');
}
else{
echo ('<td id="results">&nbsp;</td>');
}

if ($optical != NULL){
echo ('<td id="results">' . $optical .'</td>');
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
<script type="text/javascript" language="javascript" charset="utf-8" src="/inventory/include/inventory.js"></script>
<script src="/inventory/include/sorttable.js" type="text/javascript"></script>
<link rel="stylesheet" href="/inventory/include/inventory.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="index.php">Academic Computing Inventory Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td>
</tr>
</table></center>
</div>

<div id="content">
<?php
$campus = $_GET["campus"];
$bldg = $_GET["bldg"];
$labselect = $_GET["labselect"];
$mtype = $_GET["mtype"];
$platform = $_GET["platform"];
$model = $_GET["model"];
$othermodel = $_GET["othermodel"];
$asset = $_GET["asset"];
$processor = $_GET["processor"];
$proc_type = $_GET["proc_type"];
$ram = $_GET["ram"];
$disk = $_GET["hdd"];
$optical = $_GET["optical"];
$mname = $_GET["mname"];
$mac = $_GET["mac"];
$ip = $_GET["ip"];
$range = $_GET["range"];

if ($campus != NULL || $bldg != NULL || $labselect != NULL || $mtype != NULL || $platform != NULL || $model != NULL || $othermodel != NULL || $asset != NULL || $processor != NULL || $proc_type != NULL || $ram != NULL || $disk != NULL || $optical != NULL || $mname != NULL || $mac != NULL || $ip != NULL || $range != NULL) {
query_db($campus, $bldg, $mtype, $platform, $model, $othermodel, $asset, $proc_type, $processor, $ram, $disk, $optical);
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
	<option value="">Select building (must first select a campus)</option></select><br><br>
<strong>Search by Lab?</strong><br>
	<input type="radio" name="labselect" onclick="labDisplay(1)"/>Yes&nbsp;&nbsp;<input type="radio" name="labselect" onclick="labDisplay(2)"/>No<br><br><select name="lab" style="display:none" value=""></select></td>
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
<strong>Computer Platform: </strong><br>
	<input type="radio" name="platform" value="Mac" onclick="modelSelect(1)"/>Mac<br>
	<input type="radio" name="platform" value="PC" onclick="modelSelect(2)"/>PC<br><br>
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
<input type="text" name="asset" size="5" maxlength="10" /><br><br>
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
<strong>Processor Type: </strong><br>
	<select name="proc_type">
	<option value="">Select processor (must first select platform)</option></select><br><br>
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
<strong>Hard Drive: </strong><br>
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
<strong>MAC Address: </strong><br><input type="text" name="mac" size="20" maxlength="50" /><br><br>
<strong>IP Address: </strong><br><input type="text" name="ip" size="20" maxlength="50" /><br><br>
<td></tr></table><br><hr>
<input type="submit" value="Search Database" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
}
?>
</div>
</body>
</html>