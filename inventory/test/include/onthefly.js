var debug=false;
var removeflyframe;
function DisplayPage(event,page, control,field,tablename,category)
{
	flyid++;
	var id = flyid;
	
	var x,y;
	if($.browser.msie)
	{
		y = event.y;
		x = event.x;
	}
	else
	{
		y = event.clientY;
		x = event.clientX;
	}
	var params;
	var pagetype;
	if(page.indexOf("_add.")>0)
	{
		params={
			editType:"onthefly",
			id:id,
			rndval: Math.random(),
			editType: "onthefly",
			control: control,
			field: field,
			table: tablename,
			category: category
		};
		pagetype="add";
	}
	else if(page.indexOf("_list.")>0)
	{
		params={
			id:id,
			rndval: Math.random(),
			mode: "lookup",
			control: control,
			field: field,
			table: tablename,
			category: category,
			firsttime: 1
		};
		pagetype="list";
	}
	else
		return;
		
		
	$.get(page,	params,
		function(xml)
		{
			var i=xml.indexOf("\n");
			var js="";
			if(i>=0)
			{
				js = slashdecode(xml.substr(0,i));
				xml = xml.substr(i+1);
			}
			if(debug)
			{
				$(document.body).append("<textarea id=htm"+id+" cols=50 rows=10></textarea>");
				$("#htm"+id).text(xml);
			}
			DisplayFlyDiv(xml,js,id,control,x,y-20,pagetype);
		}
	);
	return false;
}

function IsIE6()
{
	var browserName=navigator.appName;
	var browserVer=parseInt(navigator.appVersion); 
	if (browserName=="Microsoft Internet Explorer" && browserVer<7)
		return true;
	return false;
}

function RemoveFlyDiv(id, dontremoveiframe)
{
	$("#fly" + id).remove();
	if(!dontremoveiframe)
	{
		removeflyframe = $("#flyframe" + id)[0];
		setTimeout('$(removeflyframe).remove()',1);
	}
	if(IsIE6())
		$("#fli"+id).remove();
}

