<?php

function db_connect() 
{
    global $host,$user,$pwd,$errstr,$sys_dbname,$port,$bSubqueriesSupported;
	$strhost=$host;
	if($port && $port!=3306)
		$strhost=$strhost.":".$port;
	$conn = mysql_connect($strhost,$user,$pwd);
	if (!$conn || !mysql_select_db($sys_dbname,$conn)) 
	{
	  trigger_error(mysql_error(), E_USER_ERROR);
	}
	$mysqlversion = "4";
	$res = @mysql_query("SHOW VARIABLES LIKE 'version'",$conn);
	if($row=@mysql_fetch_array($res,MYSQL_ASSOC))
		$mysqlversion = $row["Value"];
	if(substr($mysqlversion,0,1)<="4")
		$bSubqueriesSupported=false;
	return $conn;
}

function db_close($conn)
{
  return mysql_close($conn);
}

function db_query($qstring,$conn) 
{
	global $strLastSQL;
	$strLastSQL=$qstring;
	if(!($ret=mysql_query($qstring,$conn)))
	{
	  trigger_error(mysql_error(), E_USER_ERROR);
	}
	return $ret;
	
}

function db_exec($qstring,$conn)
{
	return db_query($qstring,$conn);
}

function db_pageseek($qhandle,$pagesize,$page)
{
	db_dataseek($qhandle,($page-1)*$pagesize);
}

function db_dataseek($qhandle,$row)
{
   mysql_data_seek($qhandle,$row);
}

function db_numrows($qhandle) {
	// return only if qhandle exists, otherwise 0
	if ($qhandle) {
		return @mysql_numrows($qhandle);
	} else {
		return 0;
	}
}

function db_result($qhandle,$row,$field) {
	return @mysql_result($qhandle,$row,$field);
}


function db_affected_rows($qhandle) {
	return @mysql_affected_rows();
}
	
function db_fetch_array($qhandle) {
	return @mysql_fetch_array($qhandle,MYSQL_ASSOC);
}

function db_fetch_numarray($qhandle) {
	return @mysql_fetch_array($qhandle,MYSQL_NUM);
}
	
function db_insertid($qhandle) {
	return @mysql_insert_id($qhandle);
}

function db_error() {
	return @mysql_error();
}





function parsevalues($enum)
{
	$values=array();
	$i=0; $j=0;
	$inquot=false;
	while($i<strlen($enum))
	{
		if($enum[$i]=="'")
			$nquot=!$inquot;
		else if(!$inquot && $enum[$i]==',')
		{
			$val=substr($enum,$j+1,$i-$j-2);
			$values[]=str_replace("''","'",$val);
			$j=$i+1;
		}
		$i++;
	}
	if($i-$j-2>0)
	{
		$val=substr($enum,$j+1,$i-$j-2);
		$values[]=str_replace("''","'",$val);
	}
	return $values;
}



function db_addslashes($str)
{
	return mysql_escape_string($str);
}

function db_stripslashes($str)
{
	return stripslashes($str);
}

function db_addslashesbinary($str)
{
	return "'".mysql_escape_string($str)."'";
}

function db_stripslashesbinary($str)
{
	return $str;
}

/*
function IsAutoIncrementField($field)
{
	global $strTableName;
	if(strpos($_SESSION[$strTableName."_fieldinfo"][$field]["extra"],"auto_increment")===FALSE)
		return false;
	return true;
}
*/



// adds wrappers to field name if required
function AddFieldWrappers($strName)
{
	global $strLeftWrapper,$strRightWrapper;
	if(substr($strName,0,1)==$strLeftWrapper)
		return $strName;
	return $strLeftWrapper.$strName.$strRightWrapper;
}

function AddTableWrappers($strName)
{
	return AddFieldWrappers($strName);
}

// removes wrappers from field name if required
function RemoveFieldWrappers($strName)
{
	global $strLeftWrapper,$strRightWrapper;
	if(substr($strName,0,1)==$strLeftWrapper)
		return substr($strName,1,strlen($strName)-2);
	return $strName;
}

function RemoveTableWrappers($strName)
{
	return RemoveFieldWrappers($strName);
}

function db_upper($dbval)
{
	return "upper(".$dbval.")";
}

function db_datequotes($val)
{
	return "'".$val."'";
}


function db_numfields($lhandle) {
	return @mysql_numfields($lhandle);
}

