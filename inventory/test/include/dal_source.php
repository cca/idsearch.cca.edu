<?php
function CustomQuery($dalSQL)
{
	global $conn;
	$rs = db_query($dalSQL,$conn);
	//$data = db_fetch_array($rs);
	//return new DalRecordset($rs);
  return $rs;
}

function UsersTableName()
{
	return "";
}

class tDAL
{
	var $acadcomp;
  function Table($strTable)
  {
          if(strtoupper($strTable)==strtoupper("acadcomp"))
              return $this->acadcomp;
  }
}

$dal = new tDAL;


class class_acadcomp
{
	var $m_TableName;
	var $m_GoodTableName;
	var $m_fldrecord_id;
	var $m_fldcampus;
	var $m_fldbldg;
	var $m_fldfloor;
	var $m_fldroom;
	var $m_fldlabselect;
	var $m_fldmach_type;
	var $m_fldplatform;
	var $m_fldmodel;
	var $m_fldother_model;
	var $m_fldasset_tag;
	var $m_fldserial;
	var $m_fldservice_tag;
	var $m_fldproc_speed;
	var $m_fldproc_type;
	var $m_fldram;
	var $m_flddisk_size;
	var $m_fldoptical_drive;
	var $m_flddisplay_model;
	var $m_flddisplay_size;
	var $m_flddisplay_asset;
	var $m_flddisplay_serial;
	var $m_fldnotes;
	var $m_fldlast_updated;
	var $m_flduname;
	var $m_fldlname;
	var $m_flddept;
	var $m_fldmach_name;
	var $m_fldip_addr;
	var $m_fldmac_addr;

	var $Param = array();
	var $Value = array();
	
	var $record_id = array();
	var $campus = array();
	var $bldg = array();
	var $floor = array();
	var $room = array();
	var $labselect = array();
	var $mach_type = array();
	var $platform = array();
	var $model = array();
	var $other_model = array();
	var $asset_tag = array();
	var $serial = array();
	var $service_tag = array();
	var $proc_speed = array();
	var $proc_type = array();
	var $ram = array();
	var $disk_size = array();
	var $optical_drive = array();
	var $display_model = array();
	var $display_size = array();
	var $display_asset = array();
	var $display_serial = array();
	var $notes = array();
	var $last_updated = array();
	var $uname = array();
	var $lname = array();
	var $dept = array();
	var $mach_name = array();
	var $ip_addr = array();
	var $mac_addr = array();
	
