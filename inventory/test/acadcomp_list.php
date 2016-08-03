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
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add"))
{
	echo "<p>"."You don't have permissions to access this table"." <a href=\"login.php\">"."Back to login page"."</a></p>";
	exit();
}


include('libs/xtempl.php');
$xt = new Xtempl();

$conn=db_connect();


//	process reqest data, fill session variables

$mode=LIST_SIMPLE;
if(postvalue("mode")=="lookup")
	$mode=LIST_LOOKUP;
$id=postvalue("id");
$xt->assign("id",$id);

if($mode==LIST_LOOKUP)
{	
	$lookupwhere="";
	$categoryfield="";
	$linkfield="";
	$lookupfield=postvalue("field");
	$lookupcontrol=postvalue("control");
	$lookupcategory=postvalue("category");
	$lookuptable=postvalue("table");
	$lookupparams="mode=lookup&id=".$id."&field=".rawurlencode($lookupfield)
		."&control=".rawurlencode($lookupcontrol)."&category=".rawurlencode($lookupcategory)
		."&table=".rawurlencode($lookuptable);
//	determine which field should be used to select values
	$lookupSelectField="";
	$lookupSelectField="record_id";
	if(AppearOnListPage($dispfield))
		$lookupSelectField=$dispfield;

	if($categoryfield)
	{
		if(!strlen(GetFullFieldName($categoryfield)))
			$categoryfield="";
	}
	if(!$categoryfield)
		$lookupcategory="";
	
}

$firsttime=postvalue("firsttime");

if(!count($_POST) && !count($_GET))
{
	$sess_unset = array();
	foreach($_SESSION as $key=>$value)
		if(substr($key,0,strlen($strTableName)+1)==$strTableName."_" &&
			strpos(substr($key,strlen($strTableName)+1),"_")===false)
			$sess_unset[] = $key;
	foreach($sess_unset as $key)
		unset($_SESSION[$key]);
}

//	Before Process event
if(function_exists("BeforeProcessList"))
	BeforeProcessList($conn);

if(@$_REQUEST["a"]=="showall")
	$_SESSION[$strTableName."_search"]=0;
else if(@$_REQUEST["a"]=="search")
{
	$_SESSION[$strTableName."_searchfield"]=postvalue("SearchField");
	$_SESSION[$strTableName."_searchoption"]=postvalue("SearchOption");
	$_SESSION[$strTableName."_searchfor"]=postvalue("SearchFor");
	if(postvalue("SearchFor")!="" || postvalue("SearchOption")=='Empty')
		$_SESSION[$strTableName."_search"]=1;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}
else if(@$_REQUEST["a"]=="advsearch")
{
	$_SESSION[$strTableName."_asearchnot"]=array();
	$_SESSION[$strTableName."_asearchopt"]=array();
	$_SESSION[$strTableName."_asearchfor"]=array();
	$_SESSION[$strTableName."_asearchfor2"]=array();
	$tosearch=0;
	$asearchfield = postvalue("asearchfield");
	$_SESSION[$strTableName."_asearchtype"] = postvalue("type");
	if(!$_SESSION[$strTableName."_asearchtype"])
		$_SESSION[$strTableName."_asearchtype"]="and";
	foreach($asearchfield as $field)
	{
		$gfield=GoodFieldName($field);
		$asopt=postvalue("asearchopt_".$gfield);
		$value1=postvalue("value_".$gfield);
		$type=postvalue("type_".$gfield);
		$value2=postvalue("value1_".$gfield);
		$not=postvalue("not_".$gfield);
		if($value1 || $asopt=='Empty')
		{
			$tosearch=1;
			$_SESSION[$strTableName."_asearchopt"][$field]=$asopt;
			if(!is_array($value1))
				$_SESSION[$strTableName."_asearchfor"][$field]=$value1;
			else
				$_SESSION[$strTableName."_asearchfor"][$field]=combinevalues($value1);
			$_SESSION[$strTableName."_asearchfortype"][$field]=$type;
			if($value2)
				$_SESSION[$strTableName."_asearchfor2"][$field]=$value2;
			$_SESSION[$strTableName."_asearchnot"][$field]=($not=="on");
		}
	}
	if($tosearch)
		$_SESSION[$strTableName."_search"]=2;
	else
		$_SESSION[$strTableName."_search"]=0;
	$_SESSION[$strTableName."_pagenumber"]=1;
}



if(@$_REQUEST["orderby"])
	$_SESSION[$strTableName."_orderby"]=@$_REQUEST["orderby"];

if(@$_REQUEST["pagesize"])
{
	$_SESSION[$strTableName."_pagesize"]=@$_REQUEST["pagesize"];
	$_SESSION[$strTableName."_pagenumber"]=1;
}

if(@$_REQUEST["goto"])
	$_SESSION[$strTableName."_pagenumber"]=@$_REQUEST["goto"];


//	process reqest data - end

$includes_js=array();
$includes_css=array();
$code_begin="";
$code_end="";
$html_begin="";
$html_end="";



if($mode==LIST_SIMPLE)
	$includes_js[]="include/jquery.js";
$includes_js[]="include/ajaxsuggest.js";
	$includes_js[]="include/onthefly.js";
