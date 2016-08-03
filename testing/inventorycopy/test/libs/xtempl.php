<?php

class XTempl
{
	var $xt_vars;
	var $xt_stack;
	var $template;
	function XTempl()
	{
		$this->xt_vars=array();
		$this->xt_stack=array(&$this->xt_vars);
		$this->assign_function("header","xt_include",array("file"=>"include/header.php"));
		$this->assign_function("footer","xt_include",array("file"=>"include/footer.php"));
		$this->assign_function("event","xt_doevent",array());
		$this->assign_function("label","xt_label",array());
		$this->assign_function("caption","xt_caption",array());
		
	}

function assign($name,$val)
{
	$this->xt_vars[$name]=$val;
}
function assignbyref($name,&$var)
{
	$this->xt_vars[$name]=&$var;
}

function assign_section($name,$begin,$end)
{
	$arr = array();
	$arr["begin"]=$begin;
	$arr["end"]=$end;
	$this->xt_vars[$name]=&$arr;
}

function assign_loopsection($name,&$data)
{
	$arr = array();
	$arr["data"]=&$data;
	$this->xt_vars[$name]=&$arr;
}


function assign_function($name,$func,$params)
{
	$this->xt_vars[$name]=array("func"=>$func,"params"=>$params);
}

function xt_getvar($name)
{
	for($i = count($this->xt_stack)-1;$i>=0;$i--)
	{
		if(array_key_exists($name,$this->xt_stack[$i]))
			return $this->xt_stack[$i][$name];
	}
	return false;
}
	function fetch_loaded($filtertag="")
	{
		ob_start();
		$this->display_loaded($filtertag);
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}

	function fetch_loaded_before($filtertag)
	{
		$pos1=strpos($this->template,"{BEGIN ".$filtertag."}");
		if($pos1===false)
			return;
		$str=substr($this->template,0,$pos1);
		ob_start();
		$this->xt_process_template($str);
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}

	function fetch_loaded_after($filtertag)
	{
		$pos2=strpos($this->template,"{END ".$filtertag."}");
		if($pos2===false)
			return;
		$str=substr($this->template,$pos2+strlen("{END ".$filtertag."}"));
		ob_start();
		$this->xt_process_template($str);
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}

	function load_template($template)
	{
		$path_parts = pathinfo(__file__);
		$path = $path_parts["dirname"];
		$path = substr($path,0,strlen($path)-4)."templates/".$template;
//	read template file
		$this->template = myfile_get_contents($path);
	}

	function display_loaded($filtertag="")
	{
		$str=$this->template;
		if($filtertag)
		{
			$pos1=strpos($this->template,"{BEGIN ".$filtertag."}");
			$pos2=strpos($this->template,"{END ".$filtertag."}");
			if($pos1===false || $pos2===false)
				return;
			$pos2+=strlen("{END ".$filtertag."}");
			$str=substr($this->template,$pos1,$pos2-$pos1);
		}
		$this->xt_process_template($str);
	}
	function display($template)
	{
		$this->load_template($template);
		$this->xt_process_template($this->template);
	}
	
	function xt_process_template($str)
	{
//	parse template file tag by tag
		$start=0;
		$literal=false;
		$len = strlen($str);
		while(true)
		{
			$pos = strpos($str,"{",$start);
			if($pos===false)
			{
				echo substr($str,$start,$len-$start);
				break;
			}
			$section=false;
			$var=false;
			$message=false;
			if(substr($str,$pos+1,6)=="BEGIN ")
				$section=true;
			elseif(substr($str,$pos+1,1)=='$')
				$var=true;
			elseif(substr($str,$pos+1,14)=='mlang_message ')
			{
				$message=true;
			}
			else
			{
//	no tag, just '{' char
				echo substr($str,$start,$pos-$start+1);
				$start=$pos+1;
				continue;
			}
			echo substr($str,$start,$pos-$start);
			if($section)
			{
//	section
				$endpos=strpos($str,"}",$pos);
				if($endpos===false)
				{
					report_error("Page is broken");
					return;
				}
				$section_name=trim(substr($str,$pos+7,$endpos-$pos-7));
				$endtag="{END ".$section_name."}";
				$endpos1=strpos($str,$endtag,$endpos);
				if($endpos1===false)
				{
					echo "End tag not found:".htmlspecialchars($endtag);
					report_error("Page is broken");
					return;
				}
				$section=substr($str,$endpos+1,$endpos1-$endpos-1);
				$start=$endpos1+strlen($endtag);
				$var = $this->xt_getvar($section_name);
				if($var===false)
				{
					continue;
				}
				$begin="";
				$end="";
				if(is_array($var))
				{
					$begin=@$var["begin"];
					$end=@$var["end"];
					$var=@$var["data"];
				}
				if(!is_array($var))
				{
//	if section
					echo $begin;
					$this->xt_process_template($section);
					echo $end;
				}
				else
				{
//	foreach section
					echo $begin;
					$keys=array_keys($var);
					foreach($keys as $i)
					{
						$this->xt_stack[]=&$var[$i];
						if(is_array($var[$i]) && array_key_exists("begin",$var[$i]))
							echo $var[$i]["begin"];
						$this->xt_process_template($section);
						array_pop($this->xt_stack);
						if(is_array($var[$i]) && array_key_exists("end",$var[$i]))
							echo $var[$i]["end"];
					}
					echo $end;
				}
			}
			elseif($var)
			{
//	display a variable or call a function
				$endpos=strpos($str,"}",$pos);
				if($endpos===false)
				{
					report_error("Page is broken");
					return;
				}
				$varparams = split(" ",trim(substr($str,$pos+2,$endpos-$pos-2)));
				$var_name = $varparams[0];
				unset($varparams[0]);
				$start=$endpos+1;
				$var = $this->xt_getvar($var_name);
				if($var===false)
					continue;
				if(!is_array($var))
				{
//	just display a value
					echo $var;
				}
				else
				{
//	call a function
					if(!array_key_exists("func",$var))
					{
						report_error("Incorrect variable value - ".$var_name);
						return;
					}
					$params=array();
					if(array_key_exists("params",$var))
						$params=$var["params"];
					foreach($varparams as $key=>$val)
						$params["custom".$key]=$val;
					$func=$var["func"];
					if(function_exists($func))
						$func($params);
				}
			}
			elseif($message)
			{
				$endpos=strpos($str,"}",$pos);
				if($endpos===false)
				{
					report_error("Page is broken");
					return;
				}
				$tag = trim(substr($str,$pos+15,$endpos-$pos-15));
				$start=$endpos+1;
				echo htmlspecialchars(mlang_message($tag));
			}
		}
	}
}

