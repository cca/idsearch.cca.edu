<?php 
function smarty_function_show_dchart($params, &$smarty)
{
?>
<noscript>
	<object id="<?php echo $params['cname'];?>" 
			name="<?php echo $params['cname'];?>" 
			classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
			width="100%" 
			height="100%" 
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
		<param name="movie" value="libs/swf/Preloader.swf" />
		<param name="bgcolor" value="#FFFFFF" />

		<param name="allowScriptAccess" value="always" />
		<param name="flashvars" value="swfFile=<?php echo 'dchartdata.php%3Fcname%3D'.$params['cname'].'%26ctype%3D'.$params['ctype'] ?>" />
		
		<embed type="application/x-shockwave-flash" 
			   pluginspage="http://www.adobe.com/go/getflashplayer" 
			   src="libs/swf/Preloader.swf" 
			   width="100%" 
			   height="100%" 
			   id="<?php echo $params['cname'];?>" 
			   name="<?php echo $params['cname'];?>" 
			   bgColor="#FFFFFF" 
			   allowScriptAccess="always" 
			   flashvars="swfFile=<?php echo 'dchartdata.php%3Fcname%3D'.$params['cname'].'%26ctype%3D'.$params['ctype'] ?>" />
	</object>				
</noscript>
<script type="text/javascript" language="javascript">
	//<![CDATA[
	var chart = new AnyChart('libs/swf/AnyChart.swf','libs/swf/Preloader.swf');
	chart.width = '<?php echo $params["width"];?>';
	chart.height = '<?php echo $params["height"];?>';

	var xmlFile = 'dchartdata.php%3Fcname%3D<?php echo $params["cname"];?>';
	xmlFile += '%26ctype%3D<?php echo $params["ctype"];?>';
	chart.setXMLFile(xmlFile);
	chart.write();
	//]]>
</script>
<?php
	if(function_exists($params["name"]))
		eval($params["name"]."();");
}
?>