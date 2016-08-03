<?php

//	field labels
$fieldLabelsacadcomp = array();
$fieldLabelsacadcomp["English"]=array();
$fieldLabelsacadcomp["English"]["record_id"] = "Record Id";
$fieldLabelsacadcomp["English"]["campus"] = "Campus";
$fieldLabelsacadcomp["English"]["bldg"] = "Bldg";
$fieldLabelsacadcomp["English"]["floor"] = "Floor";
$fieldLabelsacadcomp["English"]["room"] = "Room";
$fieldLabelsacadcomp["English"]["labselect"] = "Labselect";
$fieldLabelsacadcomp["English"]["mach_type"] = "Mach Type";
$fieldLabelsacadcomp["English"]["platform"] = "Platform";
$fieldLabelsacadcomp["English"]["model"] = "Model";
$fieldLabelsacadcomp["English"]["other_model"] = "Other Model";
$fieldLabelsacadcomp["English"]["asset_tag"] = "Asset Tag";
$fieldLabelsacadcomp["English"]["serial"] = "Serial";
$fieldLabelsacadcomp["English"]["service_tag"] = "Service Tag";
$fieldLabelsacadcomp["English"]["proc_speed"] = "Proc Speed";
$fieldLabelsacadcomp["English"]["proc_type"] = "Proc Type";
$fieldLabelsacadcomp["English"]["ram"] = "Ram";
$fieldLabelsacadcomp["English"]["disk_size"] = "Disk Size";
$fieldLabelsacadcomp["English"]["optical_drive"] = "Optical Drive";
$fieldLabelsacadcomp["English"]["display_model"] = "Display Model";
$fieldLabelsacadcomp["English"]["display_size"] = "Display Size";
$fieldLabelsacadcomp["English"]["display_asset"] = "Display Asset";
$fieldLabelsacadcomp["English"]["display_serial"] = "Display Serial";
$fieldLabelsacadcomp["English"]["notes"] = "Notes";
$fieldLabelsacadcomp["English"]["last_updated"] = "Last Updated";
$fieldLabelsacadcomp["English"]["uname"] = "Uname";
$fieldLabelsacadcomp["English"]["lname"] = "Lname";
$fieldLabelsacadcomp["English"]["dept"] = "Dept";
$fieldLabelsacadcomp["English"]["mach_name"] = "Mach Name";
$fieldLabelsacadcomp["English"]["ip_addr"] = "Ip Addr";
$fieldLabelsacadcomp["English"]["mac_addr"] = "Mac Addr";


$tdataacadcomp=array();
	 $tdataacadcomp[".NumberOfChars"]=80; 
	$tdataacadcomp[".ShortName"]="acadcomp";
	$tdataacadcomp[".OwnerID"]="";
	$tdataacadcomp[".OriginalTable"]="acadcomp";

	$keys=array();
	$keys[]="record_id";
	$tdataacadcomp[".Keys"]=$keys;

	
//	record_id
	$fdata = array();
	 $fdata["Label"]="Record Id"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "record_id";
		$fdata["FullName"]= "record_id";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 1;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["record_id"]=$fdata;
	
//	campus
	$fdata = array();
	 $fdata["Label"]="Campus"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "campus";
		$fdata["FullName"]= "campus";
	
	
	
	
	$fdata["Index"]= 2;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["campus"]=$fdata;
	
//	bldg
	$fdata = array();
	 $fdata["Label"]="Bldg"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "bldg";
		$fdata["FullName"]= "bldg";
	
	
	
	
	$fdata["Index"]= 3;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["bldg"]=$fdata;
	
//	floor
	$fdata = array();
	 $fdata["Label"]="Floor"; 
	
	
	$fdata["FieldType"]= 3;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "floor";
		$fdata["FullName"]= "floor";
	
	
	
	
	$fdata["Index"]= 4;
	
			$fdata["EditParams"]="";
						$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["floor"]=$fdata;
	
//	room
	$fdata = array();
	 $fdata["Label"]="Room"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "room";
		$fdata["FullName"]= "room";
	
	
	
	
	$fdata["Index"]= 5;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=75";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["room"]=$fdata;
	
//	labselect
	$fdata = array();
	 $fdata["Label"]="Labselect"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "labselect";
		$fdata["FullName"]= "labselect";
	
	
	
	
	$fdata["Index"]= 6;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=40";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["labselect"]=$fdata;
	
//	mach_type
	$fdata = array();
	 $fdata["Label"]="Mach Type"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "mach_type";
		$fdata["FullName"]= "mach_type";
	
	
	
	
	$fdata["Index"]= 7;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=75";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["mach_type"]=$fdata;
	
//	platform
	$fdata = array();
	 $fdata["Label"]="Platform"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "platform";
		$fdata["FullName"]= "platform";
	
	
	
	
	$fdata["Index"]= 8;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=10";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["platform"]=$fdata;
	
//	model
	$fdata = array();
	 $fdata["Label"]="Model"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "model";
		$fdata["FullName"]= "model";
	
	
	
	
	$fdata["Index"]= 9;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["model"]=$fdata;
	
//	other_model
	$fdata = array();
	 $fdata["Label"]="Other Model"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "other_model";
		$fdata["FullName"]= "other_model";
	
	
	
	
	$fdata["Index"]= 10;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["other_model"]=$fdata;
	
//	asset_tag
	$fdata = array();
	 $fdata["Label"]="Asset Tag"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "asset_tag";
		$fdata["FullName"]= "asset_tag";
	
	
	
	
	$fdata["Index"]= 11;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=15";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["asset_tag"]=$fdata;
	
//	serial
	$fdata = array();
	 $fdata["Label"]="Serial"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "serial";
		$fdata["FullName"]= "serial";
	
	
	
	
	$fdata["Index"]= 12;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["serial"]=$fdata;
	
