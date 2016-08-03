<?php
    header("Content-Type: text/xml");

    ini_set("display_errors","1");
    ini_set("display_startup_errors","1");
    header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
    set_magic_quotes_runtime(0);

    include("include/dbcommon.php");
    include("include/reportfunctions.php");
    include("include/xml.php");

    $conn=db_connect();

    $xml = new xml();
    
	$chrt_strXML="";
	if (postvalue("chartname"))
		$chrt_strXML = GetChartXML(postvalue("chartname"));

	if (!$chrt_strXML)
		$chrt_strXML = LoadSelectedChart( $_REQUEST['cname'] );

    $chrt_array = $xml->xml_to_array( $chrt_strXML );

    include("include/" . $chrt_array['settings']['short_table_name'] . "_variables.php");

    $width=700;
    $height=530;
    if($_REQUEST["width"])
    $width=$_REQUEST["width"];
        
    if($_REQUEST["height"])
    $height=$_REQUEST["height"];
    
    $numRecordsToShow = 25;
    $header = ( (preg_match("/new chart/i", $chrt_array['appearance']['head'])) &&
        (!preg_match("/new chart/i", $chrt_array['settings']['title']))) ? $chrt_array['settings']['title'] : $chrt_array['appearance']['head'];
    $footer = ( (preg_match("/new chart/i", $chrt_array['appearance']['foot'])) &&
        (!preg_match("/new chart/i", $chrt_array['settings']['title']))) ? $chrt_array['settings']['title'] : $chrt_array['appearance']['foot'];    
    
    $arrDataSeries = array();
    for ( $i=0; $i<count($chrt_array['parameters'])-1; $i++) {
        if ( $chrt_array['parameters'][$i]['name'] != "" ) {
            $arrDataSeries[] = $chrt_array['parameters'][$i]['name'];
        }	
    }
    $label = $chrt_array['parameters'][count($chrt_array['parameters'])-1]['name'];
    
    $arrAxesColor = array("","#1D8BD1","#F1683C","#0065CE");

    $strWhereClause = CalcSearchParameters();

    $strSQL = gSQLWhere($strWhereClause);
    // $strSQL = gSQLWhere($strWhereClause);

    $strOrderBy = $gstrOrderBy;
    $strSQL.= " ".$strOrderBy;

    $strSQLbak=$strSQL;
    if(function_exists("BeforeQueryChart"))
    BeforeQueryChart($strSQL,$strWhereClause,$strOrderBy);
    if($strSQLbak == $strSQL)
    {
        $strSQL=gSQLWhere($strWhereClause);
        $strSQL.= " ".$strOrderBy;
    }

    $rs=db_query($strSQL,$conn);

    writeChart();

    // close connection:
    db_close($conn);

    function writeChart() {
        global $chrt_array;
        
        $output = '<?xml version="1.0" standalone="yes"?>'."\n";
        $output .= <<<EOXML

<anychart>
  <settings>
        
EOXML;
        
        if ( $chrt_array['appearance']['sanim'] == "true" ) {
            $output .= '<animation enabled="True" />'."\n";
        } else {
            $output .= '<animation enabled="False" />'."\n";
        }
        
        $output .= <<<EOXML
  </settings>
    <charts>

EOXML;

        echo $output;

        if ( function_exists( "write_".$_REQUEST['ctype']."_chart" ) ) 
            eval( "write_".$_REQUEST['ctype']."_chart();" );
	else if ( function_exists( "write_".$chrt_array['chart_type']['type']."_chart" ) ) {
            eval( "write_".$chrt_array['chart_type']['type']."_chart();" );
        }
        
        $output = <<<EOXML
    </charts>
</anychart>		
EOXML;

        echo $output;
    }

    function write_2d_pie_chart(){
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
        <chart plot_type="Pie">
          <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" type="Pie" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }
  
        $output .= <<<EOXML
          </data>
          <data_plot_settings enable_3d_mode="false">
            <pie_series style="Default">
              <tooltip_settings enabled="true">
                <format>
EOXML;
        $output .= '{%Name}'."\n";
        $output .= 'Value: {%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'}'."\n";
        $output .= 'Percent: {%YPercentOfSeries}{numDecimals:'.$chrt_array['appearance']['dec'].'}%'."\n";
        $output .= <<<EOXML
                </format>
              </tooltip_settings>
