<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0); 

include("include/dbcommon.php");
include("include/acadcomp_variables.php");


//	check if logged in

if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";
$status="";
$message="";
$usermessage="";
$error_happened=false;
$readavalues=false;


$showKeys = array();
$showValues = array();
$showRawValues = array();
$showFields = array();
$showDetailKeys = array();
$IsSaved = false;
$HaveData = true;

if(@$_REQUEST["editType"]=="inline")
	$inlineedit=ADD_INLINE;
elseif(@$_REQUEST["editType"]=="onthefly")
	$inlineedit=ADD_ONTHEFLY;
else
	$inlineedit=ADD_SIMPLE;
$keys=array();
if($inlineedit==ADD_INLINE)
	$templatefile = "acadcomp_inline_add.htm";
else
	$templatefile = "acadcomp_add.htm";

$id=postvalue("id");
	
//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessAdd"))
	BeforeProcessAdd($conn);

include('libs/xtempl.php');
$xt = new Xtempl();

// insert new record if we have to

if(@$_POST["a"]=="added")
{
	$afilename_values=array();
	$avalues=array();
	$files_move=array();
//	processing campus - start
	$value = postvalue("value_campus");
	$type=postvalue("type_campus");
	if (in_assoc_array("type_campus",$_POST) || in_assoc_array("value_campus",$_POST) || in_assoc_array("value_campus",$_FILES))
	{
		$value=prepare_for_db("campus",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["campus"]=$value;
	}
//	processibng campus - end
//	processing bldg - start
	$value = postvalue("value_bldg");
	$type=postvalue("type_bldg");
	if (in_assoc_array("type_bldg",$_POST) || in_assoc_array("value_bldg",$_POST) || in_assoc_array("value_bldg",$_FILES))
	{
		$value=prepare_for_db("bldg",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["bldg"]=$value;
	}
//	processibng bldg - end
//	processing floor - start
	$value = postvalue("value_floor");
	$type=postvalue("type_floor");
	if (in_assoc_array("type_floor",$_POST) || in_assoc_array("value_floor",$_POST) || in_assoc_array("value_floor",$_FILES))
	{
		$value=prepare_for_db("floor",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["floor"]=$value;
	}
//	processibng floor - end
//	processing room - start
	$value = postvalue("value_room");
	$type=postvalue("type_room");
	if (in_assoc_array("type_room",$_POST) || in_assoc_array("value_room",$_POST) || in_assoc_array("value_room",$_FILES))
	{
		$value=prepare_for_db("room",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["room"]=$value;
	}
//	processibng room - end
//	processing labselect - start
	$value = postvalue("value_labselect");
	$type=postvalue("type_labselect");
	if (in_assoc_array("type_labselect",$_POST) || in_assoc_array("value_labselect",$_POST) || in_assoc_array("value_labselect",$_FILES))
	{
		$value=prepare_for_db("labselect",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["labselect"]=$value;
	}
//	processibng labselect - end
//	processing mach_type - start
	$value = postvalue("value_mach_type");
	$type=postvalue("type_mach_type");
	if (in_assoc_array("type_mach_type",$_POST) || in_assoc_array("value_mach_type",$_POST) || in_assoc_array("value_mach_type",$_FILES))
	{
		$value=prepare_for_db("mach_type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["mach_type"]=$value;
	}
//	processibng mach_type - end
//	processing platform - start
	$value = postvalue("value_platform");
	$type=postvalue("type_platform");
	if (in_assoc_array("type_platform",$_POST) || in_assoc_array("value_platform",$_POST) || in_assoc_array("value_platform",$_FILES))
	{
		$value=prepare_for_db("platform",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["platform"]=$value;
	}
//	processibng platform - end
//	processing model - start
	$value = postvalue("value_model");
	$type=postvalue("type_model");
	if (in_assoc_array("type_model",$_POST) || in_assoc_array("value_model",$_POST) || in_assoc_array("value_model",$_FILES))
	{
		$value=prepare_for_db("model",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["model"]=$value;
	}
//	processibng model - end
//	processing other_model - start
	$value = postvalue("value_other_model");
	$type=postvalue("type_other_model");
	if (in_assoc_array("type_other_model",$_POST) || in_assoc_array("value_other_model",$_POST) || in_assoc_array("value_other_model",$_FILES))
	{
		$value=prepare_for_db("other_model",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["other_model"]=$value;
	}
//	processibng other_model - end
//	processing asset_tag - start
	$value = postvalue("value_asset_tag");
	$type=postvalue("type_asset_tag");
	if (in_assoc_array("type_asset_tag",$_POST) || in_assoc_array("value_asset_tag",$_POST) || in_assoc_array("value_asset_tag",$_FILES))
	{
		$value=prepare_for_db("asset_tag",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["asset_tag"]=$value;
	}
//	processibng asset_tag - end
//	processing serial - start
	$value = postvalue("value_serial");
	$type=postvalue("type_serial");
	if (in_assoc_array("type_serial",$_POST) || in_assoc_array("value_serial",$_POST) || in_assoc_array("value_serial",$_FILES))
	{
		$value=prepare_for_db("serial",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["serial"]=$value;
	}
//	processibng serial - end
//	processing service_tag - start
	$value = postvalue("value_service_tag");
	$type=postvalue("type_service_tag");
	if (in_assoc_array("type_service_tag",$_POST) || in_assoc_array("value_service_tag",$_POST) || in_assoc_array("value_service_tag",$_FILES))
	{
		$value=prepare_for_db("service_tag",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["service_tag"]=$value;
	}
//	processibng service_tag - end
//	processing proc_speed - start
	$value = postvalue("value_proc_speed");
	$type=postvalue("type_proc_speed");
	if (in_assoc_array("type_proc_speed",$_POST) || in_assoc_array("value_proc_speed",$_POST) || in_assoc_array("value_proc_speed",$_FILES))
	{
		$value=prepare_for_db("proc_speed",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["proc_speed"]=$value;
	}
//	processibng proc_speed - end
//	processing proc_type - start
	$value = postvalue("value_proc_type");
	$type=postvalue("type_proc_type");
	if (in_assoc_array("type_proc_type",$_POST) || in_assoc_array("value_proc_type",$_POST) || in_assoc_array("value_proc_type",$_FILES))
	{
		$value=prepare_for_db("proc_type",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["proc_type"]=$value;
	}
//	processibng proc_type - end
//	processing ram - start
	$value = postvalue("value_ram");
	$type=postvalue("type_ram");
	if (in_assoc_array("type_ram",$_POST) || in_assoc_array("value_ram",$_POST) || in_assoc_array("value_ram",$_FILES))
	{
		$value=prepare_for_db("ram",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["ram"]=$value;
	}
//	processibng ram - end
//	processing disk_size - start
	$value = postvalue("value_disk_size");
	$type=postvalue("type_disk_size");
	if (in_assoc_array("type_disk_size",$_POST) || in_assoc_array("value_disk_size",$_POST) || in_assoc_array("value_disk_size",$_FILES))
	{
		$value=prepare_for_db("disk_size",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["disk_size"]=$value;
	}
//	processibng disk_size - end
//	processing optical_drive - start
	$value = postvalue("value_optical_drive");
	$type=postvalue("type_optical_drive");
	if (in_assoc_array("type_optical_drive",$_POST) || in_assoc_array("value_optical_drive",$_POST) || in_assoc_array("value_optical_drive",$_FILES))
	{
		$value=prepare_for_db("optical_drive",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["optical_drive"]=$value;
	}
//	processibng optical_drive - end
//	processing display_model - start
	$value = postvalue("value_display_model");
	$type=postvalue("type_display_model");
	if (in_assoc_array("type_display_model",$_POST) || in_assoc_array("value_display_model",$_POST) || in_assoc_array("value_display_model",$_FILES))
	{
		$value=prepare_for_db("display_model",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["display_model"]=$value;
	}
//	processibng display_model - end
//	processing display_size - start
	$value = postvalue("value_display_size");
	$type=postvalue("type_display_size");
	if (in_assoc_array("type_display_size",$_POST) || in_assoc_array("value_display_size",$_POST) || in_assoc_array("value_display_size",$_FILES))
	{
		$value=prepare_for_db("display_size",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["display_size"]=$value;
	}
//	processibng display_size - end
//	processing display_asset - start
	$value = postvalue("value_display_asset");
	$type=postvalue("type_display_asset");
	if (in_assoc_array("type_display_asset",$_POST) || in_assoc_array("value_display_asset",$_POST) || in_assoc_array("value_display_asset",$_FILES))
	{
		$value=prepare_for_db("display_asset",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["display_asset"]=$value;
	}
//	processibng display_asset - end
//	processing display_serial - start
	$value = postvalue("value_display_serial");
	$type=postvalue("type_display_serial");
	if (in_assoc_array("type_display_serial",$_POST) || in_assoc_array("value_display_serial",$_POST) || in_assoc_array("value_display_serial",$_FILES))
	{
		$value=prepare_for_db("display_serial",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["display_serial"]=$value;
	}
//	processibng display_serial - end
//	processing notes - start
	$value = postvalue("value_notes");
	$type=postvalue("type_notes");
	if (in_assoc_array("type_notes",$_POST) || in_assoc_array("value_notes",$_POST) || in_assoc_array("value_notes",$_FILES))
	{
		$value=prepare_for_db("notes",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["notes"]=$value;
	}
//	processibng notes - end
//	processing last_updated - start
	$value = postvalue("value_last_updated");
	$type=postvalue("type_last_updated");
	if (in_assoc_array("type_last_updated",$_POST) || in_assoc_array("value_last_updated",$_POST) || in_assoc_array("value_last_updated",$_FILES))
	{
		$value=prepare_for_db("last_updated",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["last_updated"]=$value;
	}
//	processibng last_updated - end
//	processing uname - start
	$value = postvalue("value_uname");
	$type=postvalue("type_uname");
	if (in_assoc_array("type_uname",$_POST) || in_assoc_array("value_uname",$_POST) || in_assoc_array("value_uname",$_FILES))
	{
		$value=prepare_for_db("uname",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["uname"]=$value;
	}
//	processibng uname - end
//	processing lname - start
	$value = postvalue("value_lname");
	$type=postvalue("type_lname");
	if (in_assoc_array("type_lname",$_POST) || in_assoc_array("value_lname",$_POST) || in_assoc_array("value_lname",$_FILES))
	{
		$value=prepare_for_db("lname",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["lname"]=$value;
	}
//	processibng lname - end
//	processing dept - start
	$value = postvalue("value_dept");
	$type=postvalue("type_dept");
	if (in_assoc_array("type_dept",$_POST) || in_assoc_array("value_dept",$_POST) || in_assoc_array("value_dept",$_FILES))
	{
		$value=prepare_for_db("dept",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["dept"]=$value;
	}
//	processibng dept - end
//	processing mach_name - start
	$value = postvalue("value_mach_name");
	$type=postvalue("type_mach_name");
	if (in_assoc_array("type_mach_name",$_POST) || in_assoc_array("value_mach_name",$_POST) || in_assoc_array("value_mach_name",$_FILES))
	{
		$value=prepare_for_db("mach_name",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["mach_name"]=$value;
	}
//	processibng mach_name - end
//	processing ip_addr - start
	$value = postvalue("value_ip_addr");
	$type=postvalue("type_ip_addr");
	if (in_assoc_array("type_ip_addr",$_POST) || in_assoc_array("value_ip_addr",$_POST) || in_assoc_array("value_ip_addr",$_FILES))
	{
		$value=prepare_for_db("ip_addr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["ip_addr"]=$value;
	}
//	processibng ip_addr - end
//	processing mac_addr - start
	$value = postvalue("value_mac_addr");
	$type=postvalue("type_mac_addr");
	if (in_assoc_array("type_mac_addr",$_POST) || in_assoc_array("value_mac_addr",$_POST) || in_assoc_array("value_mac_addr",$_FILES))
	{
		$value=prepare_for_db("mac_addr",$value,$type);
	}
	else
		$value=false;
	if(!($value===false))
	{


		$avalues["mac_addr"]=$value;
	}
//	processibng mac_addr - end





if($inlineedit==ADD_ONTHEFLY || true)
{
}


//	add filenames to values
	foreach($afilename_values as $akey=>$value)
		$avalues[$akey]=$value;
//	make SQL string
	$strSQL = "insert into ".AddTableWrappers($strOriginalTableName)." ";
	$strFields="(";
	$strValues="(";
	
//	before Add event
	$retval = true;
	if(function_exists("BeforeAdd"))
		$retval=BeforeAdd($avalues,$usermessage,$inlineedit);
	if($retval)
	{
		foreach($avalues as $akey=>$value)
		{
			$strFields.=AddFieldWrappers($akey).", ";
			$strValues.=add_db_quotes($akey,$value).", ";
		}
		if(substr($strFields,-2)==", ")
			$strFields=substr($strFields,0,strlen($strFields)-2);
		if(substr($strValues,-2)==", ")
			$strValues=substr($strValues,0,strlen($strValues)-2);
		$strSQL.=$strFields.") values ".$strValues.")";
		LogInfo($strSQL);
		set_error_handler("add_error_handler");
		db_exec($strSQL,$conn);
		set_error_handler("error_handler");
//	move files
		if(!$error_happened)
		{
			foreach ($files_move as $file)
			{
				move_uploaded_file($file[0],$file[1]);
				if(strtoupper(substr(PHP_OS,0,3))!="WIN")
					@chmod($file[1],0777);
			}
			if ( $inlineedit==ADD_INLINE ) 
			{
				$status="ADDED";
				$message=""."Record was added"."";
				$IsSaved = true;
			} 
			else
				$message="<div class=message><<< "."Record was added"." >>></div>";
			if($usermessage!="")
				$message = $usermessage;
if($inlineedit==ADD_INLINE || $inlineedit==ADD_ONTHEFLY || function_exists("AfterAdd"))
{

	$failed_inline_add = false;
						$keys["record_id"]=mysql_insert_id($conn);
}	

//	after edit event
			if(function_exists("AfterAdd"))
			{
				foreach($keys as $idx=>$val)
					$avalues[$idx]=$val;
				AfterAdd($avalues,$keys,$inlineedit);
			}
		}
	}
	else
	{
		$message = $usermessage;
		$status="DECLINED";
		$readavalues=true;
	}
}

$defvalues=array();


//	copy record
if(array_key_exists("copyid1",$_REQUEST) || array_key_exists("editid1",$_REQUEST))
{
	$copykeys=array();
	if(array_key_exists("copyid1",$_REQUEST))
	{
		$copykeys["record_id"]=postvalue("copyid1");
	}
	else
	{
		$copykeys["record_id"]=postvalue("editid1");
	}
	$strWhere=KeyWhere($copykeys);
	$strSQL = gSQLWhere($strWhere);

	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$defvalues=db_fetch_array($rs);
//	clear key fields
	$defvalues["record_id"]="";
//call CopyOnLoad event
	if(function_exists("CopyOnLoad"))
		CopyOnLoad($defvalues,$strWhere);
}
else if(!count($defvalues))
{
}
if($inlineedit==ADD_ONTHEFLY || true)
{
}
if($readavalues)
{
	$defvalues["campus"]=@$avalues["campus"];
	$defvalues["bldg"]=@$avalues["bldg"];
	$defvalues["floor"]=@$avalues["floor"];
	$defvalues["room"]=@$avalues["room"];
	$defvalues["labselect"]=@$avalues["labselect"];
	$defvalues["mach_type"]=@$avalues["mach_type"];
	$defvalues["platform"]=@$avalues["platform"];
	$defvalues["model"]=@$avalues["model"];
	$defvalues["other_model"]=@$avalues["other_model"];
	$defvalues["asset_tag"]=@$avalues["asset_tag"];
	$defvalues["serial"]=@$avalues["serial"];
	$defvalues["service_tag"]=@$avalues["service_tag"];
	$defvalues["proc_speed"]=@$avalues["proc_speed"];
	$defvalues["proc_type"]=@$avalues["proc_type"];
	$defvalues["ram"]=@$avalues["ram"];
	$defvalues["disk_size"]=@$avalues["disk_size"];
	$defvalues["optical_drive"]=@$avalues["optical_drive"];
	$defvalues["display_model"]=@$avalues["display_model"];
	$defvalues["display_size"]=@$avalues["display_size"];
	$defvalues["display_asset"]=@$avalues["display_asset"];
	$defvalues["display_serial"]=@$avalues["display_serial"];
	$defvalues["notes"]=@$avalues["notes"];
	$defvalues["last_updated"]=@$avalues["last_updated"];
	$defvalues["uname"]=@$avalues["uname"];
	$defvalues["lname"]=@$avalues["lname"];
	$defvalues["dept"]=@$avalues["dept"];
	$defvalues["mach_name"]=@$avalues["mach_name"];
	$defvalues["ip_addr"]=@$avalues["ip_addr"];
	$defvalues["mac_addr"]=@$avalues["mac_addr"];
}

/*
foreach($defvalues as $key=>$value)
	$smarty->assign("value_".GoodFieldName($key),$value);
*/

$linkdata="";
$includes="";
$arr_includes=array();
$bodyonload="";
	
if ( $inlineedit!=ADD_INLINE ) 
{
	//	include files

	//	validation stuff
	$onsubmit="";
	$needvalidate=false;
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/validate.js\"></script>\r\n";
	
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
		$includes.="var TEXT_FIELDS_REQUIRED='".addslashes("The Following fields are Required")."';\r\n";
		$includes.="var TEXT_FIELDS_ZIPCODES='".addslashes("The Following fields must be valid Zipcodes")."';\r\n";
		$includes.="var TEXT_FIELDS_EMAILS='".addslashes("The Following fields must be valid Emails")."';\r\n";
		$includes.="var TEXT_FIELDS_NUMBERS='".addslashes("The Following fields must be Numbers")."';\r\n";
		$includes.="var TEXT_FIELDS_CURRENCY='".addslashes("The Following fields must be currency")."';\r\n";
		$includes.="var TEXT_FIELDS_PHONE='".addslashes("The Following fields must be Phone Numbers")."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD1='".addslashes("The Following fields must be valid Passwords")."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD2='".addslashes("should be at least 4 characters long")."';\r\n";
		$includes.="var TEXT_FIELDS_PASSWORD3='".addslashes("Cannot be 'password'")."';\r\n";
		$includes.="var TEXT_FIELDS_STATE='".addslashes("The Following fields must be State Names")."';\r\n";
		$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
		$includes.="var TEXT_FIELDS_DATE='".addslashes("The Following fields must be valid dates")."';\r\n";
		$includes.="var TEXT_FIELDS_TIME='".addslashes("The Following fields must be valid time in 24-hours format")."';\r\n";
		$includes.="var TEXT_FIELDS_CC='".addslashes("The Following fields must be valid Credit Card Numbers")."';\r\n";
		$includes.="var TEXT_FIELDS_SSN='".addslashes("The Following fields must be Social Security Numbers")."';\r\n";
		$includes.="</script>\r\n";
	}
	else
	{
		$includes.="var TEXT_INLINE_FIELD_REQUIRED='".jsreplace("Required field")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_ZIPCODE='".jsreplace("Field should be a valid zipcode")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_EMAIL='".jsreplace("Field should be a valid email address")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_NUMBER='".jsreplace("Field should be a valid number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CURRENCY='".jsreplace("Field should be a valid currency")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PHONE='".jsreplace("Field should be a valid phone number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD1='".jsreplace("Field can not be 'password'")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_PASSWORD2='".jsreplace("Field should be at least 4 characters long")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_STATE='".jsreplace("Field should be a valid US state name")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_DATE='".jsreplace("Field should be a valid date")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_TIME='".jsreplace("Field should be a valid time in 24-hour format")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_CC='".jsreplace("Field should be a valid credit card number")."';\r\n";
		$includes.="var TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
	}
		  		$validatetype="IsNumeric";
			if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_floor_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_floor','".$validatetype."','Floor');";
				$bodyonload.="define('value_floor','".$validatetype."','".jsreplace("Floor")."');";
			
		}
			$validatetype="";
			$validatetype.="IsRequired";
		if($validatetype)
		{
			$needvalidate=true;
			if($inlineedit==ADD_ONTHEFLY)
				$linkdata.="define_fly('value_last_updated_".$id."','".$validatetype."');";
			else
//				$bodyonload.="define('value_last_updated','".$validatetype."','Last Updated');";
				$bodyonload.="define('value_last_updated','".$validatetype."','".jsreplace("Last Updated")."');";
			
		}

	if($needvalidate)
	{
		if($inlineedit==ADD_ONTHEFLY)
			$onsubmit="return validate_fly(this);";
		else
			$onsubmit="return validate();";
//		$bodyonload="onload=\"".$bodyonload."\"";
	}

	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/onthefly.js\"></script>\r\n";
		if ($useAJAX) 
			$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
		$includes.="<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="<script language=\"JavaScript\">\r\n";
	}
	$includes.="var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
	"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
	"var bLoading=false;\r\n".
	"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
	if ($useAJAX) {
	$includes.="var SUGGEST_TABLE='acadcomp_searchsuggest.php';\r\n";
	}
	if($inlineedit!=ADD_ONTHEFLY)
	{
		$includes.="</script>\r\n";
		if ($useAJAX)
			$includes.="<div id=\"search_suggest\"></div>\r\n";
	}
		//	include datepicker files
	if($inlineedit!=ADD_ONTHEFLY)
		$includes.="<script language=\"JavaScript\" src=\"include/calendar.js\"></script>\r\n";
	else
		$arr_includes[]="include/calendar.js";




	$xt->assign("campus_fieldblock",true);
	$xt->assign("bldg_fieldblock",true);
	$xt->assign("floor_fieldblock",true);
	$xt->assign("room_fieldblock",true);
	$xt->assign("labselect_fieldblock",true);
	$xt->assign("mach_type_fieldblock",true);
	$xt->assign("platform_fieldblock",true);
	$xt->assign("model_fieldblock",true);
	$xt->assign("other_model_fieldblock",true);
	$xt->assign("asset_tag_fieldblock",true);
	$xt->assign("serial_fieldblock",true);
	$xt->assign("service_tag_fieldblock",true);
	$xt->assign("proc_speed_fieldblock",true);
	$xt->assign("proc_type_fieldblock",true);
	$xt->assign("ram_fieldblock",true);
	$xt->assign("disk_size_fieldblock",true);
	$xt->assign("optical_drive_fieldblock",true);
	$xt->assign("display_model_fieldblock",true);
	$xt->assign("display_size_fieldblock",true);
	$xt->assign("display_asset_fieldblock",true);
	$xt->assign("display_serial_fieldblock",true);
	$xt->assign("notes_fieldblock",true);
	$xt->assign("last_updated_fieldblock",true);
	$xt->assign("uname_fieldblock",true);
	$xt->assign("lname_fieldblock",true);
	$xt->assign("dept_fieldblock",true);
	$xt->assign("mach_name_fieldblock",true);
	$xt->assign("ip_addr_fieldblock",true);
	$xt->assign("mac_addr_fieldblock",true);
	
	$body=array();
	$formname="editform";
	if($inlineedit!=ADD_ONTHEFLY)
	{
		if($onsubmit)
			$onsubmit="onsubmit=\"".$onsubmit."\"";
		$body["begin"]=$includes.
		"<form name=\"editform\" encType=\"multipart/form-data\" method=\"post\" action=\"acadcomp_add.php\" ".$onsubmit.">".
		"<input type=hidden name=\"a\" value=\"added\">";
		$xt->assign("backbutton_attrs","onclick=\"window.location.href='acadcomp_list.php?a=return'\"");
		$xt->assign("back_button",true);
	}
	else
	{
		$formname="editform".$id;
		$body["begin"]="<form name=\"editform".$id."\" encType=\"multipart/form-data\" method=\"post\" action=\"acadcomp_add.php\" ".$onsubmit." target=\"flyframe".$id."\">".
		"<input type=hidden name=\"a\" value=\"added\">".
		"<input type=hidden name=\"editType\" value=\"onthefly\">".
		"<input type=hidden name=\"table\" value=\"".postvalue("table")."\">".
		"<input type=hidden name=\"field\" value=\"".postvalue("field")."\">".
		"<input type=hidden name=\"category\" value=\"".postvalue("category")."\">".
		"<input type=hidden name=\"id\" value=\"".$id."\">";
		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".$id."');\"");
//		$xt->assign("cancelbutton_attrs","onclick=\"RemoveFlyDiv('".substr($id,3)."');\"");
		$xt->assign("cancel_button",true);
	}
	$xt->assign("save_button",true);
	$xt->assign("reset_button",true);
}

if($message)
{
	$xt->assign("message_block",true);
	$xt->assign("message",$message);
}
//$xt->assign("status",$status);

$readonlyfields=array();

//	show readonly fields


$record_id= postvalue("recordID");

if ($useAJAX) 
{
	if($inlineedit==ADD_ONTHEFLY)
		$record_id=$id;

	if ( $inlineedit==ADD_INLINE ) 
	{
		$linkdata=str_replace(array("&","<",">"),array("&amp;","&lt;","&gt;"),$linkdata);

	} 
	else 
	{
		$linkdata.="SetToFirstControl('".$formname."');";
		if($inlineedit==ADD_SIMPLE)
		{
			$linkdata = "<script type=\"text/javascript\">\r\n".
			"$(document).ready(function(){ \r\n".
			$linkdata.
			"});</script>";
		}
		else
		{
			$linkdata=$includes."\r\n".$linkdata;
			$includes="var s;";
			foreach($arr_includes as $file)
			{
				$includes.="s = document.createElement('script');s.src = '".$file."';\r\n".
				"document.getElementsByTagName('HEAD')[0].appendChild(s);\r\n";
			}			
			$linkdata=$includes."\r\n".$linkdata;

			if(!@$_POST["a"]=="added")
			{
				$linkdata = str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$linkdata);
				echo $linkdata;
				echo "\n";
			}
			else if(@$_POST["a"]=="added" && ($error_happened || $status=="DECLINED"))
			{
				echo "<textarea id=\"data\">decli";
				echo htmlspecialchars($linkdata);
				echo "</textarea>";
			}

		}
	}
} 
else 
{
}

if($inlineedit!=ADD_ONTHEFLY)
{
	$body["end"]="</form>".$linkdata.
	"<script>".$bodyonload."</script>";
	
	$xt->assign("body",$body);
	$xt->assign("flybody",true);
}
else
{
	$xt->assign("flybody",$body);
	$xt->assign("body",true);
}




if(@$_POST["a"]=="added" && $inlineedit==ADD_ONTHEFLY && !$error_happened && $status!="DECLINED")
{
	$LookupSQL="";
	if($LookupSQL)
		$LookupSQL.=" from ".AddTableWrappers($strOriginalTableName);

	$data=0;
	if(count($keys) && $LookupSQL)
	{
		$where=KeyWhere($keys);
		$LookupSQL.=" where ".$where;
		$rs=db_query($LookupSQL,$conn);
		$data=db_fetch_numarray($rs);
	}
	if(!$data)
	{
		$data=array(@$avalues[$linkfield],@$avalues[$dispfield]);
	}
	echo "<textarea id=\"data\">";
	echo "added";
	print_inline_array($data);
	echo "</textarea>";
	exit();
}


if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{

	//Preparation   view values
	//	get current values and show edit controls

	$data=0;
	if(count($keys))
	{

		$where=KeyWhere($keys);
			$strSQL = gSQLWhere($where);

		LogInfo($strSQL);

		$rs=db_query($strSQL,$conn);
		$data=db_fetch_array($rs);
	}
	if(!$data)
	{
		$data=$avalues;
		$HaveData=false;
	}

	//check if correct values added

	
	
	$showKeys[] = htmlspecialchars($keys["record_id"]);

	$keylink="";
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["record_id"]));

//	foreach Fields as @f filter @f.bListPage order @f.nListPageOrder

	////////////////////////////////////////////
	//	record_id - 
		$value="";
				$value = ProcessLargeText(GetData($data,"record_id", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "record_id";
				$showRawValues[] = substr($data["record_id"],0,100);
	////////////////////////////////////////////
	//	campus - 
		$value="";
				$value = ProcessLargeText(GetData($data,"campus", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "campus";
				$showRawValues[] = substr($data["campus"],0,100);
	////////////////////////////////////////////
	//	bldg - 
		$value="";
				$value = ProcessLargeText(GetData($data,"bldg", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "bldg";
				$showRawValues[] = substr($data["bldg"],0,100);
	////////////////////////////////////////////
	//	floor - 
		$value="";
				$value = ProcessLargeText(GetData($data,"floor", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "floor";
				$showRawValues[] = substr($data["floor"],0,100);
	////////////////////////////////////////////
	//	room - 
		$value="";
				$value = ProcessLargeText(GetData($data,"room", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "room";
				$showRawValues[] = substr($data["room"],0,100);
	////////////////////////////////////////////
	//	labselect - 
		$value="";
				$value = ProcessLargeText(GetData($data,"labselect", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "labselect";
				$showRawValues[] = substr($data["labselect"],0,100);
	////////////////////////////////////////////
	//	mach_type - 
		$value="";
				$value = ProcessLargeText(GetData($data,"mach_type", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "mach_type";
				$showRawValues[] = substr($data["mach_type"],0,100);
	////////////////////////////////////////////
	//	platform - 
		$value="";
				$value = ProcessLargeText(GetData($data,"platform", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "platform";
				$showRawValues[] = substr($data["platform"],0,100);
	////////////////////////////////////////////
	//	model - 
		$value="";
				$value = ProcessLargeText(GetData($data,"model", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "model";
				$showRawValues[] = substr($data["model"],0,100);
	////////////////////////////////////////////
	//	other_model - 
		$value="";
				$value = ProcessLargeText(GetData($data,"other_model", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "other_model";
				$showRawValues[] = substr($data["other_model"],0,100);
	////////////////////////////////////////////
	//	asset_tag - 
		$value="";
				$value = ProcessLargeText(GetData($data,"asset_tag", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "asset_tag";
				$showRawValues[] = substr($data["asset_tag"],0,100);
	////////////////////////////////////////////
	//	serial - 
		$value="";
				$value = ProcessLargeText(GetData($data,"serial", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "serial";
				$showRawValues[] = substr($data["serial"],0,100);
	////////////////////////////////////////////
	//	service_tag - 
		$value="";
				$value = ProcessLargeText(GetData($data,"service_tag", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "service_tag";
				$showRawValues[] = substr($data["service_tag"],0,100);
	////////////////////////////////////////////
	//	proc_speed - 
		$value="";
				$value = ProcessLargeText(GetData($data,"proc_speed", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "proc_speed";
				$showRawValues[] = substr($data["proc_speed"],0,100);
	////////////////////////////////////////////
	//	proc_type - 
		$value="";
				$value = ProcessLargeText(GetData($data,"proc_type", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "proc_type";
				$showRawValues[] = substr($data["proc_type"],0,100);
	////////////////////////////////////////////
	//	ram - 
		$value="";
				$value = ProcessLargeText(GetData($data,"ram", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "ram";
				$showRawValues[] = substr($data["ram"],0,100);
	////////////////////////////////////////////
	//	disk_size - 
		$value="";
				$value = ProcessLargeText(GetData($data,"disk_size", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "disk_size";
				$showRawValues[] = substr($data["disk_size"],0,100);
	////////////////////////////////////////////
	//	optical_drive - 
		$value="";
				$value = ProcessLargeText(GetData($data,"optical_drive", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "optical_drive";
				$showRawValues[] = substr($data["optical_drive"],0,100);
	////////////////////////////////////////////
	//	display_model - 
		$value="";
				$value = ProcessLargeText(GetData($data,"display_model", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "display_model";
				$showRawValues[] = substr($data["display_model"],0,100);
	////////////////////////////////////////////
	//	display_size - 
		$value="";
				$value = ProcessLargeText(GetData($data,"display_size", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "display_size";
				$showRawValues[] = substr($data["display_size"],0,100);
	////////////////////////////////////////////
	//	display_asset - 
		$value="";
				$value = ProcessLargeText(GetData($data,"display_asset", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "display_asset";
				$showRawValues[] = substr($data["display_asset"],0,100);
	////////////////////////////////////////////
	//	display_serial - 
		$value="";
				$value = ProcessLargeText(GetData($data,"display_serial", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "display_serial";
				$showRawValues[] = substr($data["display_serial"],0,100);
	////////////////////////////////////////////
	//	notes - 
		$value="";
				$value = ProcessLargeText(GetData($data,"notes", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "notes";
				$showRawValues[] = substr($data["notes"],0,100);
	////////////////////////////////////////////
	//	last_updated - Short Date
		$value="";
				$value = ProcessLargeText(GetData($data,"last_updated", "Short Date"),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "last_updated";
				$showRawValues[] = substr($data["last_updated"],0,100);
	////////////////////////////////////////////
	//	uname - 
		$value="";
				$value = ProcessLargeText(GetData($data,"uname", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "uname";
				$showRawValues[] = substr($data["uname"],0,100);
	////////////////////////////////////////////
	//	lname - 
		$value="";
				$value = ProcessLargeText(GetData($data,"lname", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "lname";
				$showRawValues[] = substr($data["lname"],0,100);
	////////////////////////////////////////////
	//	dept - 
		$value="";
				$value = ProcessLargeText(GetData($data,"dept", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "dept";
				$showRawValues[] = substr($data["dept"],0,100);
	////////////////////////////////////////////
	//	mach_name - 
		$value="";
				$value = ProcessLargeText(GetData($data,"mach_name", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "mach_name";
				$showRawValues[] = substr($data["mach_name"],0,100);
	////////////////////////////////////////////
	//	ip_addr - 
		$value="";
				$value = ProcessLargeText(GetData($data,"ip_addr", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "ip_addr";
				$showRawValues[] = substr($data["ip_addr"],0,100);
	////////////////////////////////////////////
	//	mac_addr - 
		$value="";
				$value = ProcessLargeText(GetData($data,"mac_addr", ""),"","",MODE_LIST);
		$showValues[] = $value;
		$showFields[] = "mac_addr";
				$showRawValues[] = substr($data["mac_addr"],0,100);
}

if ( @$_POST["a"]=="added" && $inlineedit==ADD_INLINE ) 
{
	echo "<textarea id=\"data\">";
	if($IsSaved && count($showValues))
	{
		if($HaveData)
			echo "saved";
		else
			echo "savnd";
		print_inline_array($showKeys);
		echo "\n";
		print_inline_array($showValues);
		echo "\n";
		print_inline_array($showFields);
		echo "\n";
		print_inline_array($showRawValues);
		echo "\n";
		print_inline_array($showDetailKeys,true);
		echo "\n";
		print_inline_array($showDetailKeys);
		echo "\n";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$usermessage);
	}
	else
	{
		if($status=="DECLINED")
			echo "decli";
		else
			echo "error";
		echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),$message);
	}
	echo "</textarea>";
	exit();
} 

/////////////////////////////////////////////////////////////
//	prepare Edit Controls
/////////////////////////////////////////////////////////////
$control_campus=array();
$control_campus["func"]="xt_buildeditcontrol";
$control_campus["params"] = array();
$control_campus["params"]["field"]="campus";
$control_campus["params"]["value"]=@$defvalues["campus"];
$control_campus["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_campus["params"]["mode"]="inline_add";
else
	$control_campus["params"]["mode"]="add";
$xt->assignbyref("campus_editcontrol",$control_campus);
$control_bldg=array();
$control_bldg["func"]="xt_buildeditcontrol";
$control_bldg["params"] = array();
$control_bldg["params"]["field"]="bldg";
$control_bldg["params"]["value"]=@$defvalues["bldg"];
$control_bldg["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_bldg["params"]["mode"]="inline_add";
else
	$control_bldg["params"]["mode"]="add";
$xt->assignbyref("bldg_editcontrol",$control_bldg);
$control_floor=array();
$control_floor["func"]="xt_buildeditcontrol";
$control_floor["params"] = array();
$control_floor["params"]["field"]="floor";
$control_floor["params"]["value"]=@$defvalues["floor"];
$control_floor["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_floor["params"]["mode"]="inline_add";
else
	$control_floor["params"]["mode"]="add";
$xt->assignbyref("floor_editcontrol",$control_floor);
$control_room=array();
$control_room["func"]="xt_buildeditcontrol";
$control_room["params"] = array();
$control_room["params"]["field"]="room";
$control_room["params"]["value"]=@$defvalues["room"];
$control_room["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_room["params"]["mode"]="inline_add";
else
	$control_room["params"]["mode"]="add";
$xt->assignbyref("room_editcontrol",$control_room);
$control_labselect=array();
$control_labselect["func"]="xt_buildeditcontrol";
$control_labselect["params"] = array();
$control_labselect["params"]["field"]="labselect";
$control_labselect["params"]["value"]=@$defvalues["labselect"];
$control_labselect["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_labselect["params"]["mode"]="inline_add";
else
	$control_labselect["params"]["mode"]="add";
$xt->assignbyref("labselect_editcontrol",$control_labselect);
$control_mach_type=array();
$control_mach_type["func"]="xt_buildeditcontrol";
$control_mach_type["params"] = array();
$control_mach_type["params"]["field"]="mach_type";
$control_mach_type["params"]["value"]=@$defvalues["mach_type"];
$control_mach_type["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_mach_type["params"]["mode"]="inline_add";
else
	$control_mach_type["params"]["mode"]="add";
$xt->assignbyref("mach_type_editcontrol",$control_mach_type);
$control_platform=array();
$control_platform["func"]="xt_buildeditcontrol";
$control_platform["params"] = array();
$control_platform["params"]["field"]="platform";
$control_platform["params"]["value"]=@$defvalues["platform"];
$control_platform["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_platform["params"]["mode"]="inline_add";
else
	$control_platform["params"]["mode"]="add";
$xt->assignbyref("platform_editcontrol",$control_platform);
$control_model=array();
$control_model["func"]="xt_buildeditcontrol";
$control_model["params"] = array();
$control_model["params"]["field"]="model";
$control_model["params"]["value"]=@$defvalues["model"];
$control_model["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_model["params"]["mode"]="inline_add";
else
	$control_model["params"]["mode"]="add";
$xt->assignbyref("model_editcontrol",$control_model);
$control_other_model=array();
$control_other_model["func"]="xt_buildeditcontrol";
$control_other_model["params"] = array();
$control_other_model["params"]["field"]="other_model";
$control_other_model["params"]["value"]=@$defvalues["other_model"];
$control_other_model["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_other_model["params"]["mode"]="inline_add";
else
	$control_other_model["params"]["mode"]="add";
$xt->assignbyref("other_model_editcontrol",$control_other_model);
$control_asset_tag=array();
$control_asset_tag["func"]="xt_buildeditcontrol";
$control_asset_tag["params"] = array();
$control_asset_tag["params"]["field"]="asset_tag";
$control_asset_tag["params"]["value"]=@$defvalues["asset_tag"];
$control_asset_tag["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_asset_tag["params"]["mode"]="inline_add";
else
	$control_asset_tag["params"]["mode"]="add";
$xt->assignbyref("asset_tag_editcontrol",$control_asset_tag);
$control_serial=array();
$control_serial["func"]="xt_buildeditcontrol";
$control_serial["params"] = array();
$control_serial["params"]["field"]="serial";
$control_serial["params"]["value"]=@$defvalues["serial"];
$control_serial["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_serial["params"]["mode"]="inline_add";
else
	$control_serial["params"]["mode"]="add";
$xt->assignbyref("serial_editcontrol",$control_serial);
$control_service_tag=array();
$control_service_tag["func"]="xt_buildeditcontrol";
$control_service_tag["params"] = array();
$control_service_tag["params"]["field"]="service_tag";
$control_service_tag["params"]["value"]=@$defvalues["service_tag"];
$control_service_tag["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_service_tag["params"]["mode"]="inline_add";
else
	$control_service_tag["params"]["mode"]="add";
$xt->assignbyref("service_tag_editcontrol",$control_service_tag);
$control_proc_speed=array();
$control_proc_speed["func"]="xt_buildeditcontrol";
$control_proc_speed["params"] = array();
$control_proc_speed["params"]["field"]="proc_speed";
$control_proc_speed["params"]["value"]=@$defvalues["proc_speed"];
$control_proc_speed["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_proc_speed["params"]["mode"]="inline_add";
else
	$control_proc_speed["params"]["mode"]="add";
$xt->assignbyref("proc_speed_editcontrol",$control_proc_speed);
$control_proc_type=array();
$control_proc_type["func"]="xt_buildeditcontrol";
$control_proc_type["params"] = array();
$control_proc_type["params"]["field"]="proc_type";
$control_proc_type["params"]["value"]=@$defvalues["proc_type"];
$control_proc_type["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_proc_type["params"]["mode"]="inline_add";
else
	$control_proc_type["params"]["mode"]="add";
$xt->assignbyref("proc_type_editcontrol",$control_proc_type);
$control_ram=array();
$control_ram["func"]="xt_buildeditcontrol";
$control_ram["params"] = array();
$control_ram["params"]["field"]="ram";
$control_ram["params"]["value"]=@$defvalues["ram"];
$control_ram["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_ram["params"]["mode"]="inline_add";
else
	$control_ram["params"]["mode"]="add";
$xt->assignbyref("ram_editcontrol",$control_ram);
$control_disk_size=array();
$control_disk_size["func"]="xt_buildeditcontrol";
$control_disk_size["params"] = array();
$control_disk_size["params"]["field"]="disk_size";
$control_disk_size["params"]["value"]=@$defvalues["disk_size"];
$control_disk_size["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_disk_size["params"]["mode"]="inline_add";
else
	$control_disk_size["params"]["mode"]="add";
$xt->assignbyref("disk_size_editcontrol",$control_disk_size);
$control_optical_drive=array();
$control_optical_drive["func"]="xt_buildeditcontrol";
$control_optical_drive["params"] = array();
$control_optical_drive["params"]["field"]="optical_drive";
$control_optical_drive["params"]["value"]=@$defvalues["optical_drive"];
$control_optical_drive["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_optical_drive["params"]["mode"]="inline_add";
else
	$control_optical_drive["params"]["mode"]="add";
$xt->assignbyref("optical_drive_editcontrol",$control_optical_drive);
$control_display_model=array();
$control_display_model["func"]="xt_buildeditcontrol";
$control_display_model["params"] = array();
$control_display_model["params"]["field"]="display_model";
$control_display_model["params"]["value"]=@$defvalues["display_model"];
$control_display_model["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_display_model["params"]["mode"]="inline_add";
else
	$control_display_model["params"]["mode"]="add";
$xt->assignbyref("display_model_editcontrol",$control_display_model);
$control_display_size=array();
$control_display_size["func"]="xt_buildeditcontrol";
$control_display_size["params"] = array();
$control_display_size["params"]["field"]="display_size";
$control_display_size["params"]["value"]=@$defvalues["display_size"];
$control_display_size["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_display_size["params"]["mode"]="inline_add";
else
	$control_display_size["params"]["mode"]="add";
$xt->assignbyref("display_size_editcontrol",$control_display_size);
$control_display_asset=array();
$control_display_asset["func"]="xt_buildeditcontrol";
$control_display_asset["params"] = array();
$control_display_asset["params"]["field"]="display_asset";
$control_display_asset["params"]["value"]=@$defvalues["display_asset"];
$control_display_asset["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_display_asset["params"]["mode"]="inline_add";
else
	$control_display_asset["params"]["mode"]="add";
$xt->assignbyref("display_asset_editcontrol",$control_display_asset);
$control_display_serial=array();
$control_display_serial["func"]="xt_buildeditcontrol";
$control_display_serial["params"] = array();
$control_display_serial["params"]["field"]="display_serial";
$control_display_serial["params"]["value"]=@$defvalues["display_serial"];
$control_display_serial["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_display_serial["params"]["mode"]="inline_add";
else
	$control_display_serial["params"]["mode"]="add";
$xt->assignbyref("display_serial_editcontrol",$control_display_serial);
$control_notes=array();
$control_notes["func"]="xt_buildeditcontrol";
$control_notes["params"] = array();
$control_notes["params"]["field"]="notes";
$control_notes["params"]["value"]=@$defvalues["notes"];
$control_notes["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_notes["params"]["mode"]="inline_add";
else
	$control_notes["params"]["mode"]="add";
$xt->assignbyref("notes_editcontrol",$control_notes);
$control_last_updated=array();
$control_last_updated["func"]="xt_buildeditcontrol";
$control_last_updated["params"] = array();
$control_last_updated["params"]["field"]="last_updated";
$control_last_updated["params"]["value"]=@$defvalues["last_updated"];
$control_last_updated["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_last_updated["params"]["mode"]="inline_add";
else
	$control_last_updated["params"]["mode"]="add";
$xt->assignbyref("last_updated_editcontrol",$control_last_updated);
$control_uname=array();
$control_uname["func"]="xt_buildeditcontrol";
$control_uname["params"] = array();
$control_uname["params"]["field"]="uname";
$control_uname["params"]["value"]=@$defvalues["uname"];
$control_uname["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_uname["params"]["mode"]="inline_add";
else
	$control_uname["params"]["mode"]="add";
$xt->assignbyref("uname_editcontrol",$control_uname);
$control_lname=array();
$control_lname["func"]="xt_buildeditcontrol";
$control_lname["params"] = array();
$control_lname["params"]["field"]="lname";
$control_lname["params"]["value"]=@$defvalues["lname"];
$control_lname["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_lname["params"]["mode"]="inline_add";
else
	$control_lname["params"]["mode"]="add";
$xt->assignbyref("lname_editcontrol",$control_lname);
$control_dept=array();
$control_dept["func"]="xt_buildeditcontrol";
$control_dept["params"] = array();
$control_dept["params"]["field"]="dept";
$control_dept["params"]["value"]=@$defvalues["dept"];
$control_dept["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_dept["params"]["mode"]="inline_add";
else
	$control_dept["params"]["mode"]="add";
$xt->assignbyref("dept_editcontrol",$control_dept);
$control_mach_name=array();
$control_mach_name["func"]="xt_buildeditcontrol";
$control_mach_name["params"] = array();
$control_mach_name["params"]["field"]="mach_name";
$control_mach_name["params"]["value"]=@$defvalues["mach_name"];
$control_mach_name["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_mach_name["params"]["mode"]="inline_add";
else
	$control_mach_name["params"]["mode"]="add";
$xt->assignbyref("mach_name_editcontrol",$control_mach_name);
$control_ip_addr=array();
$control_ip_addr["func"]="xt_buildeditcontrol";
$control_ip_addr["params"] = array();
$control_ip_addr["params"]["field"]="ip_addr";
$control_ip_addr["params"]["value"]=@$defvalues["ip_addr"];
$control_ip_addr["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_ip_addr["params"]["mode"]="inline_add";
else
	$control_ip_addr["params"]["mode"]="add";
$xt->assignbyref("ip_addr_editcontrol",$control_ip_addr);
$control_mac_addr=array();
$control_mac_addr["func"]="xt_buildeditcontrol";
$control_mac_addr["params"] = array();
$control_mac_addr["params"]["field"]="mac_addr";
$control_mac_addr["params"]["value"]=@$defvalues["mac_addr"];
$control_mac_addr["params"]["id"]=$record_id;
if($inlineedit==ADD_INLINE)
	$control_mac_addr["params"]["mode"]="inline_add";
else
	$control_mac_addr["params"]["mode"]="add";
$xt->assignbyref("mac_addr_editcontrol",$control_mac_addr);

$xt->assign("style_block",true);

if(function_exists("BeforeShowAdd"))
	BeforeShowAdd($xt,$templatefile);


if($inlineedit==ADD_ONTHEFLY)
{
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("flybody");
}
else
	$xt->display($templatefile);

function add_error_handler($errno, $errstr, $errfile, $errline)
{
	global $readavalues, $message, $status, $inlineedit, $error_happened;
	if ( $inlineedit!=ADD_SIMPLE ) 
		$message=""."Record was NOT added".". ".$errstr;
	else  
		$message="<div class=message><<< "."Record was NOT added"." >>><br><br>".$errstr."</div>";
	$readavalues=true;
	$error_happened=true;
}
?>