//	service_tag
	$fdata = array();
	 $fdata["Label"]="Service Tag"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "service_tag";
		$fdata["FullName"]= "service_tag";
	
	
	
	
	$fdata["Index"]= 13;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["service_tag"]=$fdata;
	
//	proc_speed
	$fdata = array();
	 $fdata["Label"]="Proc Speed"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "proc_speed";
		$fdata["FullName"]= "proc_speed";
	
	
	
	
	$fdata["Index"]= 14;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["proc_speed"]=$fdata;
	
//	proc_type
	$fdata = array();
	 $fdata["Label"]="Proc Type"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "proc_type";
		$fdata["FullName"]= "proc_type";
	
	
	
	
	$fdata["Index"]= 15;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=30";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["proc_type"]=$fdata;
	
//	ram
	$fdata = array();
	 $fdata["Label"]="Ram"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ram";
		$fdata["FullName"]= "ram";
	
	
	
	
	$fdata["Index"]= 16;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["ram"]=$fdata;
	
//	disk_size
	$fdata = array();
	 $fdata["Label"]="Disk Size"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "disk_size";
		$fdata["FullName"]= "disk_size";
	
	
	
	
	$fdata["Index"]= 17;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["disk_size"]=$fdata;
	
//	optical_drive
	$fdata = array();
	 $fdata["Label"]="Optical Drive"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "optical_drive";
		$fdata["FullName"]= "optical_drive";
	
	
	
	
	$fdata["Index"]= 18;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=40";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["optical_drive"]=$fdata;
	
//	display_model
	$fdata = array();
	 $fdata["Label"]="Display Model"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "display_model";
		$fdata["FullName"]= "display_model";
	
	
	
	
	$fdata["Index"]= 19;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["display_model"]=$fdata;
	
//	display_size
	$fdata = array();
	 $fdata["Label"]="Display Size"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "display_size";
		$fdata["FullName"]= "display_size";
	
	
	
	
	$fdata["Index"]= 20;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["display_size"]=$fdata;
	
//	display_asset
	$fdata = array();
	 $fdata["Label"]="Display Asset"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "display_asset";
		$fdata["FullName"]= "display_asset";
	
	
	
	
	$fdata["Index"]= 21;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["display_asset"]=$fdata;
	
//	display_serial
	$fdata = array();
	 $fdata["Label"]="Display Serial"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "display_serial";
		$fdata["FullName"]= "display_serial";
	
	
	
	
	$fdata["Index"]= 22;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["display_serial"]=$fdata;
	
//	notes
	$fdata = array();
	 $fdata["Label"]="Notes"; 
	
	
	$fdata["FieldType"]= 201;
	$fdata["EditFormat"]= "Text area";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "notes";
		$fdata["FullName"]= "notes";
	
	
	
	
	$fdata["Index"]= 23;
	
		$fdata["EditParams"]="";
			$fdata["EditParams"].= " rows=250";
		$fdata["nRows"] = 250;
			$fdata["EditParams"].= " cols=500";
		$fdata["nCols"] = 500;
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["notes"]=$fdata;
	
//	last_updated
	$fdata = array();
	 $fdata["Label"]="Last Updated"; 
	
	
	$fdata["FieldType"]= 135;
	$fdata["EditFormat"]= "Date";
	$fdata["ViewFormat"]= "Short Date";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "last_updated";
		$fdata["FullName"]= "last_updated";
	 $fdata["IsRequired"]=true; 
	
	
	
	$fdata["Index"]= 24;
	 $fdata["DateEditType"]=13; 
						$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["last_updated"]=$fdata;
	
//	uname
	$fdata = array();
	 $fdata["Label"]="Uname"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "uname";
		$fdata["FullName"]= "uname";
	
	
	
	
	$fdata["Index"]= 25;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["uname"]=$fdata;
	
//	lname
	$fdata = array();
	 $fdata["Label"]="Lname"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "lname";
		$fdata["FullName"]= "lname";
	
	
	
	
	$fdata["Index"]= 26;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=40";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["lname"]=$fdata;
	
//	dept
	$fdata = array();
	 $fdata["Label"]="Dept"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "dept";
		$fdata["FullName"]= "dept";
	
	
	
	
	$fdata["Index"]= 27;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["dept"]=$fdata;
	
//	mach_name
	$fdata = array();
	 $fdata["Label"]="Mach Name"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "mach_name";
		$fdata["FullName"]= "mach_name";
	
	
	
	
	$fdata["Index"]= 28;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=50";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["mach_name"]=$fdata;
	
//	ip_addr
	$fdata = array();
	 $fdata["Label"]="Ip Addr"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "ip_addr";
		$fdata["FullName"]= "ip_addr";
	
	
	
	
	$fdata["Index"]= 29;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["ip_addr"]=$fdata;
	
//	mac_addr
	$fdata = array();
	 $fdata["Label"]="Mac Addr"; 
	
	
	$fdata["FieldType"]= 200;
	$fdata["EditFormat"]= "Text field";
	$fdata["ViewFormat"]= "";
	
	
		
				$fdata["NeedEncode"]=true;
	
	$fdata["GoodName"]= "mac_addr";
		$fdata["FullName"]= "mac_addr";
	
	
	
	
	$fdata["Index"]= 30;
	
			$fdata["EditParams"]="";
			$fdata["EditParams"].= " maxlength=20";
					$fdata["FieldPermissions"]=true;
			$fdata["ListPage"]=true;
	$tdataacadcomp["mac_addr"]=$fdata;
$tables_data["acadcomp"]=&$tdataacadcomp;
$field_labels["acadcomp"] = &$fieldLabelsacadcomp;


?>