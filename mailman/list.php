<?php
	$list = $_GET["lname"];
	$shell = shell_exec('more ' . $list . '.txt');
	//$shell = shell_exec('sudo bin/config_list -o  - ' . $list);
	$results = explode("\n", $shell);
	
	$owner = substr($results[37], 10, -1);
	$moderator = substr($results[56], 13, -1);
	$description = substr($results[61], 15, -1);
	$info = substr($results[69], 8, -1);
	$anonymous_list = substr($results[87], 17);
	$reply_goes_to_list = substr($results[129], 21);
	$reply_to_addr = substr($results[156], 20, -1); 
	$welcome_msg = substr($results[199], 15, -1);
	$send_welcome_msg = substr($results[209], 19);
	$goodbye_msg = substr($results[213], 15, -1);
	$send_goodbye_msg = substr($results[220], 19);
	$admin_immed_notify = substr($results[231], 21);
	$admin_notify_mchanges = substr($results[238], 24);
	$max_message_size = substr($results[272], 19);
	$digest_is_default = substr($results[449], 20);
	$advertised = substr($results[551], 13);
	$subscribe_policy = substr($results[566], 19);
	$unsubscribe_policy = substr($results[582], 21);
	$private_roster = substr($results[597], 17);
	$archive = substr($results[966], 10);
	$archive_private = substr($results[973], 18);
	
	echo ($owner . '|' . $moderator . '|' . $description . '|' . $info . '|' . $anonymous_list . '|' . $reply_goes_to_list . '|' . $reply_to_addr . '|' . $send_welcome_msg . '|' . $welcome_msg . '|' . $send_goodbye_msg . '|' . $goodbye_msg . '|' . $admin_immed_notify . '|' . $admin_notify_mchanges . '|' . $max_message_size . '|' . $digest_is_default . '|' . $advertised . '|' . $subscribe_policy . '|' . $unsubscribe_policy . '|' . $private_roster . '|' . $archive . '|' . $archive_private);
	
?>