EOXML;

        if ( count($arrDataSeries) > 1 ){
            $output .= <<<EOXML
              <label_settings enabled="true">
                <background enabled="false"/>
                <position anchor="Center" valign="Center" halign="Center" padding="20"/>
                <font color="White">
                  <effects>
                    <drop_shadow enabled="true" distance="2" opacity="0.5" blur_x="2" blur_y="2"/>
                  </effects>
                </font>
                <format>{%YPercentOfSeries}{numDecimals:2}%</format>
              </label_settings>
EOXML;
        } else {
            $output .= <<<EOXML
              <label_settings enabled="true" mode="Outside" multi_line_align="Center">
                <background enabled="false"/>
                <position anchor="Center" valign="Center" halign="Center" padding="20"/>
                <font bold="false" />
                <format>
EOXML;
            $output .= '{%Name}'."\n";
            $output .= '{%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'} ({%YPercentOfSeries}{numDecimals:2}%)'."\n";	
            $output .= <<<EOXML
                </format>
              </label_settings>
              <connector color="Black" opacity="0.4"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </pie_series>
          </data_plot_settings>

          <chart_settings>
            <title enabled="true" padding="15">
                <text>{$header}</text>
                <font color="#{$chrt_array['appearance']['color101']}"/>
            </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
            <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" padding="15" height="20%">
              <template></template>
              <title enabled="true">
                <text>{$footer}</text>
                <font color="#{$chrt_array['appearance']['color111']}"/>
              </title>
              <columns_separator enabled="false"/>
              <background>
                <inside_margin left="10" right="10"/>
              </background>
              <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>				  
              <items>
                <item source="Points"/> 
              </items>
            </legend>
EOXML;
        }
        $output .= <<<EOXML
            <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= <<<EOXML
          </chart_background>
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_2d_doughnut_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
        <chart plot_type="Doughnut">
          <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" type="Pie" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }
  
        $output .= <<<EOXML
          </data>
          <data_plot_settings enable_3d_mode="false">
            <pie_series style="Default">
              <tooltip_settings enabled="true">
                <format>
EOXML;
        $output .= '{%Name}'."\n";
        $output .= 'Value: {%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'}'."\n";
        $output .= 'Percent: {%YPercentOfSeries}{numDecimals:2}%'."\n";
        $output .= <<<EOXML
                </format>
              </tooltip_settings>
EOXML;

        if ( count($arrDataSeries) > 1 ){
            $output .= <<<EOXML
              <label_settings enabled="true">
                <background enabled="false"/>
                  <position anchor="Center" valign="Center" halign="Center" padding="20"/>
                  <font color="White">
                    <effects>
                      <drop_shadow enabled="true" distance="2" opacity="0.5" blur_x="2" blur_y="2"/>
                    </effects>
                  </font>
                  <format>{%YPercentOfSeries}{numDecimals:2}%</format>
                </label_settings>
EOXML;
        } else {
            $output .= <<<EOXML
                <label_settings enabled="true" mode="Outside" multi_line_align="Center">
                  <background enabled="false"/>
                  <position anchor="Center" valign="Center" halign="Center" padding="20"/>
                  <font bold="false" />
                  <format>
EOXML;
            $output .= '{%Name}'."\n";
            $output .= '{%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'} ({%YPercentOfSeries}{numDecimals:2}%)'."\n";	
            $output .= <<<EOXML
                  </format>
                </label_settings>
                <connector color="Black" opacity="0.4"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </pie_series>
          </data_plot_settings>

          <chart_settings>
            <title enabled="true" padding="15">
              <text>{$header}</text>
              <font color="#{$chrt_array['appearance']['color101']}"/>
            </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
            <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" padding="15" height="20%">
              <template></template>
              <title enabled="true">
                <text>{$footer}</text>
                <font color="#{$chrt_array['appearance']['color111']}"/>
              </title>
              <columns_separator enabled="false"/>
              <background>
                <inside_margin left="10" right="10"/>
              </background>
              <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>				  
              <items>
                <item source="Points"/> 
              </items>
            </legend>
