var jsfunctions_included=true;

var flyid=1;

function sort(e,url) 
{
  var ctrlPressed = 0;     
  
  if(parseInt(navigator.appVersion) > 3) 
  {
   if (navigator.appName == "Netscape") 
   {
    var ua = navigator.userAgent;
    var isFirefox = (ua != null && ua.indexOf("Firefox/") != -1);
    if ((!isFirefox && getNNVersionNumber() >= 6) || isFirefox) ctrlPressed = e.ctrlKey;
    else ctrlPressed = ((e.modifiers+32).toString(2).substring(3,6).charAt(1)=="1");               
   } 
   else ctrlPressed = event.ctrlKey;
   if (ctrlPressed) 
   {
    var newPage = "<scr" + "ipt language=\"JavaScript\">setTimeout(\'window.location.href=\"" + url + "&ctrl=1\"\', 10);</scr" + "ipt>";
    document.write(newPage);
    document.close();               
    return false;
   }
  }
  return true;
}

function RunSearch(pid)
{
	var form,id='';
	if(pid)
	{
		id=pid;
		form=document.forms['frmSearch'+id];
	}
	else
		form=document.forms.frmSearch;
	
	form.a.value = 'search'; 
	form.SearchFor.value = document.getElementById('ctlSearchFor'+id).value; 
	if(document.getElementById('ctlSearchField'+id)!=undefined)
		form.SearchField.value = document.getElementById('ctlSearchField'+id).options[document.getElementById('ctlSearchField'+id).selectedIndex].value; 
	if(document.getElementById('ctlSearchOption'+id)!=undefined)
		form.SearchOption.value = document.getElementById('ctlSearchOption'+id).options[document.getElementById('ctlSearchOption'+id).selectedIndex].value; 
	else
		form.SearchOption.value = "Contains"; 
	form.submit();
}


function GetGotoPageUrlString (nPageNumber,sUrlText)
{
	return "<a href='JavaScript:GotoPage(" + nPageNumber + ");' style='TEXT-DECORATION: none;'>" + sUrlText 
	+ "</a>";
}

function WritePagination(mypage,maxpages)
{
	if (maxpages > 1 && mypage <= maxpages)
	{
			document.write("<table rows='1' cols='1' align='center' width='95%' border='0'>"); 
			document.write("<tr valign='center'><td align='center'>"); 
			var counterstart = mypage - 9; 
			if (mypage%10) counterstart = mypage - (mypage%10) + 1; 
 
			var counterend = counterstart + 9; 
			if (counterend > maxpages) counterend = maxpages; 
 
			if (counterstart != 1) document.write(GetGotoPageUrlString(1,TEXT_FIRST)+"&nbsp;:&nbsp;"+GetGotoPageUrlString(counterstart - 1,TEXT_PREVIOUS)+"&nbsp;"); 
 
			document.write("<b>[</b>"); 
		
		var pad="";
		var counter	= counterstart;
		for(;counter<=counterend;counter++)
		{
			if (counter != mypage) document.write("&nbsp;" + GetGotoPageUrlString(counter,pad + counter));
			else document.write("&nbsp;<b>" + pad + counter + "</b>");
		}
		document.write("&nbsp;<b>]</b>");
		if (counterend != maxpages) document.write("&nbsp;" + GetGotoPageUrlString (counterend + 1,TEXT_NEXT) + "&nbsp;:&nbsp;" + GetGotoPageUrlString(maxpages,TEXT_LAST))
			
		document.write("</td></tr></table>");		
	}
}


    var rowWithMouse = null;

    function gGetElementById(s) {
      var o = (document.getElementById ? document.getElementById(s) : document.all[s]);
      return o == null ? false : o;
    }

    function rowUpdateBg(row, myId) 
    {
        row.className = (row == rowWithMouse) ? 'rowselected' : ( (myId&1) ? '' : 'shade' );
    }

    function rowRollover(myId, isInRow) {
      // myId is our own integer id, not the DOM id
      // isInRow is 1 for onmouseover, 0 for onmouseout
      var row = document.getElementById('tr_' + myId);
      rowWithMouse = (isInRow) ? row : null;
      rowUpdateBg(row, myId);
    }



