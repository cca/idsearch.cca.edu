<?php
function query_db($x1) {

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

$query = "SELECT * FROM master_copy WHERE user_id='$x1'";
$result = mysql_query($query);

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo('<h2>User Detail</h2><table cellpadding="0px" cellspacing="0">');

// Display database results
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$user_id = $row["user_id"];
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
$proc_type = $row["proc_type"];
$processor = $row["proc_speed"];
$ram = $row["ram"];
$disk = $row["disk_size"];
$optical = $row["optical_drive"];
$disp_model = $row["display_model"];
$disp_size = $row["display_size"];
$disp_asset = $row["display_asset"];
$disp_serial = $row["display_serial"];
$notes = $row["notes"];
$last_updated = $row["last_updated"];

echo ('<tr><td id="users"><b>Username:</b></td>');
echo ('<td id="users">' . $username .  '</td></tr>');

echo ('<tr><td id="users"><b>Name:</b></td>');
if ($firstname!=NULL || $lastname!=NULL)
{echo ('<td id="users">' . $firstname . ' ' . $lastname  . '</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Department:</b></td>');
if ($department!=NULL)
{echo ('<td id="users">' . $department .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Campus:</b></td>');
if ($campus!=NULL)
{echo ('<td id="users">' . $campus .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Building:</b></td>');
if ($building!=NULL)
{echo ('<td id="users">' . $building .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Floor:</b></td>');
if ($floor!=NULL)
{echo ('<td id="users">' . $floor .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Room:</b></td>');
if ($room!=NULL)
{echo ('<td id="users">' . $room .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Machine Type:</b></td>');
if ($mtype!=NULL)
{echo ('<td id="users">' . $mtype .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Platform:</b></td>');
if ($platform!=NULL)
{echo ('<td id="users">' . $platform .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Computer Model:</b></td>');
if ($model!=NULL)
{echo ('<td id="users">' . $model .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Printer/Scanner Model:</b></td>');
if ($othermodel!=NULL)
{echo ('<td id="users">' . $othermodel .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Asset Tag:</b></td>');
if ($asset!=NULL)
{echo ('<td id="users">' . $asset .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Serial No:</b></td>');
if ($serial!=NULL)
{echo ('<td id="users">' . $serial .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Service Tag:</b></td>');
if ($service!=NULL)
{echo ('<td id="users">' . $service .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Processor Type:</b></td>');
if ($proc_type!=NULL)
{echo ('<td id="users">' . $proc_type .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Processor Speed:</b></td>');
if ($processor!=NULL)
{echo ('<td id="users">' . $processor .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>RAM:</b></td>');
if ($ram!=NULL)
{echo ('<td id="users">' . $ram .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>HDD:</b></td>');
if ($disk!=NULL)
{echo ('<td id="users">' . $disk .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Optical Drive:</b></td>');
if ($optical!=NULL)
{echo ('<td id="users">' . $optical .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Display Model:</b></td>');
if ($disp_model!=NULL)
{echo ('<td id="users">' . $disp_model .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Display Size:</b></td>');
if ($disp_size!=NULL)
{echo ('<td id="users">' . $disp_size .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Display Asset:</b></td>');
if ($disp_asset!=NULL)
{echo ('<td id="users">' . $disp_asset .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Display Serial:</b></td>');
if ($disp_serial!=NULL)
{echo ('<td id="users">' . $disp_serial .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Notes:</b></td>');
if ($notes!=NULL)
{echo ('<td id="users">' . $notes .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}

echo ('<tr><td id="users"><b>Last Updated:</b></td>');
if ($last_updated!=NULL)
{echo ('<td id="users">' . $last_updated .'</td></tr>');}
else {echo ('<td id="users">&nbsp;</td></tr>');}
}
echo ('</table><br>');

// Close the connection
mysql_close($connect);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA User Detail Page</title>
<link rel="stylesheet" href="/inventorycopy/include/inventory.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="index.php">CCA Machine Inventory Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td>
</tr>
</table></center>
</div>

<div id="content">
<?php
$user_id = $_GET["user_id"];
$username = $_GET["username"];
$firstname = $_GET["firstname"];
$lastname = $_GET["lastname"];
$department = $_GET["department"];
$campus = $_GET["campus"];
$building = $_GET["bldg"];
$floor = $_GET["floor"];
$room = $_GET["room"];
$mtype = $_GET["mtype"];
$platform = $_GET["platform"];
$model = $_GET["model"];
$othermodel = $_GET["othermodel"];
$asset = $_GET["asset"];
$serial = $_GET["serial"];
$service = $_GET["service"];
$processor = $_GET["processor"];
$proc_type = $_GET["proc_type"];
$ram = $_GET["ram"];
$disk = $_GET["hdd"];
$optical = $_GET["optical"];
$disp_model = $_GET["display_model"];
$disp_size = $_GET["display_size"];
$disp_asset = $_GET["display_asset"];
$disp_serial = $_GET["display_serial"];
$notes = $_GET["notes"];

if ($user_id != NULL) {
query_db($user_id);
}
else {
echo ('Search for a client <a href="index.php">HERE</a>');
}

?>
</div>
</body>
</html>