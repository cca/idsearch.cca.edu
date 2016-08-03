<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/acadcomp_variables.php");




//	check if logged in
if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}

$filename="";	
$message="";

$all=postvalue("all");
$pdf=postvalue("pdf");
$mypage=1;

$id=1;

//connect database
$conn = db_connect();

//	Before Process event
if(function_exists("BeforeProcessView"))
	BeforeProcessView($conn);

$strWhereClause="";
if(!$all)
{
	$keys=array();
	$keys["record_id"]=postvalue("editid1");

//	get current values and show edit controls

	$strWhereClause = KeyWhere($keys);


	$strSQL=gSQLWhere($strWhereClause);
}
else
{
	if ($_SESSION[$strTableName."_SelectedSQL"]!="" && @$_REQUEST["records"]=="") 
	{
		$strSQL = $_SESSION[$strTableName."_SelectedSQL"];
		$strWhereClause=@$_SESSION[$strTableName."_SelectedWhere"];
	}
	else
	{
		$strWhereClause=@$_SESSION[$strTableName."_where"];
		$strSQL=gSQLWhere($strWhereClause);
	}
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);
		$numrows=gSQLRowCount($strWhereClause,0);

}


$strSQLbak = $strSQL;
if(function_exists("BeforeQueryView"))
	BeforeQueryView($strSQL,$strWhereClause);
if($strSQLbak == $strSQL)
	$strSQL=gSQLWhere($strWhereClause);

if(!$all)
{
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
}
else
{
//	 Pagination:

	$nPageSize=0;
	if(@$_REQUEST["records"]=="page" && $numrows)
	{
		$mypage=(integer)@$_SESSION[$strTableName."_pagenumber"];
		$nPageSize=(integer)@$_SESSION[$strTableName."_pagesize"];
		if($numrows<=($mypage-1)*$nPageSize)
			$mypage=ceil($numrows/$nPageSize);
		if(!$nPageSize)
			$nPageSize=$gPageSize;
		if(!$mypage)
			$mypage=1;

		$strSQL.=" limit ".(($mypage-1)*$nPageSize).",".$nPageSize;
	}
	$rs=db_query($strSQL,$conn);
}

$data=db_fetch_array($rs);

include('libs/xtempl.php');
$xt = new Xtempl();

$out="";
$first=true;

$templatefile="";

while($data)
{



	$xt->assign("show_key1", htmlspecialchars(GetData($data,"record_id", "")));

$keylink="";
$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["record_id"]));

////////////////////////////////////////////
//	record_id - 
	$value="";
		$value = ProcessLargeText(GetData($data,"record_id", ""),"","",MODE_VIEW);
	$xt->assign("record_id_value",$value);
	$xt->assign("record_id_fieldblock",true);
////////////////////////////////////////////
//	campus - 
	$value="";
		$value = ProcessLargeText(GetData($data,"campus", ""),"","",MODE_VIEW);
	$xt->assign("campus_value",$value);
	$xt->assign("campus_fieldblock",true);
////////////////////////////////////////////
//	bldg - 
	$value="";
		$value = ProcessLargeText(GetData($data,"bldg", ""),"","",MODE_VIEW);
	$xt->assign("bldg_value",$value);
	$xt->assign("bldg_fieldblock",true);
////////////////////////////////////////////
//	floor - 
	$value="";
		$value = ProcessLargeText(GetData($data,"floor", ""),"","",MODE_VIEW);
	$xt->assign("floor_value",$value);
	$xt->assign("floor_fieldblock",true);
////////////////////////////////////////////
//	room - 
	$value="";
		$value = ProcessLargeText(GetData($data,"room", ""),"","",MODE_VIEW);
	$xt->assign("room_value",$value);
	$xt->assign("room_fieldblock",true);
////////////////////////////////////////////
//	labselect - 
	$value="";
		$value = ProcessLargeText(GetData($data,"labselect", ""),"","",MODE_VIEW);
	$xt->assign("labselect_value",$value);
	$xt->assign("labselect_fieldblock",true);
////////////////////////////////////////////
//	mach_type - 
	$value="";
		$value = ProcessLargeText(GetData($data,"mach_type", ""),"","",MODE_VIEW);
	$xt->assign("mach_type_value",$value);
	$xt->assign("mach_type_fieldblock",true);
////////////////////////////////////////////
//	platform - 
	$value="";
		$value = ProcessLargeText(GetData($data,"platform", ""),"","",MODE_VIEW);
	$xt->assign("platform_value",$value);
	$xt->assign("platform_fieldblock",true);
////////////////////////////////////////////
//	model - 
	$value="";
		$value = ProcessLargeText(GetData($data,"model", ""),"","",MODE_VIEW);
	$xt->assign("model_value",$value);
	$xt->assign("model_fieldblock",true);
////////////////////////////////////////////
//	other_model - 
	$value="";
		$value = ProcessLargeText(GetData($data,"other_model", ""),"","",MODE_VIEW);
	$xt->assign("other_model_value",$value);
	$xt->assign("other_model_fieldblock",true);
////////////////////////////////////////////
//	asset_tag - 
	$value="";
		$value = ProcessLargeText(GetData($data,"asset_tag", ""),"","",MODE_VIEW);
	$xt->assign("asset_tag_value",$value);
	$xt->assign("asset_tag_fieldblock",true);
////////////////////////////////////////////
//	serial - 
	$value="";
		$value = ProcessLargeText(GetData($data,"serial", ""),"","",MODE_VIEW);
	$xt->assign("serial_value",$value);
	$xt->assign("serial_fieldblock",true);
