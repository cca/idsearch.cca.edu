<?php 
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
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

$conn=db_connect();
//	Before Process event
if(function_exists("BeforeProcessExport"))
	BeforeProcessExport($conn);

$strWhereClause="";

$options = "1";
if (@$_REQUEST["a"]!="") 
{
	$options = "";
	
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


	$strSQL = gSQLWhere($sWhere);
	$strWhereClause=$sWhere;
	
	$_SESSION[$strTableName."_SelectedSQL"] = $strSQL;
	$_SESSION[$strTableName."_SelectedWhere"] = $sWhere;
}

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


$mypage=1;
if(@$_REQUEST["type"])
{
//	order by
	$strOrderBy=$_SESSION[$strTableName."_order"];
	if(!$strOrderBy)
		$strOrderBy=$gstrOrderBy;
	$strSQL.=" ".trim($strOrderBy);

	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryExport"))
		BeforeQueryExport($strSQL,$strWhereClause,$strOrderBy);
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

	if(!ini_get("safe_mode"))
		set_time_limit(300);
	
	if(@$_REQUEST["type"]=="excel")
		ExportToExcel();
	else if(@$_REQUEST["type"]=="word")
		ExportToWord();
	else if(@$_REQUEST["type"]=="xml")
		ExportToXML();
	else if(@$_REQUEST["type"]=="csv")
		ExportToCSV();
	else if(@$_REQUEST["type"]=="pdf")
		ExportToPDF();

	db_close($conn);
	return;
}

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include('libs/xtempl.php');
$xt = new Xtempl();
if($options)
{
	$xt->assign("rangeheader_block",true);
	$xt->assign("range_block",true);
}
$body=array();
$body["begin"]="<form action=\"acadcomp_export.php\" method=get id=frmexport name=frmexport>";
$body["end"]="</form>";
$xt->assignbyref("body",$body);
$xt->display("acadcomp_export.htm");


function ExportToExcel()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=acadcomp.xls");

	echo "<html>";
	echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
	
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToWord()
{
	global $cCharset;
	header("Content-type: application/vnd.ms-word");
	header("Content-Disposition: attachment;Filename=acadcomp.doc");

	echo "<html>";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
	echo "<body>";
	echo "<table border=1>";

	WriteTableData();

	echo "</table>";
	echo "</body>";
	echo "</html>";
}