//	BuildEditControl wrapper
function xt_buildeditcontrol(&$params)
{
	$field=$params["field"];
	if($params["mode"]=="edit")
		$mode=MODE_EDIT;
	else if($params["mode"]=="add")
	{
		$mode=MODE_ADD;
	}
	else if($params["mode"]=="inline_edit")
		$mode=MODE_INLINE_EDIT;
	else if($params["mode"]=="inline_add")
		$mode=MODE_INLINE_ADD;
	else
		$mode=MODE_SEARCH;
	$second=false;
	if(@$params["second"])
		$second=true;
	$id="";
	if(@$params["id"]!="")
		$id=$params["id"];
	$format=GetEditFormat($field);
	if(@$params["format"]!="")
		$format=$params["format"];
	$append="";

	if($mode==MODE_ADD && postvalue("editType")=="onthefly")
	{
		$mode=MODE_INLINE_ADD;
		$id=postvalue("id");
	}

	if(($mode==MODE_EDIT || $mode==MODE_ADD || $mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $format==EDIT_FORMAT_READONLY)
	{
		global $readonlyfields;
		echo $readonlyfields[$field];
	}
	if($mode==MODE_SEARCH)
	{
		global $editformats;
		$format=$editformats[$field];
	}
	BuildEditControl($field,@$params["value"],$format,$mode,$second,$id);
}

function xt_showchart($params)
{
$width=700;
$height=530;
if(array_key_exists("custom1",$params))
	$width=$params["custom1"];
if(array_key_exists("custom2",$params))
	$height=$params["custom2"];
?>
<div id='<?php echo $params["chartname"] ?>'>
<noscript>
	<object id="<?php echo $params['chartname'];?>" 
			name="<?php echo $params['chartname'];?>" 
			classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
			width="100%" 
			height="100%" 
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
		<param name="movie" value="libs/swf/Preloader.swf" />
		<param name="bgcolor" value="#FFFFFF" />

		<param name="allowScriptAccess" value="always" />
		<param name="flashvars" value="swfFile=<?php echo 'dchartdata.php%3Fchartname%3D'.$params['chartname'] ?>" />
		
		<embed type="application/x-shockwave-flash" 
			   pluginspage="http://www.adobe.com/go/getflashplayer" 
			   src="libs/swf/Preloader.swf" 
			   width="100%" 
			   height="100%" 
			   id="<?php echo $params['chartname'];?>" 
			   name="<?php echo $params['chartname'];?>" 
			   bgColor="#FFFFFF" 
			   allowScriptAccess="always" 
			   flashvars="swfFile=<?php echo 'dchartdata.php%3Fchartname%3D'.$params['chartname'] ?>" />
	</object>				
</noscript>
<script type="text/javascript" language="javascript">
	//<![CDATA[
	var chart = new AnyChart('libs/swf/AnyChart.swf','libs/swf/Preloader.swf');
	chart.width = '<?php echo $width; ?>';
	chart.height = '<?php echo $height; ?>';

	var xmlFile = 'dchartdata.php%3Fchartname%3D<?php echo $params["chartname"];?>';
	xmlFile += '%26ctype%3D<?php echo $params["ctype"];?>';
	chart.setXMLFile(xmlFile);
	chart.write('<?php echo $params["chartname"];?>');
	//]]>
</script>
</div>
<?php
	if(function_exists($params["chartname"]))
		eval($params["chartname"]."();");}

function xt_include($params)
{
	$path_parts = pathinfo(__file__);
	$path = $path_parts["dirname"];
	$path = substr($path,0,strlen($path)-4);
	if(file_exists($path.$params["file"]))
		include($path.$params["file"]);
}

function xt_doevent($params)
{
	if(function_exists(@$params["custom1"]))
		eval($params["custom1"].'($params);');
}

function xt_label($params)
{
	echo GetFieldLabel($params["custom1"],$params["custom2"]);
}

function xt_caption($params)
{
	echo GetTableCaption($params["custom1"]);
}

?>