	var $m_ChangedFields;
	var $m_UpdateParam;
	var $m_UpdateValue;

	
	function class_acadcomp()
	{
		$this->m_TableName = "acadcomp";
		$this->m_GoodTableName = AddTableWrappers($this->m_TableName);	
	}

function TableName()
{
	$this->m_TableName = "acadcomp";
	$this->m_GoodTableName = AddTableWrappers($this->m_TableName);
	return $this->m_GoodTableName;
} 

function Add() 
{
	global $conn;

	$insertFields="";
	$insertValues="";
		

		if ($this->record_id)
		{
			$this->Value["record_id"] = $this->record_id;
		    $this->m_fldrecord_id = $this->record_id;
		}	

		if ($this->campus)
		{
			$this->Value["campus"] = $this->campus;
		    $this->m_fldcampus = $this->campus;
		}	

		if ($this->bldg)
		{
			$this->Value["bldg"] = $this->bldg;
		    $this->m_fldbldg = $this->bldg;
		}	

		if ($this->floor)
		{
			$this->Value["floor"] = $this->floor;
		    $this->m_fldfloor = $this->floor;
		}	

		if ($this->room)
		{
			$this->Value["room"] = $this->room;
		    $this->m_fldroom = $this->room;
		}	

		if ($this->labselect)
		{
			$this->Value["labselect"] = $this->labselect;
		    $this->m_fldlabselect = $this->labselect;
		}	

		if ($this->mach_type)
		{
			$this->Value["mach_type"] = $this->mach_type;
		    $this->m_fldmach_type = $this->mach_type;
		}	

		if ($this->platform)
		{
			$this->Value["platform"] = $this->platform;
		    $this->m_fldplatform = $this->platform;
		}	

		if ($this->model)
		{
			$this->Value["model"] = $this->model;
		    $this->m_fldmodel = $this->model;
		}	

		if ($this->other_model)
		{
			$this->Value["other_model"] = $this->other_model;
		    $this->m_fldother_model = $this->other_model;
		}	

		if ($this->asset_tag)
		{
			$this->Value["asset_tag"] = $this->asset_tag;
		    $this->m_fldasset_tag = $this->asset_tag;
		}	

		if ($this->serial)
		{
			$this->Value["serial"] = $this->serial;
		    $this->m_fldserial = $this->serial;
		}	

		if ($this->service_tag)
		{
			$this->Value["service_tag"] = $this->service_tag;
		    $this->m_fldservice_tag = $this->service_tag;
		}	

		if ($this->proc_speed)
		{
			$this->Value["proc_speed"] = $this->proc_speed;
		    $this->m_fldproc_speed = $this->proc_speed;
		}	

		if ($this->proc_type)
		{
			$this->Value["proc_type"] = $this->proc_type;
		    $this->m_fldproc_type = $this->proc_type;
		}	

		if ($this->ram)
		{
			$this->Value["ram"] = $this->ram;
		    $this->m_fldram = $this->ram;
		}	

		if ($this->disk_size)
		{
			$this->Value["disk_size"] = $this->disk_size;
		    $this->m_flddisk_size = $this->disk_size;
		}	

		if ($this->optical_drive)
		{
			$this->Value["optical_drive"] = $this->optical_drive;
		    $this->m_fldoptical_drive = $this->optical_drive;
		}	

		if ($this->display_model)
		{
			$this->Value["display_model"] = $this->display_model;
		    $this->m_flddisplay_model = $this->display_model;
		}	

		if ($this->display_size)
		{
			$this->Value["display_size"] = $this->display_size;
		    $this->m_flddisplay_size = $this->display_size;
		}	

		if ($this->display_asset)
		{
			$this->Value["display_asset"] = $this->display_asset;
		    $this->m_flddisplay_asset = $this->display_asset;
		}	

		if ($this->display_serial)
		{
			$this->Value["display_serial"] = $this->display_serial;
		    $this->m_flddisplay_serial = $this->display_serial;
		}	

		if ($this->notes)
		{
			$this->Value["notes"] = $this->notes;
		    $this->m_fldnotes = $this->notes;
		}	

		if ($this->last_updated)
		{
			$this->Value["last_updated"] = $this->last_updated;
		    $this->m_fldlast_updated = $this->last_updated;
		}	

		if ($this->uname)
		{
			$this->Value["uname"] = $this->uname;
		    $this->m_flduname = $this->uname;
		}	

		if ($this->lname)
		{
			$this->Value["lname"] = $this->lname;
		    $this->m_fldlname = $this->lname;
		}	

		if ($this->dept)
		{
			$this->Value["dept"] = $this->dept;
		    $this->m_flddept = $this->dept;
		}	

		if ($this->mach_name)
		{
			$this->Value["mach_name"] = $this->mach_name;
		    $this->m_fldmach_name = $this->mach_name;
		}	

		if ($this->ip_addr)
		{
			$this->Value["ip_addr"] = $this->ip_addr;
		    $this->m_fldip_addr = $this->ip_addr;
		}	

		if ($this->mac_addr)
		{
			$this->Value["mac_addr"] = $this->mac_addr;
		    $this->m_fldmac_addr = $this->mac_addr;
		}	
	
		if ($this->Value["record_id"])
		    $this->m_fldrecord_id = $this->Value["record_id"];	
		if ($this->Value["campus"])
		    $this->m_fldcampus = $this->Value["campus"];	
		if ($this->Value["bldg"])
		    $this->m_fldbldg = $this->Value["bldg"];	
		if ($this->Value["floor"])
		    $this->m_fldfloor = $this->Value["floor"];	
		if ($this->Value["room"])
		    $this->m_fldroom = $this->Value["room"];	
		if ($this->Value["labselect"])
		    $this->m_fldlabselect = $this->Value["labselect"];	
		if ($this->Value["mach_type"])
		    $this->m_fldmach_type = $this->Value["mach_type"];	
		if ($this->Value["platform"])
		    $this->m_fldplatform = $this->Value["platform"];	
		if ($this->Value["model"])
		    $this->m_fldmodel = $this->Value["model"];	
		if ($this->Value["other_model"])
		    $this->m_fldother_model = $this->Value["other_model"];	
		if ($this->Value["asset_tag"])
		    $this->m_fldasset_tag = $this->Value["asset_tag"];	
		if ($this->Value["serial"])
		    $this->m_fldserial = $this->Value["serial"];	
		if ($this->Value["service_tag"])
		    $this->m_fldservice_tag = $this->Value["service_tag"];	
		if ($this->Value["proc_speed"])
		    $this->m_fldproc_speed = $this->Value["proc_speed"];	
		if ($this->Value["proc_type"])
		    $this->m_fldproc_type = $this->Value["proc_type"];	
		if ($this->Value["ram"])
		    $this->m_fldram = $this->Value["ram"];	
		if ($this->Value["disk_size"])
		    $this->m_flddisk_size = $this->Value["disk_size"];	
		if ($this->Value["optical_drive"])
		    $this->m_fldoptical_drive = $this->Value["optical_drive"];	
		if ($this->Value["display_model"])
		    $this->m_flddisplay_model = $this->Value["display_model"];	
		if ($this->Value["display_size"])
		    $this->m_flddisplay_size = $this->Value["display_size"];	
		if ($this->Value["display_asset"])
		    $this->m_flddisplay_asset = $this->Value["display_asset"];	
		if ($this->Value["display_serial"])
		    $this->m_flddisplay_serial = $this->Value["display_serial"];	
		if ($this->Value["notes"])
		    $this->m_fldnotes = $this->Value["notes"];	
		if ($this->Value["last_updated"])
		    $this->m_fldlast_updated = $this->Value["last_updated"];	
		if ($this->Value["uname"])
		    $this->m_flduname = $this->Value["uname"];	
		if ($this->Value["lname"])
		    $this->m_fldlname = $this->Value["lname"];	
		if ($this->Value["dept"])
		    $this->m_flddept = $this->Value["dept"];	
		if ($this->Value["mach_name"])
		    $this->m_fldmach_name = $this->Value["mach_name"];	
		if ($this->Value["ip_addr"])
		    $this->m_fldip_addr = $this->Value["ip_addr"];	
		if ($this->Value["mac_addr"])
		    $this->m_fldmac_addr = $this->Value["mac_addr"];	

		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("record_id"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("record_id").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["record_id"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["record_id"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("campus"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("campus").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["campus"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["campus"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("bldg"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("bldg").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["bldg"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["bldg"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("floor"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("floor").",";
			if (NeedQuotes(3))
				$insertValues.= "'".db_addslashes($this->Value["floor"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["floor"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("room"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("room").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["room"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["room"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("labselect"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("labselect").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["labselect"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["labselect"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_type"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("mach_type").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["mach_type"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["mach_type"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("platform"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("platform").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["platform"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["platform"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("model"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("model").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["model"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["model"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("other_model"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("other_model").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["other_model"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["other_model"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("asset_tag"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("asset_tag").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["asset_tag"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["asset_tag"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("serial"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("serial").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["serial"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["serial"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("service_tag"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("service_tag").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["service_tag"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["service_tag"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_speed"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("proc_speed").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["proc_speed"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["proc_speed"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_type"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("proc_type").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["proc_type"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["proc_type"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ram"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ram").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["ram"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ram"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("disk_size"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("disk_size").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["disk_size"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["disk_size"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("optical_drive"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("optical_drive").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["optical_drive"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["optical_drive"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_model"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("display_model").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["display_model"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["display_model"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_size"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("display_size").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["display_size"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["display_size"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_asset"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("display_asset").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["display_asset"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["display_asset"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_serial"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("display_serial").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["display_serial"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["display_serial"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("notes"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("notes").",";
			if (NeedQuotes(201))
				$insertValues.= "'".db_addslashes($this->Value["notes"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["notes"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("last_updated"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("last_updated").",";
			if (NeedQuotes(135))
				$insertValues.= "'".db_addslashes($this->Value["last_updated"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["last_updated"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("uname"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("uname").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["uname"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["uname"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("lname"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("lname").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["lname"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["lname"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("dept"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("dept").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["dept"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["dept"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_name"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("mach_name").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["mach_name"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["mach_name"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ip_addr"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("ip_addr").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["ip_addr"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["ip_addr"]) . ",";		

		}
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mac_addr"))
				$flag=true;
				
		if ($flag)
		{
			$insertFields.= AddFieldWrappers("mac_addr").",";
			if (NeedQuotes(200))
				$insertValues.= "'".db_addslashes($this->Value["mac_addr"]) . "',";
			else
				$insertValues.= "".db_addslashes($this->Value["mac_addr"]) . ",";		

		}

		
	if ($insertFields!="" && $insertValues!="")		
	{
		$insertFields = substr($insertFields,0,-1);
		$insertValues = substr($insertValues,0,-1);
		$dalSQL = "insert into ".$this->m_GoodTableName." (".$insertFields.") values (".$insertValues.")";
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["record_id"]);
        unset($this->Value["campus"]);
        unset($this->Value["bldg"]);
        unset($this->Value["floor"]);
        unset($this->Value["room"]);
        unset($this->Value["labselect"]);
        unset($this->Value["mach_type"]);
        unset($this->Value["platform"]);
        unset($this->Value["model"]);
        unset($this->Value["other_model"]);
        unset($this->Value["asset_tag"]);
        unset($this->Value["serial"]);
        unset($this->Value["service_tag"]);
        unset($this->Value["proc_speed"]);
        unset($this->Value["proc_type"]);
        unset($this->Value["ram"]);
        unset($this->Value["disk_size"]);
        unset($this->Value["optical_drive"]);
        unset($this->Value["display_model"]);
        unset($this->Value["display_size"]);
        unset($this->Value["display_asset"]);
        unset($this->Value["display_serial"]);
        unset($this->Value["notes"]);
        unset($this->Value["last_updated"]);
        unset($this->Value["uname"]);
        unset($this->Value["lname"]);
        unset($this->Value["dept"]);
        unset($this->Value["mach_name"]);
        unset($this->Value["ip_addr"]);
        unset($this->Value["mac_addr"]);
   // unset($this->m_ChangedFields);

	}
	
function Delete()
{
	global $conn;
	$deleteFields="";

		if ($this->record_id)
		{
			$this->Value["record_id"] = $this->record_id;
			$this->m_fldrecord_id = $this->record_id;
		}	
		if ($this->campus)
		{
			$this->Value["campus"] = $this->campus;
			$this->m_fldcampus = $this->campus;
		}	
		if ($this->bldg)
		{
			$this->Value["bldg"] = $this->bldg;
			$this->m_fldbldg = $this->bldg;
		}	
		if ($this->floor)
		{
			$this->Value["floor"] = $this->floor;
			$this->m_fldfloor = $this->floor;
		}	
		if ($this->room)
		{
			$this->Value["room"] = $this->room;
			$this->m_fldroom = $this->room;
		}	
		if ($this->labselect)
		{
			$this->Value["labselect"] = $this->labselect;
			$this->m_fldlabselect = $this->labselect;
		}	
		if ($this->mach_type)
		{
			$this->Value["mach_type"] = $this->mach_type;
			$this->m_fldmach_type = $this->mach_type;
		}	
		if ($this->platform)
		{
			$this->Value["platform"] = $this->platform;
			$this->m_fldplatform = $this->platform;
		}	
		if ($this->model)
		{
			$this->Value["model"] = $this->model;
			$this->m_fldmodel = $this->model;
		}	
		if ($this->other_model)
		{
			$this->Value["other_model"] = $this->other_model;
			$this->m_fldother_model = $this->other_model;
		}	
		if ($this->asset_tag)
		{
			$this->Value["asset_tag"] = $this->asset_tag;
			$this->m_fldasset_tag = $this->asset_tag;
		}	
		if ($this->serial)
		{
			$this->Value["serial"] = $this->serial;
			$this->m_fldserial = $this->serial;
		}	
		if ($this->service_tag)
		{
			$this->Value["service_tag"] = $this->service_tag;
			$this->m_fldservice_tag = $this->service_tag;
		}	
		if ($this->proc_speed)
		{
			$this->Value["proc_speed"] = $this->proc_speed;
			$this->m_fldproc_speed = $this->proc_speed;
		}	
		if ($this->proc_type)
		{
			$this->Value["proc_type"] = $this->proc_type;
			$this->m_fldproc_type = $this->proc_type;
		}	
		if ($this->ram)
		{
			$this->Value["ram"] = $this->ram;
			$this->m_fldram = $this->ram;
		}	
		if ($this->disk_size)
		{
			$this->Value["disk_size"] = $this->disk_size;
			$this->m_flddisk_size = $this->disk_size;
		}	
		if ($this->optical_drive)
		{
			$this->Value["optical_drive"] = $this->optical_drive;
			$this->m_fldoptical_drive = $this->optical_drive;
		}	
		if ($this->display_model)
		{
			$this->Value["display_model"] = $this->display_model;
			$this->m_flddisplay_model = $this->display_model;
		}	
		if ($this->display_size)
		{
			$this->Value["display_size"] = $this->display_size;
			$this->m_flddisplay_size = $this->display_size;
		}	
		if ($this->display_asset)
		{
			$this->Value["display_asset"] = $this->display_asset;
			$this->m_flddisplay_asset = $this->display_asset;
		}	
		if ($this->display_serial)
		{
			$this->Value["display_serial"] = $this->display_serial;
			$this->m_flddisplay_serial = $this->display_serial;
		}	
		if ($this->notes)
		{
			$this->Value["notes"] = $this->notes;
			$this->m_fldnotes = $this->notes;
		}	
		if ($this->last_updated)
		{
			$this->Value["last_updated"] = $this->last_updated;
			$this->m_fldlast_updated = $this->last_updated;
		}	
		if ($this->uname)
		{
			$this->Value["uname"] = $this->uname;
			$this->m_flduname = $this->uname;
		}	
		if ($this->lname)
		{
			$this->Value["lname"] = $this->lname;
			$this->m_fldlname = $this->lname;
		}	
		if ($this->dept)
		{
			$this->Value["dept"] = $this->dept;
			$this->m_flddept = $this->dept;
		}	
		if ($this->mach_name)
		{
			$this->Value["mach_name"] = $this->mach_name;
			$this->m_fldmach_name = $this->mach_name;
		}	
		if ($this->ip_addr)
		{
			$this->Value["ip_addr"] = $this->ip_addr;
			$this->m_fldip_addr = $this->ip_addr;
		}	
		if ($this->mac_addr)
		{
			$this->Value["mac_addr"] = $this->mac_addr;
			$this->m_fldmac_addr = $this->mac_addr;
		}	
	
		if ($this->Value["record_id"])
		    $this->m_fldrecord_id = $this->Value["record_id"];	
		if ($this->Value["campus"])
		    $this->m_fldcampus = $this->Value["campus"];	
		if ($this->Value["bldg"])
		    $this->m_fldbldg = $this->Value["bldg"];	
		if ($this->Value["floor"])
		    $this->m_fldfloor = $this->Value["floor"];	
		if ($this->Value["room"])
		    $this->m_fldroom = $this->Value["room"];	
		if ($this->Value["labselect"])
		    $this->m_fldlabselect = $this->Value["labselect"];	
		if ($this->Value["mach_type"])
		    $this->m_fldmach_type = $this->Value["mach_type"];	
		if ($this->Value["platform"])
		    $this->m_fldplatform = $this->Value["platform"];	
		if ($this->Value["model"])
		    $this->m_fldmodel = $this->Value["model"];	
		if ($this->Value["other_model"])
		    $this->m_fldother_model = $this->Value["other_model"];	
		if ($this->Value["asset_tag"])
		    $this->m_fldasset_tag = $this->Value["asset_tag"];	
		if ($this->Value["serial"])
		    $this->m_fldserial = $this->Value["serial"];	
		if ($this->Value["service_tag"])
		    $this->m_fldservice_tag = $this->Value["service_tag"];	
		if ($this->Value["proc_speed"])
		    $this->m_fldproc_speed = $this->Value["proc_speed"];	
		if ($this->Value["proc_type"])
		    $this->m_fldproc_type = $this->Value["proc_type"];	
		if ($this->Value["ram"])
		    $this->m_fldram = $this->Value["ram"];	
		if ($this->Value["disk_size"])
		    $this->m_flddisk_size = $this->Value["disk_size"];	
		if ($this->Value["optical_drive"])
		    $this->m_fldoptical_drive = $this->Value["optical_drive"];	
		if ($this->Value["display_model"])
		    $this->m_flddisplay_model = $this->Value["display_model"];	
		if ($this->Value["display_size"])
		    $this->m_flddisplay_size = $this->Value["display_size"];	
		if ($this->Value["display_asset"])
		    $this->m_flddisplay_asset = $this->Value["display_asset"];	
		if ($this->Value["display_serial"])
		    $this->m_flddisplay_serial = $this->Value["display_serial"];	
		if ($this->Value["notes"])
		    $this->m_fldnotes = $this->Value["notes"];	
		if ($this->Value["last_updated"])
		    $this->m_fldlast_updated = $this->Value["last_updated"];	
		if ($this->Value["uname"])
		    $this->m_flduname = $this->Value["uname"];	
		if ($this->Value["lname"])
		    $this->m_fldlname = $this->Value["lname"];	
		if ($this->Value["dept"])
		    $this->m_flddept = $this->Value["dept"];	
		if ($this->Value["mach_name"])
		    $this->m_fldmach_name = $this->Value["mach_name"];	
		if ($this->Value["ip_addr"])
		    $this->m_fldip_addr = $this->Value["ip_addr"];	
		if ($this->Value["mac_addr"])
		    $this->m_fldmac_addr = $this->Value["mac_addr"];	

		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("record_id"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("record_id").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("record_id")."='".db_addslashes($this->Value["record_id"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("record_id")."=".db_addslashes($this->Value["record_id"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("campus"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("campus").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("campus")."='".db_addslashes($this->Value["campus"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("campus")."=".db_addslashes($this->Value["campus"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("bldg"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("bldg").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("bldg")."='".db_addslashes($this->Value["bldg"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("bldg")."=".db_addslashes($this->Value["bldg"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("floor"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("floor").",";
			if (NeedQuotes(3))
				$deleteFields.= AddFieldWrappers("floor")."='".db_addslashes($this->Value["floor"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("floor")."=".db_addslashes($this->Value["floor"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("room"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("room").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("room")."='".db_addslashes($this->Value["room"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("room")."=".db_addslashes($this->Value["room"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("labselect"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("labselect").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("labselect")."='".db_addslashes($this->Value["labselect"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("labselect")."=".db_addslashes($this->Value["labselect"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_type"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("mach_type").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("mach_type")."='".db_addslashes($this->Value["mach_type"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("mach_type")."=".db_addslashes($this->Value["mach_type"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("platform"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("platform").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("platform")."='".db_addslashes($this->Value["platform"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("platform")."=".db_addslashes($this->Value["platform"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("model"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("model").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("model")."='".db_addslashes($this->Value["model"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("model")."=".db_addslashes($this->Value["model"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("other_model"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("other_model").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("other_model")."='".db_addslashes($this->Value["other_model"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("other_model")."=".db_addslashes($this->Value["other_model"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("asset_tag"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("asset_tag").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("asset_tag")."='".db_addslashes($this->Value["asset_tag"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("asset_tag")."=".db_addslashes($this->Value["asset_tag"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("serial"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("serial").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("serial")."='".db_addslashes($this->Value["serial"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("serial")."=".db_addslashes($this->Value["serial"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("service_tag"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("service_tag").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("service_tag")."='".db_addslashes($this->Value["service_tag"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("service_tag")."=".db_addslashes($this->Value["service_tag"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_speed"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("proc_speed").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("proc_speed")."='".db_addslashes($this->Value["proc_speed"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("proc_speed")."=".db_addslashes($this->Value["proc_speed"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_type"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("proc_type").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("proc_type")."='".db_addslashes($this->Value["proc_type"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("proc_type")."=".db_addslashes($this->Value["proc_type"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ram"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ram").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("ram")."='".db_addslashes($this->Value["ram"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ram")."=".db_addslashes($this->Value["ram"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("disk_size"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("disk_size").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("disk_size")."='".db_addslashes($this->Value["disk_size"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("disk_size")."=".db_addslashes($this->Value["disk_size"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("optical_drive"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("optical_drive").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("optical_drive")."='".db_addslashes($this->Value["optical_drive"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("optical_drive")."=".db_addslashes($this->Value["optical_drive"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_model"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("display_model").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("display_model")."='".db_addslashes($this->Value["display_model"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("display_model")."=".db_addslashes($this->Value["display_model"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_size"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("display_size").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("display_size")."='".db_addslashes($this->Value["display_size"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("display_size")."=".db_addslashes($this->Value["display_size"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_asset"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("display_asset").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("display_asset")."='".db_addslashes($this->Value["display_asset"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("display_asset")."=".db_addslashes($this->Value["display_asset"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_serial"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("display_serial").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("display_serial")."='".db_addslashes($this->Value["display_serial"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("display_serial")."=".db_addslashes($this->Value["display_serial"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("notes"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("notes").",";
			if (NeedQuotes(201))
				$deleteFields.= AddFieldWrappers("notes")."='".db_addslashes($this->Value["notes"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("notes")."=".db_addslashes($this->Value["notes"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("last_updated"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("last_updated").",";
			if (NeedQuotes(135))
				$deleteFields.= AddFieldWrappers("last_updated")."='".db_addslashes($this->Value["last_updated"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("last_updated")."=".db_addslashes($this->Value["last_updated"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("uname"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("uname").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("uname")."='".db_addslashes($this->Value["uname"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("uname")."=".db_addslashes($this->Value["uname"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("lname"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("lname").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("lname")."='".db_addslashes($this->Value["lname"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("lname")."=".db_addslashes($this->Value["lname"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("dept"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("dept").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("dept")."='".db_addslashes($this->Value["dept"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("dept")."=".db_addslashes($this->Value["dept"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_name"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("mach_name").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("mach_name")."='".db_addslashes($this->Value["mach_name"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("mach_name")."=".db_addslashes($this->Value["mach_name"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ip_addr"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("ip_addr").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("ip_addr")."='".db_addslashes($this->Value["ip_addr"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("ip_addr")."=".db_addslashes($this->Value["ip_addr"]) . " and ";		
		}
		$flag = false;	
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mac_addr"))
				$flag=true;
				
		if ($flag)
		{		
			//$insertFields.= AddFieldWrappers("mac_addr").",";
			if (NeedQuotes(200))
				$deleteFields.= AddFieldWrappers("mac_addr")."='".db_addslashes($this->Value["mac_addr"]) . "' and ";
			else
				$deleteFields.= AddFieldWrappers("mac_addr")."=".db_addslashes($this->Value["mac_addr"]) . " and ";		
		}

	if ($deleteFields)
	{
		$deleteFields = substr($deleteFields,0,-5);
		$dalSQL = "delete from ".$this->m_GoodTableName." where ".$deleteFields;
		db_exec($dalSQL,$conn);
	}
	
	    unset($this->Value["record_id"]);
	    unset($this->Value["campus"]);
	    unset($this->Value["bldg"]);
	    unset($this->Value["floor"]);
	    unset($this->Value["room"]);
	    unset($this->Value["labselect"]);
	    unset($this->Value["mach_type"]);
	    unset($this->Value["platform"]);
	    unset($this->Value["model"]);
	    unset($this->Value["other_model"]);
	    unset($this->Value["asset_tag"]);
	    unset($this->Value["serial"]);
	    unset($this->Value["service_tag"]);
	    unset($this->Value["proc_speed"]);
	    unset($this->Value["proc_type"]);
	    unset($this->Value["ram"]);
	    unset($this->Value["disk_size"]);
	    unset($this->Value["optical_drive"]);
	    unset($this->Value["display_model"]);
	    unset($this->Value["display_size"]);
	    unset($this->Value["display_asset"]);
	    unset($this->Value["display_serial"]);
	    unset($this->Value["notes"]);
	    unset($this->Value["last_updated"]);
	    unset($this->Value["uname"]);
	    unset($this->Value["lname"]);
	    unset($this->Value["dept"]);
	    unset($this->Value["mach_name"]);
	    unset($this->Value["ip_addr"]);
	    unset($this->Value["mac_addr"]);
		
	//unset($this->Value);	
}

function Update()
{
	global $conn;
	
	$updateParam = "";
	$updateValue = "";
	
		if ($this->record_id)
		{
			if (1==1)
				$this->Param["record_id"] = $this->record_id;
			else
				$this->Value["record_id"] = $this->record_id;
			$this->m_fldrecord_id = $this->record_id;
		}	
		if ($this->campus)
		{
			if (0==1)
				$this->Param["campus"] = $this->campus;
			else
				$this->Value["campus"] = $this->campus;
			$this->m_fldcampus = $this->campus;
		}	
		if ($this->bldg)
		{
			if (0==1)
				$this->Param["bldg"] = $this->bldg;
			else
				$this->Value["bldg"] = $this->bldg;
			$this->m_fldbldg = $this->bldg;
		}	
		if ($this->floor)
		{
			if (0==1)
				$this->Param["floor"] = $this->floor;
			else
				$this->Value["floor"] = $this->floor;
			$this->m_fldfloor = $this->floor;
		}	
		if ($this->room)
		{
			if (0==1)
				$this->Param["room"] = $this->room;
			else
				$this->Value["room"] = $this->room;
			$this->m_fldroom = $this->room;
		}	
		if ($this->labselect)
		{
			if (0==1)
				$this->Param["labselect"] = $this->labselect;
			else
				$this->Value["labselect"] = $this->labselect;
			$this->m_fldlabselect = $this->labselect;
		}	
		if ($this->mach_type)
		{
			if (0==1)
				$this->Param["mach_type"] = $this->mach_type;
			else
				$this->Value["mach_type"] = $this->mach_type;
			$this->m_fldmach_type = $this->mach_type;
		}	
		if ($this->platform)
		{
			if (0==1)
				$this->Param["platform"] = $this->platform;
			else
				$this->Value["platform"] = $this->platform;
			$this->m_fldplatform = $this->platform;
		}	
		if ($this->model)
		{
			if (0==1)
				$this->Param["model"] = $this->model;
			else
				$this->Value["model"] = $this->model;
			$this->m_fldmodel = $this->model;
		}	
		if ($this->other_model)
		{
			if (0==1)
				$this->Param["other_model"] = $this->other_model;
			else
				$this->Value["other_model"] = $this->other_model;
			$this->m_fldother_model = $this->other_model;
		}	
		if ($this->asset_tag)
		{
			if (0==1)
				$this->Param["asset_tag"] = $this->asset_tag;
			else
				$this->Value["asset_tag"] = $this->asset_tag;
			$this->m_fldasset_tag = $this->asset_tag;
		}	
		if ($this->serial)
		{
			if (0==1)
				$this->Param["serial"] = $this->serial;
			else
				$this->Value["serial"] = $this->serial;
			$this->m_fldserial = $this->serial;
		}	
		if ($this->service_tag)
		{
			if (0==1)
				$this->Param["service_tag"] = $this->service_tag;
			else
				$this->Value["service_tag"] = $this->service_tag;
			$this->m_fldservice_tag = $this->service_tag;
		}	
		if ($this->proc_speed)
		{
			if (0==1)
				$this->Param["proc_speed"] = $this->proc_speed;
			else
				$this->Value["proc_speed"] = $this->proc_speed;
			$this->m_fldproc_speed = $this->proc_speed;
		}	
		if ($this->proc_type)
		{
			if (0==1)
				$this->Param["proc_type"] = $this->proc_type;
			else
				$this->Value["proc_type"] = $this->proc_type;
			$this->m_fldproc_type = $this->proc_type;
		}	
		if ($this->ram)
		{
			if (0==1)
				$this->Param["ram"] = $this->ram;
			else
				$this->Value["ram"] = $this->ram;
			$this->m_fldram = $this->ram;
		}	
		if ($this->disk_size)
		{
			if (0==1)
				$this->Param["disk_size"] = $this->disk_size;
			else
				$this->Value["disk_size"] = $this->disk_size;
			$this->m_flddisk_size = $this->disk_size;
		}	
		if ($this->optical_drive)
		{
			if (0==1)
				$this->Param["optical_drive"] = $this->optical_drive;
			else
				$this->Value["optical_drive"] = $this->optical_drive;
			$this->m_fldoptical_drive = $this->optical_drive;
		}	
		if ($this->display_model)
		{
			if (0==1)
				$this->Param["display_model"] = $this->display_model;
			else
				$this->Value["display_model"] = $this->display_model;
			$this->m_flddisplay_model = $this->display_model;
		}	
		if ($this->display_size)
		{
			if (0==1)
				$this->Param["display_size"] = $this->display_size;
			else
				$this->Value["display_size"] = $this->display_size;
			$this->m_flddisplay_size = $this->display_size;
		}	
		if ($this->display_asset)
		{
			if (0==1)
				$this->Param["display_asset"] = $this->display_asset;
			else
				$this->Value["display_asset"] = $this->display_asset;
			$this->m_flddisplay_asset = $this->display_asset;
		}	
		if ($this->display_serial)
		{
			if (0==1)
				$this->Param["display_serial"] = $this->display_serial;
			else
				$this->Value["display_serial"] = $this->display_serial;
			$this->m_flddisplay_serial = $this->display_serial;
		}	
		if ($this->notes)
		{
			if (0==1)
				$this->Param["notes"] = $this->notes;
			else
				$this->Value["notes"] = $this->notes;
			$this->m_fldnotes = $this->notes;
		}	
		if ($this->last_updated)
		{
			if (0==1)
				$this->Param["last_updated"] = $this->last_updated;
			else
				$this->Value["last_updated"] = $this->last_updated;
			$this->m_fldlast_updated = $this->last_updated;
		}	
		if ($this->uname)
		{
			if (0==1)
				$this->Param["uname"] = $this->uname;
			else
				$this->Value["uname"] = $this->uname;
			$this->m_flduname = $this->uname;
		}	
		if ($this->lname)
		{
			if (0==1)
				$this->Param["lname"] = $this->lname;
			else
				$this->Value["lname"] = $this->lname;
			$this->m_fldlname = $this->lname;
		}	
		if ($this->dept)
		{
			if (0==1)
				$this->Param["dept"] = $this->dept;
			else
				$this->Value["dept"] = $this->dept;
			$this->m_flddept = $this->dept;
		}	
		if ($this->mach_name)
		{
			if (0==1)
				$this->Param["mach_name"] = $this->mach_name;
			else
				$this->Value["mach_name"] = $this->mach_name;
			$this->m_fldmach_name = $this->mach_name;
		}	
		if ($this->ip_addr)
		{
			if (0==1)
				$this->Param["ip_addr"] = $this->ip_addr;
			else
				$this->Value["ip_addr"] = $this->ip_addr;
			$this->m_fldip_addr = $this->ip_addr;
		}	
		if ($this->mac_addr)
		{
			if (0==1)
				$this->Param["mac_addr"] = $this->mac_addr;
			else
				$this->Value["mac_addr"] = $this->mac_addr;
			$this->m_fldmac_addr = $this->mac_addr;
		}	
	
		if ($this->Value["record_id"])
		    $this->m_fldrecord_id = $this->Value["record_id"];		
		if ($this->Value["campus"])
		    $this->m_fldcampus = $this->Value["campus"];		
		if ($this->Value["bldg"])
		    $this->m_fldbldg = $this->Value["bldg"];		
		if ($this->Value["floor"])
		    $this->m_fldfloor = $this->Value["floor"];		
		if ($this->Value["room"])
		    $this->m_fldroom = $this->Value["room"];		
		if ($this->Value["labselect"])
		    $this->m_fldlabselect = $this->Value["labselect"];		
		if ($this->Value["mach_type"])
		    $this->m_fldmach_type = $this->Value["mach_type"];		
		if ($this->Value["platform"])
		    $this->m_fldplatform = $this->Value["platform"];		
		if ($this->Value["model"])
		    $this->m_fldmodel = $this->Value["model"];		
		if ($this->Value["other_model"])
		    $this->m_fldother_model = $this->Value["other_model"];		
		if ($this->Value["asset_tag"])
		    $this->m_fldasset_tag = $this->Value["asset_tag"];		
		if ($this->Value["serial"])
		    $this->m_fldserial = $this->Value["serial"];		
		if ($this->Value["service_tag"])
		    $this->m_fldservice_tag = $this->Value["service_tag"];		
		if ($this->Value["proc_speed"])
		    $this->m_fldproc_speed = $this->Value["proc_speed"];		
		if ($this->Value["proc_type"])
		    $this->m_fldproc_type = $this->Value["proc_type"];		
		if ($this->Value["ram"])
		    $this->m_fldram = $this->Value["ram"];		
		if ($this->Value["disk_size"])
		    $this->m_flddisk_size = $this->Value["disk_size"];		
		if ($this->Value["optical_drive"])
		    $this->m_fldoptical_drive = $this->Value["optical_drive"];		
		if ($this->Value["display_model"])
		    $this->m_flddisplay_model = $this->Value["display_model"];		
		if ($this->Value["display_size"])
		    $this->m_flddisplay_size = $this->Value["display_size"];		
		if ($this->Value["display_asset"])
		    $this->m_flddisplay_asset = $this->Value["display_asset"];		
		if ($this->Value["display_serial"])
		    $this->m_flddisplay_serial = $this->Value["display_serial"];		
		if ($this->Value["notes"])
		    $this->m_fldnotes = $this->Value["notes"];		
		if ($this->Value["last_updated"])
		    $this->m_fldlast_updated = $this->Value["last_updated"];		
		if ($this->Value["uname"])
		    $this->m_flduname = $this->Value["uname"];		
		if ($this->Value["lname"])
		    $this->m_fldlname = $this->Value["lname"];		
		if ($this->Value["dept"])
		    $this->m_flddept = $this->Value["dept"];		
		if ($this->Value["mach_name"])
		    $this->m_fldmach_name = $this->Value["mach_name"];		
		if ($this->Value["ip_addr"])
		    $this->m_fldip_addr = $this->Value["ip_addr"];		
		if ($this->Value["mac_addr"])
		    $this->m_fldmac_addr = $this->Value["mac_addr"];		
	
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("record_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("record_id")."='".$this->Value["record_id"]."', ";
			else
					$updateValue.= AddFieldWrappers("record_id")."=".$this->Value["record_id"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("record_id"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("record_id")."='".db_addslashes($this->Param["record_id"])."' and ";
			else
					$updateParam.= AddFieldWrappers("record_id")."=".db_addslashes($this->Param["record_id"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("campus"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("campus")."='".$this->Value["campus"]."', ";
			else
					$updateValue.= AddFieldWrappers("campus")."=".$this->Value["campus"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("campus"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("campus")."='".db_addslashes($this->Param["campus"])."' and ";
			else
					$updateParam.= AddFieldWrappers("campus")."=".db_addslashes($this->Param["campus"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("bldg"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("bldg")."='".$this->Value["bldg"]."', ";
			else
					$updateValue.= AddFieldWrappers("bldg")."=".$this->Value["bldg"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("bldg"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("bldg")."='".db_addslashes($this->Param["bldg"])."' and ";
			else
					$updateParam.= AddFieldWrappers("bldg")."=".db_addslashes($this->Param["bldg"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("floor"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateValue.= AddFieldWrappers("floor")."='".$this->Value["floor"]."', ";
			else
					$updateValue.= AddFieldWrappers("floor")."=".$this->Value["floor"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("floor"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(3))
					$updateParam.= AddFieldWrappers("floor")."='".db_addslashes($this->Param["floor"])."' and ";
			else
					$updateParam.= AddFieldWrappers("floor")."=".db_addslashes($this->Param["floor"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("room"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("room")."='".$this->Value["room"]."', ";
			else
					$updateValue.= AddFieldWrappers("room")."=".$this->Value["room"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("room"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("room")."='".db_addslashes($this->Param["room"])."' and ";
			else
					$updateParam.= AddFieldWrappers("room")."=".db_addslashes($this->Param["room"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("labselect"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("labselect")."='".$this->Value["labselect"]."', ";
			else
					$updateValue.= AddFieldWrappers("labselect")."=".$this->Value["labselect"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("labselect"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("labselect")."='".db_addslashes($this->Param["labselect"])."' and ";
			else
					$updateParam.= AddFieldWrappers("labselect")."=".db_addslashes($this->Param["labselect"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("mach_type")."='".$this->Value["mach_type"]."', ";
			else
					$updateValue.= AddFieldWrappers("mach_type")."=".$this->Value["mach_type"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("mach_type")."='".db_addslashes($this->Param["mach_type"])."' and ";
			else
					$updateParam.= AddFieldWrappers("mach_type")."=".db_addslashes($this->Param["mach_type"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("platform"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("platform")."='".$this->Value["platform"]."', ";
			else
					$updateValue.= AddFieldWrappers("platform")."=".$this->Value["platform"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("platform"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("platform")."='".db_addslashes($this->Param["platform"])."' and ";
			else
					$updateParam.= AddFieldWrappers("platform")."=".db_addslashes($this->Param["platform"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("model")."='".$this->Value["model"]."', ";
			else
					$updateValue.= AddFieldWrappers("model")."=".$this->Value["model"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("model")."='".db_addslashes($this->Param["model"])."' and ";
			else
					$updateParam.= AddFieldWrappers("model")."=".db_addslashes($this->Param["model"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("other_model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("other_model")."='".$this->Value["other_model"]."', ";
			else
					$updateValue.= AddFieldWrappers("other_model")."=".$this->Value["other_model"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("other_model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("other_model")."='".db_addslashes($this->Param["other_model"])."' and ";
			else
					$updateParam.= AddFieldWrappers("other_model")."=".db_addslashes($this->Param["other_model"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("asset_tag"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("asset_tag")."='".$this->Value["asset_tag"]."', ";
			else
					$updateValue.= AddFieldWrappers("asset_tag")."=".$this->Value["asset_tag"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("asset_tag"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("asset_tag")."='".db_addslashes($this->Param["asset_tag"])."' and ";
			else
					$updateParam.= AddFieldWrappers("asset_tag")."=".db_addslashes($this->Param["asset_tag"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("serial"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("serial")."='".$this->Value["serial"]."', ";
			else
					$updateValue.= AddFieldWrappers("serial")."=".$this->Value["serial"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("serial"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("serial")."='".db_addslashes($this->Param["serial"])."' and ";
			else
					$updateParam.= AddFieldWrappers("serial")."=".db_addslashes($this->Param["serial"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("service_tag"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("service_tag")."='".$this->Value["service_tag"]."', ";
			else
					$updateValue.= AddFieldWrappers("service_tag")."=".$this->Value["service_tag"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("service_tag"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("service_tag")."='".db_addslashes($this->Param["service_tag"])."' and ";
			else
					$updateParam.= AddFieldWrappers("service_tag")."=".db_addslashes($this->Param["service_tag"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_speed"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("proc_speed")."='".$this->Value["proc_speed"]."', ";
			else
					$updateValue.= AddFieldWrappers("proc_speed")."=".$this->Value["proc_speed"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_speed"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("proc_speed")."='".db_addslashes($this->Param["proc_speed"])."' and ";
			else
					$updateParam.= AddFieldWrappers("proc_speed")."=".db_addslashes($this->Param["proc_speed"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("proc_type")."='".$this->Value["proc_type"]."', ";
			else
					$updateValue.= AddFieldWrappers("proc_type")."=".$this->Value["proc_type"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_type"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("proc_type")."='".db_addslashes($this->Param["proc_type"])."' and ";
			else
					$updateParam.= AddFieldWrappers("proc_type")."=".db_addslashes($this->Param["proc_type"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ram"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("ram")."='".$this->Value["ram"]."', ";
			else
					$updateValue.= AddFieldWrappers("ram")."=".$this->Value["ram"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ram"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("ram")."='".db_addslashes($this->Param["ram"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ram")."=".db_addslashes($this->Param["ram"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("disk_size"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("disk_size")."='".$this->Value["disk_size"]."', ";
			else
					$updateValue.= AddFieldWrappers("disk_size")."=".$this->Value["disk_size"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("disk_size"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("disk_size")."='".db_addslashes($this->Param["disk_size"])."' and ";
			else
					$updateParam.= AddFieldWrappers("disk_size")."=".db_addslashes($this->Param["disk_size"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("optical_drive"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("optical_drive")."='".$this->Value["optical_drive"]."', ";
			else
					$updateValue.= AddFieldWrappers("optical_drive")."=".$this->Value["optical_drive"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("optical_drive"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("optical_drive")."='".db_addslashes($this->Param["optical_drive"])."' and ";
			else
					$updateParam.= AddFieldWrappers("optical_drive")."=".db_addslashes($this->Param["optical_drive"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("display_model")."='".$this->Value["display_model"]."', ";
			else
					$updateValue.= AddFieldWrappers("display_model")."=".$this->Value["display_model"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_model"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("display_model")."='".db_addslashes($this->Param["display_model"])."' and ";
			else
					$updateParam.= AddFieldWrappers("display_model")."=".db_addslashes($this->Param["display_model"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_size"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("display_size")."='".$this->Value["display_size"]."', ";
			else
					$updateValue.= AddFieldWrappers("display_size")."=".$this->Value["display_size"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_size"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("display_size")."='".db_addslashes($this->Param["display_size"])."' and ";
			else
					$updateParam.= AddFieldWrappers("display_size")."=".db_addslashes($this->Param["display_size"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_asset"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("display_asset")."='".$this->Value["display_asset"]."', ";
			else
					$updateValue.= AddFieldWrappers("display_asset")."=".$this->Value["display_asset"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_asset"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("display_asset")."='".db_addslashes($this->Param["display_asset"])."' and ";
			else
					$updateParam.= AddFieldWrappers("display_asset")."=".db_addslashes($this->Param["display_asset"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("display_serial"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("display_serial")."='".$this->Value["display_serial"]."', ";
			else
					$updateValue.= AddFieldWrappers("display_serial")."=".$this->Value["display_serial"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_serial"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("display_serial")."='".db_addslashes($this->Param["display_serial"])."' and ";
			else
					$updateParam.= AddFieldWrappers("display_serial")."=".db_addslashes($this->Param["display_serial"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("notes"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateValue.= AddFieldWrappers("notes")."='".$this->Value["notes"]."', ";
			else
					$updateValue.= AddFieldWrappers("notes")."=".$this->Value["notes"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("notes"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(201))
					$updateParam.= AddFieldWrappers("notes")."='".db_addslashes($this->Param["notes"])."' and ";
			else
					$updateParam.= AddFieldWrappers("notes")."=".db_addslashes($this->Param["notes"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("last_updated"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateValue.= AddFieldWrappers("last_updated")."='".$this->Value["last_updated"]."', ";
			else
					$updateValue.= AddFieldWrappers("last_updated")."=".$this->Value["last_updated"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("last_updated"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(135))
					$updateParam.= AddFieldWrappers("last_updated")."='".db_addslashes($this->Param["last_updated"])."' and ";
			else
					$updateParam.= AddFieldWrappers("last_updated")."=".db_addslashes($this->Param["last_updated"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("uname"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("uname")."='".$this->Value["uname"]."', ";
			else
					$updateValue.= AddFieldWrappers("uname")."=".$this->Value["uname"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("uname"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("uname")."='".db_addslashes($this->Param["uname"])."' and ";
			else
					$updateParam.= AddFieldWrappers("uname")."=".db_addslashes($this->Param["uname"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("lname"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("lname")."='".$this->Value["lname"]."', ";
			else
					$updateValue.= AddFieldWrappers("lname")."=".$this->Value["lname"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("lname"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("lname")."='".db_addslashes($this->Param["lname"])."' and ";
			else
					$updateParam.= AddFieldWrappers("lname")."=".db_addslashes($this->Param["lname"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("dept"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("dept")."='".$this->Value["dept"]."', ";
			else
					$updateValue.= AddFieldWrappers("dept")."=".$this->Value["dept"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("dept"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("dept")."='".db_addslashes($this->Param["dept"])."' and ";
			else
					$updateParam.= AddFieldWrappers("dept")."=".db_addslashes($this->Param["dept"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("mach_name")."='".$this->Value["mach_name"]."', ";
			else
					$updateValue.= AddFieldWrappers("mach_name")."=".$this->Value["mach_name"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_name"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("mach_name")."='".db_addslashes($this->Param["mach_name"])."' and ";
			else
					$updateParam.= AddFieldWrappers("mach_name")."=".db_addslashes($this->Param["mach_name"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("ip_addr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("ip_addr")."='".$this->Value["ip_addr"]."', ";
			else
					$updateValue.= AddFieldWrappers("ip_addr")."=".$this->Value["ip_addr"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ip_addr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("ip_addr")."='".db_addslashes($this->Param["ip_addr"])."' and ";
			else
					$updateParam.= AddFieldWrappers("ip_addr")."=".db_addslashes($this->Param["ip_addr"])." and ";
		}
		
		$flag = false;
		foreach($this->Value as $field=>$value)
			if (strtoupper($field)==strtoupper("mac_addr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateValue.= AddFieldWrappers("mac_addr")."='".$this->Value["mac_addr"]."', ";
			else
					$updateValue.= AddFieldWrappers("mac_addr")."=".$this->Value["mac_addr"].", ";
		}
		
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mac_addr"))
				$flag=true;
		
		if ($flag)
		{
			if (NeedQuotes(200))
					$updateParam.= AddFieldWrappers("mac_addr")."='".db_addslashes($this->Param["mac_addr"])."' and ";
			else
					$updateParam.= AddFieldWrappers("mac_addr")."=".db_addslashes($this->Param["mac_addr"])." and ";
		}
		
	
	if ($updateParam)
		$updateParam = substr($updateParam,0,-5);
	if ($updateValue)
		$updateValue = substr($updateValue,0,-2);
		
	if ($updateValue)
	{
		$dalSQL = "update ".$this->m_GoodTableName." set ".$updateValue." where ".$updateParam;
		db_exec($dalSQL,$conn);
	}
	
        unset($this->Value["record_id"]);
		unset($this->Param["record_id"]);
        unset($this->Value["campus"]);
		unset($this->Param["campus"]);
        unset($this->Value["bldg"]);
		unset($this->Param["bldg"]);
        unset($this->Value["floor"]);
		unset($this->Param["floor"]);
        unset($this->Value["room"]);
		unset($this->Param["room"]);
        unset($this->Value["labselect"]);
		unset($this->Param["labselect"]);
        unset($this->Value["mach_type"]);
		unset($this->Param["mach_type"]);
        unset($this->Value["platform"]);
		unset($this->Param["platform"]);
        unset($this->Value["model"]);
		unset($this->Param["model"]);
        unset($this->Value["other_model"]);
		unset($this->Param["other_model"]);
        unset($this->Value["asset_tag"]);
		unset($this->Param["asset_tag"]);
        unset($this->Value["serial"]);
		unset($this->Param["serial"]);
        unset($this->Value["service_tag"]);
		unset($this->Param["service_tag"]);
        unset($this->Value["proc_speed"]);
		unset($this->Param["proc_speed"]);
        unset($this->Value["proc_type"]);
		unset($this->Param["proc_type"]);
        unset($this->Value["ram"]);
		unset($this->Param["ram"]);
        unset($this->Value["disk_size"]);
		unset($this->Param["disk_size"]);
        unset($this->Value["optical_drive"]);
		unset($this->Param["optical_drive"]);
        unset($this->Value["display_model"]);
		unset($this->Param["display_model"]);
        unset($this->Value["display_size"]);
		unset($this->Param["display_size"]);
        unset($this->Value["display_asset"]);
		unset($this->Param["display_asset"]);
        unset($this->Value["display_serial"]);
		unset($this->Param["display_serial"]);
        unset($this->Value["notes"]);
		unset($this->Param["notes"]);
        unset($this->Value["last_updated"]);
		unset($this->Param["last_updated"]);
        unset($this->Value["uname"]);
		unset($this->Param["uname"]);
        unset($this->Value["lname"]);
		unset($this->Param["lname"]);
        unset($this->Value["dept"]);
		unset($this->Param["dept"]);
        unset($this->Value["mach_name"]);
		unset($this->Param["mach_name"]);
        unset($this->Value["ip_addr"]);
		unset($this->Param["ip_addr"]);
        unset($this->Value["mac_addr"]);
		unset($this->Param["mac_addr"]);
	
}

function QueryAll()
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("record_id").", ";
		$dal_variables.= AddFieldWrappers("campus").", ";
		$dal_variables.= AddFieldWrappers("bldg").", ";
		$dal_variables.= AddFieldWrappers("floor").", ";
		$dal_variables.= AddFieldWrappers("room").", ";
		$dal_variables.= AddFieldWrappers("labselect").", ";
		$dal_variables.= AddFieldWrappers("mach_type").", ";
		$dal_variables.= AddFieldWrappers("platform").", ";
		$dal_variables.= AddFieldWrappers("model").", ";
		$dal_variables.= AddFieldWrappers("other_model").", ";
		$dal_variables.= AddFieldWrappers("asset_tag").", ";
		$dal_variables.= AddFieldWrappers("serial").", ";
		$dal_variables.= AddFieldWrappers("service_tag").", ";
		$dal_variables.= AddFieldWrappers("proc_speed").", ";
		$dal_variables.= AddFieldWrappers("proc_type").", ";
		$dal_variables.= AddFieldWrappers("ram").", ";
		$dal_variables.= AddFieldWrappers("disk_size").", ";
		$dal_variables.= AddFieldWrappers("optical_drive").", ";
		$dal_variables.= AddFieldWrappers("display_model").", ";
		$dal_variables.= AddFieldWrappers("display_size").", ";
		$dal_variables.= AddFieldWrappers("display_asset").", ";
		$dal_variables.= AddFieldWrappers("display_serial").", ";
		$dal_variables.= AddFieldWrappers("notes").", ";
		$dal_variables.= AddFieldWrappers("last_updated").", ";
		$dal_variables.= AddFieldWrappers("uname").", ";
		$dal_variables.= AddFieldWrappers("lname").", ";
		$dal_variables.= AddFieldWrappers("dept").", ";
		$dal_variables.= AddFieldWrappers("mach_name").", ";
		$dal_variables.= AddFieldWrappers("ip_addr").", ";
		$dal_variables.= AddFieldWrappers("mac_addr").", ";
	$dal_variables = substr($dal_variables,0,-2);
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName;
	$rs = db_query($dalSQL,$conn);
//	$data = db_fetch_array($rs);
//	return new DalRecordset($rs);
  return $rs;
  
}

function Query($swhere="",$orderby="")
{
	global $conn;
	$dal_variables="";
		$dal_variables.= AddFieldWrappers("record_id").", ";
		$dal_variables.= AddFieldWrappers("campus").", ";
		$dal_variables.= AddFieldWrappers("bldg").", ";
		$dal_variables.= AddFieldWrappers("floor").", ";
		$dal_variables.= AddFieldWrappers("room").", ";
		$dal_variables.= AddFieldWrappers("labselect").", ";
		$dal_variables.= AddFieldWrappers("mach_type").", ";
		$dal_variables.= AddFieldWrappers("platform").", ";
		$dal_variables.= AddFieldWrappers("model").", ";
		$dal_variables.= AddFieldWrappers("other_model").", ";
		$dal_variables.= AddFieldWrappers("asset_tag").", ";
		$dal_variables.= AddFieldWrappers("serial").", ";
		$dal_variables.= AddFieldWrappers("service_tag").", ";
		$dal_variables.= AddFieldWrappers("proc_speed").", ";
		$dal_variables.= AddFieldWrappers("proc_type").", ";
		$dal_variables.= AddFieldWrappers("ram").", ";
		$dal_variables.= AddFieldWrappers("disk_size").", ";
		$dal_variables.= AddFieldWrappers("optical_drive").", ";
		$dal_variables.= AddFieldWrappers("display_model").", ";
		$dal_variables.= AddFieldWrappers("display_size").", ";
		$dal_variables.= AddFieldWrappers("display_asset").", ";
		$dal_variables.= AddFieldWrappers("display_serial").", ";
		$dal_variables.= AddFieldWrappers("notes").", ";
		$dal_variables.= AddFieldWrappers("last_updated").", ";
		$dal_variables.= AddFieldWrappers("uname").", ";
		$dal_variables.= AddFieldWrappers("lname").", ";
		$dal_variables.= AddFieldWrappers("dept").", ";
		$dal_variables.= AddFieldWrappers("mach_name").", ";
		$dal_variables.= AddFieldWrappers("ip_addr").", ";
		$dal_variables.= AddFieldWrappers("mac_addr").", ";
	$dal_variables = substr($dal_variables,0,-2);
	if ($swhere)
		$swhere = " where ".$swhere;
	if ($orderby)
		$orderby = " order by ".$orderby;
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$swhere.$orderby;
	$rs = db_query($dalSQL,$conn);
//	$data = db_fetch_array($rs);
//	return new DalRecordset($rs); 	
  return $rs;
}

function FetchByID()
{
	global $conn;
	$dal_variables="";
	$dal_where="";
        $dal_variables.= AddFieldWrappers("record_id").", ";
        $dal_variables.= AddFieldWrappers("campus").", ";
        $dal_variables.= AddFieldWrappers("bldg").", ";
        $dal_variables.= AddFieldWrappers("floor").", ";
        $dal_variables.= AddFieldWrappers("room").", ";
        $dal_variables.= AddFieldWrappers("labselect").", ";
        $dal_variables.= AddFieldWrappers("mach_type").", ";
        $dal_variables.= AddFieldWrappers("platform").", ";
        $dal_variables.= AddFieldWrappers("model").", ";
        $dal_variables.= AddFieldWrappers("other_model").", ";
        $dal_variables.= AddFieldWrappers("asset_tag").", ";
        $dal_variables.= AddFieldWrappers("serial").", ";
        $dal_variables.= AddFieldWrappers("service_tag").", ";
        $dal_variables.= AddFieldWrappers("proc_speed").", ";
        $dal_variables.= AddFieldWrappers("proc_type").", ";
        $dal_variables.= AddFieldWrappers("ram").", ";
        $dal_variables.= AddFieldWrappers("disk_size").", ";
        $dal_variables.= AddFieldWrappers("optical_drive").", ";
        $dal_variables.= AddFieldWrappers("display_model").", ";
        $dal_variables.= AddFieldWrappers("display_size").", ";
        $dal_variables.= AddFieldWrappers("display_asset").", ";
        $dal_variables.= AddFieldWrappers("display_serial").", ";
        $dal_variables.= AddFieldWrappers("notes").", ";
        $dal_variables.= AddFieldWrappers("last_updated").", ";
        $dal_variables.= AddFieldWrappers("uname").", ";
        $dal_variables.= AddFieldWrappers("lname").", ";
        $dal_variables.= AddFieldWrappers("dept").", ";
        $dal_variables.= AddFieldWrappers("mach_name").", ";
        $dal_variables.= AddFieldWrappers("ip_addr").", ";
        $dal_variables.= AddFieldWrappers("mac_addr").", ";
	$dal_variables = substr($dal_variables,0,-2);
	
		if ($this->record_id)
			$this->Param["record_id"] = $this->record_id;	
		if ($this->campus)
			$this->Param["campus"] = $this->campus;	
		if ($this->bldg)
			$this->Param["bldg"] = $this->bldg;	
		if ($this->floor)
			$this->Param["floor"] = $this->floor;	
		if ($this->room)
			$this->Param["room"] = $this->room;	
		if ($this->labselect)
			$this->Param["labselect"] = $this->labselect;	
		if ($this->mach_type)
			$this->Param["mach_type"] = $this->mach_type;	
		if ($this->platform)
			$this->Param["platform"] = $this->platform;	
		if ($this->model)
			$this->Param["model"] = $this->model;	
		if ($this->other_model)
			$this->Param["other_model"] = $this->other_model;	
		if ($this->asset_tag)
			$this->Param["asset_tag"] = $this->asset_tag;	
		if ($this->serial)
			$this->Param["serial"] = $this->serial;	
		if ($this->service_tag)
			$this->Param["service_tag"] = $this->service_tag;	
		if ($this->proc_speed)
			$this->Param["proc_speed"] = $this->proc_speed;	
		if ($this->proc_type)
			$this->Param["proc_type"] = $this->proc_type;	
		if ($this->ram)
			$this->Param["ram"] = $this->ram;	
		if ($this->disk_size)
			$this->Param["disk_size"] = $this->disk_size;	
		if ($this->optical_drive)
			$this->Param["optical_drive"] = $this->optical_drive;	
		if ($this->display_model)
			$this->Param["display_model"] = $this->display_model;	
		if ($this->display_size)
			$this->Param["display_size"] = $this->display_size;	
		if ($this->display_asset)
			$this->Param["display_asset"] = $this->display_asset;	
		if ($this->display_serial)
			$this->Param["display_serial"] = $this->display_serial;	
		if ($this->notes)
			$this->Param["notes"] = $this->notes;	
		if ($this->last_updated)
			$this->Param["last_updated"] = $this->last_updated;	
		if ($this->uname)
			$this->Param["uname"] = $this->uname;	
		if ($this->lname)
			$this->Param["lname"] = $this->lname;	
		if ($this->dept)
			$this->Param["dept"] = $this->dept;	
		if ($this->mach_name)
			$this->Param["mach_name"] = $this->mach_name;	
		if ($this->ip_addr)
			$this->Param["ip_addr"] = $this->ip_addr;	
		if ($this->mac_addr)
			$this->Param["mac_addr"] = $this->mac_addr;	
	
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("record_id") && 1==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("record_id")."='".db_addslashes($this->Param["record_id"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("record_id")."=".db_addslashes($this->Param["record_id"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("campus") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("campus")."='".db_addslashes($this->Param["campus"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("campus")."=".db_addslashes($this->Param["campus"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("bldg") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("bldg")."='".db_addslashes($this->Param["bldg"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("bldg")."=".db_addslashes($this->Param["bldg"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("floor") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(3))
				$dal_where.= AddFieldWrappers("floor")."='".db_addslashes($this->Param["floor"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("floor")."=".db_addslashes($this->Param["floor"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("room") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("room")."='".db_addslashes($this->Param["room"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("room")."=".db_addslashes($this->Param["room"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("labselect") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("labselect")."='".db_addslashes($this->Param["labselect"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("labselect")."=".db_addslashes($this->Param["labselect"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_type") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("mach_type")."='".db_addslashes($this->Param["mach_type"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("mach_type")."=".db_addslashes($this->Param["mach_type"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("platform") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("platform")."='".db_addslashes($this->Param["platform"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("platform")."=".db_addslashes($this->Param["platform"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("model") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("model")."='".db_addslashes($this->Param["model"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("model")."=".db_addslashes($this->Param["model"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("other_model") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("other_model")."='".db_addslashes($this->Param["other_model"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("other_model")."=".db_addslashes($this->Param["other_model"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("asset_tag") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("asset_tag")."='".db_addslashes($this->Param["asset_tag"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("asset_tag")."=".db_addslashes($this->Param["asset_tag"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("serial") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("serial")."='".db_addslashes($this->Param["serial"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("serial")."=".db_addslashes($this->Param["serial"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("service_tag") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("service_tag")."='".db_addslashes($this->Param["service_tag"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("service_tag")."=".db_addslashes($this->Param["service_tag"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_speed") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("proc_speed")."='".db_addslashes($this->Param["proc_speed"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("proc_speed")."=".db_addslashes($this->Param["proc_speed"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("proc_type") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("proc_type")."='".db_addslashes($this->Param["proc_type"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("proc_type")."=".db_addslashes($this->Param["proc_type"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ram") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("ram")."='".db_addslashes($this->Param["ram"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ram")."=".db_addslashes($this->Param["ram"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("disk_size") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("disk_size")."='".db_addslashes($this->Param["disk_size"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("disk_size")."=".db_addslashes($this->Param["disk_size"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("optical_drive") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("optical_drive")."='".db_addslashes($this->Param["optical_drive"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("optical_drive")."=".db_addslashes($this->Param["optical_drive"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_model") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("display_model")."='".db_addslashes($this->Param["display_model"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("display_model")."=".db_addslashes($this->Param["display_model"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_size") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("display_size")."='".db_addslashes($this->Param["display_size"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("display_size")."=".db_addslashes($this->Param["display_size"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_asset") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("display_asset")."='".db_addslashes($this->Param["display_asset"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("display_asset")."=".db_addslashes($this->Param["display_asset"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("display_serial") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("display_serial")."='".db_addslashes($this->Param["display_serial"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("display_serial")."=".db_addslashes($this->Param["display_serial"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("notes") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(201))
				$dal_where.= AddFieldWrappers("notes")."='".db_addslashes($this->Param["notes"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("notes")."=".db_addslashes($this->Param["notes"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("last_updated") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(135))
				$dal_where.= AddFieldWrappers("last_updated")."='".db_addslashes($this->Param["last_updated"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("last_updated")."=".db_addslashes($this->Param["last_updated"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("uname") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("uname")."='".db_addslashes($this->Param["uname"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("uname")."=".db_addslashes($this->Param["uname"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("lname") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("lname")."='".db_addslashes($this->Param["lname"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("lname")."=".db_addslashes($this->Param["lname"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("dept") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("dept")."='".db_addslashes($this->Param["dept"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("dept")."=".db_addslashes($this->Param["dept"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mach_name") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("mach_name")."='".db_addslashes($this->Param["mach_name"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("mach_name")."=".db_addslashes($this->Param["mach_name"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("ip_addr") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("ip_addr")."='".db_addslashes($this->Param["ip_addr"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("ip_addr")."=".db_addslashes($this->Param["ip_addr"]) . " and ";
		$flag = false;
		foreach($this->Param as $field=>$value)
			if (strtoupper($field)==strtoupper("mac_addr") && 0==1)
				$flag = 1;
		if ($flag)
			if (NeedQuotes(200))
				$dal_where.= AddFieldWrappers("mac_addr")."='".db_addslashes($this->Param["mac_addr"]) . "' and ";
			else
				$dal_where.= AddFieldWrappers("mac_addr")."=".db_addslashes($this->Param["mac_addr"]) . " and ";
	
	if ($dal_where)
		$dal_where = " where ".substr($dal_where,0,-5);
	
	$dalSQL = "select ".$dal_variables." from ".$this->m_GoodTableName.$dal_where;
	$rs = db_query($dalSQL,$conn);
//	$data = db_fetch_array($rs);
//	return new DalRecordset($rs);
  return $rs;
}
	
function Reset()
{
        unset($this->m_fldrecord_id);
		unset($this->Value["record_id"]);
		unset($this->Param["record_id"]);
        unset($this->m_fldcampus);
		unset($this->Value["campus"]);
		unset($this->Param["campus"]);
        unset($this->m_fldbldg);
		unset($this->Value["bldg"]);
		unset($this->Param["bldg"]);
        unset($this->m_fldfloor);
		unset($this->Value["floor"]);
		unset($this->Param["floor"]);
        unset($this->m_fldroom);
		unset($this->Value["room"]);
		unset($this->Param["room"]);
        unset($this->m_fldlabselect);
		unset($this->Value["labselect"]);
		unset($this->Param["labselect"]);
        unset($this->m_fldmach_type);
		unset($this->Value["mach_type"]);
		unset($this->Param["mach_type"]);
        unset($this->m_fldplatform);
		unset($this->Value["platform"]);
		unset($this->Param["platform"]);
        unset($this->m_fldmodel);
		unset($this->Value["model"]);
		unset($this->Param["model"]);
        unset($this->m_fldother_model);
		unset($this->Value["other_model"]);
		unset($this->Param["other_model"]);
        unset($this->m_fldasset_tag);
		unset($this->Value["asset_tag"]);
		unset($this->Param["asset_tag"]);
        unset($this->m_fldserial);
		unset($this->Value["serial"]);
		unset($this->Param["serial"]);
        unset($this->m_fldservice_tag);
		unset($this->Value["service_tag"]);
		unset($this->Param["service_tag"]);
        unset($this->m_fldproc_speed);
		unset($this->Value["proc_speed"]);
		unset($this->Param["proc_speed"]);
        unset($this->m_fldproc_type);
		unset($this->Value["proc_type"]);
		unset($this->Param["proc_type"]);
        unset($this->m_fldram);
		unset($this->Value["ram"]);
		unset($this->Param["ram"]);
        unset($this->m_flddisk_size);
		unset($this->Value["disk_size"]);
		unset($this->Param["disk_size"]);
        unset($this->m_fldoptical_drive);
		unset($this->Value["optical_drive"]);
		unset($this->Param["optical_drive"]);
        unset($this->m_flddisplay_model);
		unset($this->Value["display_model"]);
		unset($this->Param["display_model"]);
        unset($this->m_flddisplay_size);
		unset($this->Value["display_size"]);
		unset($this->Param["display_size"]);
        unset($this->m_flddisplay_asset);
		unset($this->Value["display_asset"]);
		unset($this->Param["display_asset"]);
        unset($this->m_flddisplay_serial);
		unset($this->Value["display_serial"]);
		unset($this->Param["display_serial"]);
        unset($this->m_fldnotes);
		unset($this->Value["notes"]);
		unset($this->Param["notes"]);
        unset($this->m_fldlast_updated);
		unset($this->Value["last_updated"]);
		unset($this->Param["last_updated"]);
        unset($this->m_flduname);
		unset($this->Value["uname"]);
		unset($this->Param["uname"]);
        unset($this->m_fldlname);
		unset($this->Value["lname"]);
		unset($this->Param["lname"]);
        unset($this->m_flddept);
		unset($this->Value["dept"]);
		unset($this->Param["dept"]);
        unset($this->m_fldmach_name);
		unset($this->Value["mach_name"]);
		unset($this->Param["mach_name"]);
        unset($this->m_fldip_addr);
		unset($this->Value["ip_addr"]);
		unset($this->Param["ip_addr"]);
        unset($this->m_fldmac_addr);
		unset($this->Value["mac_addr"]);
		unset($this->Param["mac_addr"]);
}	
	
}//end of class


$dal->acadcomp = new class_acadcomp();


class DalRecordset
{
	
	var $m_rs;
	var $m_fields;
	var $m_eof;
	
	function Fields($field="")
	{
		if(!$field)
			return $this->m_fields;
		return $this->Field($field);
	}
	
	function Field($field)
	{
		if($this->m_eof)
			return false;
		foreach($this->m_fields as $name=>$value)
		{
			if(!strcasecmp($name,$field))
				return $value;
		}
		return false;
	}
	function DalRecordset($rs)
	{
		$this->m_rs=$rs;
		$this->MoveNext();
	}
	function EOF()
	{
		return $this->m_eof;
	}
	
	function MoveNext()
	{
		if(!$this->m_eof)
			$this->m_fields=db_fetch_array($this->m_rs);
		$this->m_eof = !$this->m_fields;
		return !$this->m_eof;
	}
}

?>