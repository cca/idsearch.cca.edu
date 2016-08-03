<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Update CCA Printer/Network Database</title>
<script type="text/javascript" language="javascript" charset="utf-8" src="include/ip.js"></script>
<script language="javascript" type="text/javascript">
<!-- 
// Make an AJAX request
function ajaxFunction(){
	// Check browser for AJAX functionality
	var xmlHttp;  // The variable that makes Ajax possible!
	try{
		// Opera 8.0+, Firefox, Safari
		xmlHttp = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser  does not support AJAX!");
				return false;
			}
		}
	}
	
	// Get username input from form
	var ipid = document.getElementById('ipid').value;
	
	// Build the URL to connect to
	var url = "ajax2.php?ip_id=" + ipid;
	
	// Open a connetion to the server
	xmlHttp.open("GET", url, true);
	
	// Setup a function for the server to run when it's done
	xmlHttp.onreadystatechange = updateForm;
	
	// Send request to server
	xmlHttp.send(null);
	
// Function to handle the server's response
function updateForm() {
	if (xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) {
		var response = xmlHttp.responseText.split(",");
		document.iptrack.ipaddr.value = response[0];
			if (response[1] == "Static") {
			document.getElementById('ipstatic').checked = true;
			document.getElementById('ipstatic').value = response[1];
			}
			else {
			document.getElementById('ipdynamic').checked = true;
			document.getElementById('ipdynamic').value = response[1];
			}
			if (response[2] == "Used") {
			document.getElementById('ipused').checked = true;
			document.getElementById('ipused').value = response[2];
			}
			else {
			document.getElementById('ipnotused').checked = true;
			document.getElementById('ipnotused').value = response[2];
			}
		document.iptrack.device_type.value = response[3];
		document.iptrack.model.value = response[4];
		document.iptrack.serial.value = response[5];
		document.iptrack.asset.value = response[6];
			if (response[7] == "Oakland") {
			document.getElementById('oakradio').checked = true;
			document.getElementById('oakradio').onchange = bldgSelect(1);
			document.getElementById('oakradio').value = response[7];
			}
			else if (response[7] == "San Francisco") {
			document.getElementById('sfradio').checked = true;
			document.getElementById('sfradio').onchange = bldgSelect(2);
			document.getElementById('sfradio').value = response[7];
			}
			else {
			return;
			}
		document.iptrack.bldg.value = response[8];
		document.iptrack.location.value = response[9];
		document.iptrack.dns.value = response[10];
		document.iptrack.mac.value = response[11];
		document.iptrack.department.value = response[12];
		document.iptrack.username.value = response[13];
		document.iptrack.notes.value = response[14];
		} else if (xmlHttp.status == 404) {
         alert ("Requested URL is not found.");
       	} else if (xmlHttp.status == 403) {
         alert("Access denied.");
       	} else
         alert("status is " + xmlHttp.status);
	}
}
}
//-->
</script>
<script>window.onload=ajaxFunction;</script>
<link rel="stylesheet" href="include/ip.css" />
</head>
<body>
<div id="header">
<center><table class="nav">
<tr><td id="logo"><a href="index.php">CCA Printer/Network Database</a></td>
<td id="links"><?php echo date ("l - F j, Y");?><br>
<a href="index.php">Search Database</a> : : <a href="post.php">Add Entry</a> : : <a href="update.php">Update Entry</a> : : <a href="delete.php">Delete Entry</a> : : <a href="upload.php">Upload File</a></td></tr>
</table></center>
</div>
<div id="updatestrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Update a record</strong>
</div>
<div id="content">
<?php 
$id = $_GET["ip_id"]; 
echo('<form name="iptrack" method="post" action="db_update2.php"><br>
<input type="hidden" name="ip_id" id="ipid" value="'.$id.'" size="3" />
<table class="form"><tr><td valign=top><h4>Network</h4></td><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>User</h4></td><td valign=top><h4>Notes</h4></td></tr>
<tr><td valign=top><strong>IP Address:</strong><br>
<input type="text" name="ipaddr" size="20" /><br><br>
<strong>IP Type:</strong><br>
<input type="radio" name="ip_type" id="ipstatic" value="Static">Static  <input type="radio" name="ip_type" id="ipdynamic" value="Dynamic">Dynamic<br><br>
<strong>IP Availability:</strong><br>
<input type="radio" name="avail" id="ipused" value="Used">Used  <input type="radio" name="avail" id="ipnotused" value="Available">Available<br><br>
<strong>DNS Name:</strong><br>
<input type="text" name="dns" size="20" /><br><br>
<strong>Mac Address:</strong><br>
<input type="text" name="mac" size="20" />
</td>
<td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" value="Oakland" id="oakradio" onclick="bldgSelect(1)"/>Oakland<br>
	<input type="radio" name="campus" value="San Francisco" id="sfradio" onclick="bldgSelect(2)"/>San Francisco<br>
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
	</select><br><br>
	<strong>Location:</strong><br>
	<input type="text" name="location" size="20" />
	</td>
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
	</select><br><br>
	<strong>Model Number:</strong><br>
	<input type="text" name="model" size="20" /><br><br>
	<strong>Serial Number:</strong><br>
	<input type="text" name="serial" size="20" /><br><br>
	<strong>Asset Tag:</strong><br>
	<input type="text" name="asset" size="20" />
	</td>
<td valign=top><strong>Username: </strong><br>
<input type="text" name="username" size="20" maxlength="50" /></td>
<td valign=top><strong>Notes:</strong><br>
<textarea class="messagebox" rows="10" cols="35" style="font-size:12px" name="notes"></textarea>
</td></tr></table><br><hr>
<input type="submit" value="Update Entry" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>');
?>
</div>
</body>
</html>
