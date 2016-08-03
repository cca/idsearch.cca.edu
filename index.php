<?php
function query_db($qstring1, $qstring2, $qstring3, $qstring4, $qstring5) {

// Connect to and select database
include('include/db_login.php');
$connect = mysql_connect($db_host, $db_username, $db_password);

if (!$connect){
die ("Could not connect to the database: <br />". mysql_error());
}

$db_select = mysql_select_db($db_database);
if (!db_select){
die ("Could not select the database: <br> />". mysql_error());
}

$query = "SELECT * FROM cca_users WHERE first_name like '%$qstring1%' && last_name like '%$qstring2%' && dept like '%$qstring3%' && badge_type like '%$qstring4%' && program like '%$qstring5%'";
$result = mysql_query($query);

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

// Display database results
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){
$first_name = $row["first_name"];
$last_name = $row["last_name"];
$phone = $row["phone"];
$dept = $row["dept"];
$program = $row["program"];
$record_id = $row["record_id"];
$badge_type = $row["badge_type"];
$image_path = "mugShots/".$record_id.'.JPG';
echo ("<strong>Name:</strong> ") . $first_name . ' ' . $last_name .'<br />';
echo ("<strong>Phone:</strong> ") . $phone .'<br />';
if ($dept != NULL){
echo ("<strong>Department:</strong> ") . $dept .'<br />';
}
else{
echo ("<strong>Academic Program:</strong> ") . $program .'<br />';
}
echo $badge_type .'<br />';
echo "<img src='$image_path'><br /><hr><br />";
}

// Close the connection
mysql_close($connect);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ID Database</title>
<link rel="stylesheet" href="include/id.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr>
<td id="logo"><a href="index.php">CCA ID Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="inventory/index.php">Search CCA Machine Inventory</a> : : <a href="ip/index.php">Search CCA IP Database</a></td>
</tr>
</table></center>
</div>
<div id="content">
<?php
$firstname = $_GET["firstname"];
$lastname = $_GET["lastname"];
$department = $_GET["department"];
$badge_type = $_GET["badge_type"];
$program = $_GET["program"];

if ($firstname != NULL || $lastname != NULL || $department != NULL || $badge_type != NULL || $program != NULL) {
query_db($firstname, $lastname, $department, $badge_type, $program);
}
else {
echo ('<form action="'.$_SERVER["PHP_SELF"].'" method="GET">');
echo ("<h3>Welcome to the CCA User Directory</h3><br>");
echo ("<strong>Search entire directory:</strong><br>");
echo ('<label>First name: <input type="text" name="firstname" size="10" maxlength="30" value="" /></label>
	<label>Last name: <input type="text" name="lastname" size="10" maxlength="30" /></label><br><br><br>
	<label><strong>Search for staff, by department:</strong><br>
	<select name="department">
	<option></option>
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
	</select></label><br><br><br>
	<label><strong>Search for: </strong><br>
	<select name="badge_type">
	<option></option>
	<option value="Faculty">Faculty</option>
	<option value="Student">Student</option>
	</select></label><br>
	<label><strong>By program:</strong><br>
	<select name="program">
	<option></option>
	<option value="Animation">Animation</option>
	<option value="Architecture">Architecture</option>
	<option value="Ceramics">Ceramics</option>
	<option value="Community Arts">Community Arts</option>
	<option value="Critical Studies">Critical Studies</option>
	<option value="Curatorial Practice">Curatorial Practice</option>
	<option value="Design">Design</option>
	<option value="Fashion Design">Fashion Design</option>
	<option value="Fine Arts">Fine Arts</option>
	<option value="Glass">Glass</option>
	<option value="Graphic Design">Graphic Design</option>
	<option value="Illustration">Illustration</option>
	<option value="Industrial Design">Industrial Design</option>
	<option value="Jewelry/Metal Arts">Jewelry/Metal Arts</option>
	<option value="Media Arts">Media Arts</option>
	<option value="Painting/Drawing">Painting/Drawing</option>
	<option value="Photography">Photography</option>
	<option value="Printmaking">Printmaking</option>
	<option value="Sculpture">Sculpture</option>
	<option value="Textiles">Textiles</option>
	<option value="Visual & Critical Studies">Visual & Critical Studies</option>
	<option value="Visual Studies">Visual Studies</option>
	<option value="Writing">Writing</option>
	<option value="Writing & Literature">Writing & Literature</option>
	</select></label><br><br>
	<input type="submit" value="Submit" />
	</form>');
}
?>
</div>
</body>
</html>