//	validation stuff
	$editValidateTypes = array();
	$editValidateFields = array();
	$addValidateTypes = array();
	$addValidateFields = array();

	$includes_js[]="include/inlineedit.js";
	if($mode==LIST_SIMPLE)
		$code_end .= 'window.inlineEditing'.$id.' = new InlineEditing(\'acadcomp\',\'php\');';
	elseif($mode==LIST_LOOKUP)
	{
//	this code must be executed after the inlineedit.js is loaded
		$afteredited_handler="";
		if($lookupSelectField)
		{
			$select_onclick='$("#display_'.$lookupcontrol.'").val($("#edit"+id+"_'.GoodFieldname($dispfield).'").attr("val")); $("#'.$lookupcontrol.'").val($("#edit"+id+"_'.GoodFieldname($linkfield).'").attr("val")); if($("#'.$lookupcontrol.'")[0].onchange) $("#'.$lookupcontrol.'")[0].onchange();RemoveFlyDiv('.$id.');';
			$afteredited_handler = 'window.inlineEditing'.$id.'.afterRecordEdited = function(id) {
				var span=$("#edit"+id+"_'.GoodFieldName($lookupSelectField).'");
				if(!span.length)
					return;
				$(span).html("<a href=#>"+$(span).html()+"</a>"); 
				$("a:first",span).click(function() {'.$select_onclick.'});
			};';
		}

		$code_end.='
				window.inlineEditing'.$id.' = new InlineEditing(\'acadcomp\',\'php\','.$id.');
				'.$afteredited_handler.'
		';
		if(strlen($lookupcategory))
		{
			$code_end.='window.inlineEditing'.$id.'.lookupfield = \''.jsreplace($lookupfield).'\';';
			$code_end.='window.inlineEditing'.$id.'.lookuptable = \''.jsreplace($lookuptable).'\';';
			$code_end.='window.inlineEditing'.$id.'.categoryvalue = \''.jsreplace($lookupcategory).'\';';
		}
	}
			$editValidateTypes[] = "";
		$editValidateFields[] = "campus";
			$editValidateTypes[] = "";
		$editValidateFields[] = "bldg";
										$validatetype="IsNumeric";
					$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "floor";
			$editValidateTypes[] = "";
		$editValidateFields[] = "room";
			$editValidateTypes[] = "";
		$editValidateFields[] = "labselect";
			$editValidateTypes[] = "";
		$editValidateFields[] = "mach_type";
			$editValidateTypes[] = "";
		$editValidateFields[] = "platform";
			$editValidateTypes[] = "";
		$editValidateFields[] = "model";
			$editValidateTypes[] = "";
		$editValidateFields[] = "other_model";
			$editValidateTypes[] = "";
		$editValidateFields[] = "asset_tag";
			$editValidateTypes[] = "";
		$editValidateFields[] = "serial";
			$editValidateTypes[] = "";
		$editValidateFields[] = "service_tag";
			$editValidateTypes[] = "";
		$editValidateFields[] = "proc_speed";
			$editValidateTypes[] = "";
		$editValidateFields[] = "proc_type";
			$editValidateTypes[] = "";
		$editValidateFields[] = "ram";
			$editValidateTypes[] = "";
		$editValidateFields[] = "disk_size";
			$editValidateTypes[] = "";
		$editValidateFields[] = "optical_drive";
			$editValidateTypes[] = "";
		$editValidateFields[] = "display_model";
			$editValidateTypes[] = "";
		$editValidateFields[] = "display_size";
			$editValidateTypes[] = "";
		$editValidateFields[] = "display_asset";
			$editValidateTypes[] = "";
		$editValidateFields[] = "display_serial";
			$editValidateTypes[] = "";
		$editValidateFields[] = "notes";
						$validatetype="";
					$validatetype.="IsRequired";
			$editValidateTypes[] = $validatetype;
			$editValidateFields[] = "last_updated";
			$editValidateTypes[] = "";
		$editValidateFields[] = "uname";
			$editValidateTypes[] = "";
		$editValidateFields[] = "lname";
			$editValidateTypes[] = "";
		$editValidateFields[] = "dept";
			$editValidateTypes[] = "";
		$editValidateFields[] = "mach_name";
			$editValidateTypes[] = "";
		$editValidateFields[] = "ip_addr";
			$editValidateTypes[] = "";
		$editValidateFields[] = "mac_addr";
	
			$addValidateTypes[] = "";
		$addValidateFields[] = "campus";
			$addValidateTypes[] = "";
		$addValidateFields[] = "bldg";
										$validatetype="IsNumeric";
					$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "floor";
			$addValidateTypes[] = "";
		$addValidateFields[] = "room";
			$addValidateTypes[] = "";
		$addValidateFields[] = "labselect";
			$addValidateTypes[] = "";
		$addValidateFields[] = "mach_type";
			$addValidateTypes[] = "";
		$addValidateFields[] = "platform";
			$addValidateTypes[] = "";
		$addValidateFields[] = "model";
			$addValidateTypes[] = "";
		$addValidateFields[] = "other_model";
			$addValidateTypes[] = "";
		$addValidateFields[] = "asset_tag";
			$addValidateTypes[] = "";
		$addValidateFields[] = "serial";
			$addValidateTypes[] = "";
		$addValidateFields[] = "service_tag";
			$addValidateTypes[] = "";
		$addValidateFields[] = "proc_speed";
			$addValidateTypes[] = "";
		$addValidateFields[] = "proc_type";
			$addValidateTypes[] = "";
		$addValidateFields[] = "ram";
			$addValidateTypes[] = "";
		$addValidateFields[] = "disk_size";
			$addValidateTypes[] = "";
		$addValidateFields[] = "optical_drive";
			$addValidateTypes[] = "";
		$addValidateFields[] = "display_model";
			$addValidateTypes[] = "";
		$addValidateFields[] = "display_size";
			$addValidateTypes[] = "";
		$addValidateFields[] = "display_asset";
			$addValidateTypes[] = "";
		$addValidateFields[] = "display_serial";
			$addValidateTypes[] = "";
		$addValidateFields[] = "notes";
						$validatetype="";
					$validatetype.="IsRequired";
			$addValidateTypes[] = $validatetype;
			$addValidateFields[] = "last_updated";
			$addValidateTypes[] = "";
		$addValidateFields[] = "uname";
			$addValidateTypes[] = "";
		$addValidateFields[] = "lname";
			$addValidateTypes[] = "";
		$addValidateFields[] = "dept";
			$addValidateTypes[] = "";
		$addValidateFields[] = "mach_name";
			$addValidateTypes[] = "";
		$addValidateFields[] = "ip_addr";
			$addValidateTypes[] = "";
		$addValidateFields[] = "mac_addr";


		$code_begin.="window.TEXT_INLINE_FIELD_REQUIRED='".jsreplace("Required field")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_ZIPCODE='".jsreplace("Field should be a valid zipcode")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_EMAIL='".jsreplace("Field should be a valid email address")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_NUMBER='".jsreplace("Field should be a valid number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_CURRENCY='".jsreplace("Field should be a valid currency")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PHONE='".jsreplace("Field should be a valid phone number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PASSWORD1='".jsreplace("Field can not be 'password'")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_PASSWORD2='".jsreplace("Field should be at least 4 characters long")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_STATE='".jsreplace("Field should be a valid US state name")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_DATE='".jsreplace("Field should be a valid date")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_TIME='".jsreplace("Field should be a valid time in 24-hour format")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_CC='".jsreplace("Field should be a valid credit card number")."';\r\n";
	$code_begin.="window.TEXT_INLINE_FIELD_SSN='".jsreplace("Field should be a valid Social Security Number")."';\r\n";
		
		if($mode==LIST_SIMPLE)
	{
		$types_separated = implode(",", $editValidateTypes);
		$fields_separated = implode(",", $editValidateFields);
		$code_end.= "inlineEditing".$id.".editValidateTypes = String('".$types_separated."').split(',');"."\r\n";
		$code_end.= "inlineEditing".$id.".editValidateFields = String('".$fields_separated."').split(',');"."\r\n";
	}
																												
		if($mode==LIST_SIMPLE)
	{
		$types_separated = implode(",", $addValidateTypes);
		$fields_separated = implode(",", $addValidateFields);
		$code_end.= "inlineEditing".$id.".addValidateTypes = String('".$types_separated."').split(',');"."\r\n";
		$code_end.= "inlineEditing".$id.".addValidateFields = String('".$fields_separated."').split(',');"."\r\n";
	}
																														
		//	include datepicker files
	$includes_js[]="include/calendar.js";
//	$includes.="<script type=\"text/javascript\" src=\"include/calendar.js\"></script>\r\n";


$includes_js[]="include/jsfunctions.js";
if($mode==LIST_SIMPLE)
	$code_begin.="\nvar bSelected=false;";
$code_begin.="\nwindow.TEXT_FIRST = \""."First"."\";".
"\nwindow.TEXT_PREVIOUS = \""."Previous"."\";".
"\nwindow.TEXT_NEXT = \""."Next"."\";".
"\nwindow.TEXT_LAST = \""."Last"."\";".
"\nwindow.TEXT_PLEASE_SELECT='".jsreplace("Please select")."';".
"\nwindow.TEXT_SAVE='".jsreplace("Save")."';".
"\nwindow.TEXT_CANCEL='".jsreplace("Cancel")."';".
"\nwindow.TEXT_INLINE_ERROR='".jsreplace("Error occurred")."';".
"\nwindow.TEXT_PREVIEW='".jsreplace("preview")."';".
"\nwindow.TEXT_HIDE='".jsreplace("hide")."';".
"\nwindow.TEXT_LOADING='".jsreplace("loading")."';".
"\nvar locale_dateformat = ".$locale_info["LOCALE_IDATE"].";".
"\nvar locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";".
"\nvar bLoading=false;\r\n";
	$code_begin.="var SUGGEST_TABLE='acadcomp_searchsuggest.php';\r\n";
	$code_begin.="var MASTER_PREVIEW_TABLE='acadcomp_masterpreview.php';\r\n";
$html_begin.="<div id=\"search_suggest".$id."\"></div>";
$html_begin.="<div id=\"master_details".$id."\" onmouseover=\"RollDetailsLink.showPopup();\" onmouseout=\"RollDetailsLink.hidePopup();\"> </div>";
if($mode==LIST_SIMPLE)
	$html_begin.="<div id=\"inline_error".$id."\"></div>";

$body = array();
if($mode==LIST_SIMPLE)
	$html_begin.="<form name=\"frmSearch\" method=\"GET\" action=\"acadcomp_list.php\">";
else
{
	$html_begin.="<form name=\"frmSearch".$id."\" target=\"flyframe".$id."\" method=\"GET\" action=\"acadcomp_list.php\">";
	$html_begin.="<input type=\"Hidden\" name=\"mode\" value=\"lookup\">";
	$html_begin.="<input type=\"Hidden\" name=\"id\" value=\"".$id."\">";
	$html_begin.="<input type=\"Hidden\" name=\"field\" value=\"".htmlspecialchars($lookupfield)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"control\" value=\"".htmlspecialchars($lookupcontrol)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"category\" value=\"".htmlspecialchars($lookupcategory)."\">";
	$html_begin.="<input type=\"Hidden\" name=\"table\" value=\"".htmlspecialchars($lookuptable)."\">";
}
$html_begin.='<input type="Hidden" name="a" value="search">
<input type="Hidden" name="value" value="1">
<input type="Hidden" name="SearchFor" value="">
<input type="Hidden" name="SearchOption" value="">
<input type="Hidden" name="SearchField" value="">
</form>';

$includes_vars="true";

if($mode==LIST_SIMPLE)
{
	$body["begin"]="";
	foreach($includes_js as $file)
		$body["begin"].="<script type=\"text/javascript\" src=\"".$file."\"></script>";
	foreach($includes_css as $file)
		$body["begin"].="<link rel='stylesheet' href='".$file."' type='text/css' media='screen'/>";
	$body["begin"].="<script language=\"javascript\">".$code_begin."</script>";
	$body["begin"].=$html_begin;
}
elseif($mode==LIST_LOOKUP)
{
	$includes_code="var s;";
	foreach($includes_js as $file)
	{
		$pos=strrpos($file,"/");
		if($pos!==false)
			$var=substr($file,$pos+1,strlen($file)-4-$pos);
		else
			$var=substr($file,strlen($file)-3);
		$var.="_included";

		$includes_vars.=" && window[ '".$var."' ]";
		
		$includes_code.="if(typeof( window[ '".$var."' ] ) == 'undefined') {";
		$includes_code.="s = document.createElement('script');s.src = '".$file."';\r\n".
		"document.getElementsByTagName('HEAD')[0].appendChild(s);}\r\n";
	}
	$code_begin=$includes_code.$code_begin;
	$body["begin"].=$html_begin;
}

//	process session variables
//	order by
$strOrderBy="";
$order_ind=-1;


$recno=1;
$recid=$recno+$id;
$numrows=0;
$rowid=0;

$href="acadcomp_list.php?orderby=arecord_id";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("record_id_orderlinkattrs",$orderlinkattrs);
$xt->assign("record_id_fieldheader",true);
$href="acadcomp_list.php?orderby=acampus";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("campus_orderlinkattrs",$orderlinkattrs);
$xt->assign("campus_fieldheader",true);
$href="acadcomp_list.php?orderby=abldg";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("bldg_orderlinkattrs",$orderlinkattrs);
$xt->assign("bldg_fieldheader",true);
$href="acadcomp_list.php?orderby=afloor";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("floor_orderlinkattrs",$orderlinkattrs);
$xt->assign("floor_fieldheader",true);
$href="acadcomp_list.php?orderby=aroom";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("room_orderlinkattrs",$orderlinkattrs);
$xt->assign("room_fieldheader",true);
$href="acadcomp_list.php?orderby=alabselect";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("labselect_orderlinkattrs",$orderlinkattrs);
$xt->assign("labselect_fieldheader",true);
$href="acadcomp_list.php?orderby=amach_type";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("mach_type_orderlinkattrs",$orderlinkattrs);
$xt->assign("mach_type_fieldheader",true);
$href="acadcomp_list.php?orderby=aplatform";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("platform_orderlinkattrs",$orderlinkattrs);
$xt->assign("platform_fieldheader",true);
$href="acadcomp_list.php?orderby=amodel";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("model_orderlinkattrs",$orderlinkattrs);
$xt->assign("model_fieldheader",true);
$href="acadcomp_list.php?orderby=aother_model";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("other_model_orderlinkattrs",$orderlinkattrs);
$xt->assign("other_model_fieldheader",true);
$href="acadcomp_list.php?orderby=aasset_tag";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("asset_tag_orderlinkattrs",$orderlinkattrs);
$xt->assign("asset_tag_fieldheader",true);
$href="acadcomp_list.php?orderby=aserial";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("serial_orderlinkattrs",$orderlinkattrs);
$xt->assign("serial_fieldheader",true);
$href="acadcomp_list.php?orderby=aservice_tag";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("service_tag_orderlinkattrs",$orderlinkattrs);
$xt->assign("service_tag_fieldheader",true);
$href="acadcomp_list.php?orderby=aproc_speed";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("proc_speed_orderlinkattrs",$orderlinkattrs);
$xt->assign("proc_speed_fieldheader",true);
$href="acadcomp_list.php?orderby=aproc_type";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("proc_type_orderlinkattrs",$orderlinkattrs);
$xt->assign("proc_type_fieldheader",true);
$href="acadcomp_list.php?orderby=aram";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("ram_orderlinkattrs",$orderlinkattrs);
$xt->assign("ram_fieldheader",true);
$href="acadcomp_list.php?orderby=adisk_size";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("disk_size_orderlinkattrs",$orderlinkattrs);
$xt->assign("disk_size_fieldheader",true);
$href="acadcomp_list.php?orderby=aoptical_drive";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("optical_drive_orderlinkattrs",$orderlinkattrs);
$xt->assign("optical_drive_fieldheader",true);
$href="acadcomp_list.php?orderby=adisplay_model";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("display_model_orderlinkattrs",$orderlinkattrs);
$xt->assign("display_model_fieldheader",true);
$href="acadcomp_list.php?orderby=adisplay_size";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("display_size_orderlinkattrs",$orderlinkattrs);
$xt->assign("display_size_fieldheader",true);
$href="acadcomp_list.php?orderby=adisplay_asset";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("display_asset_orderlinkattrs",$orderlinkattrs);
$xt->assign("display_asset_fieldheader",true);
$href="acadcomp_list.php?orderby=adisplay_serial";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("display_serial_orderlinkattrs",$orderlinkattrs);
$xt->assign("display_serial_fieldheader",true);
$href="acadcomp_list.php?orderby=anotes";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("notes_orderlinkattrs",$orderlinkattrs);
$xt->assign("notes_fieldheader",true);
$href="acadcomp_list.php?orderby=alast_updated";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("last_updated_orderlinkattrs",$orderlinkattrs);
$xt->assign("last_updated_fieldheader",true);
$href="acadcomp_list.php?orderby=auname";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("uname_orderlinkattrs",$orderlinkattrs);
$xt->assign("uname_fieldheader",true);
$href="acadcomp_list.php?orderby=alname";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("lname_orderlinkattrs",$orderlinkattrs);
$xt->assign("lname_fieldheader",true);
$href="acadcomp_list.php?orderby=adept";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("dept_orderlinkattrs",$orderlinkattrs);
$xt->assign("dept_fieldheader",true);
$href="acadcomp_list.php?orderby=amach_name";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("mach_name_orderlinkattrs",$orderlinkattrs);
$xt->assign("mach_name_fieldheader",true);
$href="acadcomp_list.php?orderby=aip_addr";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("ip_addr_orderlinkattrs",$orderlinkattrs);
$xt->assign("ip_addr_fieldheader",true);
$href="acadcomp_list.php?orderby=amac_addr";
$orderlinkattrs="";
if($mode==LIST_LOOKUP)
{
	$href.="&".$lookupparams;
	$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
}
$orderlinkattrs.=" href=\"".$href."\"";
$xt->assign("mac_addr_orderlinkattrs",$orderlinkattrs);
$xt->assign("mac_addr_fieldheader",true);

if(@$_SESSION[$strTableName."_orderby"])
{
	$order_field=GetFieldByGoodFieldName(substr($_SESSION[$strTableName."_orderby"],1));
	$order_dir=substr($_SESSION[$strTableName."_orderby"],0,1);
	$order_ind=GetFieldIndex($order_field);

	$dir="a";
	$img="down";

	if($order_dir=="a")
	{
		$dir="d";
		$img="up";
	}

	$xt->assign(GoodFieldName($order_field)."_orderimage","<img src=\"images/".$img.".gif\" border=0>");
	
	$href="acadcomp_list.php?orderby=".$dir.GoodFieldName($order_field);
	$orderlinkattrs="";
	if($mode==LIST_LOOKUP)
	{
		$href.="&".$lookupparams;
		$orderlinkattrs="onclick=\"window.frames['flyframe".$id."'].location='".$href."';return false;\"";
	}
	$orderlinkattrs.=" href=\"".$href."\"";
	$xt->assign(GoodFieldName($order_field)."_orderlinkattrs",$orderlinkattrs);

//	$xt->assign(GoodFieldName($order_field)."_orderlinkattrs","href=\"acadcomp_list.php?orderby=".$dir.GoodFieldName($order_field)."\"");

	if($order_ind)
	{
		if($order_dir=="a")
			$strOrderBy="order by ".($order_ind)." asc";
		else 
			$strOrderBy="order by ".($order_ind)." desc";
	}
}
if(!$strOrderBy)
	$strOrderBy=$gstrOrderBy;

//	page number
$mypage=(integer)$_SESSION[$strTableName."_pagenumber"];
if(!$mypage)
	$mypage=1;

//	page size
$PageSize=(integer)$_SESSION[$strTableName."_pagesize"];
if(!$PageSize)
	$PageSize=$gPageSize;
if($mode==LIST_LOOKUP)
	$PageSize=20;

$xt->assign("rpp".$PageSize."_selected","selected");

// delete record
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
		$keys["record_id"]=urldecode(@$arr[0]);
		$selected_recs[]=$keys;
	}
}