function db_fieldname($lhandle,$fnumber) {
           return @mysql_fieldname($lhandle,$fnumber);
}

function db_fieldtype($lhandle,$fname) {
	for($i=0;$i<db_numfields($lhandle);$i++)
		if(db_fieldname($lhandle,$i)==$fname)
			return db_fieldtypen($lhandle,$i);
	return "";
}



function db_fieldtypen($lhandle,$fnumber) {
	$type=mysql_fieldtype($lhandle,$fnumber);
	if($type=="blob")
	{
		$flags=mysql_fieldflags($lhandle,$fnumber);
		if(strpos($flags,"binary")===false)
			$type="text";
	}
	return $type;
}


function FieldNeedQuotes($rs,$field)
{
	$type=db_fieldtype($rs,$field);
	$t=strtoupper($type);
	if($t=="TINYINT" || $t=="SMALLINT" || $t=="MEDIUMINT" || $t=="INT" || $t=="BIGINT"
     || $t=="YEAR" || $t=="FLOAT" || $t=="DOUBLE" || $t=="DECIMAL" || $t=="NUMERIC"
	 || $t=="REAL" )
		return false;
	return true;
}


/*



function db_fieldnull($rs,$i)
{
	$flags=mysql_field_flags($rs,$i);
	if(strpos($flags,"not_null")===false)
		return true;
	return false;
}

function db_fieldlen($rs,$i)
{
	return mysql_field_len($rs,$i);
}
*/

/*



function IsBinaryType($t)
{
	$type=strtoupper($t);
	if($type=="TINYBLOB" || $type=="BLOB" || $type=="MEDIUMBLOB" || $type=="LONGBLOB")
		return true;
	return false;
}

function IsDateFieldType($stype)
{
	$type=strtoupper($stype);
	if($type=="DATE" || $type=="DATETIME" || $type=="TIME" || $type=="TIMESTAMP")
		return true;
	return false;
}


function IsCharType($stype)
{
	$type=strtoupper($stype);
	if($type=="STRING" || $type=="CHAR" || $type=="VARCHAR" || $type=="TEXT" || $type=="ENUM" || $type=="SET")
		return true;
	return false;
}

function IsTextType($type)
{
	if(strtoupper($type)=="TEXT")
		return true;
	return false;
}

function GetTableInfo()
{
	global $strTableName,$conn;
	$strSQL = "SHOW Columns from ".AddTableWrappers($strTableName);
	$rs = db_query($strSQL,$conn);
	$_SESSION[$strTableName."_fieldinfo"]=array();
	while($data=db_fetch_array($rs))
	{
		$_SESSION[$strTableName."_fieldinfo"][$data["Field"]] = array();
		$f = & $_SESSION[$strTableName."_fieldinfo"][$data["Field"]];
		$type=$data["Type"];
//	remove type modifiers
		if(substr($type,0,4)=="tiny")	$type=substr($type,4);
		else if(substr($type,0,5)=="small")	$type=substr($type,5);
		else if(substr($type,0,6)=="medium")	$type=substr($type,6);
		else if(substr($type,0,3)=="big")	$type=substr($type,3);
		else if(substr($type,0,4)=="long")	$type=substr($type,4);
		if(substr($type,0,4)=="enum")
		{
			$f["values"]=parsevalues(substr($type,5,strlen($type)-6));
			$f["type"]="enum";
		}
		else if(substr($type,0,3)=="set")
		{
			$f["values"]=parsevalues(substr($type,4,strlen($type)-5));
			$f["type"]="set";
		}
		else
		{
			if($pos=strpos($type," "))
				$type=substr($type,0,$pos);
//	parse field sizes
			if($pos=strpos($type,"("))
			{
				if($pos1=strpos($type,",",$pos))
				{
					$f["size"]=(integer)substr($type,$pos+1,$pos1-$pos-1);
					$f["scale"]=(integer)substr($type,$pos1+1,strlen($type)-$pos1-2);
				}
				else
				{
					$f["size"]=(integer)substr($type,$pos+1,strlen($type)-$pos-2);
					$f["scale"]=0;
				}
				$type=substr($type,0,$pos);
			}
			$f["type"]=$type;
		}
		$f["extra"]=@$data["Extra"];
		$f["key"]=@$data["Key"];
		$f["default"]=@$data["Default"];
		$f["null"]=@$data["Null"];
	}
}

*/
?>