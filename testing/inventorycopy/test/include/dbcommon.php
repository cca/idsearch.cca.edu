<?php

if(@$_SERVER["REQUEST_URI"])
{
	$pinfo=pathinfo($_SERVER["REQUEST_URI"]);
	$dirname = @$pinfo["dirname"];
	$dir = split("/",$dirname);
	$dirname="";
	foreach($dir as $subdir)
	{
		if($subdir!="")
			$dirname.="/".rawurlencode($subdir);
	}
	if($dirname!="")
	{
//		@session_set_cookie_params(0,$dirname);
	}
}
@session_cache_limiter("none");
@session_start();

error_reporting(E_ALL ^ E_NOTICE);

$host="zone-mysql-03.cca.edu";
$user="netsys";
$pwd="m@dskillz";
$port="";
$sys_dbname="cca_inventory";


$cCharset = "Windows-1252";

header("Content-type: text/html; charset=".$cCharset);

$dDebug=false;
$dSQL="";

$bSubqueriesSupported=true;

$tables_data=array();
$field_labels=array();
include("locale.php");
include("events.php");
include("commonfunctions.php");
include("dbconnection.php");
include("dal_source.php");


define("FORMAT_NONE","");
define("FORMAT_DATE_SHORT","Short Date");
define("FORMAT_DATE_LONG","Long Date");
define("FORMAT_DATE_TIME","Datetime");
define("FORMAT_TIME","Time");
define("FORMAT_CURRENCY","Currency");
define("FORMAT_PERCENT","Percent");
define("FORMAT_HYPERLINK","Hyperlink");
define("FORMAT_EMAILHYPERLINK","Email Hyperlink");
define("FORMAT_FILE_IMAGE","File-based Image");
define("FORMAT_DATABASE_IMAGE","Database Image");
define("FORMAT_DATABASE_FILE","Database File");
define("FORMAT_FILE","Document Download");
define("FORMAT_LOOKUP_WIZARD","Lookup wizard");
define("FORMAT_PHONE_NUMBER","Phone Number");
define("FORMAT_NUMBER","Number");
define("FORMAT_HTML","HTML");
define("FORMAT_CHECKBOX","Checkbox");
define("FORMAT_CUSTOM","Custom");

define("EDIT_FORMAT_NONE","");
define("EDIT_FORMAT_TEXT_FIELD","Text field");
define("EDIT_FORMAT_TEXT_AREA","Text area");
define("EDIT_FORMAT_PASSWORD","Password");
define("EDIT_FORMAT_DATE","Date");
define("EDIT_FORMAT_TIME","Time");
define("EDIT_FORMAT_RADIO","Radio button");
define("EDIT_FORMAT_CHECKBOX","Checkbox");
define("EDIT_FORMAT_DATABASE_IMAGE","Database image");
define("EDIT_FORMAT_DATABASE_FILE","Database file");
define("EDIT_FORMAT_FILE","Document upload");
define("EDIT_FORMAT_LOOKUP_WIZARD","Lookup wizard");
define("EDIT_FORMAT_HIDDEN","Hidden field");
define("EDIT_FORMAT_READONLY","Readonly");

define("EDIT_DATE_SIMPLE",0);
define("EDIT_DATE_SIMPLE_DP",11);
define("EDIT_DATE_DD",12);
define("EDIT_DATE_DD_DP",13);

define("MODE_ADD",0);
define("MODE_EDIT",1);
define("MODE_SEARCH",2);
define("MODE_LIST",3);
define("MODE_PRINT",4);
define("MODE_VIEW",5);
define("MODE_INLINE_ADD",6);
define("MODE_INLINE_EDIT",7);
define("MODE_EXPORT",8);

define("LOGIN_HARDCODED",0);
define("LOGIN_TABLE",1);

define("ADVSECURITY_ALL",0);
define("ADVSECURITY_VIEW_OWN",1);
define("ADVSECURITY_EDIT_OWN",2);
define("ADVSECURITY_NONE",3);

define("ACCESS_LEVEL_ADMIN","Admin");
define("ACCESS_LEVEL_ADMINGROUP","AdminGroup");
define("ACCESS_LEVEL_USER","User");
define("ACCESS_LEVEL_GUEST","Guest");

define("DATABASE_MySQL",0);
define("DATABASE_Oracle",1);
define("DATABASE_MSSQLServer",2);
define("DATABASE_Access",3);
define("DATABASE_PostgreSQL",4);

define("ADD_SIMPLE",0);
define("ADD_INLINE",1);
define("ADD_ONTHEFLY",2);

define("LIST_SIMPLE",0);
define("LIST_LOOKUP",1);

define("LCT_DROPDOWN",0);
define("LCT_AJAX",1);
define("LCT_LIST",2);


$strLeftWrapper="`";
$strRightWrapper="`";

$cLoginTable				= "";
$cUserNameField			= "";
$cPasswordField			= "";
$cUserGroupField			= "";
$cEmailField			= "";


$cFrom 					= "";
if($cFrom)
	ini_set("sendmail_from",$cFrom);

	

set_error_handler("error_handler");


$useAJAX = true;
$suggestAllContent = true;

$strLastSQL="";


?>