<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/acadcomp_variables.php");

if(!@$_SESSION["UserID"] || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Add") && !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Edit"))
{ 
	header("Location: login.php"); 
	return;
}

$field=postvalue("field");
$categoryfield="";
$categoryvalue="";
if($categoryfield)
	$categoryvalue=postvalue("category");

$table="";
$linkfield="";
$dispfield="";

if(!CheckAddNewItemAllowed($field,$table,$linkfield,$dispfield))
	return;

if(strlen(@$_POST["newitem"]))
{
	$object=GoodFieldName($field);
	$conn=db_connect();	

	$strValue = postvalue("newitem");

//	check if need quotes
	$rstemp=db_query("select * from ".AddTableWrappers($table)." where 1=0",$conn);
	if(FieldNeedQuotes($rstemp,$dispfield))
		$strValue="'".db_addslashes($strValue)."'";
	else
		$strValue=(0+$strValue);
//	check for uniqueness
	$strSQL = "select count(*) from ".AddTableWrappers($table)." where ".AddFieldWrappers($dispfield)."=".$strValue;
	if($categoryfield)
	{
		if(FieldNeedQuotes($rstemp,$categoryfield))
			$categoryvalue="'".db_addslashes($categoryvalue)."'";
		else
			$categoryvalue=(0+$categoryvalue);
		$strSQL.=" and ".AddFieldWrappers($categoryfield)."=".$categoryvalue;
	}
	$rstemp=db_query($strSQL,$conn);
	$datatemp = db_fetch_numarray($rstemp);
	if(!$datatemp[0])
	{
		$strSQL = "insert into ".AddTableWrappers($table)." (" . AddFieldWrappers($dispfield) . ") values (" . $strValue . ")";
		if($categoryfield)
		{
			$strSQL = "insert into ".AddTableWrappers($table)." (" . AddFieldWrappers($dispfield).",".AddFieldWrappers($categoryfield).") ".
			" values (" . $strValue .",".$categoryvalue.")";
		}
		db_exec($strSQL,$conn);
	}

	$strSQL = "select ".AddFieldWrappers($linkfield).",".AddFieldWrappers($dispfield)." from ".AddTableWrappers($table)." where ".AddFieldWrappers($dispfield)."=".$strValue;
	if($categoryfield)
		$strSQL.=" and ".AddFieldWrappers($categoryfield)."=".$categoryvalue;
	$rstemp=db_query($strSQL,$conn);
	$data = db_fetch_numarray($rstemp);
	
if ( FastType($field) && $useAJAX ) {

	if ( postvalue("mode") == MODE_INLINE_EDIT || postvalue("mode") == MODE_INLINE_ADD ) 
	{
		$element="window.opener.document.getElementById('".postvalue("id")."')";
		$dispelement="window.opener.document.getElementById('display_".postvalue("id")."')";
	}
	else
	{
		$element="window.opener.document.forms.editform.value_".$object;
		$dispelement="window.opener.document.forms.editform.display_value_".$object;
	}
?>	
<script>	
	<?php echo $dispelement; ?>.value = '<?php echo str_replace("'","\\'",htmlspecialchars($data[1]));?>';
	<?php echo $element; ?>.value = '<?php echo str_replace("'","\\'",htmlspecialchars($data[0]));?>';
	<?php echo $dispelement; ?>.focus();
	if(<?php echo $element; ?>.onchange)
		<?php echo $element; ?>.onchange();
	window.close();		
</script>
<?php
} 
else 
{
	if ( postvalue("mode") == MODE_INLINE_EDIT || postvalue("mode") == MODE_INLINE_ADD ) 
		$element="window.opener.document.getElementById('".postvalue("id")."')";
	else
		$element="window.opener.document.forms.editform.value_".$object;
?>
<script>	

	window.opener.create_option(<?php echo $element; ?>, '<?php echo str_replace("'","\\'",htmlspecialchars($data[1]));?>', '<?php echo str_replace("'","\\'",htmlspecialchars($data[0]));?>'); 
	<?php echo $element; ?>.options[<?php echo $element; ?>.options.length-1].selected = true;		
	<?php echo $element; ?>.focus();
	if(<?php echo $element; ?>.onchange)
		<?php echo $element; ?>.onchange();
<?php if($categoryfield && !$useAJAX) { ?>
	window.opener.arr_<?php echo $object;?>[opener.arr_<?php echo $object;?>.length]='<?php echo str_replace("'","\\'",htmlspecialchars($data[0]));?>';
	window.opener.arr_<?php echo $object;?>[opener.arr_<?php echo $object;?>.length]='<?php echo str_replace("'","\\'",htmlspecialchars($data[1]));?>';
	window.opener.arr_<?php echo $object;?>[opener.arr_<?php echo $object;?>.length]='<?php echo str_replace("'","\\'",htmlspecialchars(postvalue("category")));?>';
<?php } ?>
	window.close();	
	
</script>
<?php
}
	return;
}
?>
<link REL="stylesheet" href="include/style.css" type="text/css">
<body onload="document.forms[0].newitem.focus();">
<form method=post>
<div align=center><input type=text name=newitem size=30 maxlength=100>
<br><br><input class=button type=submit value="<?php echo "Save"?>" name=submit1>
<input class=button type=button onClick='window.close();return false;' value="<?php echo "Close window"?>">
</div>
</form>

<?php
function CheckAddNewItemAllowed($field,&$table,&$linkfield,&$dispfield)
{
	return false;
}
?>