function DisplayFlyDiv(html,js,id,control,x,y,pagetype)
{
	if(IsIE6())
		$(document.body).append("<iframe id=fli"+id+" style='background:white; position:absolute;display:none;' > </iframe>");

	var w='width:inherit;';

	if($.browser.msie && false)
	{
		w = 'width:55%;';
	}
		
	$(document.body).append("<div id='flycontainer"+id+"' style='position:absolute;'>"
		+"<div align=center pagetype='"+pagetype+"' id=fly"+id+" style='"+w+"border:solid black 1px; background:white; background-repeat:no-repeat'  control='"+control+"' onmousedown='flydivonclick(this);'></div></div>");
	var title="";
	var titlebar="<div id=display_fly"+id+" onmousedown='fly_mousedown_func(event,this.parentNode)' class=blackshade style='padding:5px 10px;border-bottom:solid black 1px;text-align:right;'><span style='float:left'> "+title+"</span><img src='images/cross.gif' onclick=\"RemoveFlyDiv('"+id+"');\"></div>";
	var container="<div id='flycontents"+id+"' style='padding: 0px 10px 10px 10px; margin=0;'>";
	html=titlebar+container+html+"</div>";
	$("#fly"+id).html(html);
	var flydiv=$("#fly"+id)[0];
	$(flydiv).css("top","-1000000px");
	w = flydiv.offsetWidth;
	var h = flydiv.offsetHeight;
	var scroll=false;
	if(w>document.body.offsetWidth*.66)
	{
		w=document.body.offsetWidth*.66;
		$(flydiv).css("width",""+w+"px");
		h = flydiv.offsetHeight;
	}
	if(h>screen.height*0.8)
	{
		h=screen.height*0.8;
		scroll=true;
	}
	x+=document.body.scrollLeft;
	y+=document.body.scrollTop;
	if(document.body.scrollLeft + document.body.clientWidth<x+flydiv.offsetWidth)
		x= document.body.scrollLeft + document.body.clientWidth - flydiv.offsetWidth-20;
	if( x<document.body.scrollLeft)
		x=document.body.scrollLeft+20;
	if(document.body.scrollTop + document.body.clientHeight<y+flydiv.offsetHeight)
		y= document.body.scrollTop + document.body.clientHeight - flydiv.offsetHeight-20;
	if( y<document.body.scrollTop)
		y=document.body.scrollTop+20;
	if(IsIE6())
	{
		var flyframe=document.getElementById("fli"+id);
		$(flyframe).css("left","" + (x) + "px");
		$(flyframe).css("top",""+(y)+"px");
		$(flyframe).css("width","" + (w) + "px");
		$(flyframe).css("height",""+(h)+"px");
		$(flyframe).show();
	}
	var flycontainer = document.getElementById("flycontainer"+id);
	$(document.body).append($(flydiv).remove());
	document.body.removeChild(flycontainer);
	flydiv=$("#fly"+id)[0];
	$(flydiv).css("position","absolute");
	$(flydiv).css("left","" + (x) + "px");
	$(flydiv).css("top",""+(y)+"px");
	$(flydiv).css("width","" + (w) + "px");
	$(flydiv).css("height",""+(h)+"px");
	if(scroll)
	{
		var flycontents=$("#flycontents"+id)[0];
		$(flycontents).css("overflow","scroll");
		var scrollerWidth = flycontents.offsetWidth-flycontents.clientWidth;
		var scrollerHeight = flycontents.offsetHeight-flycontents.clientHeight;
		$(flycontents).css("width","" + (w-scrollerWidth-3) + "px");
		$(flycontents).css("height",""+(h-18-scrollerHeight)+"px");
	}
	flydivonclick(flydiv);
	var io = createAddIframe(id,control);
	var form=$("form[@name=editform"+id+"]")[0];
	if(form!=undefined)
	{
		$("input[@type=text],input[@type=password],input[@type=hidden],input[@type=file],select",form).each(function(i){
			if ( this.type == "select-multiple"  ) {
				this.id = this.name.replace(/\[\]$/,"") + "_" + id;
			} else {
				this.id = this.name + "_" + id;
			}
		});
	}
	if(js.length)
	{
		if(debug)
		{
			$(document.body).append("<textarea id=txt"+id+" cols=50 rows=10> </textarea>");
			$("#txt"+id).text(js);
		}
		eval(js);			
	}
}

function createAddIframe(id,control)
{
	//create frame
	var frameId = 'flyframe' + id;
//	iframe already exists - reset load counter only
	if($('#'+frameId).length)
	{
		delete $('#'+frameId).loadCount;
//		delete window.frames[frameId].loadCount;
		return;
	}
    if ( window.ActiveXObject ) {
		var iframetxt='<iframe style="background:white; position:absolute;filter:alpha(opacity=0);" onload="if (typeof this.loadCount == \'undefined\') { this.loadCount = 0;	return;} var ioDocument = window.frames[\''+frameId+'\'].document;'+
		'ProcessReturn(ioDocument,\''+control+'\','+id+');" id="' + frameId + '" name="' + frameId + '" />';
		var io = document.createElement(iframetxt);
	}
    else {
		var io = document.createElement('iframe');
		io.id = frameId;
		io.name = frameId;
		$(io).load(function(){
			if (typeof this.loadCount == 'undefined') {
				this.loadCount = 0;
				return;
			}
				var ioDocument = $("#"+frameId).get(0).contentDocument;
				ProcessReturn(ioDocument,control,id);
		});
	}
    io.style.position = 'absolute';
    io.style.top = '-1000px';
    io.style.left = '-1000px';
	document.body.appendChild(io);

	return io;
}

