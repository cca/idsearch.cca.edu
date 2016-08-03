<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/acadcomp_variables.php");
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	header("Location: login.php"); 
	return;
}

$field = @$_GET["field"];

//	check permissions
if(!CheckFieldPermissions($field))
	return;

if()
	return;
//	construct sql

$keys=array();
$keys["record_id"]=postvalue("key1");
$where=KeyWhere($keys);

/*
$sql=$gstrSQL;
$sql = AddWhere($sql,$where);
*/

$conn=db_connect();


$sql = gSQLWhere($where);

$rs = db_query($sql,$conn);
if(!$rs)
  return;

$data=db_fetch_array($rs);

if(!$data)
	return;

$filename=$data[$field];
$ext=substr($filename,strlen($filename)-4);

switch($ext)
{
	case ".asf":
		$ctype = "video/x-ms-asf";
	case ".avi":
		$ctype = "video/avi";
	case ".doc":
		$ctype = "application/msword";
	case ".zip":
		$ctype = "application/zip";
	case ".xls":
		$ctype = "application/vnd.ms-excel";
	case ".gif":
		$ctype = "image/gif";
	case ".jpg":
	case "jpeg":
		$ctype = "image/jpeg";
	case ".wav":
		$ctype = "audio/wav";
	case ".mp3":
		$ctype = "audio/mpeg3";
	case ".mpg":
	case "mpeg":
		$ctype = "video/mpeg";
	case ".rtf":
		$ctype = "application/rtf";
	case ".htm":
	case "html":
		$ctype = "text/html";
	case ".asp":
		$ctype = "text/asp";
	default:
		$ctype = "application/octet-stream";
}

$filesize = filesize(GetUploadFolder($field).$filename);
if($filesize===FALSE)
{
	header("HTTP/1.0 404 Not Found");
	return;
}
header("Content-type: ".$ctype);
header("Content-Disposition: attachment;Filename=\"".$filename."\"");
header("Cache-Control: private");
header("Content-Length: ".$filesize);
$file=fopen(GetUploadFolder($field).$filename,"rb");
$bufsize=8*1024;
while(!feof($file))
	echo fread($file,$bufsize);
fclose($file);
return;

?>