function BuildSecondDropDown(arr, SecondField, FirstValue)
{
	document.forms.editform.elements[SecondField].selectedIndex=0;

	document.forms.editform.elements[SecondField].options[0]=new Option(TEXT_PLEASE_SELECT,'');

	var i=1;
	for(ctr=0;ctr<arr.length;ctr+=3)
	{
		if (FirstValue.toLowerCase() == arr[ctr+2].toLowerCase())
		{
			document.forms.editform.elements[SecondField].options[i]=new Option(arr[ctr+1],arr[ctr]);
			i++;
		}
	}
	document.forms.editform.elements[SecondField].length=i;
	if(i<3 && i>1 && !bLoading)
		document.forms.editform.elements[SecondField].selectedIndex=1;
	else
		document.forms.editform.elements[SecondField].selectedIndex=0;
}

function SetSelection(FirstField, SecondField, FirstValue, SecondValue, arr)
{
	var ctr;

	BuildSecondDropDown(arr, SecondField, FirstValue);	 
	if(SecondValue=="" && document.forms.editform.elements[SecondField].length<3)
		return;
	for (ctr=0; ctr<document.forms.editform.elements[SecondField].length; ctr++)
	 if (document.forms.editform.elements[SecondField].options[ctr].value.toLowerCase() == SecondValue.toLowerCase() )
	 	 {
		  document.forms.editform.elements[SecondField].selectedIndex = ctr;
		  break;
		 }
}
function padDateValue(value,threedigits)
{
	if(!threedigits)
	{
		if(value>9)
			return ''+value;
		return '0'+value;
	}
	if(value>9)
	{
		if(value>99)
			return ''+value;
		return '0'+value;
	}
	return '00'+value;
}

function getTimestamp()
{
	var ts = "";
	var now = new Date();
	ts += now.getFullYear();
	ts+=padDateValue(now.getMonth()+1,false);
	ts+=padDateValue(now.getDate(),false)+'-';
	ts+=padDateValue(now.getHours(),false);
	ts+=padDateValue(now.getMinutes(),false);
	ts+=padDateValue(now.getSeconds(),false);
	return ts;
}

function addTimestamp(filename)
{
	var wpos=filename.lastIndexOf('.');
	if(wpos<0)
		return filename+'-'+getTimestamp();
	return filename.substring(0,wpos)+'-'+getTimestamp()+filename.substring(wpos);
}

function create_option( theselectobj, thetext, thevalue ) 
{
theselectobj.options[theselectobj.options.length]= new Option(thetext,thevalue);
}

function SetToFirstControl(name)
{
try {
	if(name)
	{
	    var form=document.forms[name];
		for(i=0; i < form.elements.length; i++)
		{
			if (form.elements[i].type == "hidden" || form.elements[i].disabled)
		  		continue;
	   	    form.elements[i].focus();
			break;
		}
		return;
	}
	var bFound = false;
	for (f=0; !bFound && f<document.forms.length; f++)
	{
	    var form=document.forms[f];
	    for(i=0; i < form.elements.length; i++)
	    {
			if (form.elements[i].type == "hidden" || form.elements[i].disabled)
		  		continue;
			form.elements[i].focus();
	        var bFound = true;
			break;
		}
    }
} catch(er) {} 
}

function slashdecode(str)
{
	var out = new String();
	var pos = 0;
	for ( var i = 0; i < str.length - 1; i++ )
	{
		var c = str.charAt(i);
		if( c == '\\' )
		{
			out += str.substr(pos,i-pos);
			pos = i + 2;
			var c1 = str.charAt(i+1);
			i++;
			if ( c1 == '\\' ) {
				out += "\\";
			} else if ( c1 == 'r' ) {
				out += "\r";
			} else if ( c1 == 'n') {
				out += "\n";
			} else {
				i--;
				pos-=2;
			}
		}
	}
	if ( pos < str.length )
		out += str.substr(pos);
	
	return out;
}

var zindex_max=1;
