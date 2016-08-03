<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CCA ETS Inventory</title>
<script type="text/javascript" language="javascript" charset="utf-8" src="/inventorycopy/include/inventory2.js"></script>
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
	var uname = document.getElementById('uname').value;
	
	// Build the URL to connect to
	var url = "ajax.php?uname=" + escape(uname);
	
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
		document.inventory.username.value = response[0];
		document.inventory.firstname.value = response[1];
		document.inventory.lastname.value = response[2];
		document.inventory.department.value = response[3];
			if (response[4] == "OAK") {
			document.getElementById('oakradio').checked = true;
			document.getElementById('oakradio').onchange = bldgSelect(1);
			document.getElementById('oakradio').value = response[4];
			}
			else if (response[4] == "SF") {
			document.getElementById('sfradio').checked = true;
			document.getElementById('sfradio').onchange = bldgSelect(2);
			document.getElementById('sfradio').value = response[4];
			}
			else {
			return;
			}
		document.inventory.bldg.value = response[5];
		document.inventory.floor.value = response[6];
		document.inventory.room.value = response[7];
		document.inventory.mtype.value = response[8];
			if (response[9] == "Mac") {
			document.getElementById('macradio').checked = true;
			document.getElementById('macradio').onchange = modelSelect(1);
			document.getElementById('macradio').value = response[9];
			}
			else if (response[9] == "PC") {
			document.getElementById('pcradio').checked = true;
			document.getElementById('pcradio').onchange = modelSelect(2);
			document.getElementById('pcradio').value = response[9];
			}
			else {
			return;
			}
		document.inventory.model.value = response[10];
		document.inventory.othermodel.value = response[11];
		document.inventory.asset.value = response[12];
		document.inventory.serial.value = response[13];
		document.inventory.service.value = response[14];
		document.inventory.processor.value = response[15];
		document.inventory.proc_type.value = response[16];
		document.inventory.ram.value = response[17];
		document.inventory.hdd.value = response[18];
		document.inventory.optical.value = response[19];
		document.inventory.mname.value = response[20];
		document.inventory.ip.value = response[21];
		document.inventory.mac.value = response[22];
		document.inventory.display_model.value = response[23];
		document.inventory.display_size.value = response[24];
		document.inventory.display_asset.value = response[25];
		document.inventory.display_serial.value = response[26];
		document.inventory.notes.value = response[27];
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
<div id="updatestrip">
<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Update a record</strong>
</div><br>
<div id="content">
<form name="inventory" method="post" action="db_update.php">
<table class="form"><tr><strong>Enter username of record to update:</strong><br>
<input type="text" name="uname" id="uname" value="" onkeyup="ajaxFunction();" size="15"></tr><br><br><hr>
<tr><td valign=top><h4>User</h4></td><td valign=top><h4>Location</h4></td><td valign=top><h4>Device</h4></td><td valign=top><h4>Network</h4></td><td valign=top><h4>Display</h4></td></tr>
<tr><td valign=top><strong>Username: </strong><br><input type="text" name="username" size="15" /><br><br>
<strong>First Name: </strong><br><input type="text" name="firstname" size="15" /><br><br>
<strong>Last Name: </strong><br><input type="text" name="lastname" size="15" /><br><br>
<strong>Department:</strong><br>
	<select name="department">
	<option value="">Select department</option>
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
	</select></td>
<td valign=top><strong>Campus: </strong><br>
	<input type="radio" name="campus" id="oakradio" value="OAK" onclick="bldgSelect(1)" />Oakland<br>
	<input type="radio" name="campus" id="sfradio" value="SF" onclick="bldgSelect(2)" />San Francisco<br><br>
<strong>Building: </strong><br>
	<select name="bldg">
	<option value="">Select building (must first select a campus)</option></select><br><br>
<strong>Floor: </strong><br>
	<input type="text" name="floor" size="5" maxlength="50" /><br><br>
<strong>Room: </strong><br>
	<input type="text" name="room" size="15" maxlength="50" /></td>
<td valign=top><strong>Device Type: </strong><br>
<select name="mtype">
	<option value="">Select type</option>
	<option value="Computer">Computer</option>
	<option value="Monitor">Monitor</option>
	<option value="Scanner">Scanner</option>
    <option value="Recycled Computer">Recycled Computer</option>
    <option value="Sold Computer">Sold Computer</option>
    <option value="Stolen Computer">Stolen Computer</option>
	<option value="Undistributed Computer">Undistributed Computer</option>
	<option value="Undistributed Monitor">Undistributed Monitor</option>
	<option value="Undistributed Scanner">Undistributed Scanner</option>
	</select><br><br>
<strong>Computer Platform: </strong><br>
	<input type="radio" name="platform" id="macradio" value="Mac" onclick="modelSelect(1)"/>Mac<br>
	<input type="radio" name="platform" id="pcradio" value="PC" onclick="modelSelect(2)"/>PC<br><br>
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
	<input type="text" name="asset" size="10" maxlength="50" /><br><br>
<strong>Serial Number: </strong><br>
	<input type="text" name="serial" size="15" maxlength="50" /><br><br>
<strong>Service Tag: </strong><br>
	<input type="text" name="service" size="10" maxlength="50" /><br><br>
<strong>Processor Type: </strong><br>
	<select name="proc_type">
	<option value="">Select processor (must first select platform)</option></select><br><br>
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
    <option value="8 GB">8 GB</option>
	</select><br><br>
<strong>Hard drive: </strong><br>
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
	<option value="281 - 321 GB">281 - 500 GB</option>
	<option value="281 - 501 GB">281 GB - 1 TB</option>
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
<strong>IP Address: </strong><br><input type="text" name="ip" size="20" maxlength="50" /><br><br>
<strong>MAC Address: </strong><br><input type="text" name="mac" size="20" maxlength="50" /></td>
<td valign=top><strong>Display Model: </strong><br><input type="text" name="display_model" size="15" maxlength="50" value="" /><br><br>
<strong>Display Size: </strong><br><input type="text" name="display_size" size="10" maxlength="50" /><br><br>
<strong>Display Asset: </strong><br><input type="text" name="display_asset" size="10" maxlength="50" /><br><br>
<strong>Display Serial: </strong><br><input type="text" name="display_serial" size="30" maxlength="50" /><br><br><h4>Notes</h4>
<textarea class="messagebox" rows="7" cols="35" style="font-size:11px" name="notes"></textarea></td></table><br>
<hr><br><input type="submit" value="Update Record" />&nbsp;&nbsp;<input type="reset" value="Reset Form" />
</form>
</div>
</body>
</html>