EOXML;
        }
        $output .= '<chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }

        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= <<<EOXML
          </chart_background>
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_2d_column_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <data_plot_settings default_series_type="Bar">
EOXML;
        $output .= '<bar_series group_padding="0.5"';
        if ( $chrt_array['appearance']['aqua'] == 1 ) {
            $output .= ' style="AquaLight"';
        } elseif ( $chrt_array['appearance']['aqua'] == 2 ) {
            $output .= ' style="AquaDark"';
        }
        if ( $chrt_array['appearance']['cview'] == 1 ) {
            $output .= ' shape_type="Cone"';
        } elseif ( $chrt_array['appearance']['cview'] == 2 ) {
            $output .= ' shape_type="Cylinder"';
        } elseif ( $chrt_array['appearance']['cview'] == 3 ) {
            $output .= ' shape_type="Pyramid"';
        }
        $output .= '>';
        
        $output .= <<<EOXML
            <tooltip_settings enabled="True">
                <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </tooltip_settings>
            <label_settings enabled="True">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
          </bar_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
                <background enabled="false">
                  <fill enabled="false" />
                  <border enabled="true" />
                </background>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="#{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="#{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_2d_column_stacked_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <data_plot_settings default_series_type="Bar">
EOXML;
        $output .= '<bar_series group_padding="0.5"';
        if ( $chrt_array['appearance']['aqua'] == 1 ) {
            $output .= ' style="AquaLight"';
        } elseif ( $chrt_array['appearance']['aqua'] == 2 ) {
            $output .= ' style="AquaDark"';
        }
        if ( $chrt_array['appearance']['cview'] == 1 ) {
            $output .= ' shape_type="Cone"';
        } elseif ( $chrt_array['appearance']['cview'] == 2 ) {
            $output .= ' shape_type="Cylinder"';
        } elseif ( $chrt_array['appearance']['cview'] == 3 ) {
            $output .= ' shape_type="Pyramid"';
        }
        $output .= '>';
        
        $output .= <<<EOXML
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </tooltip_settings>
            <label_settings enabled="True"  rotation="0">
              <position  anchor="Center" halign="Center" valign="Center" padding="0"/>
              <format>{%YPercentOfSeries}{numDecimals:{$chrt_array['appearance']['dec']}}%</format>
              <font bold="true" color="White">
                <effects>
                  <drop_shadow enabled="True" opacity="0.5" distance="2" blur_x="1" blur_y="1"/>
                </effects>
              </font>
              <background enabled="False"/>
            </label_settings>
          </bar_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Right">
            <format>{%Icon} {%Name}</format>
            <template></template>
            <title enabled="false">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sstacked'] == "true" ) {
            $output .= '<scale mode="PercentStacked" maximum="100" major_interval="10"/>';
        } else {
            $output .= '<scale mode="Stacked"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_3d_column_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <data_plot_settings default_series_type="Bar" enable_3d_mode="True">
          <bar_series group_padding="0.5">
            <tooltip_settings enabled="True">
                <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </tooltip_settings>
            <label_settings enabled="True">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>            
          </bar_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_3d_column_stacked_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <data_plot_settings default_series_type="Bar" enable_3d_mode="True">
          <bar_series group_padding="0.5">
            <label_settings enabled="True"  rotation="0">
              <position  anchor="Center" halign="Center" valign="Center" padding="0"/>
              <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
              <font bold="False" color="White">
                <effects>
                  <drop_shadow enabled="True" opacity="0.5" distance="2" blur_x="1" blur_y="1"/>
                </effects>
              </font>
              <background enabled="False"/>
            </label_settings>
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </tooltip_settings>
          </bar_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Right">
            <format>{%Icon} {%Name}</format>
            <template></template>
            <title enabled="false">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enbaled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sstacked'] == "true" ) {
            $output .= '<scale mode="PercentStacked" maximum="100" major_interval="10"/>';
        } else {
            $output .= '<scale mode="Stacked"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_3d_bar_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedHorizontal">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <data_plot_settings default_series_type="Bar" enable_3d_mode="True" z_aspect="1.1">
          <!-- <bar_series group_padding="0.5" shape_type="Cylinder"> -->
            <bar_series group_padding="0.5">
              <tooltip_settings enabled="True">
                <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>				
              </tooltip_settings>
              <label_settings enabled="True"  rotation="0">
                <position  anchor="Center" halign="Center" valign="Center" padding="0"/>
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font bold="False" color="White">
                  <effects>
                    <drop_shadow enabled="True" opacity="0.5" distance="2" blur_x="1" blur_y="1"/>
                  </effects>
                </font>
                <background enabled="False"/>
              </label_settings>
            </bar_series>
          </data_plot_settings>		
                
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
                        <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enbaled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_3d_bar_stacked_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedHorizontal">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="Bar" enable_3d_mode="True" z_aspect="1.1">
          <!-- <bar_series group_padding="0.5" shape_type="Cylinder"> -->
          <bar_series group_padding="0.5">
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>				
            </tooltip_settings>
            <label_settings enabled="True"  rotation="0">
              <position  anchor="Center" halign="Center" valign="Center" padding="0"/>
              <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
              <font bold="False" color="White">
                <effects>
                  <drop_shadow enabled="True" opacity="0.5" distance="2" blur_x="1" blur_y="1"/>
                </effects>
              </font>
              <background enabled="False"/>
            </label_settings>
          </bar_series>
        </data_plot_settings>		
                
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Right">
                        <format>{%Icon} {%Name}</format>
            <template></template>
            <title enabled="false">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enbaled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sstacked'] == "true" ) {
            $output .= '<scale mode="PercentStacked" maximum="100" major_interval="10"/>';
        } else {
            $output .= '<scale mode="Stacked" maximum_offset="0.001"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_2d_bar_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedHorizontal">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="Bar">
EOXML;
        $output .= '<bar_series group_padding="0.5"';
        if ( $chrt_array['appearance']['aqua'] == 1 ) {
            $output .= ' style="AquaLight"';
        } elseif ( $chrt_array['appearance']['aqua'] == 2 ) {
            $output .= ' style="AquaDark"';
        }
        if ( $chrt_array['appearance']['cview'] == 1 ) {
            $output .= ' shape_type="Cone"';
        } elseif ( $chrt_array['appearance']['cview'] == 2 ) {
            $output .= ' shape_type="Cylinder"';
        } elseif ( $chrt_array['appearance']['cview'] == 3 ) {
            $output .= ' shape_type="Pyramid"';
        }
        $output .= '>';
        
        $output .= <<<EOXML
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>				
            </tooltip_settings>
            <label_settings enabled="true">
              <background enabled="false"/>
              <font color="DarkColor(%Color)"/>
              <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
          </bar_series>
        </data_plot_settings>		
                
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
                        <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="true">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_2d_bar_stacked_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedHorizontal">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="Bar">
EOXML;
        $output .= '<bar_series group_padding="0.5"';
        if ( $chrt_array['appearance']['aqua'] == 1 ) {
            $output .= ' style="AquaLight"';
        } elseif ( $chrt_array['appearance']['aqua'] == 2 ) {
            $output .= ' style="AquaDark"';
        }
        if ( $chrt_array['appearance']['cview'] == 1 ) {
            $output .= ' shape_type="Cone"';
        } elseif ( $chrt_array['appearance']['cview'] == 2 ) {
            $output .= ' shape_type="Cylinder"';
        } elseif ( $chrt_array['appearance']['cview'] == 3 ) {
            $output .= ' shape_type="Pyramid"';
        }
        $output .= '>';
        
        $output .= <<<EOXML
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>				
            </tooltip_settings>
            <label_settings enabled="True"  rotation="0">
              <position  anchor="Center" halign="Center" valign="Center" padding="0"/>
              <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
              <font bold="False" color="White">
                <effects>
                  <drop_shadow enabled="True" opacity="0.5" distance="2" blur_x="1" blur_y="1"/>
                </effects>
              </font>
              <background enabled="False"/>
            </label_settings>			
          </bar_series>
        </data_plot_settings>		
                
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Right">
            <format>{%Icon} {%Name}</format>
            <template></template>
            <title enabled="false">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sstacked'] == "true" ) {
            $output .= '<scale mode="PercentStacked" maximum="100" major_interval="10"/>';
        } else {
            $output .= '<scale mode="Stacked" maximum_offset="0.001"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_line_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="Line">
          <line_series point_padding="0.2" group_padding="1">
            <label_settings enabled="true">
                <background enabled="false"/>
                <font color="Rgb(45,45,45)" bold="true" size="9">
                    <effects enabled="true">
                        <glow enabled="true" color="White" opacity="1" blur_x="1.5" blur_y="1.5" strength="3"/>
                    </effects>
                </font>
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            <tooltip_settings enabled="True">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%YValue}{numDecimal:'.$chrt_array['appearance']['dec'].'}';	
        $output .= <<<EOXML
