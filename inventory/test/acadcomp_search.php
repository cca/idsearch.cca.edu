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

//connect database
$conn = db_connect();

include('libs/xtempl.php');
$xt = new Xtempl();

//	Before Process event
if(function_exists("BeforeProcessSearch"))
	BeforeProcessSearch($conn);


$includes=
"<script language=\"JavaScript\" src=\"include/calendar.js\"></script>
<script language=\"JavaScript\" src=\"include/jsfunctions.js\"></script>\r\n";
$includes.="<script language=\"JavaScript\" src=\"include/jquery.js\"></script>";
if ($useAJAX) {
$includes.="<script language=\"JavaScript\" src=\"include/ajaxsuggest.js\"></script>\r\n";
}
$includes.="<script language=\"JavaScript\" type=\"text/javascript\">\r\n".
"var locale_dateformat = ".$locale_info["LOCALE_IDATE"].";\r\n".
"var locale_datedelimiter = \"".$locale_info["LOCALE_SDATE"]."\";\r\n".
"var bLoading=false;\r\n".
"var TEXT_PLEASE_SELECT='".addslashes("Please select")."';\r\n";
if ($useAJAX) {
$includes.="var SUGGEST_TABLE = \"acadcomp_searchsuggest.php\";\r\n";
}
$includes.="var detect = navigator.userAgent.toLowerCase();

function checkIt(string)
{
	place = detect.indexOf(string) + 1;
	thestring = string;
	return place;
}


