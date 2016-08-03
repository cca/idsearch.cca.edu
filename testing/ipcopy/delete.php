<?php
function query_db($x1) {

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

$query = "DELETE FROM master_copy WHERE ip_addr='$x1'";
$result = mysql_query($query);

if(!$result){
die ("Could not query the database: <br />". mysql_error());
}

echo ("<b>IP Address $x1 has been deleted from the CCA IP Database!</b>");

// Close the connection
mysql_close($connect);
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Remove entry from CCA Printer/Network Database</title>
<link rel="stylesheet" href="/ipcopy/include/ip.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr><td id="logo"><a href="../ipcopy/index.php">CCA Printer/Network Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="../ipcopy/index.php">Search Database</a> : : <a href="../ipcopy/post.php">Add Entry</a> : : <a href="../ipcopy/update.php">Update Entry</a> : : <a href="../ipcopy/delete.php">Delete Entry</a> : : <a href="../ipcopy/upload.php">Upload File</a></td></tr>
</table></center>
</div>

<div id="updatestrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete a record</strong>
</div>

<div id="content">
<?php
$ip = $_GET["ip"];

if ($ip != NULL) {
	query_db($ip);
}
else {
echo ('<form name="iptrack" method="GET" action="'.$_SERVER["PHP_SELF"].'">
<br><label><strong>Enter IP address to delete:</strong></label><br>
<input type="text" name="ip" size="15"><br><br><hr>
<input type="submit" value="Delete Record" />&nbsp;&nbsp;<input type="reset" value="Reset" />
</form>');
}
?>
</div>
</body>
</html>