</format>
              <background>
                <border type="Solid" color="DarkColor(%Color)"/>
              </background>
              <font color="DarkColor(%Color)"/>
            </tooltip_settings>
            <marker_settings enabled="true"/>
            <line_style>
              <line thickness="3"/>
            </line_style>
          </line_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
            <background enabled="false"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>              
              </labels>
EOXML;

        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis tickmarks_placement="Center">
              <title enabled="false">
                <text>{$label}</text>
              </title>
              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="Stager">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_spline_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="Spline">
          <line_series point_padding="0.2" group_padding="1">
            <label_settings enabled="true">
                <background enabled="false"/>
                <font color="Rgb(45,45,45)" bold="true" size="9">
                    <effects enabled="true">
                        <glow enabled="true" color="White" opacity="1" blur_x="1.5" blur_y="1.5" strength="3"/>
                    </effects>
                </font>
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            <tooltip_settings enabled="True">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%YValue}{numDecimals:'.$chrt_array['appearance']['dec'].'}';	
        $output .= <<<EOXML
</format>
                <background>
                    <border type="Solid" color="DarkColor(%Color)"/>
                </background>
                <font color="DarkColor(%Color)"/>
            </tooltip_settings>
            <marker_settings enabled="true"/>
            <line_style>
              <line thickness="3"/>
            </line_style>
          </line_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
            <background enabled="false"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis tickmarks_placement="Center">
              <title enabled="false"><text>{$label}</text></title>
              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="Stager">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_step_line_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <data_plot_settings default_series_type="StepLineForward">
          <line_series point_padding="0.2" group_padding="1">
            <label_settings enabled="true">
                    <background enabled="false"/>
                    <font color="Rgb(45,45,45)" bold="true" size="9">
                            <effects enabled="true">
                                    <glow enabled="true" color="White" opacity="1" blur_x="1.5" blur_y="1.5" strength="3"/>
                            </effects>
                    </font>
                    <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            <tooltip_settings enabled="True">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%YValue}{numDecimals:'.$chrt_array['appearance']['dec'].'}';	
        $output .= <<<EOXML