function ShowHideControls()
{
	document.getElementById('second_record_id').style.display =  
		document.forms.editform.elements['asearchopt_record_id'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_campus').style.display =  
		document.forms.editform.elements['asearchopt_campus'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_bldg').style.display =  
		document.forms.editform.elements['asearchopt_bldg'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_floor').style.display =  
		document.forms.editform.elements['asearchopt_floor'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_room').style.display =  
		document.forms.editform.elements['asearchopt_room'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_labselect').style.display =  
		document.forms.editform.elements['asearchopt_labselect'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_mach_type').style.display =  
		document.forms.editform.elements['asearchopt_mach_type'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_platform').style.display =  
		document.forms.editform.elements['asearchopt_platform'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_model').style.display =  
		document.forms.editform.elements['asearchopt_model'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_other_model').style.display =  
		document.forms.editform.elements['asearchopt_other_model'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_asset_tag').style.display =  
		document.forms.editform.elements['asearchopt_asset_tag'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_serial').style.display =  
		document.forms.editform.elements['asearchopt_serial'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_service_tag').style.display =  
		document.forms.editform.elements['asearchopt_service_tag'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_proc_speed').style.display =  
		document.forms.editform.elements['asearchopt_proc_speed'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_proc_type').style.display =  
		document.forms.editform.elements['asearchopt_proc_type'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_ram').style.display =  
		document.forms.editform.elements['asearchopt_ram'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_disk_size').style.display =  
		document.forms.editform.elements['asearchopt_disk_size'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_optical_drive').style.display =  
		document.forms.editform.elements['asearchopt_optical_drive'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_display_model').style.display =  
		document.forms.editform.elements['asearchopt_display_model'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_display_size').style.display =  
		document.forms.editform.elements['asearchopt_display_size'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_display_asset').style.display =  
		document.forms.editform.elements['asearchopt_display_asset'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_display_serial').style.display =  
		document.forms.editform.elements['asearchopt_display_serial'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_notes').style.display =  
		document.forms.editform.elements['asearchopt_notes'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_last_updated').style.display =  
		document.forms.editform.elements['asearchopt_last_updated'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_uname').style.display =  
		document.forms.editform.elements['asearchopt_uname'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_lname').style.display =  
		document.forms.editform.elements['asearchopt_lname'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_dept').style.display =  
		document.forms.editform.elements['asearchopt_dept'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_mach_name').style.display =  
		document.forms.editform.elements['asearchopt_mach_name'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_ip_addr').style.display =  
		document.forms.editform.elements['asearchopt_ip_addr'].value==\"Between\" ? '' : 'none'; 
	document.getElementById('second_mac_addr').style.display =  
		document.forms.editform.elements['asearchopt_mac_addr'].value==\"Between\" ? '' : 'none'; 
	return false;
}
function ResetControls()
{
	var i;
	e = document.forms[0].elements; 
	for (i=0;i<e.length;i++) 
	{
		if (e[i].name!='type' && e[i].className!='button' && e[i].type!='hidden')
		{
			if(e[i].type=='select-one')
				e[i].selectedIndex=0;
			else if(e[i].type=='select-multiple')
			{
				var j;
				for(j=0;j<e[i].options.length;j++)
					e[i].options[j].selected=false;
			}
			else if(e[i].type=='checkbox' || e[i].type=='radio')
				e[i].checked=false;
			else 
				e[i].value = ''; 
		}
		else if(e[i].name.substr(0,6)=='value_' && e[i].type=='hidden')
			e[i].value = ''; 
	}
	ShowHideControls();	
	return false;
}";

$includes.="
$(document).ready(function() {
	document.forms.editform.value_record_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_record_id,'advanced')};
	document.forms.editform.value1_record_id.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_record_id,'advanced1')};
	document.forms.editform.value_record_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_record_id,'advanced')};
	document.forms.editform.value1_record_id.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_record_id,'advanced1')};
	document.forms.editform.value_campus.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_campus,'advanced')};
	document.forms.editform.value1_campus.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_campus,'advanced1')};
	document.forms.editform.value_campus.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_campus,'advanced')};
	document.forms.editform.value1_campus.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_campus,'advanced1')};
	document.forms.editform.value_bldg.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_bldg,'advanced')};
	document.forms.editform.value1_bldg.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_bldg,'advanced1')};
	document.forms.editform.value_bldg.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_bldg,'advanced')};
	document.forms.editform.value1_bldg.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_bldg,'advanced1')};
	document.forms.editform.value_floor.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_floor,'advanced')};
	document.forms.editform.value1_floor.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_floor,'advanced1')};
	document.forms.editform.value_floor.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_floor,'advanced')};
	document.forms.editform.value1_floor.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_floor,'advanced1')};
	document.forms.editform.value_room.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_room,'advanced')};
	document.forms.editform.value1_room.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_room,'advanced1')};
	document.forms.editform.value_room.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_room,'advanced')};
	document.forms.editform.value1_room.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_room,'advanced1')};
	document.forms.editform.value_labselect.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_labselect,'advanced')};
	document.forms.editform.value1_labselect.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_labselect,'advanced1')};
	document.forms.editform.value_labselect.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_labselect,'advanced')};
	document.forms.editform.value1_labselect.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_labselect,'advanced1')};
	document.forms.editform.value_mach_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_mach_type,'advanced')};
	document.forms.editform.value1_mach_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_mach_type,'advanced1')};
	document.forms.editform.value_mach_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_mach_type,'advanced')};
	document.forms.editform.value1_mach_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_mach_type,'advanced1')};
	document.forms.editform.value_platform.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_platform,'advanced')};
	document.forms.editform.value1_platform.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_platform,'advanced1')};
	document.forms.editform.value_platform.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_platform,'advanced')};
	document.forms.editform.value1_platform.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_platform,'advanced1')};
	document.forms.editform.value_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_model,'advanced')};
	document.forms.editform.value1_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_model,'advanced1')};
	document.forms.editform.value_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_model,'advanced')};
	document.forms.editform.value1_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_model,'advanced1')};
	document.forms.editform.value_other_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_other_model,'advanced')};
	document.forms.editform.value1_other_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_other_model,'advanced1')};
	document.forms.editform.value_other_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_other_model,'advanced')};
	document.forms.editform.value1_other_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_other_model,'advanced1')};
	document.forms.editform.value_asset_tag.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_asset_tag,'advanced')};
	document.forms.editform.value1_asset_tag.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_asset_tag,'advanced1')};
	document.forms.editform.value_asset_tag.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_asset_tag,'advanced')};
	document.forms.editform.value1_asset_tag.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_asset_tag,'advanced1')};
	document.forms.editform.value_serial.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_serial,'advanced')};
	document.forms.editform.value1_serial.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_serial,'advanced1')};
	document.forms.editform.value_serial.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_serial,'advanced')};
	document.forms.editform.value1_serial.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_serial,'advanced1')};
	document.forms.editform.value_service_tag.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_service_tag,'advanced')};
	document.forms.editform.value1_service_tag.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_service_tag,'advanced1')};
	document.forms.editform.value_service_tag.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_service_tag,'advanced')};
	document.forms.editform.value1_service_tag.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_service_tag,'advanced1')};
	document.forms.editform.value_proc_speed.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_proc_speed,'advanced')};
	document.forms.editform.value1_proc_speed.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_proc_speed,'advanced1')};
	document.forms.editform.value_proc_speed.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_proc_speed,'advanced')};
	document.forms.editform.value1_proc_speed.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_proc_speed,'advanced1')};
	document.forms.editform.value_proc_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_proc_type,'advanced')};
	document.forms.editform.value1_proc_type.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_proc_type,'advanced1')};
	document.forms.editform.value_proc_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_proc_type,'advanced')};
	document.forms.editform.value1_proc_type.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_proc_type,'advanced1')};
	document.forms.editform.value_ram.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_ram,'advanced')};
	document.forms.editform.value1_ram.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_ram,'advanced1')};
	document.forms.editform.value_ram.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_ram,'advanced')};
	document.forms.editform.value1_ram.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_ram,'advanced1')};
	document.forms.editform.value_disk_size.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_disk_size,'advanced')};
	document.forms.editform.value1_disk_size.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_disk_size,'advanced1')};
	document.forms.editform.value_disk_size.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_disk_size,'advanced')};
	document.forms.editform.value1_disk_size.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_disk_size,'advanced1')};
	document.forms.editform.value_optical_drive.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_optical_drive,'advanced')};
	document.forms.editform.value1_optical_drive.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_optical_drive,'advanced1')};
	document.forms.editform.value_optical_drive.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_optical_drive,'advanced')};
	document.forms.editform.value1_optical_drive.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_optical_drive,'advanced1')};
	document.forms.editform.value_display_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_display_model,'advanced')};
	document.forms.editform.value1_display_model.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_display_model,'advanced1')};
	document.forms.editform.value_display_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_display_model,'advanced')};
	document.forms.editform.value1_display_model.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_display_model,'advanced1')};
	document.forms.editform.value_display_size.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_display_size,'advanced')};
	document.forms.editform.value1_display_size.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_display_size,'advanced1')};
	document.forms.editform.value_display_size.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_display_size,'advanced')};
	document.forms.editform.value1_display_size.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_display_size,'advanced1')};
	document.forms.editform.value_display_asset.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_display_asset,'advanced')};
	document.forms.editform.value1_display_asset.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_display_asset,'advanced1')};
	document.forms.editform.value_display_asset.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_display_asset,'advanced')};
	document.forms.editform.value1_display_asset.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_display_asset,'advanced1')};
	document.forms.editform.value_display_serial.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_display_serial,'advanced')};
	document.forms.editform.value1_display_serial.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_display_serial,'advanced1')};
	document.forms.editform.value_display_serial.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_display_serial,'advanced')};
	document.forms.editform.value1_display_serial.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_display_serial,'advanced1')};
	document.forms.editform.value_uname.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_uname,'advanced')};
	document.forms.editform.value1_uname.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_uname,'advanced1')};
	document.forms.editform.value_uname.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_uname,'advanced')};
	document.forms.editform.value1_uname.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_uname,'advanced1')};
	document.forms.editform.value_lname.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_lname,'advanced')};
	document.forms.editform.value1_lname.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_lname,'advanced1')};
	document.forms.editform.value_lname.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_lname,'advanced')};
	document.forms.editform.value1_lname.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_lname,'advanced1')};
	document.forms.editform.value_dept.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_dept,'advanced')};
	document.forms.editform.value1_dept.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_dept,'advanced1')};
	document.forms.editform.value_dept.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_dept,'advanced')};
	document.forms.editform.value1_dept.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_dept,'advanced1')};
	document.forms.editform.value_mach_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_mach_name,'advanced')};
	document.forms.editform.value1_mach_name.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_mach_name,'advanced1')};
	document.forms.editform.value_mach_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_mach_name,'advanced')};
	document.forms.editform.value1_mach_name.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_mach_name,'advanced1')};
	document.forms.editform.value_ip_addr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_ip_addr,'advanced')};
	document.forms.editform.value1_ip_addr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_ip_addr,'advanced1')};
	document.forms.editform.value_ip_addr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_ip_addr,'advanced')};
	document.forms.editform.value1_ip_addr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_ip_addr,'advanced1')};
	document.forms.editform.value_mac_addr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value_mac_addr,'advanced')};
	document.forms.editform.value1_mac_addr.onkeyup=function(event) {searchSuggest(event,document.forms.editform.value1_mac_addr,'advanced1')};
	document.forms.editform.value_mac_addr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value_mac_addr,'advanced')};
	document.forms.editform.value1_mac_addr.onkeydown=function(event) {return listenEvent(event,document.forms.editform.value1_mac_addr,'advanced1')};
});
</script>
<div id=\"search_suggest\"></div>
";



