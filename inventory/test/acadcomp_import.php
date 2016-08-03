<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
session_cache_limiter("none");
set_magic_quotes_runtime(0);

header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

include("include/dbcommon.php");
include("include/acadcomp_variables.php");
include("libs/excelreader.php");

$strOriginalTableName="`acadcomp`";

if(!@$_SESSION["UserID"])
{ 
	$_SESSION["MyURL"]=$_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
	header("Location: login.php?message=expired"); 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Import"))
{
	echo "<p>"."You don't have permissions to access this table"."<a href=\"login.php\">"."Back to login page"."</a></p>";
	return;
}

//connect to the database
$conn = db_connect();

// keys array
$keys[]=AddFieldWrappers("record_id");
$keys_present=1;
$fields=array();

set_error_handler("import_error_handler");
$total_records=0;
$goodlines = 0;
//////////////////////
// import from Excel
//////////////////////
function ImportFromExcel($uploadfile)
{

$ret = 1;
global $error_message, $keys, $fields, $goodlines, $total_records;

$data = new Spreadsheet_Excel_Reader();

$data->read($uploadfile);

// populate field names array
for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
	$fields[] = AddFieldWrappers($data->sheets[0]['cells'][1][$j]); 


$total_records = $data->sheets[0]['numRows']-1;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) 
{
	$arr = array();
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) 
	{
		$arr[] = $data->sheets[0]['cells'][$i][$j];
	}
	
	$ret = InsertRecord($arr);

}

return $ret;

}

/////////////////////////
// import from CSV
/////////////////////////
function ImportFromCSV($uploadfile)
{

$ret = 1;
global $error_message, $keys, $fields, $goodlines, $total_records;
$fcontents = file($uploadfile); 
	
$line2 = trim($fcontents[0]);
$fields = explode(",", $line2); 


// populate field names array
for ($j=0;$j<count($fields);$j++)
{
	$fields[$j] = AddFieldWrappers(str_replace('"', "", $fields[$j]));
}

$keys_present=1;
for($k=0; $k<sizeof($keys); $k++)
{
	if (!in_array($keys[$k], $fields))
	{
		$keys_present=0;
		break;
	}
}


// scan the file line by line
$total_records = sizeof($fcontents)-1;
for($i=1; $i<sizeof($fcontents); $i++) 
	{

		$line = trim($fcontents[$i]); 
		$arr = ParseCSVLine($line);
		$ret = InsertRecord($arr);
	}


return $ret;	
} 

function import_error_handler($errno, $errstr, $errfile, $errline)
{
	global $error_happened;
	// echo $errstr ."<br>";
	$error_happened=1;
}
		