$records_deleted=0;
foreach($selected_recs as $keys)
{
	$where = KeyWhere($keys);

	$strSQL="delete from ".AddTableWrappers($strOriginalTableName)." where ".$where;
	$retval=true;
	if(function_exists("AfterDelete") || function_exists("BeforeDelete"))
	{
		$deletedrs = db_query(gSQLWhere($where),$conn);
		$deleted_values = db_fetch_array($deletedrs);
	}
	if(function_exists("BeforeDelete"))
		$retval = BeforeDelete($where,$deleted_values);
	if($retval && @$_REQUEST["a"]=="delete")
	{
		$records_deleted++;
				LogInfo($strSQL);
		db_exec($strSQL,$conn);
		if(function_exists("AfterDelete"))
			AfterDelete($where,$deleted_values);
	}
}

if(count($selected_recs))
{
	if(function_exists("AfterMassDelete"))
		AfterMassDelete($records_deleted);
}

//deal with permissions

//	table selector
$allow_acadcomp=true;

$createmenu=false;
if($allow_acadcomp)
{
	$createmenu=true;
	$xt->assign("acadcomp_tablelink",true);
	$page="";
		$page="list";
		$strPerm = GetUserPermissions("acadcomp");
	if(strpos($strPerm, "A")!==false && strpos($strPerm, "S")===false)
		$page="add";
	$xt->assign("acadcomp_tablelink_attrs","href=\"acadcomp_".$page.".php\"");
	$xt->assign("acadcomp_optionattrs","value=\"acadcomp_".$page.".php\"");
}
if($createmenu && $mode==LIST_SIMPLE)
	$xt->assign("menu_block",true);


$strPerm = GetUserPermissions();
$allow_add=(strpos($strPerm,"A")!==false);
$allow_delete=(strpos($strPerm,"D")!==false);
$allow_edit=(strpos($strPerm,"E")!==false);
$allow_search=(strpos($strPerm,"S")!==false);
$allow_export=(strpos($strPerm,"P")!==false);
$allow_import=(strpos($strPerm,"I")!==false);



//	make sql "select" string

$strWhereClause="";

//	add search params

