var detailspreview_included=true;

function preview_inline(link)
{
	var tparents=$(link).parents("tr");
	if(!tparents.length)
		return;
	var i;
	for(i=0;i<tparents.length;i++)
		if($(tparents[i]).attr("rowid"))
			break;
	if(i==tparents.length)
		return;
	var tr=tparents[i];
	var rowid=$(tr).attr("rowid");
// determine record id
	var pos = link.id.lastIndexOf("_preview");
	if(pos<0)
		return;
	var recid = new Number(link.id.substring(pos+8));
	var dtable=link.id.substring(0,pos);
//	check if preview TR is created
	var previewtr=document.getElementById("dpreviewrow_"+rowid);
	if(!previewtr)
	{
	//	count number if cells in TR, find current record in row
		var tchildren=$(tr).children("td");
		varcolscount= new Array();
		var start=0;
		tparents=$(link).parents("td");
		if(!tparents.length)
			return;
		var tdparent=tparents[0];
		var myplace=0;
		for(i=0;i<tchildren.length;i++)
		{
			if(tdparent==tchildren[i])
				myplace=varcolscount.length;
			if($(tchildren[i]).attr("colid")=="endrecord")
			{
				varcolscount[varcolscount.length]=i-start;
				start=i+1;
			}
		}
		varcolscount[varcolscount.length]=i-start;
		
	//	create new TR
		previewtr=$(tr).clone();
		$(previewtr).attr("id","dpreviewrow_"+rowid);
		$(previewtr).insertAfter(tr);
		previewtr=document.getElementById("dpreviewrow_"+rowid);
	//	remove all unnecessary TDs
		$("td[@colid!=endrecord]",previewtr).remove();
	//	fill row with new TDs
		tchildren=$(previewtr).children("td");
		for(i=0;i<tchildren.length;i++)
		{
			$(tchildren[i]).before("<td id=\"dpreview_"+(recid+i-myplace)+"\" colspan="+varcolscount[i]+"></td>");
		}
		if(i)
			$(tchildren[i-1]).after("<td id=\"dpreview_"+(recid+i-myplace)+"\" colspan="+varcolscount[i]+"></td>");
		else
			$(previewtr).html("<td id=\"dpreview_"+(recid+i-myplace)+"\" colspan="+varcolscount[i]+"></td>");
			
	}
//	get details page contents
	var tdpreview = document.getElementById("dpreview_"+recid);
	if(!tdpreview)
		return;
	pos = link.href.indexOf("?");
	if(pos<0)
		return;
	var url=dtable+"_detailspreview.php"+link.href.substr(pos);
	tdpreview.style.borderWidth="1px";
	tdpreview.style.borderStyle="solid";
	tdpreview.style.borderColor="darkgray";
	if(!tdpreview.innerHTML.length)
		$(tdpreview).html(TEXT_LOADING + "...");
//	change other links to "preview"
	$("[@id$=_preview"+recid+"]").each(function (){
		this.innerHTML=TEXT_PREVIEW;
		this.onclick=function() {preview_inline(this); return false;};
	});
	$.get(url, 
	{
	    counter: 0,
		mode: "inline",
	    rndVal: (new Date().getTime())
	}, 
	function(txt){ 
		$(tdpreview).html(txt);
		$(link).html(TEXT_HIDE);
		link.onclick=function() {hide_inline(link); return false;};
	});				
	
}

function hide_inline(link)
{
	$(link).html(TEXT_PREVIEW);
	link.onclick=function() {preview_inline(link); return false;};
// determine record id
	var pos = link.id.lastIndexOf("_preview");
	if(pos<0)
		return;
	var recid = new Number(link.id.substring(pos+8));
	var dtable=link.id.substring(0,pos);
	var tdpreview = document.getElementById("dpreview_"+recid);
	if(!tdpreview)
		return;
	tdpreview.innerHTML="";
	tdpreview.style.borderStyle="none";
//	check if whole row can be removed
	var tparents=$(tdpreview).parents("tr");
	if(!tparents.length)
		return;
	var previewtr=tparents[0];
	var tchildren=$(previewtr).children("td");
	for(i=0;i<tchildren.length;i++)
		if($(tchildren[i]).attr("colid")!="endrecord" && tchildren[i].innerHTML.length)
			break;
	if(i<tchildren.length)
		return;
	$(previewtr).remove();
}