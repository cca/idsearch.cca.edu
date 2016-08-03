<?php 
if(!isset($pdf))
{
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
	if(!CheckFieldPermissions($field))
		return DisplayNoImage();

//	construct sql

$keys=array();
$keys["record_id"]=postvalue("key1");
	$conn=db_connect();
}
else
{
	$field = @$params["field"];
	$keys=array();
	$keys["record_id"]=@$params["key1"];
}

$where=KeyWhere($keys);


$sql = gSQLWhere($where);

$rs = db_query($sql,$conn);

if(isset($pdf))
{
	if($rs && ($data=db_fetch_array($rs)))
		$file = $data[$field];
}
else
{

if(!$rs || !($data=db_fetch_array($rs)))
  return DisplayNoImage();


$value=db_stripslashesbinary($data[$field]);
if(!$value)
{
	if(@$_GET["alt"])
	{
		$value=db_stripslashesbinary($data[$_GET["alt"]]);
		if(!$value)
			return DisplayNoImage();
	}
	else
		return DisplayNoImage();
}

$itype=SupposeImageType($value);
if($itype)
	header("Content-type: $itype");
else
	return DisplayFile();
echobig($value);
return;
}


?>