</format>
                <background>
                  <border type="Solid" color="DarkColor(%Color)"/>
                </background>
                <font color="DarkColor(%Color)"/>
              </tooltip_settings>
              <marker_settings enabled="true"/>
              <line_style>
                <line thickness="3"/>
              </line_style>
          </line_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
            <background enabled="false"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis tickmarks_placement="Center">
              <title enabled="false">
                <text>{$label}</text>
              </title>
              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="Stager">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_area_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
        <!-- <data_plot_settings default_series_type="SplineArea"> -->
        <data_plot_settings default_series_type="Area">
          <area_series point_padding="0.2" group_padding="1">
            <label_settings enabled="true">
                <position anchor="CenterBottom"/>
                <background enabled="true">
                    <border enabled="false"/>
                    <fill enabled="true" type="Solid" color="DarkColor(%Color)" opacity="0.8"/>
                    <effects enabled="false"/>
                    <inside_margin all="0"/>
                    <corners type="Rounded" all="3"/>
                </background>
                <font color="White" bold="false"/>
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            <area_style>
                <line enabled="true" thickness="2" color="%Color"/>
                <fill color="%Color" opacity="0.5"/>
                <states>
                    <hover>
                        <line enabled="true" thickness="2" color="LightColor(%Color)"/>
                        <fill color="LightColor(%Color)" opacity="1.0"/>
                    </hover>
                </states>
            </area_style>
            <marker_settings enabled="True">
                <marker type="Circle" size="6"/>
            </marker_settings>		  
            <tooltip_settings enabled="True">
              <format>{%Name} - {%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
              <background>
                <border color="DarkColor(%Color)"/>
              </background>
              <font color="DarkColor(%Color)"/>
            </tooltip_settings>
          </area_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
            <background enabled="false"/>
          </title>
EOXML;
        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
            <format>{%Icon} {%Name} ({%YValue}{numDecimals:{$chrt_array['appearance']['dec']}})</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis tickmarks_placement="Center">
              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
              <title enabled="false"><text>{$label}</text></title>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_area_stacked_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;

        for ( $i=0; $i < count($arrDataSeries); $i++ ) {
            /*
             * Use default color palette
             *
             * $output .= '<series name="'.$arrDataSeries[$i].'" palette="Default">';
             */            
            $output .= '<series name="'.$arrDataSeries[$i].'" color="#'.$chrt_array["appearance"]["color".($i+1)."1"].'">';
            $rs=db_query($strSQL,$conn);
            $j = 0;
            while ($row = db_fetch_array($rs)) 
            {
                $j++;
                if ( $j > $numRecordsToShow ) {
                    break;
                }
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[$i]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }

        $output .= <<<EOXML
        </data>
          <!-- <data_plot_settings default_series_type="Area"> -->
          <data_plot_settings default_series_type="SplineArea">
          <area_series point_padding="0.2" group_padding="1">
            <area_style>
                <line enabled="true" thickness="2" color="%Color"/>
                <fill color="%Color" opacity="0.5"/>
                <states>
                  <hover>
                    <line enabled="true" thickness="2" color="LightColor(%Color)"/>
                    <fill color="LightColor(%Color)" opacity="1.0"/>
                  </hover>
                </states>
            </area_style>
            <marker_settings enabled="True">
              <marker type="Circle" size="5"/>
            </marker_settings>
            <!--
            <label_settings enabled="true">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            -->
            <tooltip_settings enabled="True">
              <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
              <background>
                <border color="DarkColor(%Color)"/>
              </background>
              <font color="DarkColor(%Color)"/>
            </tooltip_settings>
          </area_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
            <background enabled="false"/>
          </title>
EOXML;

        if ( $chrt_array['appearance']['slegend'] == "true" ) {
            $output .= <<<EOXML
          <legend enabled="true" position="Right">
            <format>{%Icon} {%Name}</format>
            <template></template>
            <title enabled="false">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
          </legend>
EOXML;
        }

        $output .= <<<EOXML
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sstacked'] == "true" ) {
            $output .= '<scale mode="PercentStacked" maximum="100" major_interval="10"/>';
        } else {
            $output .= '<scale mode="Stacked" maximum_offset="0.001"/>';
        }
        // <scale minimum="1" type="Logarithmic" log_base="5"/>
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis tickmarks_placement="Center">
              <labels enbaled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
              <title enabled="false"><text>{$label}</text></title>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }

    function write_combined_chart() {
        global $strSQL, $conn, $chrt_array, $arrDataSeries, $label, $arrAxesColor,
        $numRecordsToShow, $header, $footer;
        
        $output = <<<EOXML
      <chart plot_type="CategorizedVertical">
        <data>
EOXML;
        
        $output .= '<series name="'.$arrDataSeries[0].'" type="Spline">';
        $rs=db_query($strSQL,$conn);
        $i = 0;
        while ($row = db_fetch_array($rs)) 
        {
            $i++;
            if ( $i > $numRecordsToShow ) {
                break;
            }
            $value=$row[$label];
            if(strlen($value)>15)
            {
                $value=substr($row[$label],0,12)."...";
            }
            $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[0]]+0). '"/>'."\n";
        }
        $output .= '</series>';
        
        if ( count($arrDataSeries) > 1 ) {
            $output .= '<series name="'.$arrDataSeries[1].'" type="SplineArea">';
            $rs=db_query($strSQL,$conn);
            $i = 0;	
            while ($row = db_fetch_array($rs)) 
            {
                $i++;
                if ( $i > $numRecordsToShow ) {
                    break;
                }		
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[1]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }
        
        if ( count($arrDataSeries) > 2 ) {
            $output .= '<series name="'.$arrDataSeries[2].'" type="Bar">';
            $rs=db_query($strSQL,$conn);
            $i = 0;	
            while ($row = db_fetch_array($rs)) 
            {
                $i++;
                if ( $i > $numRecordsToShow ) {
                    break;
                }		
                $value=$row[$label];
                if(strlen($value)>15)
                {
                    $value=substr($row[$label],0,12)."...";
                }
                $output .= '<point name="' . xmlencode($value) . '" y="'. xmlencode($row[$arrDataSeries[2]]+0). '"/>'."\n";
            }
            $output .= '</series>';
        }
        
        $output .= <<<EOXML
        </data>
                <data_plot_settings default_series_type="Bar">
          <bar_series group_padding="0.3">
            <!--
            <label_settings enabled="true">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            -->
            <tooltip_settings enabled="True">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'}'."\n";	
        $output .= <<<EOXML
</format>				
            </tooltip_settings>
          </bar_series>
          <line_series>
            <!--
            <label_settings enabled="true">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            -->
            <tooltip_settings enabled="True">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'}'."\n";	
        $output .= <<<EOXML
</format>
            </tooltip_settings>
            <line_style>
              <line thickness="3"/>
            </line_style>			
          </line_series>
          <area_series>
            <!--
            <label_settings enabled="true">
                <format>{%YValue}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
            </label_settings>
            -->
            <tooltip_settings enabled="true">
<format>
EOXML;
        $output .= 'Series: {%SeriesName}'."\n";
        $output .= 'Point Name: {%Name}'."\n";
        $output .= 'Value: {%Value}{numDecimals:'.$chrt_array['appearance']['dec'].'}'."\n";	
        $output .= <<<EOXML
</format>
            </tooltip_settings>
            <area_style>
              <line enabled="true" thickness="1" color="DarkColor(%Color)"/>
              <fill opacity="0.7"/>
              <states>
                <hover>
                      <fill opacity="0.9"/>
                        <hatch_fill enabled="true" type="Checkerboard" opacity="0.2"/>
                    </hover>
              </states>
            </area_style>
        </area_series>
        </data_plot_settings>
        <chart_settings>
          <title enabled="true">
            <text>{$header}</text>
            <font color="#{$chrt_array['appearance']['color101']}"/>
          </title>
          <!--
          <legend enabled="true" position="Bottom" align="Spread" ignore_auto_item="true" height="20%">
<format>
EOXML;
        $output .= '{%Icon} {%Name} ({%YValue}'.$chrt_array['appearance']['dec'].')'."\n";
        $output .= <<<EOXML
</format>
            <template></template>
            <title enabled="true">
              <text>{$footer}</text>
              <font color="#{$chrt_array['appearance']['color111']}"/>
            </title>
            <columns_separator enabled="false"/>
            <background>
              <inside_margin left="10" right="10"/>
            </background>
            <items>
              <item source="Points"/> 
            </items>
          </legend>
          <axes>
            <y_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None"/>
              <major_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <minor_tickmark thickness="1" color="DarkColor(#{$chrt_array['appearance']['color141']})" caps="None" opacity="1"/>
              <title enabled="true">
                <text>{$arrDataSeries[0]}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color141']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sval']}" align="Inside">
                <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                <font color="#{$chrt_array['appearance']['color61']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
