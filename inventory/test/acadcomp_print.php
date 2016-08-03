<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/acadcomp_variables.php");

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Export"))
{
	echo "<p>"."You don't have permissions to access this table"."<a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}

$all=postvalue("all");

include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();

//	Before Process event
if(function_exists("BeforeProcessPrint"))
	BeforeProcessPrint($conn);

$strWhereClause="";

if (@$_REQUEST["a"]!="") 
{
	
	$sWhere = "1=0";	
	
//	process selection
	$selected_recs=array();
	if (@$_REQUEST["mdelete"])
	{
		foreach(@$_REQUEST["mdelete"] as $ind)
		{
			$keys=array();
			$keys["record_id"]=refine($_REQUEST["mdelete1"][$ind-1]);
			$selected_recs[]=$keys;
		}
	}
	elseif(@$_REQUEST["selection"])
	{
		foreach(@$_REQUEST["selection"] as $keyblock)
		{
			$arr=split("&",refine($keyblock));
			if(count($arr)<1)
				continue;
			$keys=array();
			$keys["record_id"]=urldecode($arr[0]);
			$selected_recs[]=$keys;
		}
	}

	foreach($selected_recs as $keys)
	{
		$sWhere = $sWhere . " or ";
		$sWhere.=KeyWhere($keys);
	}
//	$strSQL = AddWhere($gstrSQL,$sWhere);
	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
}
else
{
	$strWhereClause=@$_SESSION[$strTableName."_where"];
	$strSQL = gSQLWhere($strWhereClause);
}
if(postvalue("pdf"))
	$strWhereClause = @$_SESSION[$strTableName."_pdfwhere"];

$_SESSION[$strTableName."_pdfwhere"] = $strWhereClause;


$strOrderBy=$_SESSION[$strTableName."_order"];
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;
$strSQL.=" ".trim($strOrderBy);

$strSQLbak = $strSQL;
if(function_exists("BeforeQueryPrint"))
	BeforeQueryPrint($strSQL,$strWhereClause,$strOrderBy);

//	Rebuild SQL if needed
if($strSQL!=$strSQLbak)
{
//	changed $strSQL - old style	
	$numrows=GetRowCount($strSQL);
}
else
{
	$strSQL = gSQLWhere($strWhereClause);
	$strSQL.=" ".trim($strOrderBy);
	$numrows=gSQLRowCount($strWhereClause,0);
}

LogInfo($strSQL);

$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;

$recno=1;
$records=0;	
$pageindex=1;

if(!$all)
{	
	if($numrows)
	{
		$maxRecords = $numrows;
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);
	
	
	//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
		$recordsonpage=$PageSize;
		
}
else
{
	$rs=db_query($strSQL,$conn);
	$recordsonpage = $numrows;
	$maxpages=ceil($recordsonpage/30);
	$xt->assign("page_number",true);
}

$colsonpage=1;
if($colsonpage>$recordsonpage)
	$colsonpage=$recordsonpage;
if($colsonpage<1)
	$colsonpage=1;


//	fill $rowinfo array
	$pages = array();
	$rowinfo = array();
	$rowinfo["data"]=array();

	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowPrint"))
		{
			if(!BeforeProcessRowPrint($data))
				continue;
		}
		break;
	}

	while($data && ($all || $recno<=$PageSize))
	{
		$row=array();
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		for($col=1;$data && ($all || $recno<=$PageSize) && $col<=1;$col++)
		{
			$record=array();
			$recno++;
			$records++;
			$keylink="";
			$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["record_id"]));


//	record_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"record_id", ""),"field=record%5Fid".$keylink,"",MODE_PRINT);
			$record["record_id_value"]=$value;

//	campus - 
			$value="";
				$value = ProcessLargeText(GetData($data,"campus", ""),"field=campus".$keylink,"",MODE_PRINT);
			$record["campus_value"]=$value;

//	bldg - 
			$value="";
				$value = ProcessLargeText(GetData($data,"bldg", ""),"field=bldg".$keylink,"",MODE_PRINT);
			$record["bldg_value"]=$value;

//	floor - 
			$value="";
				$value = ProcessLargeText(GetData($data,"floor", ""),"field=floor".$keylink,"",MODE_PRINT);
			$record["floor_value"]=$value;

//	room - 
			$value="";
				$value = ProcessLargeText(GetData($data,"room", ""),"field=room".$keylink,"",MODE_PRINT);
			$record["room_value"]=$value;

//	labselect - 
			$value="";
				$value = ProcessLargeText(GetData($data,"labselect", ""),"field=labselect".$keylink,"",MODE_PRINT);
			$record["labselect_value"]=$value;