function ProcessReturn(doc,control,id)
{
	if(debug)
	{
		$(document.body).append("<textarea id=err"+id+" cols=50 rows=10></textarea>");
		$("#err"+id).text(doc.body.innerHTML);
	}
	var pagetype=$("#fly"+id).attr("pagetype");
	var txt;
	if($("#data",doc).length)
		txt = $("#data",doc).text();
	else
		txt="error"+doc.body.innerHTML;
	if(txt.substr(0,5)=='added')
	{
		txt=txt.substr(5);
		var blocks=txt.split("\n");
		$.each(blocks,function(i,n){
			blocks[i] = slashdecode(n);
		});

		var fields=blocks[0].split("\n");
		$.each(fields,function(i,n){
			fields[i] = slashdecode(n);
			});
		var lookup = document.getElementById(control);
		if(lookup.tagName=='SELECT')
		{
			create_option(lookup,fields[1],fields[0]);
			if(!lookup.multiple)
				lookup.selectedIndex=lookup.options.length-1;
			else
				lookup.options[lookup.options.length-1].selected=true;
				
		}
		else
		{
			lookup.value=fields[0];
			document.getElementById("display_"+control).value=fields[1];
		}
		if(lookup.onchange)
			lookup.onchange();
		
		RemoveFlyDiv(id);
		
	}
	else if(txt.substr(0,5)=='decli')
	{
		txt = txt.substr(5);
		var y = document.getElementById("fly"+id).offsetTop;
		var x = document.getElementById("fly"+id).offsetLeft;
		$("#data",doc).remove();
		RemoveFlyDiv(id,true);
		DisplayFlyDiv(doc.body.innerHTML,txt,id,control,x,y,pagetype);
	}
	else
	{
		txt = txt.substr(5);
		var y = document.getElementById("fly"+id).offsetTop;
		var x = document.getElementById("fly"+id).offsetLeft;
		RemoveFlyDiv(id,true);
		DisplayFlyDiv(txt,"",id,control,x,y,pagetype);
	}
}


function flydivonclick(div)
{
	if($.browser.msie)
		div.style.zIndex=++zindex_max;
	else
		$(div).css("z-index",++zindex_max);
}

var fly_mousedown=false;
var fly_offsetx,fly_offsety;
var fly_movingdiv;
var fly_initmove=false;


function fly_mousedown_func(e,div)
{
	if(!e)
		e=window.event;
	if(!fly_initmove)
	{
		document.body.oldmousemove=document.body.onmousemove;
		if($.browser.msie)
		{
			document.body.onmousemove = function()
			{
			  var e=window.event
			  if(fly_mousedown)
			  {
				fly_movingdiv.style.left=""+(e.x-fly_offsetx)+"px";
			    fly_movingdiv.style.top=""+(e.y-fly_offsety)+"px";
				if(IsIE6())
				{
					var flyframe=document.getElementById("fli"+fly_movingdiv.id.substr(3));
					flyframe.style.left=""+(e.x-fly_offsetx)+"px";
				    flyframe.style.top=""+(e.y-fly_offsety)+"px";
				}
				
			  }
			  if(document.body.oldmousemove!=null)
				  document.body.oldmousemove();
			}
		}
		else
		{
			document.body.onmousemove = function(e)
			{
			  if(fly_mousedown)
			  {
				fly_movingdiv.style.left=(e.clientX-fly_offsetx);
			    fly_movingdiv.style.top=(e.clientY-fly_offsety);
			  }
			  if(document.body.oldmousemove!=null)
				  document.body.oldmousemove();
			}
		}
		document.body.oldmouseup=document.body.onmouseup;
		document.body.onmouseup=function()
		{
			fly_mousedown=false;
			if(document.body.oldmousemove)
				document.body.oldmousemove();
		}
		fly_initmove=true;
	}
	fly_mousedown=true;
	if($.browser.msie)
	{
		fly_offsetx = e.x-div.offsetLeft;
		fly_offsety = e.y-div.offsetTop;
	}
	else
	{
		fly_offsetx = e.clientX-div.offsetLeft;
		fly_offsety = e.clientY-div.offsetTop;
	}
	fly_movingdiv = div;
	
}

					
function define_fly(id, type) 
{
	var obj=document.getElementById(id);
	if(!obj)
		return;
	obj.validatetype=type;
}