function ExportToXML()
{
	global $nPageSize,$rs,$strTableName,$conn;
	header("Content-type: text/xml");
	header("Content-Disposition: attachment;Filename=acadcomp.xml");
	if(!($row=db_fetch_array($rs)))
		return;
	global $cCharset;
	echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
	echo "<table>\r\n";
	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		echo "<row>\r\n";
		$field=htmlspecialchars(XMLNameEncode("record_id"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"record_id",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("campus"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"campus",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("bldg"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"bldg",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("floor"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"floor",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("room"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"room",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("labselect"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"labselect",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("mach_type"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"mach_type",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("platform"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"platform",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("model"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"model",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("other_model"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"other_model",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("asset_tag"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"asset_tag",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("serial"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"serial",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("service_tag"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"service_tag",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("proc_speed"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"proc_speed",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("proc_type"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"proc_type",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("ram"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"ram",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("disk_size"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"disk_size",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("optical_drive"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"optical_drive",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("display_model"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"display_model",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("display_size"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"display_size",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("display_asset"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"display_asset",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("display_serial"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"display_serial",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("notes"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"notes",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("last_updated"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"last_updated",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("uname"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"uname",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("lname"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"lname",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("dept"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"dept",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("mach_name"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"mach_name",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("ip_addr"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"ip_addr",""));
		echo "</".$field.">\r\n";
		$field=htmlspecialchars(XMLNameEncode("mac_addr"));
		echo "<".$field.">";
		echo htmlspecialchars(GetData($row,"mac_addr",""));
		echo "</".$field.">\r\n";
		echo "</row>\r\n";
		$i++;
		$row=db_fetch_array($rs);
	}
	echo "</table>\r\n";
}

function ExportToCSV()
{
	global $rs,$nPageSize,$strTableName,$conn;
	header("Content-type: application/csv");
	header("Content-Disposition: attachment;Filename=acadcomp.csv");

	if(!($row=db_fetch_array($rs)))
		return;

	$totals=array();

	
// write header
	$outstr="";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"record_id\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"campus\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"bldg\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"floor\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"room\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"labselect\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"mach_type\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"platform\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"model\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"other_model\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"asset_tag\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"serial\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"service_tag\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"proc_speed\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"proc_type\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"ram\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"disk_size\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"optical_drive\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"display_model\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"display_size\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"display_asset\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"display_serial\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"notes\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"last_updated\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"uname\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"lname\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"dept\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"mach_name\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"ip_addr\"";
	if($outstr!="")
		$outstr.=",";
	$outstr.= "\"mac_addr\"";
	echo $outstr;
	echo "\r\n";

// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		$outstr="";
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"record_id",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"campus",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"bldg",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"floor",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"room",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"labselect",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"mach_type",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"platform",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"model",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"other_model",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"asset_tag",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"serial",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"service_tag",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"proc_speed",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"proc_type",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"ram",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"disk_size",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"optical_drive",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"display_model",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"display_size",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"display_asset",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"display_serial",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"notes",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="Short Date";
		$outstr.='"'.htmlspecialchars(GetData($row,"last_updated",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"uname",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"lname",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"dept",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"mach_name",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"ip_addr",$format)).'"';
		if($outstr!="")
			$outstr.=",";
			$format="";
		$outstr.='"'.htmlspecialchars(GetData($row,"mac_addr",$format)).'"';
		echo $outstr;
		echo "\r\n";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

//	display totals
	$first=true;

}


function WriteTableData()
{
	global $rs,$nPageSize,$strTableName,$conn;
	if(!($row=db_fetch_array($rs)))
		return;
// write header
	echo "<tr>";
	if($_REQUEST["type"]=="excel")
	{
		echo '<td style="width: 100" x:str>'.PrepareForExcel("record_id").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("campus").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("bldg").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("floor").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("room").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("labselect").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("mach_type").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("platform").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("model").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("other_model").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("asset_tag").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("serial").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("service_tag").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("proc_speed").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("proc_type").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("ram").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("disk_size").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("optical_drive").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("display_model").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("display_size").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("display_asset").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("display_serial").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("notes").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("last_updated").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("uname").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("lname").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("dept").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("mach_name").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("ip_addr").'</td>';
		echo '<td style="width: 100" x:str>'.PrepareForExcel("mac_addr").'</td>';
	}
	else
	{
		echo "<td>record_id</td>";
		echo "<td>campus</td>";
		echo "<td>bldg</td>";
		echo "<td>floor</td>";
		echo "<td>room</td>";
		echo "<td>labselect</td>";
		echo "<td>mach_type</td>";
		echo "<td>platform</td>";
		echo "<td>model</td>";
		echo "<td>other_model</td>";
		echo "<td>asset_tag</td>";
		echo "<td>serial</td>";
		echo "<td>service_tag</td>";
		echo "<td>proc_speed</td>";
		echo "<td>proc_type</td>";
		echo "<td>ram</td>";
		echo "<td>disk_size</td>";
		echo "<td>optical_drive</td>";
		echo "<td>display_model</td>";
		echo "<td>display_size</td>";
		echo "<td>display_asset</td>";
		echo "<td>display_serial</td>";
		echo "<td>notes</td>";
		echo "<td>last_updated</td>";
		echo "<td>uname</td>";
		echo "<td>lname</td>";
		echo "<td>dept</td>";
		echo "<td>mach_name</td>";
		echo "<td>ip_addr</td>";
		echo "<td>mac_addr</td>";
	}
	echo "</tr>";

	$totals=array();
// write data rows
	$iNumberOfRows = 0;
	while((!$nPageSize || $iNumberOfRows<$nPageSize) && $row)
	{
		echo "<tr>";
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"record_id",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"campus",$format));
		else
			echo htmlspecialchars(GetData($row,"campus",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"bldg",$format));
		else
			echo htmlspecialchars(GetData($row,"bldg",$format));
	echo '</td>';
	echo '<td>';

		$format="";
			echo htmlspecialchars(GetData($row,"floor",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"room",$format));
		else
			echo htmlspecialchars(GetData($row,"room",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"labselect",$format));
		else
			echo htmlspecialchars(GetData($row,"labselect",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"mach_type",$format));
		else
			echo htmlspecialchars(GetData($row,"mach_type",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"platform",$format));
		else
			echo htmlspecialchars(GetData($row,"platform",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"model",$format));
		else
			echo htmlspecialchars(GetData($row,"model",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"other_model",$format));
		else
			echo htmlspecialchars(GetData($row,"other_model",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"asset_tag",$format));
		else
			echo htmlspecialchars(GetData($row,"asset_tag",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"serial",$format));
		else
			echo htmlspecialchars(GetData($row,"serial",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"service_tag",$format));
		else
			echo htmlspecialchars(GetData($row,"service_tag",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"proc_speed",$format));
		else
			echo htmlspecialchars(GetData($row,"proc_speed",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"proc_type",$format));
		else
			echo htmlspecialchars(GetData($row,"proc_type",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"ram",$format));
		else
			echo htmlspecialchars(GetData($row,"ram",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"disk_size",$format));
		else
			echo htmlspecialchars(GetData($row,"disk_size",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"optical_drive",$format));
		else
			echo htmlspecialchars(GetData($row,"optical_drive",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"display_model",$format));
		else
			echo htmlspecialchars(GetData($row,"display_model",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"display_size",$format));
		else
			echo htmlspecialchars(GetData($row,"display_size",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"display_asset",$format));
		else
			echo htmlspecialchars(GetData($row,"display_asset",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"display_serial",$format));
		else
			echo htmlspecialchars(GetData($row,"display_serial",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"notes",$format));
		else
			echo htmlspecialchars(GetData($row,"notes",$format));
	echo '</td>';
	echo '<td>';

		$format="Short Date";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"last_updated",$format));
		else
			echo htmlspecialchars(GetData($row,"last_updated",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"uname",$format));
		else
			echo htmlspecialchars(GetData($row,"uname",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"lname",$format));
		else
			echo htmlspecialchars(GetData($row,"lname",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"dept",$format));
		else
			echo htmlspecialchars(GetData($row,"dept",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"mach_name",$format));
		else
			echo htmlspecialchars(GetData($row,"mach_name",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"ip_addr",$format));
		else
			echo htmlspecialchars(GetData($row,"ip_addr",$format));
	echo '</td>';
	if($_REQUEST["type"]=="excel")
		echo '<td x:str>';
	else
		echo '<td>';

		$format="";
			if($_REQUEST["type"]=="excel")
			echo PrepareForExcel(GetData($row,"mac_addr",$format));
		else
			echo htmlspecialchars(GetData($row,"mac_addr",$format));
	echo '</td>';
		echo "</tr>";
		$iNumberOfRows++;
		$row=db_fetch_array($rs);
	}

}

function XMLNameEncode($strValue)
{	
	$search=array(" ","#","'","/","\\","(",")",",","[","]","+","\"","-","_","|","}","{","=");
	return str_replace($search,"",$strValue);
}

function PrepareForExcel($str)
{
	$ret = htmlspecialchars($str);
	if (substr($ret,0,1)== "=") 
		$ret = "&#61;".substr($ret,1);
	return $ret;

}




function ExportToPDF()
{
	global $nPageSize,$rs,$strTableName,$conn;
		global $colwidth,$leftmargin;
	if(!($row=db_fetch_array($rs)))
		return;


	include("libs/fpdf.php");

	class PDF extends FPDF
	{
	//Current column
		var $col=0;
	//Ordinate of column start
		var $y0;
		var $maxheight;

	function AcceptPageBreak()
	{
		global $colwidth,$leftmargin;
		if($this->y0+$this->rowheight>$this->PageBreakTrigger)
			return true;
		$x=$leftmargin;
		if($this->maxheight<$this->PageBreakTrigger-$this->y0)
			$this->maxheight=$this->PageBreakTrigger-$this->y0;
		$this->Rect($x,$this->y0,$colwidth["record_id"],$this->maxheight);
		$x+=$colwidth["record_id"];
		$this->Rect($x,$this->y0,$colwidth["campus"],$this->maxheight);
		$x+=$colwidth["campus"];
		$this->Rect($x,$this->y0,$colwidth["bldg"],$this->maxheight);
		$x+=$colwidth["bldg"];
		$this->Rect($x,$this->y0,$colwidth["floor"],$this->maxheight);
		$x+=$colwidth["floor"];
		$this->Rect($x,$this->y0,$colwidth["room"],$this->maxheight);
		$x+=$colwidth["room"];
		$this->Rect($x,$this->y0,$colwidth["labselect"],$this->maxheight);
		$x+=$colwidth["labselect"];
		$this->Rect($x,$this->y0,$colwidth["mach_type"],$this->maxheight);
		$x+=$colwidth["mach_type"];
		$this->Rect($x,$this->y0,$colwidth["platform"],$this->maxheight);
		$x+=$colwidth["platform"];
		$this->Rect($x,$this->y0,$colwidth["model"],$this->maxheight);
		$x+=$colwidth["model"];
		$this->Rect($x,$this->y0,$colwidth["other_model"],$this->maxheight);
		$x+=$colwidth["other_model"];
		$this->Rect($x,$this->y0,$colwidth["asset_tag"],$this->maxheight);
		$x+=$colwidth["asset_tag"];
		$this->Rect($x,$this->y0,$colwidth["serial"],$this->maxheight);
		$x+=$colwidth["serial"];
		$this->Rect($x,$this->y0,$colwidth["service_tag"],$this->maxheight);
		$x+=$colwidth["service_tag"];
		$this->Rect($x,$this->y0,$colwidth["proc_speed"],$this->maxheight);
		$x+=$colwidth["proc_speed"];
		$this->Rect($x,$this->y0,$colwidth["proc_type"],$this->maxheight);
		$x+=$colwidth["proc_type"];
		$this->Rect($x,$this->y0,$colwidth["ram"],$this->maxheight);
		$x+=$colwidth["ram"];
		$this->Rect($x,$this->y0,$colwidth["disk_size"],$this->maxheight);
		$x+=$colwidth["disk_size"];
		$this->Rect($x,$this->y0,$colwidth["optical_drive"],$this->maxheight);
		$x+=$colwidth["optical_drive"];
		$this->Rect($x,$this->y0,$colwidth["display_model"],$this->maxheight);
		$x+=$colwidth["display_model"];
		$this->Rect($x,$this->y0,$colwidth["display_size"],$this->maxheight);
		$x+=$colwidth["display_size"];
		$this->Rect($x,$this->y0,$colwidth["display_asset"],$this->maxheight);
		$x+=$colwidth["display_asset"];
		$this->Rect($x,$this->y0,$colwidth["display_serial"],$this->maxheight);
		$x+=$colwidth["display_serial"];
		$this->Rect($x,$this->y0,$colwidth["notes"],$this->maxheight);
		$x+=$colwidth["notes"];
		$this->Rect($x,$this->y0,$colwidth["last_updated"],$this->maxheight);
		$x+=$colwidth["last_updated"];
		$this->Rect($x,$this->y0,$colwidth["uname"],$this->maxheight);
		$x+=$colwidth["uname"];
		$this->Rect($x,$this->y0,$colwidth["lname"],$this->maxheight);
		$x+=$colwidth["lname"];
		$this->Rect($x,$this->y0,$colwidth["dept"],$this->maxheight);
		$x+=$colwidth["dept"];
		$this->Rect($x,$this->y0,$colwidth["mach_name"],$this->maxheight);
		$x+=$colwidth["mach_name"];
		$this->Rect($x,$this->y0,$colwidth["ip_addr"],$this->maxheight);
		$x+=$colwidth["ip_addr"];
		$this->Rect($x,$this->y0,$colwidth["mac_addr"],$this->maxheight);
		$x+=$colwidth["mac_addr"];
		$this->maxheight = $this->rowheight;
//	draw frame	
		return true;
	}

	function Header()
	{
		global $colwidth,$leftmargin;
	    //Page header
		$this->SetFillColor(192);
		$this->SetX($leftmargin);
//		$this->Cell($colwidth["record_id"],$this->rowheight,"Record Id",1,0,'C',1);
		$this->Cell($colwidth["record_id"],$this->rowheight,"Record Id",1,0,'C',1);
//		$this->Cell($colwidth["campus"],$this->rowheight,"Campus",1,0,'C',1);
		$this->Cell($colwidth["campus"],$this->rowheight,"Campus",1,0,'C',1);
//		$this->Cell($colwidth["bldg"],$this->rowheight,"Bldg",1,0,'C',1);
		$this->Cell($colwidth["bldg"],$this->rowheight,"Bldg",1,0,'C',1);
//		$this->Cell($colwidth["floor"],$this->rowheight,"Floor",1,0,'C',1);
		$this->Cell($colwidth["floor"],$this->rowheight,"Floor",1,0,'C',1);
//		$this->Cell($colwidth["room"],$this->rowheight,"Room",1,0,'C',1);
		$this->Cell($colwidth["room"],$this->rowheight,"Room",1,0,'C',1);
//		$this->Cell($colwidth["labselect"],$this->rowheight,"Labselect",1,0,'C',1);
		$this->Cell($colwidth["labselect"],$this->rowheight,"Labselect",1,0,'C',1);
//		$this->Cell($colwidth["mach_type"],$this->rowheight,"Mach Type",1,0,'C',1);
		$this->Cell($colwidth["mach_type"],$this->rowheight,"Mach Type",1,0,'C',1);
//		$this->Cell($colwidth["platform"],$this->rowheight,"Platform",1,0,'C',1);
		$this->Cell($colwidth["platform"],$this->rowheight,"Platform",1,0,'C',1);
//		$this->Cell($colwidth["model"],$this->rowheight,"Model",1,0,'C',1);
		$this->Cell($colwidth["model"],$this->rowheight,"Model",1,0,'C',1);
//		$this->Cell($colwidth["other_model"],$this->rowheight,"Other Model",1,0,'C',1);
		$this->Cell($colwidth["other_model"],$this->rowheight,"Other Model",1,0,'C',1);
//		$this->Cell($colwidth["asset_tag"],$this->rowheight,"Asset Tag",1,0,'C',1);
		$this->Cell($colwidth["asset_tag"],$this->rowheight,"Asset Tag",1,0,'C',1);
//		$this->Cell($colwidth["serial"],$this->rowheight,"Serial",1,0,'C',1);
		$this->Cell($colwidth["serial"],$this->rowheight,"Serial",1,0,'C',1);
//		$this->Cell($colwidth["service_tag"],$this->rowheight,"Service Tag",1,0,'C',1);
		$this->Cell($colwidth["service_tag"],$this->rowheight,"Service Tag",1,0,'C',1);
//		$this->Cell($colwidth["proc_speed"],$this->rowheight,"Proc Speed",1,0,'C',1);
		$this->Cell($colwidth["proc_speed"],$this->rowheight,"Proc Speed",1,0,'C',1);
//		$this->Cell($colwidth["proc_type"],$this->rowheight,"Proc Type",1,0,'C',1);
		$this->Cell($colwidth["proc_type"],$this->rowheight,"Proc Type",1,0,'C',1);
//		$this->Cell($colwidth["ram"],$this->rowheight,"Ram",1,0,'C',1);
		$this->Cell($colwidth["ram"],$this->rowheight,"Ram",1,0,'C',1);
//		$this->Cell($colwidth["disk_size"],$this->rowheight,"Disk Size",1,0,'C',1);
		$this->Cell($colwidth["disk_size"],$this->rowheight,"Disk Size",1,0,'C',1);
//		$this->Cell($colwidth["optical_drive"],$this->rowheight,"Optical Drive",1,0,'C',1);
		$this->Cell($colwidth["optical_drive"],$this->rowheight,"Optical Drive",1,0,'C',1);
//		$this->Cell($colwidth["display_model"],$this->rowheight,"Display Model",1,0,'C',1);
		$this->Cell($colwidth["display_model"],$this->rowheight,"Display Model",1,0,'C',1);
//		$this->Cell($colwidth["display_size"],$this->rowheight,"Display Size",1,0,'C',1);
		$this->Cell($colwidth["display_size"],$this->rowheight,"Display Size",1,0,'C',1);
//		$this->Cell($colwidth["display_asset"],$this->rowheight,"Display Asset",1,0,'C',1);
		$this->Cell($colwidth["display_asset"],$this->rowheight,"Display Asset",1,0,'C',1);
//		$this->Cell($colwidth["display_serial"],$this->rowheight,"Display Serial",1,0,'C',1);
		$this->Cell($colwidth["display_serial"],$this->rowheight,"Display Serial",1,0,'C',1);
//		$this->Cell($colwidth["notes"],$this->rowheight,"Notes",1,0,'C',1);
		$this->Cell($colwidth["notes"],$this->rowheight,"Notes",1,0,'C',1);
//		$this->Cell($colwidth["last_updated"],$this->rowheight,"Last Updated",1,0,'C',1);
		$this->Cell($colwidth["last_updated"],$this->rowheight,"Last Updated",1,0,'C',1);
//		$this->Cell($colwidth["uname"],$this->rowheight,"Uname",1,0,'C',1);
		$this->Cell($colwidth["uname"],$this->rowheight,"Uname",1,0,'C',1);
//		$this->Cell($colwidth["lname"],$this->rowheight,"Lname",1,0,'C',1);
		$this->Cell($colwidth["lname"],$this->rowheight,"Lname",1,0,'C',1);
//		$this->Cell($colwidth["dept"],$this->rowheight,"Dept",1,0,'C',1);
		$this->Cell($colwidth["dept"],$this->rowheight,"Dept",1,0,'C',1);
//		$this->Cell($colwidth["mach_name"],$this->rowheight,"Mach Name",1,0,'C',1);
		$this->Cell($colwidth["mach_name"],$this->rowheight,"Mach Name",1,0,'C',1);
//		$this->Cell($colwidth["ip_addr"],$this->rowheight,"Ip Addr",1,0,'C',1);
		$this->Cell($colwidth["ip_addr"],$this->rowheight,"Ip Addr",1,0,'C',1);
//		$this->Cell($colwidth["mac_addr"],$this->rowheight,"Mac Addr",1,0,'C',1);
		$this->Cell($colwidth["mac_addr"],$this->rowheight,"Mac Addr",1,0,'C',1);
		$this->Ln($this->rowheight);
		$this->y0=$this->GetY();
	}

	}

	$pdf=new PDF();

	$leftmargin=5;
	$pagewidth=200;
	$pageheight=290;
	$rowheight=5;


	$defwidth=$pagewidth/30;
	$colwidth=array();
    $colwidth["record_id"]=$defwidth;
    $colwidth["campus"]=$defwidth;
    $colwidth["bldg"]=$defwidth;
    $colwidth["floor"]=$defwidth;
    $colwidth["room"]=$defwidth;
    $colwidth["labselect"]=$defwidth;
    $colwidth["mach_type"]=$defwidth;
    $colwidth["platform"]=$defwidth;
    $colwidth["model"]=$defwidth;
    $colwidth["other_model"]=$defwidth;
    $colwidth["asset_tag"]=$defwidth;
    $colwidth["serial"]=$defwidth;
    $colwidth["service_tag"]=$defwidth;
    $colwidth["proc_speed"]=$defwidth;
    $colwidth["proc_type"]=$defwidth;
    $colwidth["ram"]=$defwidth;
    $colwidth["disk_size"]=$defwidth;
    $colwidth["optical_drive"]=$defwidth;
    $colwidth["display_model"]=$defwidth;
    $colwidth["display_size"]=$defwidth;
    $colwidth["display_asset"]=$defwidth;
    $colwidth["display_serial"]=$defwidth;
    $colwidth["notes"]=$defwidth;
    $colwidth["last_updated"]=$defwidth;
    $colwidth["uname"]=$defwidth;
    $colwidth["lname"]=$defwidth;
    $colwidth["dept"]=$defwidth;
    $colwidth["mach_name"]=$defwidth;
    $colwidth["ip_addr"]=$defwidth;
    $colwidth["mac_addr"]=$defwidth;
	
	$pdf->AddFont('CourierNewPSMT','','courcp1252.php');
	$pdf->rowheight=$rowheight;
	
	$pdf->SetFont('CourierNewPSMT','',8);
	$pdf->AddPage();
	

	$i=0;
	while((!$nPageSize || $i<$nPageSize) && $row)
	{
		$pdf->maxheight=$rowheight;
		$x=$leftmargin;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["record_id"],$rowheight,GetData($row,"record_id",""));
		$x+=$colwidth["record_id"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["campus"],$rowheight,GetData($row,"campus",""));
		$x+=$colwidth["campus"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["bldg"],$rowheight,GetData($row,"bldg",""));
		$x+=$colwidth["bldg"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["floor"],$rowheight,GetData($row,"floor",""));
		$x+=$colwidth["floor"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["room"],$rowheight,GetData($row,"room",""));
		$x+=$colwidth["room"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["labselect"],$rowheight,GetData($row,"labselect",""));
		$x+=$colwidth["labselect"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["mach_type"],$rowheight,GetData($row,"mach_type",""));
		$x+=$colwidth["mach_type"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["platform"],$rowheight,GetData($row,"platform",""));
		$x+=$colwidth["platform"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["model"],$rowheight,GetData($row,"model",""));
		$x+=$colwidth["model"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["other_model"],$rowheight,GetData($row,"other_model",""));
		$x+=$colwidth["other_model"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["asset_tag"],$rowheight,GetData($row,"asset_tag",""));
		$x+=$colwidth["asset_tag"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["serial"],$rowheight,GetData($row,"serial",""));
		$x+=$colwidth["serial"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["service_tag"],$rowheight,GetData($row,"service_tag",""));
		$x+=$colwidth["service_tag"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["proc_speed"],$rowheight,GetData($row,"proc_speed",""));
		$x+=$colwidth["proc_speed"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["proc_type"],$rowheight,GetData($row,"proc_type",""));
		$x+=$colwidth["proc_type"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["ram"],$rowheight,GetData($row,"ram",""));
		$x+=$colwidth["ram"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["disk_size"],$rowheight,GetData($row,"disk_size",""));
		$x+=$colwidth["disk_size"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["optical_drive"],$rowheight,GetData($row,"optical_drive",""));
		$x+=$colwidth["optical_drive"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["display_model"],$rowheight,GetData($row,"display_model",""));
		$x+=$colwidth["display_model"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["display_size"],$rowheight,GetData($row,"display_size",""));
		$x+=$colwidth["display_size"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["display_asset"],$rowheight,GetData($row,"display_asset",""));
		$x+=$colwidth["display_asset"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["display_serial"],$rowheight,GetData($row,"display_serial",""));
		$x+=$colwidth["display_serial"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["notes"],$rowheight,GetData($row,"notes",""));
		$x+=$colwidth["notes"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["last_updated"],$rowheight,GetData($row,"last_updated","Short Date"));
		$x+=$colwidth["last_updated"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["uname"],$rowheight,GetData($row,"uname",""));
		$x+=$colwidth["uname"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["lname"],$rowheight,GetData($row,"lname",""));
		$x+=$colwidth["lname"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["dept"],$rowheight,GetData($row,"dept",""));
		$x+=$colwidth["dept"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["mach_name"],$rowheight,GetData($row,"mach_name",""));
		$x+=$colwidth["mach_name"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["ip_addr"],$rowheight,GetData($row,"ip_addr",""));
		$x+=$colwidth["ip_addr"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
		$pdf->SetY($pdf->y0);
		$pdf->SetX($x);
		$pdf->MultiCell($colwidth["mac_addr"],$rowheight,GetData($row,"mac_addr",""));
		$x+=$colwidth["mac_addr"];
		if($pdf->GetY()-$pdf->y0>$pdf->maxheight)
			$pdf->maxheight=$pdf->GetY()-$pdf->y0;
//	draw fames
		$x=$leftmargin;
		$pdf->Rect($x,$pdf->y0,$colwidth["record_id"],$pdf->maxheight);
		$x+=$colwidth["record_id"];
		$pdf->Rect($x,$pdf->y0,$colwidth["campus"],$pdf->maxheight);
		$x+=$colwidth["campus"];
		$pdf->Rect($x,$pdf->y0,$colwidth["bldg"],$pdf->maxheight);
		$x+=$colwidth["bldg"];
		$pdf->Rect($x,$pdf->y0,$colwidth["floor"],$pdf->maxheight);
		$x+=$colwidth["floor"];
		$pdf->Rect($x,$pdf->y0,$colwidth["room"],$pdf->maxheight);
		$x+=$colwidth["room"];
		$pdf->Rect($x,$pdf->y0,$colwidth["labselect"],$pdf->maxheight);
		$x+=$colwidth["labselect"];
		$pdf->Rect($x,$pdf->y0,$colwidth["mach_type"],$pdf->maxheight);
		$x+=$colwidth["mach_type"];
		$pdf->Rect($x,$pdf->y0,$colwidth["platform"],$pdf->maxheight);
		$x+=$colwidth["platform"];
		$pdf->Rect($x,$pdf->y0,$colwidth["model"],$pdf->maxheight);
		$x+=$colwidth["model"];
		$pdf->Rect($x,$pdf->y0,$colwidth["other_model"],$pdf->maxheight);
		$x+=$colwidth["other_model"];
		$pdf->Rect($x,$pdf->y0,$colwidth["asset_tag"],$pdf->maxheight);
		$x+=$colwidth["asset_tag"];
		$pdf->Rect($x,$pdf->y0,$colwidth["serial"],$pdf->maxheight);
		$x+=$colwidth["serial"];
		$pdf->Rect($x,$pdf->y0,$colwidth["service_tag"],$pdf->maxheight);
		$x+=$colwidth["service_tag"];
		$pdf->Rect($x,$pdf->y0,$colwidth["proc_speed"],$pdf->maxheight);
		$x+=$colwidth["proc_speed"];
		$pdf->Rect($x,$pdf->y0,$colwidth["proc_type"],$pdf->maxheight);
		$x+=$colwidth["proc_type"];
		$pdf->Rect($x,$pdf->y0,$colwidth["ram"],$pdf->maxheight);
		$x+=$colwidth["ram"];
		$pdf->Rect($x,$pdf->y0,$colwidth["disk_size"],$pdf->maxheight);
		$x+=$colwidth["disk_size"];
		$pdf->Rect($x,$pdf->y0,$colwidth["optical_drive"],$pdf->maxheight);
		$x+=$colwidth["optical_drive"];
		$pdf->Rect($x,$pdf->y0,$colwidth["display_model"],$pdf->maxheight);
		$x+=$colwidth["display_model"];
		$pdf->Rect($x,$pdf->y0,$colwidth["display_size"],$pdf->maxheight);
		$x+=$colwidth["display_size"];
		$pdf->Rect($x,$pdf->y0,$colwidth["display_asset"],$pdf->maxheight);
		$x+=$colwidth["display_asset"];
		$pdf->Rect($x,$pdf->y0,$colwidth["display_serial"],$pdf->maxheight);
		$x+=$colwidth["display_serial"];
		$pdf->Rect($x,$pdf->y0,$colwidth["notes"],$pdf->maxheight);
		$x+=$colwidth["notes"];
		$pdf->Rect($x,$pdf->y0,$colwidth["last_updated"],$pdf->maxheight);
		$x+=$colwidth["last_updated"];
		$pdf->Rect($x,$pdf->y0,$colwidth["uname"],$pdf->maxheight);
		$x+=$colwidth["uname"];
		$pdf->Rect($x,$pdf->y0,$colwidth["lname"],$pdf->maxheight);
		$x+=$colwidth["lname"];
		$pdf->Rect($x,$pdf->y0,$colwidth["dept"],$pdf->maxheight);
		$x+=$colwidth["dept"];
		$pdf->Rect($x,$pdf->y0,$colwidth["mach_name"],$pdf->maxheight);
		$x+=$colwidth["mach_name"];
		$pdf->Rect($x,$pdf->y0,$colwidth["ip_addr"],$pdf->maxheight);
		$x+=$colwidth["ip_addr"];
		$pdf->Rect($x,$pdf->y0,$colwidth["mac_addr"],$pdf->maxheight);
		$x+=$colwidth["mac_addr"];
		$pdf->y0+=$pdf->maxheight;
		$i++;
		$row=db_fetch_array($rs);
	}
	$pdf->Output();
}

?>