<SCRIPT language="javascript">
var checkObjects = new Array();
var errors = "";
var RequiredMsg = "";
var returnVal = false;
var ZipcodeMsg = "";
var EmailMsg = "";
var NumMsg = "";
var MoneyMsg = "";
var PhnMsg = "";
var ShipRateMsg = "";
var PwdMsg = "";
var StateMsg = "";
var SSNMsg="";
var DateMsg="";
var CCMsg = "";

var arrStates = new Array('AL','AK','AS','AZ','AR','CA','CO','CT','DE','DC','FM','FL','GA','GU',
'HI','ID','IL','IN','IA','KS','KY','LA','ME','MH','MD','MA','MI','MN','MS','MO','MT','NE','NV',
'NH','NJ','NM','NY','NC','ND','MP','OH','OK','OR','PW','PA','PR','RI','SC','SD','TN','TX','UT',
'VT','VI','VA','WA','WV','WI','WY');
					
// -----------------------------------------------------------------------------
// define - Call this function in the beginning of the page. I.e. onLoad.
// n = name of the input field (Required)
// type= IsRequired, IsNumeric, IsEmail, IsZipCode, IsDate, IsPhoneNumber,
//	IsMoney, IsPassword (Required), IsSSN, IsState, IsCC
// -----------------------------------------------------------------------------
function define(n, type, HTMLname) 
{
	if (document.editform != null)
	{
		var p = BuildFileName(n);
		if (document.editform[n]!=undefined)
		{
			var s="V_"+p+" = new formResult(document.editform[n], type, HTMLname);"
			eval(s);
			checkObjects[eval(checkObjects.length)] = eval("V_"+p);
		}
	}
}

function formResult(form, type, HTMLname) 
{
	this.form = form;
	this.type = type;
	this.HTMLname = HTMLname;
}

function BuildFileName(name)
{
	var s=name;
	s = s.replace(/\s/g,"");
	s = s.replace(/\W/g,"");
	return s;
}