////////////////////////////////////////////
//	service_tag - 
	$value="";
		$value = ProcessLargeText(GetData($data,"service_tag", ""),"","",MODE_VIEW);
	$xt->assign("service_tag_value",$value);
	$xt->assign("service_tag_fieldblock",true);
////////////////////////////////////////////
//	proc_speed - 
	$value="";
		$value = ProcessLargeText(GetData($data,"proc_speed", ""),"","",MODE_VIEW);
	$xt->assign("proc_speed_value",$value);
	$xt->assign("proc_speed_fieldblock",true);
////////////////////////////////////////////
//	proc_type - 
	$value="";
		$value = ProcessLargeText(GetData($data,"proc_type", ""),"","",MODE_VIEW);
	$xt->assign("proc_type_value",$value);
	$xt->assign("proc_type_fieldblock",true);
////////////////////////////////////////////
//	ram - 
	$value="";
		$value = ProcessLargeText(GetData($data,"ram", ""),"","",MODE_VIEW);
	$xt->assign("ram_value",$value);
	$xt->assign("ram_fieldblock",true);
////////////////////////////////////////////
//	disk_size - 
	$value="";
		$value = ProcessLargeText(GetData($data,"disk_size", ""),"","",MODE_VIEW);
	$xt->assign("disk_size_value",$value);
	$xt->assign("disk_size_fieldblock",true);
////////////////////////////////////////////
//	optical_drive - 
	$value="";
		$value = ProcessLargeText(GetData($data,"optical_drive", ""),"","",MODE_VIEW);
	$xt->assign("optical_drive_value",$value);
	$xt->assign("optical_drive_fieldblock",true);
////////////////////////////////////////////
//	display_model - 
	$value="";
		$value = ProcessLargeText(GetData($data,"display_model", ""),"","",MODE_VIEW);
	$xt->assign("display_model_value",$value);
	$xt->assign("display_model_fieldblock",true);
////////////////////////////////////////////
//	display_size - 
	$value="";
		$value = ProcessLargeText(GetData($data,"display_size", ""),"","",MODE_VIEW);
	$xt->assign("display_size_value",$value);
	$xt->assign("display_size_fieldblock",true);
////////////////////////////////////////////
//	display_asset - 
	$value="";
		$value = ProcessLargeText(GetData($data,"display_asset", ""),"","",MODE_VIEW);
	$xt->assign("display_asset_value",$value);
	$xt->assign("display_asset_fieldblock",true);
////////////////////////////////////////////
//	display_serial - 
	$value="";
		$value = ProcessLargeText(GetData($data,"display_serial", ""),"","",MODE_VIEW);
	$xt->assign("display_serial_value",$value);
	$xt->assign("display_serial_fieldblock",true);
////////////////////////////////////////////
//	notes - 
	$value="";
		$value = ProcessLargeText(GetData($data,"notes", ""),"","",MODE_VIEW);
	$xt->assign("notes_value",$value);
	$xt->assign("notes_fieldblock",true);
////////////////////////////////////////////
//	last_updated - Short Date
	$value="";
		$value = ProcessLargeText(GetData($data,"last_updated", "Short Date"),"","",MODE_VIEW);
	$xt->assign("last_updated_value",$value);
	$xt->assign("last_updated_fieldblock",true);
////////////////////////////////////////////
//	uname - 
	$value="";
		$value = ProcessLargeText(GetData($data,"uname", ""),"","",MODE_VIEW);
	$xt->assign("uname_value",$value);
	$xt->assign("uname_fieldblock",true);
////////////////////////////////////////////
//	lname - 
	$value="";
		$value = ProcessLargeText(GetData($data,"lname", ""),"","",MODE_VIEW);
	$xt->assign("lname_value",$value);
	$xt->assign("lname_fieldblock",true);
////////////////////////////////////////////
//	dept - 
	$value="";
		$value = ProcessLargeText(GetData($data,"dept", ""),"","",MODE_VIEW);
	$xt->assign("dept_value",$value);
	$xt->assign("dept_fieldblock",true);
////////////////////////////////////////////
//	mach_name - 
	$value="";
		$value = ProcessLargeText(GetData($data,"mach_name", ""),"","",MODE_VIEW);
	$xt->assign("mach_name_value",$value);
	$xt->assign("mach_name_fieldblock",true);
////////////////////////////////////////////
//	ip_addr - 
	$value="";
		$value = ProcessLargeText(GetData($data,"ip_addr", ""),"","",MODE_VIEW);
	$xt->assign("ip_addr_value",$value);
	$xt->assign("ip_addr_fieldblock",true);
////////////////////////////////////////////
//	mac_addr - 
	$value="";
		$value = ProcessLargeText(GetData($data,"mac_addr", ""),"","",MODE_VIEW);
	$xt->assign("mac_addr_value",$value);
	$xt->assign("mac_addr_fieldblock",true);

$body=array();
$body["begin"]="";

$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("stylefiles_block",true);
if(!$pdf && !$all)
{
	$xt->assign("back_button",true);
	$xt->assign("backbutton_attrs","onclick=\"window.location.href='acadcomp_list.php?a=return'\"");
}

$oldtemplatefile=$templatefile;
$templatefile = "acadcomp_view.htm";
if(!$all)
{
	if(function_exists("BeforeShowView"))
		BeforeShowView($xt,$templatefile,$data);
	if(!$pdf)
		$xt->display($templatefile);
	break;
}

}


?>
