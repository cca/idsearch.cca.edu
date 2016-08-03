<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ETS Inventory</title>
<meta http-equiv="content-type" content="text/html; charset="utf-8" />
<meta name="description" content="" />
<link rel="stylesheet" href="/inventorycopy/include/inventory.css" />
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
<div id="content">
<form enctype="multipart/form-data" action="db_upload.php" method="POST">
<h3>Upload a *.csv or *.txt file to the database:</h3><hr><br>
<b>Directions before uploading file:</b><br><br>
<b>1.</b> File MUST have its field values separated by commas with no spaces in between each field<br>
<b>2.</b> The file should NOT contain any column headers, only data<br>
<b>3.</b> The first and last value of each line MUST be "NULL" (this is used by the MySQL database to auto-increment each row appropriately)<br>
<b>4.</b> Each line in the file should be structured as follows:<br>NULL,Username,First Name,Last Name,Department,Campus,Building,Floor,Room,Device Type,Computer Model,Printer/Scanner Model,Platform,Asset Number,Serial Number,Service Tag,Processor Type,Processor Speed,RAM,Disk Size,Optical Drive,Machine Name,IP Address,MAC Address,Display Model,Display Size,Display Asset,Display Serial,Notes,NULL<br>
<b>5.</b> If a value is to be left blank, it should appear as ",," in the file.<br>
<b>6.</b> Each line in the file will create a new entry in the MySQL database.
<br><br><hr>
Choose a <b>.csv</b> or <b>.txt</b> file to upload: <input name="file" id="file" type="file" /><br><br>
<input type="submit" name="submit" value="Upload to Database" />
</form>
</div>
</body>
</html>