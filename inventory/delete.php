<?php
function query_db($x1) {

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

$query = "DELETE FROM master WHERE uname='$x1'";
$result = mysql_query($query);

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<h3>Record $x1 has been deleted the CCA Inventory Database!</h3>");

// Close the connection
mysql_close($connect);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ETS Inventory</title>
<link rel="stylesheet" href="include/inventory.css" />
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
<div id="updatestrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete a record</strong>
</div><br>
<div id="content">
<?php
$uname = $_GET["username"];

if ($uname != NULL) {
	query_db($uname);
}
else {
echo ('<form name="inventory" method="GET" action="'.$_SERVER["PHP_SELF"].'">
<label><strong>Enter username of record to delete:</strong></label> 
<input type="text" name="username" size="15"><br><br><hr>
<input type="submit" value="Delete Record" />&nbsp;&nbsp;<input type="reset" value="Reset" />
</form>');
}
?>
</div>
</body>
</html>