EOXML;
        
        if ( $chrt_array['appearance']['slog'] == "true" ) {        
            $output .= '<scale type="Logarithmic" log_base="10"/>';
        }        
        
        if ( $chrt_array['appearance']['sgrid'] == "true" ) {        
            $output .= <<<EOXML
              <major_grid interlaced="True">
                <line color="#{$chrt_array['appearance']['color121']}" opacity="0.7"/>
                <interlaced_fills>
                  <even><fill color="#{$chrt_array['appearance']['color121']}" opacity="0.1"/></even>
                  <odd><fill color="#{$chrt_array['appearance']['color121']}" opacity="0"/></odd>
                </interlaced_fills>
              </major_grid>
              <minor_grid enabled="false"/>
EOXML;
        }
        
        $output .= <<<EOXML
            </y_axis>
            <x_axis>
              <line thickness="1" color="DarkColor(#{$chrt_array['appearance']['color131']})" caps="None"/>
              <title enabled="true" align="Near">
                <text>{$label}</text>
                <font color="DarkColor(#{$chrt_array['appearance']['color131']})"/>
              </title>

              <labels enabled="{$chrt_array['appearance']['sname']}" display_mode="normal">
                <font color="#{$chrt_array['appearance']['color51']}" bold="false" italic="false" underline="false" render_as_html="false">
                  <effects enabled="true">
                    <drop_shadow enabled="true" />
                  </effects>
                </font>
              </labels>
            </x_axis>
              <extra>