function ParseCSVLine($line)
{

	$arr = array();
	$inword=0;
	$hasquotes=0;
	$start=0;
	for ($i=0;$i<strlen($line);$i++)
	{
		$c = $line[$i];
		switch ($c)
		{
			case "\"":
			
				if (!$inword)
					{
						$inword=1;
						$hasquotes=1;
						$start=$i+1;
					}
				else
					{
						if ($line[$i+1]=="\"")
						{
							$i++;
							continue;
						}
						else
						{
							$inword=0;
							$hasquotes=0;
							$arr[] = substr($line, $start, $i-$start);
							$start=$i+1;
						}
					}				
					
				break;
			case ",":
				if (!$inword)
					{
						if ($line[$i+1]==",") $inword=1;
						$hasquotes=0;
						$start=$i+1;
					}
				else
					{
						if (!$hasquotes)
						{
							$inword=0;
							if ($line[$i+1]==",") $inword=1;
							$hasquotes=0;
							$arr[] = substr($line, $start, $i-$start);
							$start=$i+1;
						}
					}		
				break;
			case " ":
				break;
			default:
				$inword=1;		
				break;
		}
	}
	
		if ($start<strlen($line))
			$arr[] = substr($line, $start);


	
	return $arr;

}		
		
		
function InsertRecord($arr)
{

	global $fields, $goodlines, $conn, $error_message, $keys_present, $keys, $strOriginalTableName;
	$ret=1;
	
	
		for ($j=0;$j<count($arr);$j++)
			{
	
				$type=GetFieldType(RemoveFieldWrappers($fields[$j]));
				if(IsBinaryType($type))
					$arr[$j] = db_addslashesbinary($arr[$j]);
				if(($arr[$j]==="" || $arr[$j]===FALSE) && !ischartype($arr[$j]))
					$arr[$j] = "null";
				if ($arr[$j]=="null")
					$arr[$j] = "NULL";
				else
				{
					if(NeedQuotes($type))
					{
						if(!IsDateFieldType($type))
							$arr[$j]="'".db_addslashes($arr[$j])."'";
						else
							if (strtotime($arr[$j])!=-1)
								$arr[$j]=db_datequotes(date("Y-m-d",strtotime($arr[$j])));
							else
								$arr[$j]=db_datequotes($arr[$j]);
					}	
					else
					{
						$strvalue = (string)$arr[$j];
						$strvalue = str_replace(",",".",$strvalue);
						$arr[$j]=0+$strvalue;
						//check numbers
					}
				}
			}


		$sql = "insert into ".AddTableWrappers($strOriginalTableName)." (".implode(",", $fields).") values (".implode(",", $arr) .")"; 
		if (db_exec($sql,$conn))
		{
			$goodlines++;
		}
		else	
		{
			$temp_error_message="<b>Error:</b> in the line: ".implode(",",$arr)."<br><br>";

			// we'll try to update the record
			
			if ($keys_present)
			{
				$sql = "update ".AddTableWrappers($strOriginalTableName)." set ";
				$where = " where ";
				for($k=0; $k<sizeof($fields); $k++)
				{
					if (!in_array($fields[$k], $keys))
						$sql .= $fields[$k] . "=". $arr[$k] . ", ";
					else 
						$where.= $fields[$k] . "=". $arr[$k] . " and ";
				}				

				$sql = substr($sql, 0, strlen($sql)-2);
				$where = substr($where, 0, strlen ($where)-5);
				$sql.= " " . $where;
			
			
				$rstmp=db_query("select * from " .AddTableWrappers($strOriginalTableName). " " . $where,$conn);
				$data=db_fetch_array($rstmp);
				
				if ($data)
				{
				
					if (db_exec($sql,$conn))
					{
						// update successfull
						$goodlines++;
					}
					else
					{
						// update not successfull
						$error_message .= $temp_error_message;
						$ret = 0;			
					}
				}
				else    // 	nothing to update  
					{
						$error_message .= $temp_error_message;
						$ret = 0;			
					}
			}
			else
				$error_message .= $temp_error_message;
		}


	return $ret;
	

}		
		
	//$avalues=array();
	$error_message = "";


	if(@$_POST["a"]=="added")
	{
		
	$value=$_REQUEST["value_ImportFileName"];
	$type=@$_POST["type_ImportFileName"];
	$file=&$_FILES["file_ImportFileName"];
		
		
	//check the file extension  
	$ext = substr($value, strlen($value)-4);
	$ext=strtoupper($ext);

	if($ext==".XLS")
	{
		ImportFromExcel($file['tmp_name']);
	}	
	else 
	{
		ImportFromCSV($file['tmp_name']);
	}	
	
//echo "goodlines: " . $goodlines ."<br>";	
//echo "total_records: " . $total_records ."<br>";	
	
if ($goodlines==$total_records)
{
	$error_message = "<font size=2>" . $goodlines . " records were imported</font><br>";
	$error_message .= "<font size=2>To back to your list click on the <b>Back to list</b> button.</font>";
}
else
{
	$error_message .= "Number of records: ". $total_records ."<br>";
	$error_message .= "Imported: ".$goodlines."<br>";
	$error_message .= "Not imported: ";
	$error_message .= $total_records-$goodlines ."<br>";
}	
}


include('libs/xtempl.php');
$xt = new Xtempl();
$body=array();
$body["begin"]="<FORM name=frmimport id=frmimport action=\"acadcomp_import.php\" method=post encType=multipart/form-data >".
"<input type=hidden name=\"a\" value=\"added\">".
"<input type=\"hidden\" name=\"type_ImportFileName\" value=\"upload2\">".
"<input type=hidden name=\"value_ImportFileName\" size=\"30\" maxlength=\"100\">";
$body["end"]="</FORM><script>SetToFirstControl();</script>";
$xt->assignbyref("body",$body);
$xt->assign("importfile_attrs","name=\"file_ImportFileName\" onChange=\"var path=this.form.file_ImportFileName.value; var wpos=path.lastIndexOf('\\\\'); var upos=path.lastIndexOf('/'); 
				var pos=wpos; if(upos>wpos) pos=upos; this.form.value_ImportFileName.value=path.substr(pos+1);\"");
$xt->assign("backtolist_attrs","onclick=\"javascript: window.location='acadcomp_list.php?a=return';\"");
$xt->assign("error_message",$error_message);
$xt->display("acadcomp_import.htm");

?>

