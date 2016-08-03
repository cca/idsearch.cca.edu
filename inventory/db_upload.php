<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA Machine Inventory</title>
<meta http-equiv="content-type" content="text/html; charset="utf-8" />
<meta name="description" content="" />
<link rel="stylesheet" href="include/inventory.css" />
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

if (($_FILES["file"]["size"] < 2000000)) {
  if ($_FILES["file"]["error"] > 0) {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else {
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    if (file_exists("uploads/" . $_FILES["file"]["name"]))
      {
      echo "<br><h3>Error: " . $_FILES["file"]["name"] . " already exists!</h3>";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "uploads/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "/Sites/secure.cca.edu/idsearch/documents/inventory/uploads/" . $_FILES["file"]["name"] . "<br />";
      echo "<h4>File successfully uploaded!</h4>";
      }
    }
  }
else {
  echo "Invalid file.  File must be less than 2 MB!";
  }
  
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

$file = $_FILES["file"]["name"];
$query = "LOAD DATA LOCAL INFILE 'uploads/$file' INTO TABLE master FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n'";
$result = mysql_query($query);

if(!$result){
die ("Could not load file into database: <br />". mysql_error());
}

echo "Data loaded into database!";

// Close the connection
mysql_close($connect);

?>
</div>
</body>
</html>