function validate_fly(form)
{
	
	var isValid = true;

	$("div",form).remove(".error");

	$.each(form.elements,function(i){	
		
		if ( this.validatetype == undefined ) { return; }
		
		if ( this.validatetype.indexOf("IsRequired") >= 0 ) {
			var sVal = $(this).val();
			var regexp = /.+/;
			
			if ( !sVal.match(regexp) ) {
				isValid = false;
				$(this).after('<div class="error">' + TEXT_INLINE_FIELD_REQUIRED + '</div>');
			}		
		} 
		
		if ( $("input[@type=text]",this).length ) {
		
			var sVal = $(this).val();
			if ( this.validatetype.indexOf("IsNumeric") >= 0 ) {
				sVal = sVal.replace(/,/g,"");
				if (isNaN(sVal)) 
				{
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_NUMBER + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsPassword") >= 0 ) {
				var regexp1 = /^password$/;
				var regexp2 = /.{4,}/;
				
				if ( sVal.match(/.+/) ) {
					if ( sVal.match(regexp1) ) {
						isValid = false;
						$(this).after('<div class="error">' + TEXT_INLINE_FIELD_PASSWORD1 + '</div>');
					} else if ( !sVal.match(regexp2) ) {
						isValid = false;
						$(this).after('<div class="error">' + TEXT_INLINE_FIELD_PASSWORD2 + '</div>');					
					}
				}				
			}  else if ( this.validatetype.indexOf("IsEmail") >= 0 ) {
				var regexp = /^[A-z0-9_-]+([.][A-z0-9_-]+)*[@][A-z0-9_-]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/;
				
				if ( sVal.match(/.+/) && !sVal.match(regexp) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_EMAIL + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsMoney") >= 0 ) {
				var regexp = /^(\d*)\.?(\d*)$/;
				
				if ( sVal.match(/.+/) && !sVal.match(regexp) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_CURRENCY + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsZipCode") >= 0 ) {
				var regexp = /^\d{5}([\-]\d{4})?$/;
				
				if ( sVal.match(/.+/) && !sVal.match(regexp) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_ZIPCODE + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsPhoneNumber") >= 0 ) {
				var regexp = /^\(\d{3}\)\s?\d{3}\-\d{4}$/;
			    var stripped = sVal.replace(/[\(\)\.\-\ ]/g, '');    

				if ( sVal.match(/.+/) && (isNaN(parseInt(stripped)) || stripped.length != 10) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_PHONE + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsState") >= 0 ) {
				
				if ( sVal.match(/.+/) && !arrStates.inArray(sVal,false) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_STATE + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsSSN") >= 0 ) {
			    // 123-45-6789 or 123 45 6789
				var regexp = /^\d{3}(-|\s)\d{2}(-|\s)\d{4}$/;

				if ( sVal.match(/.+/) && !sVal.match(regexp) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_SSN + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsCC") >= 0 ) {
				//Visa, Master Card, American Express
				var regexp = /^((4\d{3})|(5[1-5]\d{2}))(-?|\040?)(\d{4}(-?|\040?)){3}|^(3[4,7]\d{2})(-?|\040?)\d{6}(-?|\040?)\d{5}/;
				
				if ( sVal.match(/.+/) && !sVal.match(regexp) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_CC + '</div>');
				}		
			}  else if ( this.validatetype.indexOf("IsTime") >= 0 ) {
				
				if ( sVal.match(/.+/) ) {
					var regexp = /\d+/g;
					var arr = sVal.match(regexp);
					var bFlag = true;
					
					if ( arr==null || arr.length > 3 ) { bFlag = false; }
					while ( bFlag && arr.length < 3 ) { arr[arr.length] = 0; }
					if( bFlag && (arr[0]<0 || arr[0]>23 || arr[1]<0 || arr[1]>59 || arr[2]<0 || arr[2]>59) ) { bFlag = false; }
					
					if ( !bFlag ) {
						isValid = false;
						$(this).after('<div class="error">' + TEXT_INLINE_FIELD_TIME + '</div>');
					}
				}
			}  else if ( this.validatetype.indexOf("IsDate") >= 0 ) {
				var fmt = "";
				
				switch (locale_dateformat) {
					case 0 :
						fmt="MDY";
						break;
					case 1 : 
						fmt="DMY";
						break;	
					default:
						fmt="YMD";
						break;				
				};
				
				if ( sVal.match(/.+/) && !isValidDate( sVal, fmt ) ) {
					isValid = false;
					$(this).after('<div class="error">' + TEXT_INLINE_FIELD_DATE + '</div>');			
				}		
			}
		}
	});

	return isValid;
}
var onthefly_included=true;