//	mach_type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mach_type", ""),"field=mach%5Ftype".$keylink,"",MODE_PRINT);
			$record["mach_type_value"]=$value;

//	platform - 
			$value="";
				$value = ProcessLargeText(GetData($data,"platform", ""),"field=platform".$keylink,"",MODE_PRINT);
			$record["platform_value"]=$value;

//	model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"model", ""),"field=model".$keylink,"",MODE_PRINT);
			$record["model_value"]=$value;

//	other_model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"other_model", ""),"field=other%5Fmodel".$keylink,"",MODE_PRINT);
			$record["other_model_value"]=$value;

//	asset_tag - 
			$value="";
				$value = ProcessLargeText(GetData($data,"asset_tag", ""),"field=asset%5Ftag".$keylink,"",MODE_PRINT);
			$record["asset_tag_value"]=$value;

//	serial - 
			$value="";
				$value = ProcessLargeText(GetData($data,"serial", ""),"field=serial".$keylink,"",MODE_PRINT);
			$record["serial_value"]=$value;

//	service_tag - 
			$value="";
				$value = ProcessLargeText(GetData($data,"service_tag", ""),"field=service%5Ftag".$keylink,"",MODE_PRINT);
			$record["service_tag_value"]=$value;

//	proc_speed - 
			$value="";
				$value = ProcessLargeText(GetData($data,"proc_speed", ""),"field=proc%5Fspeed".$keylink,"",MODE_PRINT);
			$record["proc_speed_value"]=$value;

//	proc_type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"proc_type", ""),"field=proc%5Ftype".$keylink,"",MODE_PRINT);
			$record["proc_type_value"]=$value;

//	ram - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ram", ""),"field=ram".$keylink,"",MODE_PRINT);
			$record["ram_value"]=$value;

//	disk_size - 
			$value="";
				$value = ProcessLargeText(GetData($data,"disk_size", ""),"field=disk%5Fsize".$keylink,"",MODE_PRINT);
			$record["disk_size_value"]=$value;

//	optical_drive - 
			$value="";
				$value = ProcessLargeText(GetData($data,"optical_drive", ""),"field=optical%5Fdrive".$keylink,"",MODE_PRINT);
			$record["optical_drive_value"]=$value;

//	display_model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_model", ""),"field=display%5Fmodel".$keylink,"",MODE_PRINT);
			$record["display_model_value"]=$value;

//	display_size - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_size", ""),"field=display%5Fsize".$keylink,"",MODE_PRINT);
			$record["display_size_value"]=$value;

//	display_asset - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_asset", ""),"field=display%5Fasset".$keylink,"",MODE_PRINT);
			$record["display_asset_value"]=$value;

//	display_serial - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_serial", ""),"field=display%5Fserial".$keylink,"",MODE_PRINT);
			$record["display_serial_value"]=$value;

//	notes - 
			$value="";
				$value = ProcessLargeText(GetData($data,"notes", ""),"field=notes".$keylink,"",MODE_PRINT);
			$record["notes_value"]=$value;

//	last_updated - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"last_updated", "Short Date"),"field=last%5Fupdated".$keylink,"",MODE_PRINT);
			$record["last_updated_value"]=$value;

//	uname - 
			$value="";
				$value = ProcessLargeText(GetData($data,"uname", ""),"field=uname".$keylink,"",MODE_PRINT);
			$record["uname_value"]=$value;

//	lname - 
			$value="";
				$value = ProcessLargeText(GetData($data,"lname", ""),"field=lname".$keylink,"",MODE_PRINT);
			$record["lname_value"]=$value;

//	dept - 
			$value="";
				$value = ProcessLargeText(GetData($data,"dept", ""),"field=dept".$keylink,"",MODE_PRINT);
			$record["dept_value"]=$value;

//	mach_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mach_name", ""),"field=mach%5Fname".$keylink,"",MODE_PRINT);
			$record["mach_name_value"]=$value;

//	ip_addr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ip_addr", ""),"field=ip%5Faddr".$keylink,"",MODE_PRINT);
			$record["ip_addr_value"]=$value;