EOXML;
        
        if ( $chrt_array['appearance']['saxes'] == "true" ) {        
            for ( $i=1; $i < count($arrDataSeries); $i++ ) 
            {
                $position = ( $i % 2 == 0 ) ? "Normal" : "Opposite";
                $output .= <<<EOXML
                <y_axis name="{$arrDataSeries[$i]}" position="{$position}">
                    <line thickness="1" color="DarkColor({$arrAxesColor[$i]})" caps="None"/>
                    <major_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_tickmark thickness="1" color="DarkColor({$arrAxesColor[$i]})" opacity="1"/>
                    <minor_grid enabled="false"/>
                    <major_grid enabled="false"/>
                    <title enabled="true" align="Center">
                        <text>{$arrDataSeries[$i]}</text>
                        <font color="DarkColor({$arrAxesColor[$i]})"/>
                    </title>

                    <labels align="Inside">
                        <font color="DarkColor({$arrAxesColor[$i]})" bold="True" size="9"/>
                        <format>{%Value}{numDecimals:{$chrt_array['appearance']['dec']}}</format>
                    </labels>
                </y_axis>
EOXML;
            }
        }
        
        $output .= <<<EOXML
            </extra>
          </axes>
          -->
          <chart_background>
EOXML;
        
        if ( $chrt_array['appearance']['color71'] != "" ) {
            $output .= <<<EOXML
            <!--
            <fill type="Solid" color="{$chrt_array['appearance']['color71']}" opacity="0.5" />
            -->
            <fill type="Gradient">
              <gradient angle="90">
                <key position="0" color="#{$chrt_array['appearance']['color71']}"/>
                <key position="1" color="DarkColor(#1D8BD1)" opacity="0.5"/>
              </gradient>					
            </fill>
            <corners type="Square"/>
EOXML;
        }
        if ( $chrt_array['appearance']['color91'] != "" ) {
            $output .= <<<EOXML
            <!--
            <border color="DarkColor(#{$chrt_array['appearance']['color91']})"/>
            -->
            <border enabled="True" thickness="2" type="Gradient">
              <gradient type="Linear">
                <key position="0" color="#{$chrt_array['appearance']['color91']}" opacity="0.5" />
                <key position="1" color="DarkColor(#{$chrt_array['appearance']['color91']})" opacity="1" />
              </gradient>
            </border>
EOXML;
        }

        $output .= '</chart_background>'."\n";
        
        if ( $chrt_array['appearance']['color81'] != "" ) {
            $output .= <<<EOXML
          <data_plot_background>
            <fill color="{$chrt_array['appearance']['color81']}" opacity="0.3"/>
          </data_plot_background>
EOXML;
        }        

        $output .= <<<EOXML
        </chart_settings>
      </chart>
EOXML;

        echo $output;
    }
?>