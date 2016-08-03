<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>CCA Mailing List configuration</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="description" content="" />
<link rel="stylesheet" type="text/css" media="all" href="http://technology.cca.edu/include/technology.css" />
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
	var lname = document.getElementById('lname').value;
	
	// Build the URL to connect to
	var url = "list.php?lname=" + escape(lname);
	
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
		var response = xmlHttp.responseText.split("|");
		document.listconfig.owner.value = response[0];
		document.listconfig.moderator.value = response[1];
		document.listconfig.phrase.value = response[2];
		document.listconfig.description.value = response[3];
		if (response[4] == "False") {
		document.getElementById('user').checked = true;
		}
		else {
		document.getElementById('listname').checked = true;
		}
		if (response[5] == "0") {
		document.getElementById('poster').checked = true;
		}
		else if (response[5] == "1") {
		document.getElementById('thislist').checked = true;
		}
		else {
		document.getElementById('specificaddr').checked = true;
		}
		document.listconfig.reply_to_address.value = response[6];
		if (response[7] == "True") {
		document.getElementById('yeswelcome').checked = true;
		}
		else {
		document.getElementById('nowelcome').checked = true;
		}
		document.listconfig.welcome_msg.value = response[8];
		if (response[9] == "True") {
		document.getElementById('yesgoodbye').checked = true;
		}
		else {			
		document.getElementById('nogoodbye').checked = true;
		}
		document.listconfig.goodbye_msg.value = response[10];
		if (response[11] == "True") {
		document.getElementById('yesnotice').checked = true;
		}
		else {			
		document.getElementById('nonotice').checked = true;
		}
		if (response[12] == "True") {
		document.getElementById('yessubscribe').checked = true;
		}
		else {			
		document.getElementById('nosubscribe').checked = true;
		}
		document.listconfig.max_message_size.value = response[13];
		if (response[14] == "False") {
		document.getElementById('feed').checked = true;
		}
		else {			
		document.getElementById('digest').checked = true;
		}
		if (response[15] == "True") {
		document.getElementById('yesadvertise').checked = true;
		}
		else {			
		document.getElementById('noadvertise').checked = true;
		}
		if (response[16] == "1") {
		document.getElementById('confirm').checked = true;
		}
		else if (response[16] == "2") {
		document.getElementById('approval').checked = true;
		}
		else {
		document.getElementById('confirmapprov').checked = true;
		}
		if (response[17] == "False") {
		document.getElementById('self').checked = true;
		}
		else {			
		document.getElementById('moderatorapprov').checked = true;
		}
		if (response[18] == "0") {
		document.getElementById('anyone').checked = true;
		}
		else if (response[18] == "1") {
		document.getElementById('listmembers').checked = true;
		}
		else {
		document.getElementById('administrators').checked = true;
		}
		if (response[19] == "True") {
		document.getElementById('yesarchive').checked = true;
		}
		else {			
		document.getElementById('noarchive').checked = true;
		}
		if (response[20] == "False") {
		document.getElementById('anyonearchive').checked = true;
		}
		else {			
		document.getElementById('subscribers').checked = true;
		}
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
</head>
<body>
<div id="page">
	
	<div id="header">
		<a href="/"><img src="http://technology.cca.edu/images/title.ets.gif" alt="CCA Educational Technology Services" 
			width="476" height="42" border="0" /></a>
	</div>
	
	<div id="navsection">
	<a href="http://technology.cca.edu/about/" onmouseover="iC('about','on')" onmouseout="iC('about','')">
		<img src="http://technology.cca.edu/images/nav.about.gif" alt="About ETS" width="58" height="28" border="0" name="about" /></a>
	<img src="http://technology.cca.edu/images/nav.support1.gif" alt="Technology Support" width="118" height="28" border="0" 	name="support" />
	<a href="http://technology.cca.edu/labs/" onmouseover="iC('labs','on')" onmouseout="iC('labs','')"><img src="http://technology.cca.edu/images/nav.labs.gif" alt="Computer Labs" width="85" height="28" border="0" name="labs" /></a>
	<a href="http://technology.cca.edu/mediacenters/" onmouseover="iC('media','on')" onmouseout="iC('media','')"><img src="http://technology.cca.edu/images/nav.media.gif" alt="Media Centers" width="81" height="28" border="0" name="media" /></a>
	<a href="http://technology.cca.edu/printservices/" onmouseover="iC('print','on')" onmouseout="iC('print','')"><img src="http://technology.cca.edu/images/nav.print.gif" alt="Print Services" width="81" height="28" border="0" name="print" /></a>
	<a href="http://technology.cca.edu/laptops/" onmouseover="iC('laptop','on')" onmouseout="iC('laptop','')"><img src="http://technology.cca.edu/images/nav.laptop.gif" alt="Laptop Program" width="92" height="28" border="0" name="laptop" /></a>
	<a href="http://technology.cca.edu/store/" onmouseover="iC('store','on')" onmouseout="iC('store','')"><img src="http://technology.cca.edu/images/nav.store.gif" alt="Computer Store" width="91" height="28" border="0" name="store" /></a>
	</div>

	<div id="sbanner">
	<img src="http://technology.cca.edu/images/banner.support.jpg" alt="Technology Support Banner image" width="765" height="72" border="0" />
	</div>

	<div id="content-section">
		<div id="content-nav">
			<ul>
				<li><a href="http://technology.cca.edu/support/">&#8250; Technology Support</a></li>
				<li><a href="http://technology.cca.edu/support/helpdesk/">&#8250; Helpdesk</a></li>
				<li><a href="http://technology.cca.edu/support/email/">&#8250; Email</a></li>
				<li><a href="http://technology.cca.edu/support/osx/">&#8250; Mac OS X</a></li>
				<li><a href="http://technology.cca.edu/support/labs/">&#8250; Lab Accounts</a></li>
				<li><a href="http://technology.cca.edu/support/printing/">&#8250; Printing</a></li>
				<li><a href="http://technology.cca.edu/support/laptops/">&#8250; Laptops</a></li>
				<li><a href="http://technology.cca.edu/support/wireless.php">&#8250; Wireless Network</a></li>
				<li><a href="http://technology.cca.edu/support/telephones.php">&#8250; Telephones</a></li>
				<li><a href="http://technology.cca.edu/support/workshops.php">&#8250; Workshops</a></li>
			</ul>
			<div class="clearer">&nbsp;</div>
		</div>
		
		<div id="content-left-sep">
			<img src="http://technology.cca.edu/images/bar.sep.gif" alt="" width="1" height="283" border="0" />
		</div>
		
		<div id="content-center">
			<p><strong>Enter list name to configure: </strong><input type="text" name="lname" id="lname" size="15"><br><br>
			<input type="submit" value="Retrieve list info" onclick="ajaxFunction()"<br><hr></p>
			<form name="listconfig" action="update.php" method="post">
			<p><b>List owner addresses:</b> <textarea name="owner" rows="3" cols="30" style="font-size:11px"></textarea><br><br>
			<b>List moderator addresses:</b> <textarea name="moderator" rows="3" cols="30" style="font-size:11px"></textarea><br><br>
			<b>Short phrase describing list:</b> <input name="phrase" type="text" value="" size="30" /><br><br>
			<b>Describe the list:</b><br><textarea name="description" rows="7" cols="30" style="font-size:11px"></textarea><br><br>
			<b>Sender should be:</b><br><input type="radio" name="anonymous_list" id="user" value="False" />User
			&nbsp;&nbsp;<input type="radio" name="anonymous_list" id="listname" value="True" />List name<br><br>
			<b>Reply-to address should be:</b><br><input type="radio" name="reply_goes_to_list" id="poster" value="0"/>
			Poster&nbsp;&nbsp;<input type="radio" name="reply_goes_to_list" id="thislist" value="1" />This List&nbsp;&nbsp;
			<input type="radio" name="reply_goes_to_list" id="specificaddr" value="2" />Specific Address<br><br>
			<b>If specific address, enter address: <b><input name="reply_to_address" type="text" value="" size="30" /><br><br>
			<b>Send welcome message to new subscribers?</b><br><input type="radio" name="send_welcome_msg" id="yeswelcome" value="True" />
			Yes&nbsp;&nbsp;<input type="radio" name="send_welcome_msg" id="nowelcome" value="False" />No<br><br>
			<b>If yes, specify welcome message: <b><input name="welcome_msg" type="text" value="" size="30" /><br><br>
			<b>Send unsubscription message?</b><br><input type="radio" name="send_goodbye_msg" id="yesgoodbye" value="True" />
			Yes&nbsp;&nbsp;<input type="radio" name="send_goodbye_msg" id="nogoodbye" value="False" />No<br><br>
			<b>If yes, specify unsubscription message: <b><input name="goodbye_msg" type="text" value="" size="30" /><br><br>
			<b>Should list administrators and moderators get notices of requests pending approval?</b><br>
			<input type="radio" name="admin_immed_notify" id="yesnotice" value="True" />Yes&nbsp;&nbsp;
			<input type="radio" name="admin_immed_notify" id="nonotice" value="False" />No<br><br>
			<b>Should list administrators get notices of subscribes and unsubscribes?</b><br>
			<input type="radio" name="admin_notify_mchanges" id="yessubscribe" value="True" />Yes&nbsp;&nbsp;
			<input type="radio" name="admin_notify_mchanges" id="nosubscribe" value="False" />No<br><br>
			<b>Maximum message size (suggested 500KB):<b><br><input name="max_message_size" type="text" value="" size="10" /><br><br>
			<b>Default mode for new subscribers:</b><br><input type="radio" name="digest_is_default" id="feed" value="False" />
			Feed&nbsp;&nbsp;<input type="radio" name="admin_notify_mchanges" id="digest" value="True" />Digest<br><br>
			<b>Advertise the list?</b><br><input type="radio" name="advertise" id="yesadvertise" value="True" />Yes&nbsp;&nbsp;
			<input type="radio" name="advertise" id="noadvertise" value="False" />No<br><br>
			<b>How can folks subscribe themselves?</b><br><input type="radio" name="subscribe_policy" id="confirm" value="1" />
			Confirmation&nbsp;&nbsp;<input type="radio" name="subscribe_policy" id="approval" value="2" />
			Require approval&nbsp;&nbsp;<input type="radio" name="subscribe_policy" id="confirmapprov" value="3" />
			Confirmation and approval<br><br>
			<b>How can folks unsubscribe?</b><br><input type="radio" name="unsubscribe_policy" id="self" value="False" />
			Self&nbsp;&nbsp;<input type="radio" name="unsubscribe_policy" id="moderatorapprov" value="True" />Moderator approved<br><br>
			<b>Who can see a list of subscribers?</b><br><input type="radio" name="private_roster" id="anyone" value="0" />
			Anyone&nbsp;&nbsp;<input type="radio" name="private_roster" id="listmembers" value="2" />List members&nbsp;&nbsp;
			<input type="radio" name="private_roster" id="administrators" value="2" />Administrators<br><br>
			<b>Archive messages?</b><br><input type="radio" name="archive" id="yesarchive" value="True" />Yes&nbsp;&nbsp;
			<input type="radio" name="archive" id="noarchive" value="False" />No<br><br>
			<b>Who can browse the archive?</b><br><input type="radio" name="archive_private" id="anyonearchive" value="False" />
			Anyone&nbsp;&nbsp;<input type="radio" name="archive_private" id="subscribers" value="True" />Subscribers only<br><br>
			<input type="submit" value="Submit">&nbsp;&nbsp;<input type="reset" value="Reset Form"></p>
			</form>
		</div>

		<div id="content-right-sep">
			<img src="http://technology.cca.edu/images/bar.sep.gif" alt="" width="1" height="283" border="0" />
		</div>
		
		<div id="content-right">
			<h2>Tech How-To's</h2>
			<p>Answers to common tech questions can be found here.<br /><a href="http://technology.cca.edu/support/knowledgebase/">
				View the How-To's &raquo;</a></p>
				<h2>Need Help?</h2>
				<p>Contact the ETS Helpdesk at<br />510.594.5010 or<br /><a href="http://helpdesk.cca.edu">submit a ticket online.</a></p>
				<h2>Lab Helpsheets</h2>
				<p>A variety of helpsheets are provided in PDF format.<br />
				<a href="http://technology.cca.edu/support/helpsheets.php">View helpsheets &raquo;</a></p>
		</div>
	</div>
</div>
</body>
</html>