//	mac_addr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mac_addr", ""),"field=mac%5Faddr".$keylink,"",MODE_PRINT);
			$record["mac_addr_value"]=$value;
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			
			if(function_exists("BeforeMoveNextPrint"))
				BeforeMoveNextPrint($data,$row,$col);
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowPrint"))
				{
					if(!BeforeProcessRowPrint($data))
						continue;
				}
				break;
			}
		}
		if($col<=$colsonpage)
		{
			$row["grid_record"]["data"][count($row["grid_record"]["data"])-1]["endrecord_block"]=false;
		}
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
		
		if($all && $records>=30)
		{
			$page=array("grid_row" =>$rowinfo);
			$page["page"]=$pageindex;
			$pageindex++;
			$pages[] = $page;
			$records=0;
			$rowinfo=array();
		}
		
	}
	if(count($rowinfo))
	{
		$page=array("grid_row" =>$rowinfo);
		$page["page"]=$pageindex;
		$pages[] = $page;
	}
	
	for($i=0;$i<count($pages);$i++)
	{
	 	if($i<count($pages)-1)
			$pages[$i]["begin"]="<div name=page class=printpage>";
		else
		    $pages[$i]["begin"]="<div name=page>";
			
			$pages[$i]["maxpages"]=$maxpages;	
		$pages[$i]["end"]="</div>";
	}

	if(count($pages))
	{
		$pages[count($pages)-1]["totals_row"]=true;
	}
	$page=array("data"=>&$pages);
	$xt->assignbyref("page",$page);


	

$strSQL=$_SESSION[$strTableName."_sql"];

	
$body=array();
$xt->assignbyref("body",$body);
$xt->assign("grid_block",true);

