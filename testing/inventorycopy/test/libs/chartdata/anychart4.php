<?php

$x='<chart>
<attr value="tables">
	<attr value="0">##@TABLE.strDatasourceTable##</attr>
</attr>
<attr value="chart_type">
	<attr value="type">##@TABLE.strChartID##</attr>
</attr>

<attr value="parameters">
##foreach @TABLE.arrChartDataSeries as @field filter @field.strDataField!=""##
<attr value="##eval @index-1##">
<attr value="name">##@field.strDataField s##</attr>
</attr>
##endfor##
</attr>

<attr value="appearance">
##foreach @TABLE.arrChartDataSeries as @field##
	<attr value="color##@index##1">##@field.strColor##</attr>
	<attr value="color##@index##2">##@field.strColor##</attr>
##endfor##

<attr value="color51">##@TABLE.strChartNamesColor##</attr>
<attr value="color52">##@TABLE.strChartNamesColor##</attr>
<attr value="color61">##@TABLE.strChartValuesColor##</attr>
<attr value="color62">##@TABLE.strChartValuesColor##</attr>
<attr value="color71">##@TABLE.strChartFirstColor##</attr>
<attr value="color72">##@TABLE.strChartFirstColor##</attr>
<attr value="color81">##@TABLE.strChartSecondColor##</attr>
<attr value="color82">##@TABLE.strChartSecondColor##</attr>
<attr value="color91">##@TABLE.strChartBorderColor##</attr>
<attr value="color92">##@TABLE.strChartBorderColor##</attr>
<attr value="color101">##@TABLE.strChartHeaderColor##</attr>
<attr value="color102">##@TABLE.strChartHeaderColor##</attr>
<attr value="color111">##@TABLE.strChartFooterColor##</attr>
<attr value="color112">##@TABLE.strChartFooterColor##</attr>
<attr value="color121"></attr>
<attr value="color122"></attr>
<attr value="color131">##@TABLE.strXAxisColor##</attr>
<attr value="color132">##@TABLE.strXAxisColor##</attr>
<attr value="color141">##@TABLE.strYAxisColor##</attr>
<attr value="color142">##@TABLE.strYAxisColor##</attr>

<attr value="slegend">##if @TABLE.bChartLegend##true##else##false##endif##</attr>
<attr value="sgrid">##if @TABLE.bChartGrid##true##else##false##endif##</attr>
<attr value="sname">##if @TABLE.bChartNames##true##else##false##endif##</attr>
<attr value="sval">##if @TABLE.bChartValues##true##else##false##endif##</attr>
<attr value="sanim">##if @TABLE.bAnimation##true##else##false##endif##</attr>
<attr value="scur">##if @TABLE.bChartCurrency##true##else##false##endif##</attr>
<attr value="sstacked">##if @TABLE.b100Stacked##true##else##false##endif##</attr>
<attr value="saxes">##if @TABLE.bMultipleAxes##true##else##false##endif##</attr>
<attr value="slog">##if @TABLE.bLogarithmic##true##else##false##endif##</attr>
<attr value="dec">##@TABLE.nDigits##</attr>
<attr value="head">##@TABLE.strChartHeader##</attr>
<attr value="foot">##@TABLE.strChartFooter##</attr>
<attr value="aqua">##@TABLE.strChartStyle##</attr>
<attr value="cview">##@TABLE.strChartShape##</attr>
</attr>

</attr>

<attr value="settings">
<attr value="name">##@TABLE.strName##</attr>
<attr value="short_table_name">##@TABLE.strShortTableName s##</attr>
</attr>

</chart>';

?>