if(@$_SESSION[$strTableName."_search"]==1)
//	 regular search
{  
	$strSearchFor=trim($_SESSION[$strTableName."_searchfor"]);
	$strSearchOption=trim($_SESSION[$strTableName."_searchoption"]);
	if(@$_SESSION[$strTableName."_searchfield"])
	{
		$strSearchField = $_SESSION[$strTableName."_searchfield"];
		if($where = StrWhere($strSearchField, $strSearchFor, $strSearchOption, ""))
			$strWhereClause = whereAdd($strWhereClause,$where);
		else
			$strWhereClause = whereAdd($strWhereClause,"1=0");
	}
	else
	{
		$strWhere = "1=0";
		if($where=StrWhere("record_id", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("campus", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("bldg", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("floor", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("room", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("labselect", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("mach_type", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("platform", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("model", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("other_model", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("asset_tag", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("serial", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("service_tag", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("proc_speed", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("proc_type", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("ram", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("disk_size", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("optical_drive", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("display_model", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("display_size", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("display_asset", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("display_serial", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("notes", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("last_updated", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("uname", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("lname", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("dept", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("mach_name", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("ip_addr", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		if($where=StrWhere("mac_addr", $strSearchFor, $strSearchOption, ""))
			$strWhere .= " or ".$where;
		$strWhereClause = whereAdd($strWhereClause,$strWhere);
	}
}
else if(@$_SESSION[$strTableName."_search"]==2)
//	 advanced search
{
	$sWhere="";
	foreach(@$_SESSION[$strTableName."_asearchfor"] as $f => $sfor)
		{
			$strSearchFor=trim($sfor);
			$strSearchFor2="";
			$type=@$_SESSION[$strTableName."_asearchfortype"][$f];
			if(array_key_exists($f,@$_SESSION[$strTableName."_asearchfor2"]))
				$strSearchFor2=trim(@$_SESSION[$strTableName."_asearchfor2"][$f]);
			if($strSearchFor!="" || true)
			{
				if (!$sWhere) 
				{
					if($_SESSION[$strTableName."_asearchtype"]=="and")
						$sWhere="1=1";
					else
						$sWhere="1=0";
				}
				$strSearchOption=trim($_SESSION[$strTableName."_asearchopt"][$f]);
				if($where=StrWhereAdv($f, $strSearchFor, $strSearchOption, $strSearchFor2,$type))
				{
					if($_SESSION[$strTableName."_asearchnot"][$f])
						$where="not (".$where.")";
					if($_SESSION[$strTableName."_asearchtype"]=="and")
	   					$sWhere .= " and ".$where;
					else
	   					$sWhere .= " or ".$where;
				}
			}
		}
		$strWhereClause = whereAdd($strWhereClause,$sWhere);
	}




if($mode==LIST_LOOKUP)
{
	if(strlen($lookupcategory))
		$strWhereClause = whereAdd($strWhereClause,GetFullFieldName($categoryfield)."=".make_db_value($categoryfield,$lookupcategory));
	if(strlen($lookupwhere))
		$strWhereClause = whereAdd($strWhereClause,$lookupwhere);
}


$strSQL = gSQLWhere($strWhereClause);

//	order by
$strSQL.=" ".trim($strOrderBy);

//	save SQL for use in "Export" and "Printer-friendly" pages

$_SESSION[$strTableName."_sql"] = $strSQL;
$_SESSION[$strTableName."_where"] = $strWhereClause;
$_SESSION[$strTableName."_order"] = $strOrderBy;

$rowsfound=false;

//	select and display records
if($allow_search)
{
	$strSQLbak = $strSQL;
	if(function_exists("BeforeQueryList"))
		BeforeQueryList($strSQL,$strWhereClause,$strOrderBy);
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
	if(!$numrows)
	{
		$rowsfound=false;
		$message="No records found";
		$message_block=array();
		$message_block["begin"]="<span name=\"notfound_message".$id."\">";
		$message_block["end"]="</span>";
		$xt->assignbyref("message_block",$message_block);
		$xt->assign("message",$message);
	}
	else
	{
		$rowsfound=true;
		$maxRecords = $numrows;
		$xt->assign("records_found",$numrows);
		$maxpages=ceil($maxRecords/$PageSize);
		if($mypage > $maxpages)
			$mypage = $maxpages;
		if($mypage<1) 
			$mypage=1;
		$maxrecs=$PageSize;
		$xt->assign("page",$mypage);
		$xt->assign("maxpages",$maxpages);
		

//	write pagination
	if($maxpages>1)
	{
		$xt->assign("pagination_block",true);
		if($mode==LIST_SIMPLE)
			$code_end.="function GotoPage(nPageNumber)
				{
					window.location='acadcomp_list.php?goto='+nPageNumber;
				}";
		else
			$code_end.="window.GotoPage".$id." = function (nPageNumber)
				{
					window.frames['flyframe".$id."'].location='acadcomp_list.php?".$lookupparams."&goto='+nPageNumber;
				};";
	
/*
		if($mode==LIST_SIMPLE)
		{
			$xt->assign("pagination","<script language=\"JavaScript\">WritePagination(".$mypage.",".$maxpages.");
			function GotoPage(nPageNumber)
			{
				window.location='acadcomp_list.php?goto='+nPageNumber;
			}
			</script>");
		}
*/		
		$pagination="<table rows='1' cols='1' align='center' width='95%' border='0'>";
		$pagination.="<tr valign='center'><td align='center'>";
		$counterstart = $mypage - 9; 
		if($mypage%10) 
			$counterstart = $mypage - ($mypage%10) + 1; 
		$counterend = $counterstart + 9;
		if($counterend > $maxpages) $counterend = $maxpages; 
		if($counterstart != 1) 
		{
			$pagination.="<a href='JavaScript:GotoPage".$id."(1);' style='TEXT-DECORATION: none;'>"."First"."</a>";
			$pagination.="&nbsp;:&nbsp;";
			$pagination.="<a href='JavaScript:GotoPage".$id."(".($counterstart-1).");' style='TEXT-DECORATION: none;'>"."Previous"."</a>";
			$pagination.="&nbsp;";
		}
		$pagination.="<b>[</b>"; 
		for($counter = $counterstart;$counter<=$counterend;$counter++)
		{
			if ($counter != $mypage)
				$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".$counter.");' style='TEXT-DECORATION: none;'>".$counter."</a>";
			else 
				$pagination.="&nbsp;<b>".$counter."</b>";
		}
		$pagination.="&nbsp;<b>]</b>";
		if ($counterend != $maxpages) 
		{
			$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".($counterend+1).");' style='TEXT-DECORATION: none;'>"."Next"."</a>";
			$pagination.="&nbsp;:&nbsp;";
			$pagination.="&nbsp;<a href='JavaScript:GotoPage".$id."(".($maxpages).");' style='TEXT-DECORATION: none;'>"."Last"."</a>";
		}
		$pagination.="</td></tr></table>";
		$xt->assign("pagination",$pagination);
	}

		$strSQL.=" limit ".(($mypage-1)*$PageSize).",".$PageSize;
	}
	$rs=db_query($strSQL,$conn);

//	hide colunm headers if needed
	$recordsonpage=$numrows-($mypage-1)*$PageSize;
	if($recordsonpage>$PageSize)
	$recordsonpage=$PageSize;
	$colsonpage=1;
	if($colsonpage>$recordsonpage)
		$colsonpage=$recordsonpage;
	if($colsonpage<1)
		$colsonpage=1;


//	fill $rowinfo array
	$rowinfo = array();
	$rowinfo["data"]=array();
	$shade=false;
	$editlink="";
	$copylink="";

	if($allow_add )
	{
//	add inline add row	
		$row=array();
		$row["rowattrs"]="class=\"addarea".$id."\" rowid=\"add\"";
		$row["rowspace_attrs"]="class=\"addarea".$id."\"";
		$record=array();
		$record["edit_link"]=true;
		$record["inlineedit_link"]=true;
		$record["view_link"]=true;
		$record["copy_link"]=true;
		$record["checkbox"]=true;
		$record["checkbox"]=true;
		$record["editlink_attrs"]="id=\"editlink_add".$id."\"";
		if($allow_edit)
			$record["inlineeditlink_attrs"]= "id=\"ieditlink_add".$id."\"";
		
		$record["copylink_attrs"]="id=\"copylink_add".$id."\"";
		$record["viewlink_attrs"]="id=\"viewlink_add".$id."\"";
		$record["checkbox_attrs"]="id=\"check_add".$id."\" name=\"selection[]\"";
		$record["record_id_value"] = "<span id=\"add".$id."_record_id\">&nbsp;</span>";
		if(!$allow_edit || $mode==LIST_LOOKUP)
			$record["record_id_value"] = "<span id=\"ieditlink_add".$id."\"></span>".$record["record_id_value"];

		$record["campus_value"] = "<span id=\"add".$id."_campus\">&nbsp;</span>";
		$record["bldg_value"] = "<span id=\"add".$id."_bldg\">&nbsp;</span>";
		$record["floor_value"] = "<span id=\"add".$id."_floor\">&nbsp;</span>";
		$record["room_value"] = "<span id=\"add".$id."_room\">&nbsp;</span>";
		$record["labselect_value"] = "<span id=\"add".$id."_labselect\">&nbsp;</span>";
		$record["mach_type_value"] = "<span id=\"add".$id."_mach_type\">&nbsp;</span>";
		$record["platform_value"] = "<span id=\"add".$id."_platform\">&nbsp;</span>";
		$record["model_value"] = "<span id=\"add".$id."_model\">&nbsp;</span>";
		$record["other_model_value"] = "<span id=\"add".$id."_other_model\">&nbsp;</span>";
		$record["asset_tag_value"] = "<span id=\"add".$id."_asset_tag\">&nbsp;</span>";
		$record["serial_value"] = "<span id=\"add".$id."_serial\">&nbsp;</span>";
		$record["service_tag_value"] = "<span id=\"add".$id."_service_tag\">&nbsp;</span>";
		$record["proc_speed_value"] = "<span id=\"add".$id."_proc_speed\">&nbsp;</span>";
		$record["proc_type_value"] = "<span id=\"add".$id."_proc_type\">&nbsp;</span>";
		$record["ram_value"] = "<span id=\"add".$id."_ram\">&nbsp;</span>";
		$record["disk_size_value"] = "<span id=\"add".$id."_disk_size\">&nbsp;</span>";
		$record["optical_drive_value"] = "<span id=\"add".$id."_optical_drive\">&nbsp;</span>";
		$record["display_model_value"] = "<span id=\"add".$id."_display_model\">&nbsp;</span>";
		$record["display_size_value"] = "<span id=\"add".$id."_display_size\">&nbsp;</span>";
		$record["display_asset_value"] = "<span id=\"add".$id."_display_asset\">&nbsp;</span>";
		$record["display_serial_value"] = "<span id=\"add".$id."_display_serial\">&nbsp;</span>";
		$record["notes_value"] = "<span id=\"add".$id."_notes\">&nbsp;</span>";
		$record["last_updated_value"] = "<span id=\"add".$id."_last_updated\">&nbsp;</span>";
		$record["uname_value"] = "<span id=\"add".$id."_uname\">&nbsp;</span>";
		$record["lname_value"] = "<span id=\"add".$id."_lname\">&nbsp;</span>";
		$record["dept_value"] = "<span id=\"add".$id."_dept\">&nbsp;</span>";
		$record["mach_name_value"] = "<span id=\"add".$id."_mach_name\">&nbsp;</span>";
		$record["ip_addr_value"] = "<span id=\"add".$id."_ip_addr\">&nbsp;</span>";
		$record["mac_addr_value"] = "<span id=\"add".$id."_mac_addr\">&nbsp;</span>";
		if($colsonpage>1)
			$record["endrecord_block"]=true;
		$record["grid_recordheader"]=true;
		$record["grid_vrecord"]=true;
		$row["grid_record"]["data"][]=$record;
		for($i=1;$i<$colsonpage;$i++)
		{
			$rec=array();
			if($i<$colsonpage-1)
				$rec["endrecord_block"]=true;
			$row["grid_record"]["data"][]=$rec;
		}

		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		$rowinfo["data"][]=$row;
	}
	

//	add grid data	
	
	while($data=db_fetch_array($rs))
	{
		if(function_exists("BeforeProcessRowList"))
		{
			if(!BeforeProcessRowList($data))
				continue;
		}
		break;
	}

	while($data && $recno<=$PageSize)
	{
	
		$row=array();
		if(!$shade)
		{
			$row["rowattrs"]="class=shade onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = 'shade';\"";
			$shade=true;
		}
		else
		{
			$row["rowattrs"]="onmouseover=\"this.className = 'rowselected';\" onmouseout=\"this.className = '';\"";
			$shade=false;
		}
		$row["grid_record"]=array();
		$row["grid_record"]["data"]=array();
		$row["rowattrs"].=" rowid=\"".$rowid."\"";
		$rowid++;
		for($col=1;$data && $recno<=$PageSize && $col<=$colsonpage;$col++)
		{
			$recid=$recno+$id;
			$record=array();

	$editable=CheckSecurity($data[""],"Edit");
	$record["edit_link"]=$editable;
	$record["inlineedit_link"]=$editable;
	$record["view_link"]=true;
	$record["copy_link"]=true;


//	detail tables

//	key fields
	$keyblock="";
	$editlink="";
	$copylink="";
	$keylink="";
	$keyblock.= rawurlencode($data["record_id"]);
	$editlink.="editid1=".htmlspecialchars(rawurlencode($data["record_id"]));
	$copylink.="copyid1=".htmlspecialchars(rawurlencode($data["record_id"]));
	$keylink.="&key1=".htmlspecialchars(rawurlencode(@$data["record_id"]));

	$record["editlink_attrs"]="href=\"acadcomp_edit.php?".$editlink."\" id=\"editlink".$recid."\"";
	$record["inlineeditlink_attrs"]= "href=\"acadcomp_edit.php?".$editlink."\" onclick=\"return inlineEditing".$id.".inlineEdit('".$recid."','".$editlink."');\" id=\"ieditlink".$recid."\"";
	$record["copylink_attrs"]="href=\"acadcomp_add.php?".$copylink."\" id=\"copylink".$recid."\"";
	$record["viewlink_attrs"]="href=\"acadcomp_view.php?".$editlink."\" id=\"viewlink".$recid."\"";
	if($mode!=LIST_LOOKUP)
	{
		$record["checkbox"]=$editable;
		if($allow_export)
			$record["checkbox"]=true;
		$record["checkbox_attrs"]="name=\"selection[]\" value=\"".$keyblock."\" id=\"check".$recid."\"";
	}
	else
	{
		$checkbox_attrs="name=\"selection[]\" value=\"".htmlspecialchars(@$data[$linkfield])."\" id=\"check".$recid."\"";
		$record["checkbox"]=array("begin"=>"<input type=radio ".$checkbox_attrs.">", "data"=>array());
	}


//	record_id - 
			$value="";
				$value = ProcessLargeText(GetData($data,"record_id", ""),"field=record%5Fid".$keylink,"",MODE_LIST);
			$record["record_id_value"]=$value;

//	campus - 
			$value="";
				$value = ProcessLargeText(GetData($data,"campus", ""),"field=campus".$keylink,"",MODE_LIST);
			$record["campus_value"]=$value;

//	bldg - 
			$value="";
				$value = ProcessLargeText(GetData($data,"bldg", ""),"field=bldg".$keylink,"",MODE_LIST);
			$record["bldg_value"]=$value;

//	floor - 
			$value="";
				$value = ProcessLargeText(GetData($data,"floor", ""),"field=floor".$keylink,"",MODE_LIST);
			$record["floor_value"]=$value;

//	room - 
			$value="";
				$value = ProcessLargeText(GetData($data,"room", ""),"field=room".$keylink,"",MODE_LIST);
			$record["room_value"]=$value;

//	labselect - 
			$value="";
				$value = ProcessLargeText(GetData($data,"labselect", ""),"field=labselect".$keylink,"",MODE_LIST);
			$record["labselect_value"]=$value;

//	mach_type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mach_type", ""),"field=mach%5Ftype".$keylink,"",MODE_LIST);
			$record["mach_type_value"]=$value;

//	platform - 
			$value="";
				$value = ProcessLargeText(GetData($data,"platform", ""),"field=platform".$keylink,"",MODE_LIST);
			$record["platform_value"]=$value;

//	model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"model", ""),"field=model".$keylink,"",MODE_LIST);
			$record["model_value"]=$value;

//	other_model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"other_model", ""),"field=other%5Fmodel".$keylink,"",MODE_LIST);
			$record["other_model_value"]=$value;

//	asset_tag - 
			$value="";
				$value = ProcessLargeText(GetData($data,"asset_tag", ""),"field=asset%5Ftag".$keylink,"",MODE_LIST);
			$record["asset_tag_value"]=$value;

//	serial - 
			$value="";
				$value = ProcessLargeText(GetData($data,"serial", ""),"field=serial".$keylink,"",MODE_LIST);
			$record["serial_value"]=$value;

//	service_tag - 
			$value="";
				$value = ProcessLargeText(GetData($data,"service_tag", ""),"field=service%5Ftag".$keylink,"",MODE_LIST);
			$record["service_tag_value"]=$value;

//	proc_speed - 
			$value="";
				$value = ProcessLargeText(GetData($data,"proc_speed", ""),"field=proc%5Fspeed".$keylink,"",MODE_LIST);
			$record["proc_speed_value"]=$value;

//	proc_type - 
			$value="";
				$value = ProcessLargeText(GetData($data,"proc_type", ""),"field=proc%5Ftype".$keylink,"",MODE_LIST);
			$record["proc_type_value"]=$value;

//	ram - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ram", ""),"field=ram".$keylink,"",MODE_LIST);
			$record["ram_value"]=$value;

//	disk_size - 
			$value="";
				$value = ProcessLargeText(GetData($data,"disk_size", ""),"field=disk%5Fsize".$keylink,"",MODE_LIST);
			$record["disk_size_value"]=$value;

//	optical_drive - 
			$value="";
				$value = ProcessLargeText(GetData($data,"optical_drive", ""),"field=optical%5Fdrive".$keylink,"",MODE_LIST);
			$record["optical_drive_value"]=$value;

//	display_model - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_model", ""),"field=display%5Fmodel".$keylink,"",MODE_LIST);
			$record["display_model_value"]=$value;

//	display_size - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_size", ""),"field=display%5Fsize".$keylink,"",MODE_LIST);
			$record["display_size_value"]=$value;

//	display_asset - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_asset", ""),"field=display%5Fasset".$keylink,"",MODE_LIST);
			$record["display_asset_value"]=$value;

//	display_serial - 
			$value="";
				$value = ProcessLargeText(GetData($data,"display_serial", ""),"field=display%5Fserial".$keylink,"",MODE_LIST);
			$record["display_serial_value"]=$value;

//	notes - 
			$value="";
				$value = ProcessLargeText(GetData($data,"notes", ""),"field=notes".$keylink,"",MODE_LIST);
			$record["notes_value"]=$value;

//	last_updated - Short Date
			$value="";
				$value = ProcessLargeText(GetData($data,"last_updated", "Short Date"),"field=last%5Fupdated".$keylink,"",MODE_LIST);
			$record["last_updated_value"]=$value;

//	uname - 
			$value="";
				$value = ProcessLargeText(GetData($data,"uname", ""),"field=uname".$keylink,"",MODE_LIST);
			$record["uname_value"]=$value;

//	lname - 
			$value="";
				$value = ProcessLargeText(GetData($data,"lname", ""),"field=lname".$keylink,"",MODE_LIST);
			$record["lname_value"]=$value;

//	dept - 
			$value="";
				$value = ProcessLargeText(GetData($data,"dept", ""),"field=dept".$keylink,"",MODE_LIST);
			$record["dept_value"]=$value;

//	mach_name - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mach_name", ""),"field=mach%5Fname".$keylink,"",MODE_LIST);
			$record["mach_name_value"]=$value;

//	ip_addr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"ip_addr", ""),"field=ip%5Faddr".$keylink,"",MODE_LIST);
			$record["ip_addr_value"]=$value;

//	mac_addr - 
			$value="";
				$value = ProcessLargeText(GetData($data,"mac_addr", ""),"field=mac%5Faddr".$keylink,"",MODE_LIST);
			$record["mac_addr_value"]=$value;
			if(function_exists("BeforeMoveNextList"))
				BeforeMoveNextList($data,$record,$col);
			if($mode==LIST_LOOKUP && $lookupSelectField)
				$code_end.='inlineEditing'.$id.'.afterRecordEdited('.$recid.');';
			
			$span="<span ";
			$span.="id=\"edit".$recid."_record_id\" ";
			$span.="val=\"".htmlspecialchars($data["record_id"])."\" ";
			$span.=">";
			$record["record_id_value"] = $span.$record["record_id_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_campus\" ";
			$span.="val=\"".htmlspecialchars($data["campus"])."\" ";
			$span.=">";
			$record["campus_value"] = $span.$record["campus_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_bldg\" ";
			$span.="val=\"".htmlspecialchars($data["bldg"])."\" ";
			$span.=">";
			$record["bldg_value"] = $span.$record["bldg_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_floor\" ";
			$span.="val=\"".htmlspecialchars($data["floor"])."\" ";
			$span.=">";
			$record["floor_value"] = $span.$record["floor_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_room\" ";
			$span.="val=\"".htmlspecialchars($data["room"])."\" ";
			$span.=">";
			$record["room_value"] = $span.$record["room_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_labselect\" ";
			$span.="val=\"".htmlspecialchars($data["labselect"])."\" ";
			$span.=">";
			$record["labselect_value"] = $span.$record["labselect_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_mach_type\" ";
			$span.="val=\"".htmlspecialchars($data["mach_type"])."\" ";
			$span.=">";
			$record["mach_type_value"] = $span.$record["mach_type_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_platform\" ";
			$span.="val=\"".htmlspecialchars($data["platform"])."\" ";
			$span.=">";
			$record["platform_value"] = $span.$record["platform_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_model\" ";
			$span.="val=\"".htmlspecialchars($data["model"])."\" ";
			$span.=">";
			$record["model_value"] = $span.$record["model_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_other_model\" ";
			$span.="val=\"".htmlspecialchars($data["other_model"])."\" ";
			$span.=">";
			$record["other_model_value"] = $span.$record["other_model_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_asset_tag\" ";
			$span.="val=\"".htmlspecialchars($data["asset_tag"])."\" ";
			$span.=">";
			$record["asset_tag_value"] = $span.$record["asset_tag_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_serial\" ";
			$span.="val=\"".htmlspecialchars($data["serial"])."\" ";
			$span.=">";
			$record["serial_value"] = $span.$record["serial_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_service_tag\" ";
			$span.="val=\"".htmlspecialchars($data["service_tag"])."\" ";
			$span.=">";
			$record["service_tag_value"] = $span.$record["service_tag_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_proc_speed\" ";
			$span.="val=\"".htmlspecialchars($data["proc_speed"])."\" ";
			$span.=">";
			$record["proc_speed_value"] = $span.$record["proc_speed_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_proc_type\" ";
			$span.="val=\"".htmlspecialchars($data["proc_type"])."\" ";
			$span.=">";
			$record["proc_type_value"] = $span.$record["proc_type_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_ram\" ";
			$span.="val=\"".htmlspecialchars($data["ram"])."\" ";
			$span.=">";
			$record["ram_value"] = $span.$record["ram_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_disk_size\" ";
			$span.="val=\"".htmlspecialchars($data["disk_size"])."\" ";
			$span.=">";
			$record["disk_size_value"] = $span.$record["disk_size_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_optical_drive\" ";
			$span.="val=\"".htmlspecialchars($data["optical_drive"])."\" ";
			$span.=">";
			$record["optical_drive_value"] = $span.$record["optical_drive_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_display_model\" ";
			$span.="val=\"".htmlspecialchars($data["display_model"])."\" ";
			$span.=">";
			$record["display_model_value"] = $span.$record["display_model_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_display_size\" ";
			$span.="val=\"".htmlspecialchars($data["display_size"])."\" ";
			$span.=">";
			$record["display_size_value"] = $span.$record["display_size_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_display_asset\" ";
			$span.="val=\"".htmlspecialchars($data["display_asset"])."\" ";
			$span.=">";
			$record["display_asset_value"] = $span.$record["display_asset_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_display_serial\" ";
			$span.="val=\"".htmlspecialchars($data["display_serial"])."\" ";
			$span.=">";
			$record["display_serial_value"] = $span.$record["display_serial_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_notes\" ";
			$span.="val=\"".htmlspecialchars($data["notes"])."\" ";
			$span.=">";
			$record["notes_value"] = $span.$record["notes_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_last_updated\" ";
			$span.="val=\"".htmlspecialchars($data["last_updated"])."\" ";
			$span.=">";
			$record["last_updated_value"] = $span.$record["last_updated_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_uname\" ";
			$span.="val=\"".htmlspecialchars($data["uname"])."\" ";
			$span.=">";
			$record["uname_value"] = $span.$record["uname_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_lname\" ";
			$span.="val=\"".htmlspecialchars($data["lname"])."\" ";
			$span.=">";
			$record["lname_value"] = $span.$record["lname_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_dept\" ";
			$span.="val=\"".htmlspecialchars($data["dept"])."\" ";
			$span.=">";
			$record["dept_value"] = $span.$record["dept_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_mach_name\" ";
			$span.="val=\"".htmlspecialchars($data["mach_name"])."\" ";
			$span.=">";
			$record["mach_name_value"] = $span.$record["mach_name_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_ip_addr\" ";
			$span.="val=\"".htmlspecialchars($data["ip_addr"])."\" ";
			$span.=">";
			$record["ip_addr_value"] = $span.$record["ip_addr_value"]."</span>";
			$span="<span ";
			$span.="id=\"edit".$recid."_mac_addr\" ";
			$span.="val=\"".htmlspecialchars($data["mac_addr"])."\" ";
			$span.=">";
			$record["mac_addr_value"] = $span.$record["mac_addr_value"]."</span>";
		//	add spans with the link and display field values to the row
			if($mode==LIST_LOOKUP && $lookupSelectField)
			{
				$span="";
				if(!AppearOnListPage($linkfield))
				{
					$span.="<span ";
					$span.="id=\"edit".$recid."_".GoodFieldname($linkfield)."\" ";
					$span.="val=\"".htmlspecialchars($data[$linkfield])."\" ";
					$span.="></span>";
				}
				if(!AppearOnListPage($dispfield))
				{
					$span.="<span ";
					$span.="id=\"edit".$recid."_".GoodFieldname($dispfield)."\" ";
					$span.="val=\"".htmlspecialchars($data[$dispfield])."\" ";
					$span.="></span>";
				}
				$record[GoodFieldname($lookupSelectField)."_value"].=$span;
			}
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$record["grid_recordheader"]=true;
			$record["grid_vrecord"]=true;
			$row["grid_record"]["data"][]=$record;
			while($data=db_fetch_array($rs))
			{
				if(function_exists("BeforeProcessRowList"))
				{
					if(!BeforeProcessRowList($data))
						continue;
				}
				break;
			}
			$recno++;
			
		}
		while($col<=$colsonpage)
		{
			$record = array();
			if($col<$colsonpage)
				$record["endrecord_block"]=true;
			$row["grid_record"]["data"][]=$record;
			$col++;
		}
//	assign row spacings for vertical layout
		$row["grid_rowspace"]=true;
		$row["grid_recordspace"] = array("data"=>array());
		for($i=0;$i<$colsonpage*2-1;$i++)
			$row["grid_recordspace"]["data"][]=true;
		
		$rowinfo["data"][]=$row;
	}
	if(count($rowinfo["data"]))
		$rowinfo["data"][count($rowinfo["data"])-1]["grid_rowspace"]=false;
	$xt->assignbyref("grid_row",$rowinfo);


}


if($allow_search)
{

	$searchfor_attrs="autocomplete=off onkeydown=\"return listenEvent(event,this,'ordinary');\" onkeyup=\"searchSuggest(event,this,'ordinary');\"";
	if($mode==LIST_LOOKUP)
		$searchfor_attrs="onkeydown=\"e=event; if(!e) e = window.event; if (e.keyCode != 13) return true; e.cancel = true; RunSearch('".$id."'); return false;\"";
	if($_SESSION[$strTableName."_search"]==1)
	{
//	fill in search variables
	//	field selection
		if(@$_SESSION[$strTableName."_searchfield"])
			$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchfield"])."_searchfieldoption","selected");
	// search type selection
		$xt->assign(GoodFieldName(@$_SESSION[$strTableName."_searchoption"])."_searchtypeoption","selected");
		$searchfor_attrs.=" value=\"".htmlspecialchars(@$_SESSION[$strTableName."_searchfor"])."\"";
	}
	$searchfor_attrs.=" name=\"ctlSearchFor".$id."\" id=\"ctlSearchFor".$id."\"";
	$xt->assign("searchfor_attrs",$searchfor_attrs);
	$xt->assign("searchbutton_attrs","onClick=\"javascript: RunSearch('".$id."');\"");
	$xt->assign("showallbutton_attrs","onClick=\"javascript: document.forms.frmSearch".$id.".a.value = 'showall'; document.forms.frmSearch".$id.".submit();\"");
}


if($mode==LIST_SIMPLE)
{

		$xt->assign("login_block",true);
	$xt->assign("username",htmlspecialchars($_SESSION["UserID"]));
	$xt->assign("logoutlink_attrs","onclick=\"window.location.href='login.php?a=logout';\"");


	$xt->assign("toplinks_block",true);

	$xt->assign("print_link",$allow_export);
	$xt->assign("printall_link",$allow_export);
	$xt->assign("printlink_attrs","href=\"acadcomp_print.php\" onclick=\"window.open('acadcomp_print.php','wPrint');return false;\"");
	$xt->assign("printalllink_attrs","href=\"acadcomp_print.php?all=1\" onclick=\"window.open('acadcomp_print.php?all=1','wPrint');return false;\"");
	$xt->assign("export_link",$allow_export);
	$xt->assign("exportlink_attrs","href=\"acadcomp_export.php\" onclick=\"window.open('acadcomp_export.php','wExport');return false;\"");
	
	$xt->assign("printselected_link",$allow_export);
	$xt->assign("printselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='acadcomp_print.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='acadcomp_list.php'; 
	document.forms.frmAdmin.target='_self';return false\"
	href=\"acadcomp_print.php\"");
	$xt->assign("exportselected_link",$allow_export);
	$xt->assign("exportselectedlink_attrs","disptype=\"control1\" onclick=\"
	if(!\$('input[@type=checkbox][@checked][@name^=selection]').length)
		return true;
	document.forms.frmAdmin.action='acadcomp_export.php';
	document.forms.frmAdmin.target='_blank';
	document.forms.frmAdmin.submit(); 
	document.forms.frmAdmin.action='acadcomp_list.php'; 
	document.forms.frmAdmin.target='_self';return false;\"
	href=\"acadcomp_export.php\"");
	
	$xt->assign("add_link",$allow_add);
	$xt->assign("copy_column",$allow_add);
	$xt->assign("addlink_attrs","href=\"acadcomp_add.php\" onClick=\"window.location.href='acadcomp_add.php'\"");
	$xt->assign("inlineadd_link",$allow_add);
	$xt->assign("inlineaddlink_attrs","href=\"acadcomp_add.php\" onclick=\"return inlineEditing".$id.".inlineAdd(flyid++,null,'acadcomp_add.php');\"");

	$xt->assign("selectall_link",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("selectalllink_attrs","href=# onclick=\"var i; 
	bSelected=!bSelected;
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=bSelected;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=bSelected\"");
	
	$xt->assign("checkbox_column",$allow_delete || $allow_export  || $allow_edit);
	$xt->assign("checkbox_header",true);
	$xt->assign("checkboxheader_attrs","onClick = \"var i; 
if ((typeof frmAdmin.elements['selection[]'].length)=='undefined')
	frmAdmin.elements['selection[]'].checked=this.checked;
else
for (i=0;i<frmAdmin.elements['selection[]'].length;++i) 
	frmAdmin.elements['selection[]'][i].checked=this.checked;\"");
	$xt->assign("editselected_link",$allow_edit);
	$xt->assign("editselectedlink_attrs","href=\"acadcomp_edit.php\" disptype=\"control1\" name=\"edit_selected".$id."\" onclick=\"\$('input[@type=checkbox][@checked][@id^=check]').each(function(i){
				if(!isNaN(parseInt(this.id.substr(5))))
					\$('a#ieditlink'+this.id.substr(5)).click();});\"");
	$xt->assign("saveall_link",$allow_edit||$allow_edit);
	$xt->assign("savealllink_attrs","disptype=\"control1\" name=\"saveall_edited".$id."\" style=\"display:none\" onclick=\"\$('a[@id^=save_]').click();\"");
	$xt->assign("cancelall_link",$allow_edit||$allow_edit);
	$xt->assign("cancelalllink_attrs","disptype=\"control1\" name=\"revertall_edited".$id."\" style=\"display:none\" onclick=\"\$('a[@id^=revert_]').click();\"");
	

	$xt->assign("edit_column",$allow_edit);
	$xt->assign("edit_headercolumn",$allow_edit);
	$xt->assign("edit_footercolumn",$allow_edit);
	$xt->assign("inlineedit_column",$allow_edit);
	$xt->assign("inlineedit_headercolumn",$allow_edit);
	$xt->assign("inlineedit_footercolumn",$allow_edit);
	
	$xt->assign("view_column",$allow_search);


	$xt->assign("delete_link",$allow_delete);
	$xt->assign("deletelink_attrs","onclick=\"
		if(\$('input[@type=checkbox][@checked][@name^=selection]').length && confirm('"."Do you really want to delete these records?"."'))
			frmAdmin.submit(); 
		return false;\"");

}
elseif ($mode==LIST_LOOKUP)
{
//	$xt->assign("checkbox_column",true);
	$xt->assign("inlineadd_link",$allow_add);
	$xt->assign("inlineaddlink_attrs","href=\"acadcomp_add.php\" onclick=\"return inlineEditing".$id.".inlineAdd(flyid++,".$id.",'acadcomp_add.php');\"");
//	$xt->assign("inlineedit_column",$allow_edit);
}

$xt->assign("record_id_fieldheadercolumn",true);
$xt->assign("record_id_fieldcolumn",true);
$xt->assign("record_id_fieldfootercolumn",true);
$xt->assign("campus_fieldheadercolumn",true);
$xt->assign("campus_fieldcolumn",true);
$xt->assign("campus_fieldfootercolumn",true);
$xt->assign("bldg_fieldheadercolumn",true);
$xt->assign("bldg_fieldcolumn",true);
$xt->assign("bldg_fieldfootercolumn",true);
$xt->assign("floor_fieldheadercolumn",true);
$xt->assign("floor_fieldcolumn",true);
$xt->assign("floor_fieldfootercolumn",true);
$xt->assign("room_fieldheadercolumn",true);
$xt->assign("room_fieldcolumn",true);
$xt->assign("room_fieldfootercolumn",true);
$xt->assign("labselect_fieldheadercolumn",true);
$xt->assign("labselect_fieldcolumn",true);
$xt->assign("labselect_fieldfootercolumn",true);
$xt->assign("mach_type_fieldheadercolumn",true);
$xt->assign("mach_type_fieldcolumn",true);
$xt->assign("mach_type_fieldfootercolumn",true);
$xt->assign("platform_fieldheadercolumn",true);
$xt->assign("platform_fieldcolumn",true);
$xt->assign("platform_fieldfootercolumn",true);
$xt->assign("model_fieldheadercolumn",true);
$xt->assign("model_fieldcolumn",true);
$xt->assign("model_fieldfootercolumn",true);
$xt->assign("other_model_fieldheadercolumn",true);
$xt->assign("other_model_fieldcolumn",true);
$xt->assign("other_model_fieldfootercolumn",true);
$xt->assign("asset_tag_fieldheadercolumn",true);
$xt->assign("asset_tag_fieldcolumn",true);
$xt->assign("asset_tag_fieldfootercolumn",true);
$xt->assign("serial_fieldheadercolumn",true);
$xt->assign("serial_fieldcolumn",true);
$xt->assign("serial_fieldfootercolumn",true);
$xt->assign("service_tag_fieldheadercolumn",true);
$xt->assign("service_tag_fieldcolumn",true);
$xt->assign("service_tag_fieldfootercolumn",true);
$xt->assign("proc_speed_fieldheadercolumn",true);
$xt->assign("proc_speed_fieldcolumn",true);
$xt->assign("proc_speed_fieldfootercolumn",true);
$xt->assign("proc_type_fieldheadercolumn",true);
$xt->assign("proc_type_fieldcolumn",true);
$xt->assign("proc_type_fieldfootercolumn",true);
$xt->assign("ram_fieldheadercolumn",true);
$xt->assign("ram_fieldcolumn",true);
$xt->assign("ram_fieldfootercolumn",true);
$xt->assign("disk_size_fieldheadercolumn",true);
$xt->assign("disk_size_fieldcolumn",true);
$xt->assign("disk_size_fieldfootercolumn",true);
$xt->assign("optical_drive_fieldheadercolumn",true);
$xt->assign("optical_drive_fieldcolumn",true);
$xt->assign("optical_drive_fieldfootercolumn",true);
$xt->assign("display_model_fieldheadercolumn",true);
$xt->assign("display_model_fieldcolumn",true);
$xt->assign("display_model_fieldfootercolumn",true);
$xt->assign("display_size_fieldheadercolumn",true);
$xt->assign("display_size_fieldcolumn",true);
$xt->assign("display_size_fieldfootercolumn",true);
$xt->assign("display_asset_fieldheadercolumn",true);
$xt->assign("display_asset_fieldcolumn",true);
$xt->assign("display_asset_fieldfootercolumn",true);
$xt->assign("display_serial_fieldheadercolumn",true);
$xt->assign("display_serial_fieldcolumn",true);
$xt->assign("display_serial_fieldfootercolumn",true);
$xt->assign("notes_fieldheadercolumn",true);
$xt->assign("notes_fieldcolumn",true);
$xt->assign("notes_fieldfootercolumn",true);
$xt->assign("last_updated_fieldheadercolumn",true);
$xt->assign("last_updated_fieldcolumn",true);
$xt->assign("last_updated_fieldfootercolumn",true);
$xt->assign("uname_fieldheadercolumn",true);
$xt->assign("uname_fieldcolumn",true);
$xt->assign("uname_fieldfootercolumn",true);
$xt->assign("lname_fieldheadercolumn",true);
$xt->assign("lname_fieldcolumn",true);
$xt->assign("lname_fieldfootercolumn",true);
$xt->assign("dept_fieldheadercolumn",true);
$xt->assign("dept_fieldcolumn",true);
$xt->assign("dept_fieldfootercolumn",true);
$xt->assign("mach_name_fieldheadercolumn",true);
$xt->assign("mach_name_fieldcolumn",true);
$xt->assign("mach_name_fieldfootercolumn",true);
$xt->assign("ip_addr_fieldheadercolumn",true);
$xt->assign("ip_addr_fieldcolumn",true);
$xt->assign("ip_addr_fieldfootercolumn",true);
$xt->assign("mac_addr_fieldheadercolumn",true);
$xt->assign("mac_addr_fieldcolumn",true);
$xt->assign("mac_addr_fieldfootercolumn",true);
	
$display_grid = $allow_add || $allow_search && $rowsfound;

$xt->assign("asearch_link",$allow_search);
$xt->assign("asearchlink_attrs","href=\"acadcomp_search.php\" onclick=\"window.location.href='acadcomp_search.php';return false;\"");
$xt->assign("import_link",$allow_import);
$xt->assign("importlink_attrs","href=\"acadcomp_import.php\" onclick=\"window.location.href='acadcomp_import.php';return false;\"");

$xt->assign("search_records_block",$allow_search);
$xt->assign("searchform",$allow_search);
$xt->assign("searchform_showall",$allow_search);
if($mode!=LIST_LOOKUP)
{
	$xt->assign("searchform_field",$allow_search);
	$xt->assign("searchform_option",$allow_search);
}
$xt->assign("searchform_text",$allow_search);
$xt->assign("searchform_search",$allow_search);

$xt->assign("usermessage",true);

if($display_grid)
{
	if($mode==LIST_SIMPLE)
		$xt->assign_section("grid_block",
		"<form method=\"POST\" action=\"acadcomp_list.php\" name=\"frmAdmin\" id=\"frmAdmin\">
		<input type=\"hidden\" id=\"a\" name=\"a\" value=\"delete\">",
		"</form>");
	elseif($mode==LIST_LOOKUP)
		$xt->assign_section("grid_block",
		"<form method=\"POST\" action=\"acadcomp_list.php\" name=\"frmAdmin".$id."\" id=\"frmAdmin".$id."\" target=\"flyframe".$id."\">
		<input type=\"hidden\" id=\"a".$id."\" name=\"a\" value=\"delete\">",
		"</form>");
	
	$record_header=array("data"=>array());
	$record_footer=array("data"=>array());
	for($i=0;$i<$colsonpage;$i++)
	{
		$rheader=array();
		$rfooter=array();
		if($i<$colsonpage-1)
		{
			$rheader["endrecordheader_block"]=true;
			$rfooter["endrecordfooter_block"]=true;
		}
		$record_header["data"][]=$rheader;
		$record_footer["data"][]=$rfooter;
	}
	$xt->assignbyref("record_header",$record_header);
	$xt->assignbyref("record_footer",$record_footer);
	$xt->assign("grid_header",true);
	$xt->assign("grid_footer",true);

	$xt->assign("record_controls",true);
}

$xt->assign("recordcontrols_block",$allow_add || $display_grid);

$xt->assign("newrecord_controls",$allow_add);

if($mode==LIST_SIMPLE)
{
	$xt->assign("details_block",$allow_search && $rowsfound);
	$xt->assign("recordspp_block",$allow_search && $rowsfound);
	$xt->assign("recordspp_attrs","onchange=\"javascript: document.location='acadcomp_list.php?pagesize='+this.options[this.selectedIndex].value;\"");
	$xt->assign("pages_block",$allow_search && $rowsfound);
}
else
	$xt->assign("recordspp_attrs","onchange=\"javascript: window.frames['flyframe".$id."'].location='acadcomp_list.php?".$lookupparams."&pagesize='+this.options[this.selectedIndex].value;\"");
$xt->assign("grid_controls",$display_grid);




	$code_end.="\$(\".addarea".$id."\").each(function(i) { \$(this).hide();});\r\n";
	$code_end.="if(flyid<".($recid+1).") flyid=".($recid+1).";\r\n";
	if(!$numrows)
	{
		$code_end .= "$('#record_controls".$id."').hide();";
		if($mode==LIST_SIMPLE)
			$code_end .= "$('[@name=maintable]').hide();";
		else
		{
			$code_end .= "$('[@name=maintable]',$('#fly".$id."')).hide();";
		}
	}

$html_end .= "<style>
#inline_error {
	font-family: Verdana, Arial, Helvetica, sans serif;
	font-size: 11px;
	position: absolute;
	background-color: white;
	border: 1px solid red;
	padding: 10px;
	background-repeat: no-repeat;
	display: none;
	}
</style>";
if($mode==LIST_SIMPLE)
	$code_end.="if(!$('[@disptype=control1]').length && $('[@disptype=controltable1]').length)
		$('[@disptype=controltable1]').hide();";
if($_SESSION[$strTableName."_search"]==1)
	$code_end.= "if(document.getElementById('ctlSearchFor".$id."')) document.getElementById('ctlSearchFor".$id."').focus();";

	
if($mode==LIST_SIMPLE)
{
	$body["end"]="<script language=\"javascript\">".$code_end."</script>";
	$body["end"].=$html_end;
}
elseif($mode==LIST_LOOKUP)
{
	$body["end"].=$html_end;
}
$xt->assignbyref("body",$body);
$xt->assign("style_block",true);
$xt->assign("iestyle_block",true);


$strSQL=$_SESSION[$strTableName."_sql"];
$xt->assign("changepwd_link",$_SESSION["AccessLevel"] != ACCESS_LEVEL_GUEST);
$xt->assign("changepwdlink_attrs","href=\"changepwd.php\" onclick=\"window.location.href='changepwd.php';return false;\"");




$xt->assign("endrecordblock_attrs","colid=\"endrecord\"");
$templatefile = "acadcomp_list.htm";
if(function_exists("BeforeShowList"))
	BeforeShowList($xt,$templatefile);

if($mode==LIST_SIMPLE)
	$xt->display($templatefile);
elseif($mode==LIST_LOOKUP)
{
//	$code_end must run after all include files loaded
	$code_end = 'window.Init'.$id.' = function() {
		if('.$includes_vars.') 
		{
		'.$code_end.'
		}
		else setTimeout(Init'.$id.',200);
	};
	Init'.$id.'();';

	if($firsttime)
	{
		echo str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$code_begin);
		echo str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$code_end);
		echo "\n";
	}
	else
	{
		echo "<textarea id=data>decli";
		echo htmlspecialchars($code_begin);
		echo htmlspecialchars($code_end);
		echo "</textarea>";
	}
	$xt->load_template($templatefile);
	$xt->display_loaded("style_block");
	$xt->display_loaded("iestyle_block");
	$xt->display_loaded("body");
}