function validate() 
{
	if (checkObjects.length > 0) 
	{
		errorObject = "";
		for (i = 0; i < checkObjects.length; i++) 
		{
			validateObject = new Object();
			validateObject.form = checkObjects[i].form;
			validateObject.HTMLname = checkObjects[i].HTMLname;
			//this checks to see if the first three letters are cbo - for a drop
			//down - a special call is necessary to get the value in the drop down
			if (checkObjects[i].form.name.substring(0,3) == "cbo")
			{
				validateObject.val = checkObjects[i].form[checkObjects[i].form.selectedIndex].value;
				validateObject.len = checkObjects[i].form[checkObjects[i].form.selectedIndex].length;
			}
			else
			{
				validateObject.val = checkObjects[i].form.value;
				validateObject.len = checkObjects[i].form.value.length;
			}
			validateObject.type = checkObjects[i].type;

						
		// required field ?
			if (validateObject.type.indexOf("IsRequired")>=0)
			{
				if (isWhitespace(validateObject.val))
				{
				//  This builds one list of required fields
					errors += validateObject.HTMLname + "\n";
					RequiredMsg += validateObject.HTMLname + "\n";
				}
				validateObject.type = validateObject.type.substring(0,validateObject.type.indexOf("IsRequired"));

			}

			if (validateObject.type.indexOf("IsNumeric")>=0) 
			{
				var sVal = validateObject.val;
				sVal = sVal.replace(/,/g,"");
				if ( validateObject.len > 0 && isNaN(sVal)) 
				{
					errors += validateObject.HTMLname + "\n";
					NumMsg += validateObject.HTMLname + "\n";
				} 

			} 

			if (validateObject.type.indexOf("IsDate")>=0) 
			{
				var dt = toDate(validateObject.val);
				if(dt==null || isNaN(dt))
				{
					errors += validateObject.HTMLname + "\n";
					DateMsg += validateObject.HTMLname + "\n";
				} 
			} 

			// 123-45-6789 or 123 45 6789
			if (validateObject.type.indexOf("IsSSN")>=0) 
			{
				var bGood = true;
				if ( validateObject.len!=11)
					bGood = false;
				else
				{
					if (validateObject.val.charAt(3)!=" " && validateObject.val.charAt(3)!="-")
						bGood=false;
					if (validateObject.val.charAt(6)!=" " && validateObject.val.charAt(6)!="-")
						bGood=false;
					
					if (bGood)
					{
						var s="";
						for (var j=0; j<validateObject.val.len;++j)
							if (!isNan(validateObject.val.charAt(j)))
								s+=validateObject.val.charAt(j);
						if (isNaN(s))
							bGood = false;	
					}
					
				}
				if (bGood==false)
				{
					errors += validateObject.HTMLname + "\n";
					SSNMsg += validateObject.HTMLname + "\n";
				} 
			} 

			else if(validateObject.type.indexOf("IsEmail")>=0) 
			// Checking existense of "@" and ".". 
			// Length of must >= 5 and the "." must 
			// not directly precede or follow the "@"
			{

				if (isWhitespace(validateObject.val)!=true)
				{
				if ((validateObject.val.indexOf("@") == -1) || (validateObject.val.charAt(0) == ".") || (validateObject.val.charAt(0) == "@") || (validateObject.len < 6) || (validateObject.val.indexOf(".") == -1) || (validateObject.val.charAt(validateObject.val.indexOf("@")+1) == ".") || (validateObject.val.charAt(validateObject.val.indexOf("@")-1) == ".")) 
				{ 
					errors += validateObject.HTMLname + "\n"; 
					EmailMsg += validateObject.HTMLname + "\n";
				}
				}
			}

			else if(validateObject.type.indexOf("IsState")>=0) 
			{

			if (isWhitespace(validateObject.val)!=true)
			{
				var bFound = false;
				for (ind = 0; ind < arrStates.length; ind++) 
					if (validateObject.val == arrStates[ind]) 
					{ 
						bFound=true;
						break;				
					}
				if (bFound==false)
				{
				errors += validateObject.HTMLname + "\n"; 
				StateMsg += validateObject.HTMLname + "\n";
				}
			}
			}

			else if(validateObject.type.indexOf("IsZipCode")>=0)
			{
				
					if (validateObject.len < 4 || validateObject.len > 10)
					{
						errors += validateObject.HTMLname + "\n"; 
						ZipcodeMsg += validateObject.HTMLname + "\n";
					}
					if (ZipcodeMsg == "")
					{
						for (var j=0; j < validateObject.len; j++)
						{
							if ((validateObject.val.charAt(j) < '0' || validateObject.val.charAt(j) > '9') && validateObject.val.charAt(j) != '-')
							{
								errors += validateObject.HTMLname + "\n";
								ZipcodeMsg += validateObject.HTMLname + "\n";
								j = validateObject.len;
							}
						}
					}
				
			}

			else if(validateObject.type.indexOf("IsPhoneNumber")>=0)
			//IsPhoneNumber tells whether the field is a Phone number or not
			//These Phone number fields are valid if there is nothing in the field
			{
				var strField = new String(validateObject.val);
				var numPass=true;
				var k = 0;
				for (k = 0; k < strField.length; k++)
				{
					if ((strField.charAt(k) < '0' || strField.charAt(k) > '9') && (strField.charAt(k) != '-') && (strField.charAt(k) != '(' && (strField.charAt(k) != ')' && (strField.charAt(k) != ' '))))
					{
						errors += validateObject.HTMLname + "\n";
						PhnMsg += validateObject.HTMLname + "\n";
						k = strField.length;
					}
				}
			}


			else if(validateObject.type.indexOf("IsCC")>=0)
			{
                      	var white_space = " -";
                      	var strCC="";
                      	var check_char;

						
      	if (validateObject.val.length != 0)
		{

                      	for (var ii = 0; ii < validateObject.val.length; ii++)
                      	{
                      		check_char = white_space.indexOf(validateObject.val.charAt(ii))
                      		if (check_char < 0)
                      			strCC += validateObject.val.substring(ii, (ii + 1));
                      	}	

                        if ( strCC.length == 0 || isNaN(strCC) )
					{
						errors += validateObject.HTMLname + "\n";
						CCMsg += validateObject.HTMLname + "\n";
					}
	
				else
				{
                         	var doubledigit = strCC.length % 2 == 1 ? false : true;
                         	var checkdigit = 0;
                         	var tempdigit;

                         	for (var ii = 0; ii < strCC.length; ii++)
                         	{
                         		tempdigit = eval(strCC.charAt(ii))

                         		if (doubledigit)
                         		{
                         			tempdigit *= 2;
                         			checkdigit += (tempdigit % 10);

                         			if ((tempdigit / 10) >= 1.0)
                         			{
                         				checkdigit++;
                         			}

                         			doubledigit = false;
                         		}
                         		else
                         		{
                         			checkdigit += tempdigit;
                         			doubledigit = true;
                         		}
                         	}	

					if ((checkdigit % 10) != 0)
					{
						errors += validateObject.HTMLname + "\n";
						CCMsg += validateObject.HTMLname + "\n";
					}
				}
				}
				

			}


			else if(validateObject.type.indexOf("IsMoney")>=0)
			//IsMoney tells whether a field is a currency field or not
			{
				var moneyPass=true;
				var dotFound=false;
				var dotFoundAt=-1;
				var strField = new String(validateObject.val);
				
				var k = 0;

				for (k = 0; k < strField.length; k++)
				{
					var x=strField.charAt(k);
					if (x == ".")
					{
						dotFound = true;
						if (dotFoundAt < 0)
						{
							dotFoundAt=k;
						}
					}
					if (((x < '0') || (x > '9')) && (x != '.'))
					{
						errors += validateObject.HTMLname + "\n";
						MoneyMsg += validateObject.HTMLname + "\n";
						k = strField.length;
					}
					if ((x == '.') && (dotFoundAt != k))
					{
						errors += validateObject.HTMLname + "\n";
						MoneyMsg += validateObject.HTMLname + "\n";
						k = strField.length;
					}
				}
			}

			else if(validateObject.type.indexOf("IsPassword")>=0)
			//IsPassword tells whehter a particular field is a password or not
			{
				var strField = new String(validateObject.val);
				var pwdPass=true;
				if (isWhitespace(strField))
				{
					errors += validateObject.HTMLname + "\n";
					PwdMsg += validateObject.HTMLname + "\n";
				}
				else if (strField.length < 4)
				{
					errors += validateObject.HTMLname + "\n";
					PwdMsg += validateObject.HTMLname + "\n";
				}
			}
		
		}
	
	
	if (errors) 
	{
		var errMsg="";
	
		if (RequiredMsg != "" )
			errMsg = errMsg + TEXT_FIELDS_REQUIRED + ": \r\n\r\n"+RequiredMsg + "\r\n\r\n";
		
		if (ZipcodeMsg != "")
			errMsg = errMsg + TEXT_FIELDS_ZIPCODES + ": \r\n\r\n"+ZipcodeMsg + "\r\n\r\n";
		
		if (EmailMsg != "")
			errMsg = errMsg + TEXT_FIELDS_EMAILS + ": \r\n\r\n"+EmailMsg + "\r\n\r\n";

		if (NumMsg != "")
			errMsg = errMsg + TEXT_FIELDS_NUMBERS + ": \r\n\r\n"+ NumMsg + "\r\n\r\n";
		
		if (MoneyMsg != "")
			errMsg=errMsg + TEXT_FIELDS_CURRENCY + ": \r\n\r\n" + MoneyMsg + "\r\n\r\n";
		
		if (PhnMsg != "")
			errMsg = errMsg + TEXT_FIELDS_PHONE + ": \r\n\r\n" + PhnMsg +"\r\n\r\n";
		
		if (PwdMsg != "")
			errMsg=errMsg + TEXT_FIELDS_PASSWORD1 + ": \r\n " + TEXT_FIELDS_PASSWORD2 + "\r\n " + TEXT_FIELDS_PASSWORD3 + ": \r\n\r\n" + PwdMsg + "\r\n\r\n";
		
		if (StateMsg != "")
			errMsg=errMsg + TEXT_FIELDS_STATE + ": \r\n\r\n" + StateMsg +"\r\n\r\n";

		if (SSNMsg != "")
			errMsg=errMsg + TEXT_FIELDS_SSN + ": \r\n\r\n" + SSNMsg +"\r\n\r\n";

		if (DateMsg != "")
			errMsg=errMsg + TEXT_FIELDS_DATE + ": \r\n\r\n" + DateMsg +"\r\n\r\n";

		if (CCMsg != "")
			errMsg=errMsg + TEXT_FIELDS_CC + ": \r\n\r\n" + CCMsg +"\r\n\r\n";
					
		alert(errMsg);
		errors = "";
		RequiredMsg = "";
		ZipcodeMsg = "";
		EmailMsg = "";
		NumMsg = "";
		MoneyMsg = "";
		PhnMsg = "";
		PwdMsg = "";
		StateMsg = "";
		SSNMsg = "";
		DateMsg = "";
		CCMsg = "";
		errMsg = "";
		returnVal = false;
	} 
	else 
		returnVal = true;

	return returnVal;
	}


}