$all_checkbox="value=\"and\"";
$any_checkbox="value=\"or\"";

if(@$_SESSION[$strTableName."_asearchtype"]=="or")
	$any_checkbox.=" checked";
else
	$all_checkbox.=" checked";
$xt->assign("any_checkbox",$any_checkbox);
$xt->assign("all_checkbox",$all_checkbox);

$editformats=array();

// record_id 
$opt="";
$not=false;
$control_record_id=array();
$control_record_id["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["record_id"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["record_id"];
	$control_record_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["record_id"];
}
$control_record_id["func"]="xt_buildeditcontrol";
$control_record_id["params"]["field"]="record_id";
$control_record_id["params"]["mode"]="search";
$xt->assignbyref("record_id_editcontrol",$control_record_id);
$control1_record_id=$control_record_id;
$control1_record_id["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_record_id["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["record_id"];
$xt->assignbyref("record_id_editcontrol1",$control1_record_id);
	
$xt->assign_section("record_id_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"record_id\">","");
$record_id_notbox="name=\"not_record_id\"";
if($not)
	$record_id_notbox=" checked";
$xt->assign("record_id_notbox",$record_id_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_record_id\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_record_id",$searchtype);
//	edit format
$editformats["record_id"]="Text field";
// campus 
$opt="";
$not=false;
$control_campus=array();
$control_campus["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["campus"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["campus"];
	$control_campus["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["campus"];
}
$control_campus["func"]="xt_buildeditcontrol";
$control_campus["params"]["field"]="campus";
$control_campus["params"]["mode"]="search";
$xt->assignbyref("campus_editcontrol",$control_campus);
$control1_campus=$control_campus;
$control1_campus["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_campus["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["campus"];
$xt->assignbyref("campus_editcontrol1",$control1_campus);
	
$xt->assign_section("campus_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"campus\">","");
$campus_notbox="name=\"not_campus\"";
if($not)
	$campus_notbox=" checked";
$xt->assign("campus_notbox",$campus_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_campus\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_campus",$searchtype);
//	edit format
$editformats["campus"]="Text field";
// bldg 
$opt="";
$not=false;
$control_bldg=array();
$control_bldg["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["bldg"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["bldg"];
	$control_bldg["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["bldg"];
}
$control_bldg["func"]="xt_buildeditcontrol";
$control_bldg["params"]["field"]="bldg";
$control_bldg["params"]["mode"]="search";
$xt->assignbyref("bldg_editcontrol",$control_bldg);
$control1_bldg=$control_bldg;
$control1_bldg["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_bldg["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["bldg"];
$xt->assignbyref("bldg_editcontrol1",$control1_bldg);
	
$xt->assign_section("bldg_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"bldg\">","");
$bldg_notbox="name=\"not_bldg\"";
if($not)
	$bldg_notbox=" checked";
$xt->assign("bldg_notbox",$bldg_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_bldg\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_bldg",$searchtype);
//	edit format
$editformats["bldg"]="Text field";
// floor 
$opt="";
$not=false;
$control_floor=array();
$control_floor["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["floor"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["floor"];
	$control_floor["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["floor"];
}
$control_floor["func"]="xt_buildeditcontrol";
$control_floor["params"]["field"]="floor";
$control_floor["params"]["mode"]="search";
$xt->assignbyref("floor_editcontrol",$control_floor);
$control1_floor=$control_floor;
$control1_floor["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_floor["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["floor"];
$xt->assignbyref("floor_editcontrol1",$control1_floor);
	
$xt->assign_section("floor_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"floor\">","");
$floor_notbox="name=\"not_floor\"";
if($not)
	$floor_notbox=" checked";
$xt->assign("floor_notbox",$floor_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_floor\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_floor",$searchtype);
//	edit format
$editformats["floor"]="Text field";
// room 
$opt="";
$not=false;
$control_room=array();
$control_room["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["room"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["room"];
	$control_room["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["room"];
}
$control_room["func"]="xt_buildeditcontrol";
$control_room["params"]["field"]="room";
$control_room["params"]["mode"]="search";
$xt->assignbyref("room_editcontrol",$control_room);
$control1_room=$control_room;
$control1_room["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_room["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["room"];
$xt->assignbyref("room_editcontrol1",$control1_room);
	
$xt->assign_section("room_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"room\">","");
$room_notbox="name=\"not_room\"";
if($not)
	$room_notbox=" checked";
$xt->assign("room_notbox",$room_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_room\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_room",$searchtype);
//	edit format
$editformats["room"]="Text field";
// labselect 
$opt="";
$not=false;
$control_labselect=array();
$control_labselect["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["labselect"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["labselect"];
	$control_labselect["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["labselect"];
}
$control_labselect["func"]="xt_buildeditcontrol";
$control_labselect["params"]["field"]="labselect";
$control_labselect["params"]["mode"]="search";
$xt->assignbyref("labselect_editcontrol",$control_labselect);
$control1_labselect=$control_labselect;
$control1_labselect["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_labselect["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["labselect"];
$xt->assignbyref("labselect_editcontrol1",$control1_labselect);
	
$xt->assign_section("labselect_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"labselect\">","");
$labselect_notbox="name=\"not_labselect\"";
if($not)
	$labselect_notbox=" checked";
$xt->assign("labselect_notbox",$labselect_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_labselect\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_labselect",$searchtype);
//	edit format
$editformats["labselect"]="Text field";
// mach_type 
$opt="";
$not=false;
$control_mach_type=array();
$control_mach_type["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["mach_type"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["mach_type"];
	$control_mach_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["mach_type"];
}
$control_mach_type["func"]="xt_buildeditcontrol";
$control_mach_type["params"]["field"]="mach_type";
$control_mach_type["params"]["mode"]="search";
$xt->assignbyref("mach_type_editcontrol",$control_mach_type);
$control1_mach_type=$control_mach_type;
$control1_mach_type["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_mach_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["mach_type"];
$xt->assignbyref("mach_type_editcontrol1",$control1_mach_type);
	
$xt->assign_section("mach_type_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"mach_type\">","");
$mach_type_notbox="name=\"not_mach_type\"";
if($not)
	$mach_type_notbox=" checked";
$xt->assign("mach_type_notbox",$mach_type_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_mach_type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_mach_type",$searchtype);
//	edit format
$editformats["mach_type"]="Text field";
// platform 
$opt="";
$not=false;
$control_platform=array();
$control_platform["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["platform"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["platform"];
	$control_platform["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["platform"];
}
$control_platform["func"]="xt_buildeditcontrol";
$control_platform["params"]["field"]="platform";
$control_platform["params"]["mode"]="search";
$xt->assignbyref("platform_editcontrol",$control_platform);
$control1_platform=$control_platform;
$control1_platform["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_platform["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["platform"];
$xt->assignbyref("platform_editcontrol1",$control1_platform);
	
$xt->assign_section("platform_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"platform\">","");
$platform_notbox="name=\"not_platform\"";
if($not)
	$platform_notbox=" checked";
$xt->assign("platform_notbox",$platform_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_platform\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_platform",$searchtype);
//	edit format
$editformats["platform"]="Text field";
// model 
$opt="";
$not=false;
$control_model=array();
$control_model["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["model"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["model"];
	$control_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["model"];
}
$control_model["func"]="xt_buildeditcontrol";
$control_model["params"]["field"]="model";
$control_model["params"]["mode"]="search";
$xt->assignbyref("model_editcontrol",$control_model);
$control1_model=$control_model;
$control1_model["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["model"];
$xt->assignbyref("model_editcontrol1",$control1_model);
	
$xt->assign_section("model_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"model\">","");
$model_notbox="name=\"not_model\"";
if($not)
	$model_notbox=" checked";
$xt->assign("model_notbox",$model_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_model\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_model",$searchtype);
//	edit format
$editformats["model"]="Text field";
// other_model 
$opt="";
$not=false;
$control_other_model=array();
$control_other_model["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["other_model"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["other_model"];
	$control_other_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["other_model"];
}
$control_other_model["func"]="xt_buildeditcontrol";
$control_other_model["params"]["field"]="other_model";
$control_other_model["params"]["mode"]="search";
$xt->assignbyref("other_model_editcontrol",$control_other_model);
$control1_other_model=$control_other_model;
$control1_other_model["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_other_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["other_model"];
$xt->assignbyref("other_model_editcontrol1",$control1_other_model);
	
$xt->assign_section("other_model_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"other_model\">","");
$other_model_notbox="name=\"not_other_model\"";
if($not)
	$other_model_notbox=" checked";
$xt->assign("other_model_notbox",$other_model_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_other_model\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_other_model",$searchtype);
//	edit format
$editformats["other_model"]="Text field";
// asset_tag 
$opt="";
$not=false;
$control_asset_tag=array();
$control_asset_tag["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["asset_tag"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["asset_tag"];
	$control_asset_tag["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["asset_tag"];
}
$control_asset_tag["func"]="xt_buildeditcontrol";
$control_asset_tag["params"]["field"]="asset_tag";
$control_asset_tag["params"]["mode"]="search";
$xt->assignbyref("asset_tag_editcontrol",$control_asset_tag);
$control1_asset_tag=$control_asset_tag;
$control1_asset_tag["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_asset_tag["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["asset_tag"];
$xt->assignbyref("asset_tag_editcontrol1",$control1_asset_tag);
	
$xt->assign_section("asset_tag_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"asset_tag\">","");
$asset_tag_notbox="name=\"not_asset_tag\"";
if($not)
	$asset_tag_notbox=" checked";
$xt->assign("asset_tag_notbox",$asset_tag_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_asset_tag\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_asset_tag",$searchtype);
//	edit format
$editformats["asset_tag"]="Text field";
// serial 
$opt="";
$not=false;
$control_serial=array();
$control_serial["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["serial"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["serial"];
	$control_serial["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["serial"];
}
$control_serial["func"]="xt_buildeditcontrol";
$control_serial["params"]["field"]="serial";
$control_serial["params"]["mode"]="search";
$xt->assignbyref("serial_editcontrol",$control_serial);
$control1_serial=$control_serial;
$control1_serial["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_serial["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["serial"];
$xt->assignbyref("serial_editcontrol1",$control1_serial);
	
$xt->assign_section("serial_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"serial\">","");
$serial_notbox="name=\"not_serial\"";
if($not)
	$serial_notbox=" checked";
$xt->assign("serial_notbox",$serial_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_serial\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_serial",$searchtype);
//	edit format
$editformats["serial"]="Text field";
// service_tag 
$opt="";
$not=false;
$control_service_tag=array();
$control_service_tag["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["service_tag"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["service_tag"];
	$control_service_tag["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["service_tag"];
}
$control_service_tag["func"]="xt_buildeditcontrol";
$control_service_tag["params"]["field"]="service_tag";
$control_service_tag["params"]["mode"]="search";
$xt->assignbyref("service_tag_editcontrol",$control_service_tag);
$control1_service_tag=$control_service_tag;
$control1_service_tag["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_service_tag["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["service_tag"];
$xt->assignbyref("service_tag_editcontrol1",$control1_service_tag);
	
$xt->assign_section("service_tag_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"service_tag\">","");
$service_tag_notbox="name=\"not_service_tag\"";
if($not)
	$service_tag_notbox=" checked";
$xt->assign("service_tag_notbox",$service_tag_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_service_tag\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_service_tag",$searchtype);
//	edit format
$editformats["service_tag"]="Text field";
// proc_speed 
$opt="";
$not=false;
$control_proc_speed=array();
$control_proc_speed["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["proc_speed"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["proc_speed"];
	$control_proc_speed["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["proc_speed"];
}
$control_proc_speed["func"]="xt_buildeditcontrol";
$control_proc_speed["params"]["field"]="proc_speed";
$control_proc_speed["params"]["mode"]="search";
$xt->assignbyref("proc_speed_editcontrol",$control_proc_speed);
$control1_proc_speed=$control_proc_speed;
$control1_proc_speed["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_proc_speed["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["proc_speed"];
$xt->assignbyref("proc_speed_editcontrol1",$control1_proc_speed);
	
$xt->assign_section("proc_speed_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"proc_speed\">","");
$proc_speed_notbox="name=\"not_proc_speed\"";
if($not)
	$proc_speed_notbox=" checked";
$xt->assign("proc_speed_notbox",$proc_speed_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_proc_speed\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_proc_speed",$searchtype);
//	edit format
$editformats["proc_speed"]="Text field";
// proc_type 
$opt="";
$not=false;
$control_proc_type=array();
$control_proc_type["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["proc_type"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["proc_type"];
	$control_proc_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["proc_type"];
}
$control_proc_type["func"]="xt_buildeditcontrol";
$control_proc_type["params"]["field"]="proc_type";
$control_proc_type["params"]["mode"]="search";
$xt->assignbyref("proc_type_editcontrol",$control_proc_type);
$control1_proc_type=$control_proc_type;
$control1_proc_type["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_proc_type["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["proc_type"];
$xt->assignbyref("proc_type_editcontrol1",$control1_proc_type);
	
$xt->assign_section("proc_type_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"proc_type\">","");
$proc_type_notbox="name=\"not_proc_type\"";
if($not)
	$proc_type_notbox=" checked";
$xt->assign("proc_type_notbox",$proc_type_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_proc_type\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_proc_type",$searchtype);
//	edit format
$editformats["proc_type"]="Text field";
// ram 
$opt="";
$not=false;
$control_ram=array();
$control_ram["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["ram"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["ram"];
	$control_ram["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["ram"];
}
$control_ram["func"]="xt_buildeditcontrol";
$control_ram["params"]["field"]="ram";
$control_ram["params"]["mode"]="search";
$xt->assignbyref("ram_editcontrol",$control_ram);
$control1_ram=$control_ram;
$control1_ram["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_ram["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["ram"];
$xt->assignbyref("ram_editcontrol1",$control1_ram);
	
$xt->assign_section("ram_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"ram\">","");
$ram_notbox="name=\"not_ram\"";
if($not)
	$ram_notbox=" checked";
$xt->assign("ram_notbox",$ram_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_ram\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_ram",$searchtype);
//	edit format
$editformats["ram"]="Text field";
// disk_size 
$opt="";
$not=false;
$control_disk_size=array();
$control_disk_size["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["disk_size"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["disk_size"];
	$control_disk_size["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["disk_size"];
}
$control_disk_size["func"]="xt_buildeditcontrol";
$control_disk_size["params"]["field"]="disk_size";
$control_disk_size["params"]["mode"]="search";
$xt->assignbyref("disk_size_editcontrol",$control_disk_size);
$control1_disk_size=$control_disk_size;
$control1_disk_size["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_disk_size["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["disk_size"];
$xt->assignbyref("disk_size_editcontrol1",$control1_disk_size);
	
$xt->assign_section("disk_size_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"disk_size\">","");
$disk_size_notbox="name=\"not_disk_size\"";
if($not)
	$disk_size_notbox=" checked";
$xt->assign("disk_size_notbox",$disk_size_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_disk_size\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_disk_size",$searchtype);
//	edit format
$editformats["disk_size"]="Text field";
// optical_drive 
$opt="";
$not=false;
$control_optical_drive=array();
$control_optical_drive["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["optical_drive"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["optical_drive"];
	$control_optical_drive["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["optical_drive"];
}
$control_optical_drive["func"]="xt_buildeditcontrol";
$control_optical_drive["params"]["field"]="optical_drive";
$control_optical_drive["params"]["mode"]="search";
$xt->assignbyref("optical_drive_editcontrol",$control_optical_drive);
$control1_optical_drive=$control_optical_drive;
$control1_optical_drive["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_optical_drive["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["optical_drive"];
$xt->assignbyref("optical_drive_editcontrol1",$control1_optical_drive);
	
$xt->assign_section("optical_drive_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"optical_drive\">","");
$optical_drive_notbox="name=\"not_optical_drive\"";
if($not)
	$optical_drive_notbox=" checked";
$xt->assign("optical_drive_notbox",$optical_drive_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_optical_drive\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_optical_drive",$searchtype);
//	edit format
$editformats["optical_drive"]="Text field";
// display_model 
$opt="";
$not=false;
$control_display_model=array();
$control_display_model["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["display_model"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["display_model"];
	$control_display_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["display_model"];
}
$control_display_model["func"]="xt_buildeditcontrol";
$control_display_model["params"]["field"]="display_model";
$control_display_model["params"]["mode"]="search";
$xt->assignbyref("display_model_editcontrol",$control_display_model);
$control1_display_model=$control_display_model;
$control1_display_model["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_display_model["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["display_model"];
$xt->assignbyref("display_model_editcontrol1",$control1_display_model);
	
$xt->assign_section("display_model_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"display_model\">","");
$display_model_notbox="name=\"not_display_model\"";
if($not)
	$display_model_notbox=" checked";
$xt->assign("display_model_notbox",$display_model_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_display_model\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_display_model",$searchtype);
//	edit format
$editformats["display_model"]="Text field";
// display_size 
$opt="";
$not=false;
$control_display_size=array();
$control_display_size["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["display_size"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["display_size"];
	$control_display_size["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["display_size"];
}
$control_display_size["func"]="xt_buildeditcontrol";
$control_display_size["params"]["field"]="display_size";
$control_display_size["params"]["mode"]="search";
$xt->assignbyref("display_size_editcontrol",$control_display_size);
$control1_display_size=$control_display_size;
$control1_display_size["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_display_size["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["display_size"];
$xt->assignbyref("display_size_editcontrol1",$control1_display_size);
	
$xt->assign_section("display_size_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"display_size\">","");
$display_size_notbox="name=\"not_display_size\"";
if($not)
	$display_size_notbox=" checked";
$xt->assign("display_size_notbox",$display_size_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_display_size\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_display_size",$searchtype);
//	edit format
$editformats["display_size"]="Text field";
// display_asset 
$opt="";
$not=false;
$control_display_asset=array();
$control_display_asset["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["display_asset"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["display_asset"];
	$control_display_asset["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["display_asset"];
}
$control_display_asset["func"]="xt_buildeditcontrol";
$control_display_asset["params"]["field"]="display_asset";
$control_display_asset["params"]["mode"]="search";
$xt->assignbyref("display_asset_editcontrol",$control_display_asset);
$control1_display_asset=$control_display_asset;
$control1_display_asset["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_display_asset["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["display_asset"];
$xt->assignbyref("display_asset_editcontrol1",$control1_display_asset);
	
$xt->assign_section("display_asset_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"display_asset\">","");
$display_asset_notbox="name=\"not_display_asset\"";
if($not)
	$display_asset_notbox=" checked";
$xt->assign("display_asset_notbox",$display_asset_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_display_asset\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_display_asset",$searchtype);
//	edit format
$editformats["display_asset"]="Text field";
// display_serial 
$opt="";
$not=false;
$control_display_serial=array();
$control_display_serial["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["display_serial"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["display_serial"];
	$control_display_serial["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["display_serial"];
}
$control_display_serial["func"]="xt_buildeditcontrol";
$control_display_serial["params"]["field"]="display_serial";
$control_display_serial["params"]["mode"]="search";
$xt->assignbyref("display_serial_editcontrol",$control_display_serial);
$control1_display_serial=$control_display_serial;
$control1_display_serial["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_display_serial["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["display_serial"];
$xt->assignbyref("display_serial_editcontrol1",$control1_display_serial);
	
$xt->assign_section("display_serial_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"display_serial\">","");
$display_serial_notbox="name=\"not_display_serial\"";
if($not)
	$display_serial_notbox=" checked";
$xt->assign("display_serial_notbox",$display_serial_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_display_serial\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_display_serial",$searchtype);
//	edit format
$editformats["display_serial"]="Text field";
// notes 
$opt="";
$not=false;
$control_notes=array();
$control_notes["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["notes"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["notes"];
	$control_notes["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["notes"];
}
$control_notes["func"]="xt_buildeditcontrol";
$control_notes["params"]["field"]="notes";
$control_notes["params"]["mode"]="search";
$xt->assignbyref("notes_editcontrol",$control_notes);
$control1_notes=$control_notes;
$control1_notes["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_notes["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["notes"];
$xt->assignbyref("notes_editcontrol1",$control1_notes);
	
$xt->assign_section("notes_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"notes\">","");
$notes_notbox="name=\"not_notes\"";
if($not)
	$notes_notbox=" checked";
$xt->assign("notes_notbox",$notes_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_notes\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_notes",$searchtype);
//	edit format
$editformats["notes"]=EDIT_FORMAT_TEXT_FIELD;
// last_updated 
$opt="";
$not=false;
$control_last_updated=array();
$control_last_updated["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["last_updated"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["last_updated"];
	$control_last_updated["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["last_updated"];
}
$control_last_updated["func"]="xt_buildeditcontrol";
$control_last_updated["params"]["field"]="last_updated";
$control_last_updated["params"]["mode"]="search";
$xt->assignbyref("last_updated_editcontrol",$control_last_updated);
$control1_last_updated=$control_last_updated;
$control1_last_updated["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_last_updated["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["last_updated"];
$xt->assignbyref("last_updated_editcontrol1",$control1_last_updated);
	
$xt->assign_section("last_updated_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"last_updated\">","");
$last_updated_notbox="name=\"not_last_updated\"";
if($not)
	$last_updated_notbox=" checked";
$xt->assign("last_updated_notbox",$last_updated_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_last_updated\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_last_updated",$searchtype);
//	edit format
$editformats["last_updated"]="Date";
// uname 
$opt="";
$not=false;
$control_uname=array();
$control_uname["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["uname"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["uname"];
	$control_uname["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["uname"];
}
$control_uname["func"]="xt_buildeditcontrol";
$control_uname["params"]["field"]="uname";
$control_uname["params"]["mode"]="search";
$xt->assignbyref("uname_editcontrol",$control_uname);
$control1_uname=$control_uname;
$control1_uname["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_uname["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["uname"];
$xt->assignbyref("uname_editcontrol1",$control1_uname);
	
$xt->assign_section("uname_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"uname\">","");
$uname_notbox="name=\"not_uname\"";
if($not)
	$uname_notbox=" checked";
$xt->assign("uname_notbox",$uname_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_uname\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_uname",$searchtype);
//	edit format
$editformats["uname"]="Text field";
// lname 
$opt="";
$not=false;
$control_lname=array();
$control_lname["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["lname"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["lname"];
	$control_lname["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["lname"];
}
$control_lname["func"]="xt_buildeditcontrol";
$control_lname["params"]["field"]="lname";
$control_lname["params"]["mode"]="search";
$xt->assignbyref("lname_editcontrol",$control_lname);
$control1_lname=$control_lname;
$control1_lname["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_lname["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["lname"];
$xt->assignbyref("lname_editcontrol1",$control1_lname);
	
$xt->assign_section("lname_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"lname\">","");
$lname_notbox="name=\"not_lname\"";
if($not)
	$lname_notbox=" checked";
$xt->assign("lname_notbox",$lname_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_lname\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_lname",$searchtype);
//	edit format
$editformats["lname"]="Text field";
// dept 
$opt="";
$not=false;
$control_dept=array();
$control_dept["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["dept"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["dept"];
	$control_dept["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["dept"];
}
$control_dept["func"]="xt_buildeditcontrol";
$control_dept["params"]["field"]="dept";
$control_dept["params"]["mode"]="search";
$xt->assignbyref("dept_editcontrol",$control_dept);
$control1_dept=$control_dept;
$control1_dept["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_dept["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["dept"];
$xt->assignbyref("dept_editcontrol1",$control1_dept);
	
$xt->assign_section("dept_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"dept\">","");
$dept_notbox="name=\"not_dept\"";
if($not)
	$dept_notbox=" checked";
$xt->assign("dept_notbox",$dept_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_dept\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_dept",$searchtype);
//	edit format
$editformats["dept"]="Text field";
// mach_name 
$opt="";
$not=false;
$control_mach_name=array();
$control_mach_name["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["mach_name"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["mach_name"];
	$control_mach_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["mach_name"];
}
$control_mach_name["func"]="xt_buildeditcontrol";
$control_mach_name["params"]["field"]="mach_name";
$control_mach_name["params"]["mode"]="search";
$xt->assignbyref("mach_name_editcontrol",$control_mach_name);
$control1_mach_name=$control_mach_name;
$control1_mach_name["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_mach_name["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["mach_name"];
$xt->assignbyref("mach_name_editcontrol1",$control1_mach_name);
	
$xt->assign_section("mach_name_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"mach_name\">","");
$mach_name_notbox="name=\"not_mach_name\"";
if($not)
	$mach_name_notbox=" checked";
$xt->assign("mach_name_notbox",$mach_name_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_mach_name\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_mach_name",$searchtype);
//	edit format
$editformats["mach_name"]="Text field";
// ip_addr 
$opt="";
$not=false;
$control_ip_addr=array();
$control_ip_addr["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["ip_addr"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["ip_addr"];
	$control_ip_addr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["ip_addr"];
}
$control_ip_addr["func"]="xt_buildeditcontrol";
$control_ip_addr["params"]["field"]="ip_addr";
$control_ip_addr["params"]["mode"]="search";
$xt->assignbyref("ip_addr_editcontrol",$control_ip_addr);
$control1_ip_addr=$control_ip_addr;
$control1_ip_addr["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_ip_addr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["ip_addr"];
$xt->assignbyref("ip_addr_editcontrol1",$control1_ip_addr);
	
$xt->assign_section("ip_addr_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"ip_addr\">","");
$ip_addr_notbox="name=\"not_ip_addr\"";
if($not)
	$ip_addr_notbox=" checked";
$xt->assign("ip_addr_notbox",$ip_addr_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_ip_addr\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_ip_addr",$searchtype);
//	edit format
$editformats["ip_addr"]="Text field";
// mac_addr 
$opt="";
$not=false;
$control_mac_addr=array();
$control_mac_addr["params"] = array();
if(@$_SESSION[$strTableName."_search"]==2)
{
	$opt=@$_SESSION[$strTableName."_asearchopt"]["mac_addr"];
	$not=@$_SESSION[$strTableName."_asearchnot"]["mac_addr"];
	$control_mac_addr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor"]["mac_addr"];
}
$control_mac_addr["func"]="xt_buildeditcontrol";
$control_mac_addr["params"]["field"]="mac_addr";
$control_mac_addr["params"]["mode"]="search";
$xt->assignbyref("mac_addr_editcontrol",$control_mac_addr);
$control1_mac_addr=$control_mac_addr;
$control1_mac_addr["params"]["second"]=true;
if(@$_SESSION[$strTableName."_search"]==2)
	$control1_mac_addr["params"]["value"]=@$_SESSION[$strTableName."_asearchfor2"]["mac_addr"];
$xt->assignbyref("mac_addr_editcontrol1",$control1_mac_addr);
	
$xt->assign_section("mac_addr_fieldblock","<input type=\"Hidden\" name=\"asearchfield[]\" value=\"mac_addr\">","");
$mac_addr_notbox="name=\"not_mac_addr\"";
if($not)
	$mac_addr_notbox=" checked";
$xt->assign("mac_addr_notbox",$mac_addr_notbox);

//	write search options
$options="";
$options.="<OPTION VALUE=\"Contains\" ".(($opt=="Contains")?"selected":"").">"."Contains"."</option>";
$options.="<OPTION VALUE=\"Equals\" ".(($opt=="Equals")?"selected":"").">"."Equals"."</option>";
$options.="<OPTION VALUE=\"Starts with ...\" ".(($opt=="Starts with ...")?"selected":"").">"."Starts with ..."."</option>";
$options.="<OPTION VALUE=\"More than ...\" ".(($opt=="More than ...")?"selected":"").">"."More than ..."."</option>";
$options.="<OPTION VALUE=\"Less than ...\" ".(($opt=="Less than ...")?"selected":"").">"."Less than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or more than ...\" ".(($opt=="Equal or more than ...")?"selected":"").">"."Equal or more than ..."."</option>";
$options.="<OPTION VALUE=\"Equal or less than ...\" ".(($opt=="Equal or less than ...")?"selected":"").">"."Equal or less than ..."."</option>";
$options.="<OPTION VALUE=\"Between\" ".(($opt=="Between")?"selected":"").">"."Between"."</option>";
$options.="<OPTION VALUE=\"Empty\" ".(($opt=="Empty")?"selected":"").">"."Empty"."</option>";
$searchtype = "<SELECT ID=\"SearchOption\" NAME=\"asearchopt_mac_addr\" SIZE=1 onChange=\"return ShowHideControls();\">";
$searchtype .= $options;
$searchtype .= "</SELECT>";
$xt->assign("searchtype_mac_addr",$searchtype);
//	edit format
$editformats["mac_addr"]="Text field";

$linkdata="";

$linkdata .= "<script type=\"text/javascript\">\r\n";

if ($useAJAX) {
}
else
{
}
$linkdata.="</script>\r\n";


$body=array();
$body["begin"]=$includes;
$body["end"]=$linkdata."<script>ShowHideControls()</script>";
$xt->assignbyref("body",$body);

$contents_block=array();
$contents_block["begin"]="<form method=\"POST\" ";
if(isset( $_GET["rname"]))
{
	$contents_block["begin"].="action=\"dreport.php?rname=".$_GET["rname"]."\" ";
}	
else if(isset( $_GET["cname"]))
{
	$contents_block["begin"].="action=\"dchart.php?cname=".$_GET["cname"]."\" ";
}	
else
{
$contents_block["begin"].="action=\"acadcomp_list.php\" ";
}
$contents_block["begin"].="name=\"editform\"><input type=\"hidden\" id=\"a\" name=\"a\" value=\"advsearch\">";
$contents_block["end"]="</form>";
$xt->assignbyref("contents_block",$contents_block);

$xt->assign("searchbutton_attrs","name=\"SearchButton\" onclick=\"javascript:document.forms.editform.submit();\"");
$xt->assign("resetbutton_attrs","onclick=\"return ResetControls();\"");

$xt->assign("backbutton_attrs","onclick=\"javascript: document.forms.editform.a.value='return'; document.forms.editform.submit();\"");

$xt->assign("conditions_block",true);
$xt->assign("search_button",true);
$xt->assign("reset_button",true);
$xt->assign("back_button",true);

if ( isset( $_GET["rname"] ) ) {
	$xt->assign("dynamic", "true");
	$xt->assign("rname", $_GET["rname"]);
}
if ( isset( $_GET["cname"] ) ) {
	$xt->assign("dynamic", "true");
	$xt->assign("cname", $_GET["cname"]);
}
	
$templatefile = "acadcomp_search.htm";
if(function_exists("BeforeShowSearch"))
	BeforeShowSearch($xt,$templatefile);

$xt->display($templatefile);

?>