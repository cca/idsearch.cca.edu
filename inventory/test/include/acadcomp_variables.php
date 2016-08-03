<?php

$strTableName="acadcomp";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="acadcomp";

$gPageSize=20;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="SELECT record_id,  campus,  bldg,  floor,  room,  labselect,  mach_type,  platform,  model,  other_model,  asset_tag,  serial,  service_tag,  proc_speed,  proc_type,  ram,  disk_size,  optical_drive,  display_model,  display_size,  display_asset,  display_serial,  notes,  last_updated,  uname,  lname,  dept,  mach_name,  ip_addr,  mac_addr ";
$gsqlFrom="FROM acadcomp ";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "SELECT  record_id,  campus,  bldg,  floor,  room,  labselect,  mach_type,  platform,  model,  other_model,  asset_tag,  serial,  service_tag,  proc_speed,  proc_type,  ram,  disk_size,  optical_drive,  display_model,  display_size,  display_asset,  display_serial,  notes,  last_updated,  uname,  lname,  dept,  mach_name,  ip_addr,  mac_addr  FROM acadcomp  ";
$gstrSQL = gSQLWhere("");

include("acadcomp_settings.php");
include("acadcomp_events.php");
?>