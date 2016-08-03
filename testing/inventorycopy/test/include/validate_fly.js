var validate_fly_included=true;

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
