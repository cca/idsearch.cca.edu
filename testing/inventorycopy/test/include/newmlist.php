<?php
include("##@TABLE.strShortTableName##_settings.php");

function DisplayMasterTableInfo_##@TABLE.strShortTableName##($params)
{
	$detailtable=$params["detailtable"];
	$keys=$params["keys"];
	global $conn,$strTableName;
	$xt = new Xtempl();
	
	$oldTableName=$strTableName;
	$strTableName="##@TABLE.strDataSourceTable s##";

//$strSQL = "##@TABLE.strSQL ls##";

$sqlHead="##@TABLE.sqlHead ls##";
$sqlFrom="##@TABLE.sqlFrom ls##";
$sqlWhere="##@TABLE.sqlWhere ls##";
$sqlTail="##@TABLE.sqlTail ls##";

$where="";

##foreach @BUILDER.Tables as @t filter @t.arrMasterTables[strMasterTable==@TABLE.strDataSourceTable].len##
##if @first##
if($detailtable=="##@t.strDataSourceTable s##")
##else##
elseif($detailtable=="##@t.strDataSourceTable s##")
##endif##
{
	##foreach @t.arrMasterTables[strMasterTable==@TABLE.strDataSourceTable].arrMasterKeys as @mk##
	##if !@first##
	$where.=" and ";
	##endif##
	$where.= GetFullFieldName("##@mk s##")."=".make_db_value("##@mk s##",$keys[##@index##-1]);
	##endfor##
}
##endfor##
if(!$where)
{
	$strTableName=$oldTableName;
	return;
}
	$str = SecuritySQL("Search");
	if(strlen($str))
		$where.=" and ".$str;

	$strWhere=whereAdd($sqlWhere,$where);
	if(strlen($strWhere))
		$strWhere=" where ".$strWhere." ";
	$strSQL= $sqlHead.$sqlFrom.$strWhere.$sqlTail;

//	$strSQL=AddWhere($strSQL,$where);
	LogInfo($strSQL);
	$rs=db_query($strSQL,$conn);
	$data=db_fetch_array($rs);
	$keylink="";
##foreach @TABLE.arrKeyFields as @k##
	$keylink.="&key##@index##=".htmlspecialchars(rawurlencode(@$data["##@k s##"]));
##endfor##
	
##foreach Fields as @f filter @f.bListPage order @f.nListPageOrder##

//	##@f.strName## - ##@f.strViewFormat##
			$value="";
	##if @f.strViewFormat==FORMAT_DATABASE_IMAGE##
		##if @f.bShowThumbnail##
				$value.="<a ";
				##if @f.bUseiBox##
					$value .= "rel='ibox' ";
				##else##
					$value .= "target=_blank ";
				##endif##
				$value.="href=\"##@TABLE.strShortTableName s##_imager.php?field=##@f.strName uhs##".$keylink."\">";
				$value.= "<img border=0";
				$value.=" src=\"##@TABLE.strShortTableName s##_imager.php?field=##@f.strThumbnail uhs##&alt=##@f.strName uhs##".$keylink."\">";
				$value.= "</a>";
		##else##
				$value = "<img";
			##if @f.ListFormatObj.nImageWidth##
				$value.=" width=##@f.ListFormatObj.nImageWidth##";
			##endif##
			##if @f.ListFormatObj.nImageHeight##
				$value.=" height=##@f.ListFormatObj.nImageHeight##";
			##endif##
				$value.=" border=0";
				$value.=" src=\"##@TABLE.strShortTableName s##_imager.php?field=##@f.strName uhs##".$keylink."\">";
		##endif##
	##elseif @f.strViewFormat==FORMAT_FILE_IMAGE##
			if(CheckImageExtension($data["##@f.strName s##"])) 
			{
		##if @f.bShowThumbnail##
			 	// show thumbnail
				$thumbname="##@f.strThumbnail s##".$data["##@f.strName s##"];
				if(substr("##@f.strhlPrefix s##",0,7)!="http://" && !file_exists(GetUploadFolder("##@f.strName s##").$thumbname))
					$thumbname=$data["##@f.strName s##"];
				$value="<a ";
				##if @f.bUseiBox##
					$value .= "rel='ibox' ";
				##else##
					$value .= "target=_blank ";
				##endif##
				$value.="href=\"".htmlspecialchars(AddLinkPrefix("##@f.strName s##",$data["##@f.strName s##"]))."\">";
				$value.="<img";
				if($thumbname==$data["##@f.strName s##"])
				{
			##if @f.ListFormatObj.nImageWidth##
					$value.=" width=##@f.ListFormatObj.nImageWidth##";
			##endif##
			##if @f.ListFormatObj.nImageHeight##
					$value.=" height=##@f.ListFormatObj.nImageHeight##";
			##endif##
				}
				$value.=" border=0";
				$value.=" src=\"".htmlspecialchars(AddLinkPrefix("##@f.strName s##",$thumbname))."\"></a>";
		##else##
				$value="<img";
			##if @f.ListFormatObj.nImageWidth##
				$value.=" width=##@f.ListFormatObj.nImageWidth##";
			##endif##
			##if @f.ListFormatObj.nImageHeight##
				$value.=" height=##@f.ListFormatObj.nImageHeight##";
			##endif##
				$value.=" border=0";
				$value.=" src=\"".htmlspecialchars(AddLinkPrefix("##@f.strName s##",$data["##@f.strName s##"]))."\">";
		##endif##
			}
	##elseif @f.strViewFormat==FORMAT_DATABASE_FILE##
		##if @f.ListFormatObj.strFilename##
			$filename=$data["##@f.ListFormatObj.strFilename s##"];
			if(!$filename)
				$filename="file.bin";
		##else##
			$filename="file.bin";
		##endif##
			if(strlen($data["##@f.strName s##"]))
			{
				$value="<a href=\"##@TABLE.strShortTableName s##_getfile.php?filename=".rawurlencode($filename)."&field=##@f.strName uhs##".$keylink."\">";
				$value.=htmlspecialchars($filename);
				$value.="</a>";
			}
	##elseif (@f.strEditFormat==EDIT_FORMAT_LOOKUP_WIZARD || @f.strEditFormat==EDIT_FORMAT_RADIO) && pLookupObj.nLookupType == LT_LOOKUPTABLE##
			$value=DisplayLookupWizard("##@f.strName s##",$data["##@f.strName s##"],$data,$keylink,MODE_LIST);
	##elseif NeedEncode(@f)##
			$value = ProcessLargeText(GetData($data,"##@f.strName s##", "##@f.strViewFormat##"),"field=##@f.strName uhs##".$keylink);
	##else##
			$value = GetData($data,"##@f.strName s##", "##@f.strViewFormat##");
	##endif##
			$xt->assign("##@f.strName g##_mastervalue",$value);
##endfor##
	$strTableName=$oldTableName;
	$xt->display("##@TABLE.strShortTableName##_masterlist.htm");
}

// events
##foreach @TABLE.arrEventHandlers as @eh filter @eh.strEventID=="EVENT_ONSCREEN" && @eh.strPage==PAGENAME_MASTERLIST##
function ##@eh.strName##(&$params)
{
##@eh.strEventCode##
}
##endfor##

?>