// whitespace characters
var whitespace = " \t\n\r";

/****************************************************************/

// Check whether string s is empty.

function isEmpty(s)
{   return ((s == null) || (s.length == 0))
}

/****************************************************************/


// Returns true if string s is empty or 
// whitespace characters only.

function isWhitespace(s)

{   var i;

    // Is s empty?
    if (isEmpty(s)) return true;

    // Search through string's characters one by one
    // until we find a non-whitespace character.
    // When we do, return false; if we don't, return true.

    for (i = 0; i < s.length; i++)
    {   
	// Check that current character isn't whitespace.
	var c = s.charAt(i);

	if (whitespace.indexOf(c) == -1) return false;
	
    }

    // All characters are whitespace.
    return true;
}

/****************************************************************/

// Checks to see if a required field is blank.  If it is, a warning
// message is displayed...

function ForceEntry(objField, FieldName)
{
  var strField = new String(objField.value);

	if (isWhitespace(strField) )  {
		alert("Please enter information for " + FieldName + ".");
		objField.focus();
		return false;
	}
	
	return true;
}

function toDate(str)
{
	var re=/\d+/g;
	var arr=str.match(re);
	var dt;
	if(arr==null || arr.length<3)
		return null;
	while(arr.length<6)
		arr[arr.length]=0;
<?php
if($locale_info["LOCALE_IDATE"]=="0")
	echo "	dt = new Date(arr[2],arr[0]-1,arr[1],arr[3],arr[4],arr[5]);";
else if($locale_info["LOCALE_IDATE"]=="1")
	echo "	dt = new Date(arr[2],arr[1]-1,arr[0],arr[3],arr[4],arr[5]);";
else
	echo "	dt = new Date(arr[0],arr[1]-1,arr[2],arr[3],arr[4],arr[5]);";
?>

	if(isNaN(dt))
		return null;
//	check date and month
<?php
if($locale_info["LOCALE_IDATE"]=="0")
	echo "	if(dt.getMonth()!=arr[0]-1 || dt.getDate()!=arr[1])";
else if($locale_info["LOCALE_IDATE"]=="1")
	echo "	if(dt.getMonth()!=arr[1]-1 || dt.getDate()!=arr[0])";
else
	echo "	if(dt.getMonth()!=arr[1]-1 || dt.getDate()!=arr[2])";
?>
		
		return null;
	return dt;
}
</script>
