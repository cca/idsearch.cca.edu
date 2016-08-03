<?php
function GetUserGroups() {
	return array();
}

function GetUserGroup() {
	return array();
}

function CheckLastID($type) {
	global $conn;
	
	$strSQL = "SELECT rpt_id FROM webreports WHERE rpt_type = '".$type."'";
	$rs = db_query($strSQL,$conn);
	$maxID = 0;
	
	while( $row = db_fetch_numarray( $rs ) ) {
		if ( $maxID < $row[0] ) {
			$maxID = $row[0];
		}
	}
	
	return ++$maxID;
}

function GetNumberFieldsList($table) {
	$t = GetFieldsList($table);
	$arr=array();
	foreach($t as $f)
		if(IsNumberType(GetFieldType($f,$table)))
			$arr[]=$f;
	return $arr;
}

function GetChartsList() {
	global $conn;

	$xml = new xml();
	$arr=array();
	$strSQL = "SELECT rpt_name, rpt_title, rpt_owner, rpt_status, rpt_content";
	$strSQL .= " FROM webreports WHERE rpt_type = 'chart'";
	$rsChart = db_query($strSQL,$conn);
	
	while( $row = db_fetch_numarray( $rsChart ) ) {
		$chart_arr = $xml->xml_to_array( $row[4] );
        $view = 0;
		$edit = 0;
		$arrUserGroup = GetUserGroup();
        
        for ( $i=0; $i < count($arrUserGroup); $i++ ) {
            if ( @$_SESSION["UserID"] != "Guest" ) {
                $arrAllGroups = GetUserGroups();
                $bFlag = 0;
                for ( $j=0; $j < count($arrAllGroups); $j++ ) {
                    if ( $arrUserGroup[$i] == $arrAllGroups[$j][0] ){
                        $bFlag = 1;
                    }
                }
                if ( !$bFlag ) {
                    $arrUserGroup[$i] = "Default";
                }
            } else {
                $arrUserGroup[$i] = "Guest";
            }
        }
        
		if ( isset($chart_arr['permissions']) ) {
			foreach ( $chart_arr['permissions'] as $arr_prm ) {
				if (in_array($arr_prm['id'], $arrUserGroup)) {
					$view = ( $arr_prm['view'] == "true" ) ? 1 : 0;
					$edit = ( $arr_prm['edit'] == "true" ) ? 1 : 0;
				}
			}
		}

		$arr[] = array(
			"name"		=> $row[0],
			"title"		=> $row[1],
			"owner"		=> $row[2],
			"status"	=> $row[3],
			"view"		=> $view,
			"edit"		=> $edit
		);
	}

	return $arr;
}

function LoadSelectedChart($report) {
	global $conn;
	
	$strSQL = "SELECT rpt_content FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_query($strSQL,$conn);
	$rptContent = db_fetch_numarray($rsReport);

	return $rptContent[0];	
}

function SaveChart($report, $rtitle, $rstatus, $strXML) {
	global $conn;
	
	$strSQL = "SELECT rpt_id FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_query($strSQL,$conn);
	$cnt = db_numrows($rsReport);
	if ( $cnt > 0 ) {
		$strSQL = "UPDATE webreports SET rpt_title='".$rtitle."', rpt_content='".$strXML."', rpt_status='".$rstatus."', rpt_mdate='".now()."' WHERE rpt_name='".$report."'";
		$rsReport = db_exec($strSQL,$conn);
	} else {
		$strSQL = "INSERT INTO webreports ( rpt_name, rpt_title, rpt_cdate, rpt_mdate, rpt_content, rpt_owner, rpt_status, rpt_type )";
		$strSQL .= " VALUES('".$report."', '".$rtitle."', '".now()."', '".now()."', '".$strXML."', '".@$_SESSION["UserID"]."', '".$rstatus."', 'chart')";
		$rsReport = db_exec($strSQL,$conn);
	}
}

function DeleteChart($report) {
	global $conn;
	
	$strSQL = "DELETE FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_exec($strSQL,$conn);
}

function GetReportsList() {
	global $conn;

	$xml = new xml();
	$arr=array();
	$strSQL = "SELECT rpt_name, rpt_title, rpt_owner, rpt_status, rpt_content";
	$strSQL .= " FROM webreports WHERE rpt_type = 'report'";
	$rsReport = db_query($strSQL,$conn);
	
	while( $row = db_fetch_numarray( $rsReport ) ) {
		$report_arr = $xml->xml_to_array( $row[4] );
        $view = 0;
		$edit = 0;
		$arrUserGroup = GetUserGroup();
        
        for ( $i=0; $i < count($arrUserGroup); $i++ ) {
            if ( @$_SESSION["UserID"] != "Guest" ) {
                $arrAllGroups = GetUserGroups();
                $bFlag = 0;
                for ( $j=0; $j < count($arrAllGroups); $j++ ) {
                    if ( $arrUserGroup[$i] == $arrAllGroups[$j][0] ){
                        $bFlag = 1;
                    }
                }
                if ( !$bFlag ) {
                    $arrUserGroup[$i] = "Default";
                }
            } else {
                $arrUserGroup[$i] = "Guest";
            }
        }

		if ( isset($report_arr['permissions']) ) {
			foreach ( $report_arr['permissions'] as $arr_prm ) {
				if (in_array($arr_prm['id'], $arrUserGroup)) {
					$view = ( $arr_prm['view'] == "true" ) ? 1 : 0;
					$edit = ( $arr_prm['edit'] == "true" ) ? 1 : 0;
				}
			}
		}

		$arr[] = array(
			"name"		=> $row[0],
			"title"		=> $row[1],
			"owner"		=> $row[2],
			"status"	=> $row[3],
			"view"		=> $view,
			"edit"		=> $edit
		);
	}
	
	return $arr;
}

function LoadSelectedReport($report) {
	global $conn;
	
	$strSQL = "SELECT rpt_content FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_query($strSQL,$conn);
	$rptContent = db_fetch_numarray($rsReport);

	return $rptContent[0];	
}

function SaveReport($report, $rtitle, $rstatus, $strXML) {
	global $conn;
	
	$strSQL = "SELECT rpt_id FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_query($strSQL,$conn);
	$cnt = db_numrows($rsReport);
	if ( $cnt > 0 ) {
		$strSQL = "UPDATE webreports SET rpt_title='".$rtitle."', rpt_content='".$strXML."', rpt_status='".$rstatus."', rpt_mdate='".now()."' WHERE rpt_name='".$report."'";
		$rsReport = db_exec($strSQL,$conn);
	} else {
		$strSQL = "INSERT INTO webreports ( rpt_name, rpt_title, rpt_cdate, rpt_mdate, rpt_content, rpt_owner, rpt_status, rpt_type )";
		$strSQL .= " VALUES('".$report."', '".$rtitle."', '".now()."', '".now()."', '".$strXML."', '".@$_SESSION["UserID"]."', '".$rstatus."', 'report')";		
		$rsReport = db_exec($strSQL,$conn);
	}
}

function DeleteReport($report) {
	global $conn;
	
	$strSQL = "DELETE FROM webreports WHERE rpt_name='".$report."'";
	$rsReport = db_exec($strSQL,$conn);
}
?>