$xt->assign("record_id_fieldheadercolumn",true);
$xt->assign("record_id_fieldheader",true);
$xt->assign("record_id_fieldcolumn",true);
$xt->assign("record_id_fieldfootercolumn",true);
$xt->assign("campus_fieldheadercolumn",true);
$xt->assign("campus_fieldheader",true);
$xt->assign("campus_fieldcolumn",true);
$xt->assign("campus_fieldfootercolumn",true);
$xt->assign("bldg_fieldheadercolumn",true);
$xt->assign("bldg_fieldheader",true);
$xt->assign("bldg_fieldcolumn",true);
$xt->assign("bldg_fieldfootercolumn",true);
$xt->assign("floor_fieldheadercolumn",true);
$xt->assign("floor_fieldheader",true);
$xt->assign("floor_fieldcolumn",true);
$xt->assign("floor_fieldfootercolumn",true);
$xt->assign("room_fieldheadercolumn",true);
$xt->assign("room_fieldheader",true);
$xt->assign("room_fieldcolumn",true);
$xt->assign("room_fieldfootercolumn",true);
$xt->assign("labselect_fieldheadercolumn",true);
$xt->assign("labselect_fieldheader",true);
$xt->assign("labselect_fieldcolumn",true);
$xt->assign("labselect_fieldfootercolumn",true);
$xt->assign("mach_type_fieldheadercolumn",true);
$xt->assign("mach_type_fieldheader",true);
$xt->assign("mach_type_fieldcolumn",true);
$xt->assign("mach_type_fieldfootercolumn",true);
$xt->assign("platform_fieldheadercolumn",true);
$xt->assign("platform_fieldheader",true);
$xt->assign("platform_fieldcolumn",true);
$xt->assign("platform_fieldfootercolumn",true);
$xt->assign("model_fieldheadercolumn",true);
$xt->assign("model_fieldheader",true);
$xt->assign("model_fieldcolumn",true);
$xt->assign("model_fieldfootercolumn",true);
$xt->assign("other_model_fieldheadercolumn",true);
$xt->assign("other_model_fieldheader",true);
$xt->assign("other_model_fieldcolumn",true);
$xt->assign("other_model_fieldfootercolumn",true);
$xt->assign("asset_tag_fieldheadercolumn",true);
$xt->assign("asset_tag_fieldheader",true);
$xt->assign("asset_tag_fieldcolumn",true);
$xt->assign("asset_tag_fieldfootercolumn",true);
$xt->assign("serial_fieldheadercolumn",true);
$xt->assign("serial_fieldheader",true);
$xt->assign("serial_fieldcolumn",true);
$xt->assign("serial_fieldfootercolumn",true);
$xt->assign("service_tag_fieldheadercolumn",true);
$xt->assign("service_tag_fieldheader",true);
$xt->assign("service_tag_fieldcolumn",true);
$xt->assign("service_tag_fieldfootercolumn",true);
$xt->assign("proc_speed_fieldheadercolumn",true);
$xt->assign("proc_speed_fieldheader",true);
$xt->assign("proc_speed_fieldcolumn",true);
$xt->assign("proc_speed_fieldfootercolumn",true);
$xt->assign("proc_type_fieldheadercolumn",true);
$xt->assign("proc_type_fieldheader",true);
$xt->assign("proc_type_fieldcolumn",true);
$xt->assign("proc_type_fieldfootercolumn",true);
$xt->assign("ram_fieldheadercolumn",true);
$xt->assign("ram_fieldheader",true);
$xt->assign("ram_fieldcolumn",true);
$xt->assign("ram_fieldfootercolumn",true);
$xt->assign("disk_size_fieldheadercolumn",true);
$xt->assign("disk_size_fieldheader",true);
$xt->assign("disk_size_fieldcolumn",true);
$xt->assign("disk_size_fieldfootercolumn",true);
$xt->assign("optical_drive_fieldheadercolumn",true);
$xt->assign("optical_drive_fieldheader",true);
$xt->assign("optical_drive_fieldcolumn",true);
$xt->assign("optical_drive_fieldfootercolumn",true);
$xt->assign("display_model_fieldheadercolumn",true);
$xt->assign("display_model_fieldheader",true);
$xt->assign("display_model_fieldcolumn",true);
$xt->assign("display_model_fieldfootercolumn",true);
$xt->assign("display_size_fieldheadercolumn",true);
$xt->assign("display_size_fieldheader",true);
$xt->assign("display_size_fieldcolumn",true);
$xt->assign("display_size_fieldfootercolumn",true);
$xt->assign("display_asset_fieldheadercolumn",true);
$xt->assign("display_asset_fieldheader",true);
$xt->assign("display_asset_fieldcolumn",true);
$xt->assign("display_asset_fieldfootercolumn",true);
$xt->assign("display_serial_fieldheadercolumn",true);
$xt->assign("display_serial_fieldheader",true);
$xt->assign("display_serial_fieldcolumn",true);
$xt->assign("display_serial_fieldfootercolumn",true);
$xt->assign("notes_fieldheadercolumn",true);
$xt->assign("notes_fieldheader",true);
$xt->assign("notes_fieldcolumn",true);
$xt->assign("notes_fieldfootercolumn",true);
$xt->assign("last_updated_fieldheadercolumn",true);
$xt->assign("last_updated_fieldheader",true);
$xt->assign("last_updated_fieldcolumn",true);
$xt->assign("last_updated_fieldfootercolumn",true);
$xt->assign("uname_fieldheadercolumn",true);
$xt->assign("uname_fieldheader",true);
$xt->assign("uname_fieldcolumn",true);
$xt->assign("uname_fieldfootercolumn",true);
$xt->assign("lname_fieldheadercolumn",true);
$xt->assign("lname_fieldheader",true);
$xt->assign("lname_fieldcolumn",true);
$xt->assign("lname_fieldfootercolumn",true);
$xt->assign("dept_fieldheadercolumn",true);
$xt->assign("dept_fieldheader",true);
$xt->assign("dept_fieldcolumn",true);
$xt->assign("dept_fieldfootercolumn",true);
$xt->assign("mach_name_fieldheadercolumn",true);
$xt->assign("mach_name_fieldheader",true);
$xt->assign("mach_name_fieldcolumn",true);
$xt->assign("mach_name_fieldfootercolumn",true);
$xt->assign("ip_addr_fieldheadercolumn",true);
$xt->assign("ip_addr_fieldheader",true);
$xt->assign("ip_addr_fieldcolumn",true);
$xt->assign("ip_addr_fieldfootercolumn",true);
$xt->assign("mac_addr_fieldheadercolumn",true);
$xt->assign("mac_addr_fieldheader",true);
$xt->assign("mac_addr_fieldcolumn",true);
$xt->assign("mac_addr_fieldfootercolumn",true);

	$record_header=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
		}
		$record_header["data"][]=$rheader;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);


$templatefile = "acadcomp_print.htm";
	
if(function_exists("BeforeShowPrint"))
	BeforeShowPrint($xt,$templatefile);

if(!postvalue("pdf"))
	$xt->display($templatefile);
else
{

	$xt->load_template($templatefile);
	$page = $xt->fetch_loaded();
	$pagewidth=postvalue("width")*1.05;
	$pageheight=postvalue("height")*1.05;
	$landscape=false;
	if(postvalue("all"))
	{
		if($pagewidth>$pageheight)
		{
			$landscape=true;
			if($pagewidth/$pageheight<297/210)
				$pagewidth = 297/210*$pageheight;
		}
		else
		{
			if($pagewidth/$pageheight<210/297)
				$pagewidth = 210/297*$pageheight;
		}
	}
	include("plugins/page2pdf.php");
}

